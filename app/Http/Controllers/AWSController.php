<?php

namespace App\Http\Controllers;

use App\Models\AwsSitePriceList;
use App\Models\Option;
use App\Models\Product;
use App\Models\Variation;
use Aws\Athena\AthenaClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Mail;
use phpDocumentor\Reflection\Types\Self_;

class AWSController extends Controller
{
    public function index()
    {
        // \Log::info(Carbon::now()->format('Y-m-d H:i:s')); die;

        $options = [
            'region'            => env('AWS_DEFAULT_REGION'),
            'version'           => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ];

        $athenaClient = new AthenaClient($options);

        $databaseName = 'pricedata';
        $sql = 'SELECT * FROM "ffd_price_data" WHERE date > 1643602411 limit 2000';
        $outputS3Location = 's3://ffd-athena/';

        //1. issue of the query
        //* specified database
        //* Athena specify the output destination because it outputs the result to the S3
        //* SQL specified
        $startQueryResponse = $athenaClient->startQueryExecution([
            'QueryExecutionContext' => [
                'Database' => $databaseName
            ],
            'QueryString' => $sql,
            'ResultConfiguration'   => [
                'OutputLocation' => $outputS3Location
            ]
        ]);

        $queryExecutionId = $startQueryResponse->get('QueryExecutionId');

        //2. Query wait for to finish
        $waitForSucceeded = function () use ($athenaClient, $queryExecutionId, &$waitForSucceeded) {

            $getQueryExecutionResponse = $athenaClient->getQueryExecution([

                'QueryExecutionId' => $queryExecutionId

            ]);

            $status = $getQueryExecutionResponse->get('QueryExecution')['Status']['State'];

            return $status === 'SUCCEEDED' || $waitForSucceeded();
        };
        $waitForSucceeded();

        //3. result acquiring
        $getQueryResultsResponse = $athenaClient->getQueryResults([

            'QueryExecutionId' => $queryExecutionId
        ]);

        $result = $getQueryResultsResponse->get('ResultSet');

        $dataArray = array();
        $allOptionsArray = range(4,14,0.5);

        $productsWithOutMultiplePrices = array();

        foreach($result['Rows'] as $key=>$value) {
            if($key != 0) {
                $date = isset($value['Data'][0]['VarCharValue']) ? $value['Data'][0]['VarCharValue'] : '';
                $title = isset($value['Data'][1]['VarCharValue']) ? $value['Data'][1]['VarCharValue'] : '';
                $category = isset($value['Data'][2]['VarCharValue']) ? $value['Data'][2]['VarCharValue'] : '';
                $source = isset($value['Data'][3]['VarCharValue']) ? $value['Data'][3]['VarCharValue'] : '';
                $term = isset($value['Data'][4]['VarCharValue']) ? $value['Data'][4]['VarCharValue'] : '';
                $itemid = isset($value['Data'][5]['VarCharValue']) ? $value['Data'][5]['VarCharValue'] : '';
                $price = isset($value['Data'][6]['VarCharValue']) ? $value['Data'][6]['VarCharValue'] : '';

                $pricestockx = isset($value['Data'][7]['VarCharValue']) ? $value['Data'][7]['VarCharValue'] : '';
                $priceflightclub = isset($value['Data'][8]['VarCharValue']) ? $value['Data'][8]['VarCharValue'] : '';
                $pricegoat = isset($value['Data'][9]['VarCharValue']) ? $value['Data'][9]['VarCharValue'] : '';

                $multiplePrices = isset($value['Data'][10]['VarCharValue']) ? $value['Data'][10]['VarCharValue'] : '';

                $dataArray = array('product_name' => $title, 'brand_name' => '', 'aws_date' => $date, 'aws_category' => $category, 'aws_source' => $source, 'aws_term' => $term, 'aws_itemid' => $itemid, 'aws_price' => $price,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));

                if (!Product::where('aws_date', $date)->where('aws_itemid', $itemid)->exists())
                {
                    $emptyOptions = array();
                    $product = Product::create($dataArray);
                    $productId = $product->id;

                    self::creatAwsSitePrice($productId, $pricestockx, $priceflightclub, $pricegoat);

                    $variations = Variation::get()->pluck('id')->toArray();

                    if(!empty($multiplePrices))
                    {
                        $optionValues = array();
                        $sitePrices = json_decode($multiplePrices,true);
                        foreach($sitePrices as $sitekey=>$sitePrice) {
                            if(!empty($sitePrice)) {

                                if(!empty($sitekey)) {
                                    $siteID = Config::get('constants.price_compare_site_inverse.'.$sitekey);
                                }
                                else
                                $siteID = '';

                                $options = array_keys($sitePrice);
                                $costPrice = array_values($sitePrice);

                                $emptyOptions = array_diff($allOptionsArray, $options);

                                foreach($options as $k=>$option) {
                                        $optionDetails = Option::where('option_value',$option)->get()->toArray();
                                        if(!empty($optionDetails)) {
                                        $product->variations()->attach($variations[0], array('site_id'=>$siteID, 'option_id' => $optionDetails[0]['id'] , 'qty' => '1', 'cost_price' => $costPrice[$k], 'total_price' => $costPrice[$k]));
                                    }
                                }
                                /* foreach($emptyOptions as $emptyOption) {
                                    $optionsdata = Option::where('option_value',$emptyOption)->get()->toArray();
                                    $optionValues = $optionsdata->option_value;
                                } */
                            }

                        } // foreach siteprices

                        $productsWithOutMultiplePrices[] = [ $productId, $title, $date, $category, $source, $term, $itemid, $price, $pricestockx, $priceflightclub, $pricegoat , json_encode($emptyOptions) ];

                    } // check if multiprices is empty or not

                    else
                    {
                        $productsWithOutMultiplePrices[] = [ $productId, $title, $date, $category, $source, $term, $itemid, $price, $pricestockx, $priceflightclub, $pricegoat, '--' ];
                    }

                } // if ends (exist)
            }
        } // product foreach ends

        if(!empty($productsWithOutMultiplePrices)) {

        self::sendMail($productsWithOutMultiplePrices);

        }
        \Log::info("Products added in db");
        \Log::info(Carbon::now()->format('Y-m-d H:i:s'));

        // return redirect()->route('products')->with('success', 'Product Created Successfully.');
    }

    static function creatAwsSitePrice($productId, $pricestockx, $priceflightclub, $pricegoat) {

        $stockXArray = array();
        $flightClubArray = array();
        $goatArray = array();

        if(!empty($pricestockx))
        {
            $stockXArray =
            [
                'product_id'=>$productId,
                'site_id' => Config::get('constants.price_compare_site_inverse.stockx'),
                'price' => $pricestockx,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            AwsSitePriceList::insert($stockXArray);
        }

        if(!empty($priceflightclub))
        {
            $flightClubArray =
            [
                'product_id'=>$productId,
                 'site_id' => Config::get('constants.price_compare_site_inverse.flightclub') ,
                 'price' => $priceflightclub,
                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            AwsSitePriceList::insert($flightClubArray);
        }

        if(!empty($pricegoat))
        {
            $goatArray =
            [
                'product_id'=>$productId,
                'site_id' => Config::get('constants.price_compare_site_inverse.goat'),
                'price' => $pricegoat,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            AwsSitePriceList::insert($goatArray);
        }
    }


    static function sendMail($products) {
        $details = [
            'subject' => 'Fred Fund - NFT Collectible Asset Fund',
            'title' => 'Fred Fund - NFT Collectible Asset Fund - Products List',
            'email' => 'da@flyfred.com',
            'content' => $products
          ];

          Mail::send('emails.products-list', $details, function($message) use ($details) {
            $message->to($details['email'])
            ->subject($details['subject']);
          });
          die;
    }
}
