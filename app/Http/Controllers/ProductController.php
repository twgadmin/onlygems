<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Option;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Variation;
use Auth;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Config;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return View('products.show-products');
    }

    public function list(Request $request)
    {
        // $currentUser = Auth::user();
        $limit = $request->length;
        $offset = $request->start;
        $search = $request->search['value'];
        $order = $request->order[0]['column'];
        $adod = $request->order[0]['dir'];
        $oby = "id";

        $productsCount = Product::count();

        $totfil = $totrec = $productsCount;

        if (!empty($search)) {
            $totalProductSearch =   Product::where('product_name', 'LIKE', '%' . $search . '%')
                                            ->orWhere('brand_name', 'LIKE', '%' . $search . '%')
                                            ->orWhere('description', 'like', '%' . $search . '%')
                                            ->orWhere('tags', 'like', '%' . $search . '%')
                                            ->orWhere('product_type', 'like', '%' . $search . '%')
                                            ->orWhere('sku_code_type', 'like', '%' . $search . '%')
                                            ->orWhere('sku_code', 'like', '%' . $search . '%')
                                            ->orWhere('aws_date', 'like', '%' . $search . '%')
                                            ->orWhere('aws_category', 'like', '%' . $search . '%')
                                            ->orWhere('aws_source', 'like', '%' . $search . '%')
                                            ->orWhere('aws_term', 'like', '%' . $search . '%')
                                            ->orWhere('aws_itemid', 'like', '%' . $search . '%')
                                            ->orWhere('aws_price', 'like', '%' . $search . '%')
                                        ->count();
            $totfil = $totalProductSearch; #TOTAL FILTERED RECORDS
        }

        $listOfProducts = Product::with(['variations','awsSitePriceLists'])
            ->when(!empty($search), function($query) use ($search){
                $query->whereHas('awsSitePriceLists', function($q) use ($search) {
                    $q->where('price', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('product_name', 'LIKE', '%' . $search . '%')
                ->orWhere('brand_name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('tags', 'like', '%' . $search . '%')
                ->orWhere('product_type', 'like', '%' . $search . '%')
                ->orWhere('sku_code_type', 'like', '%' . $search . '%')
                ->orWhere('sku_code', 'like', '%' . $search . '%')
                ->orWhere('aws_date', 'like', '%' . $search . '%')
                ->orWhere('aws_category', 'like', '%' . $search . '%')
                ->orWhere('aws_source', 'like', '%' . $search . '%')
                ->orWhere('aws_term', 'like', '%' . $search . '%')
                ->orWhere('aws_itemid', 'like', '%' . $search . '%')
                ->orWhere('aws_price', 'like', '%' . $search . '%');
            })
            ->orderBy($oby, $adod)->skip($offset)->take($limit)->get()->toArray();

        $adata['recordsTotal'] = $totrec; #TOTAL RECORDS
        $adata['recordsFiltered'] = $totfil; #TOTAL FILTERED RECORDS

        if(!empty($listOfProducts)){
            $lop = $listOfProducts;
            /* prepare html of listing - datatable */
            for ($i = 0; $i < count($lop); $i++) {
                $dataArray = array(
                    ($offset + $i) + 1,
                    (empty($lop[$i]['product_name']) ? '--' : $lop[$i]['product_name']),
                    (empty($lop[$i]['brand_name']) ? '--' : $lop[$i]['brand_name']),
                    (empty($lop[$i]['description']) ? '--' : $lop[$i]['description']),
                    (empty($lop[$i]['tags']) ? '--' : $lop[$i]['tags']),
                    (empty($lop[$i]['product_type']) ? '--' : $lop[$i]['product_type']),
                    (empty($lop[$i]['sku_code_type']) ? '--' : $lop[$i]['sku_code_type']),
                    (empty($lop[$i]['sku_code']) ? '--' : $lop[$i]['sku_code']),
                    (empty($lop[$i]['aws_date']) ? '--' : $lop[$i]['aws_date']),
                    (empty($lop[$i]['aws_category']) ? '--' : $lop[$i]['aws_category']),
                    (empty($lop[$i]['aws_source']) ? '--' : $lop[$i]['aws_source']),
                    (empty($lop[$i]['aws_term']) ? '--' : $lop[$i]['aws_term']),
                    (empty($lop[$i]['aws_itemid']) ? '--' : $lop[$i]['aws_itemid']),
                    (empty($lop[$i]['aws_price']) ? '--' : $lop[$i]['aws_price']),
                    (empty($lop[$i]['aws_site_price_lists'][0]['price']) ? '--' : $lop[$i]['aws_site_price_lists'][0]['price']),
                    (empty($lop[$i]['aws_site_price_lists'][1]['price']) ? '--' : $lop[$i]['aws_site_price_lists'][1]['price']),
                    (empty($lop[$i]['aws_site_price_lists'][2]['price']) ? '--' : $lop[$i]['aws_site_price_lists'][2]['price']),
                    date('d-m-Y', strtotime($lop[$i]['created_at'])));

                    if($lop[$i]['aws_source'] == NULL)
                    $dataArray[] = '<a class="btn btn-sm btn-info btn-block edit-btn" href="products/'. $lop[$i]['id'] .'/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                    else
                    $dataArray[] = '--';

                $adata['data'][] = $dataArray;
            }
        }

        if($totrec == 0 || $totfil == 0){
            $adata['data'] = array();
        }

        return  json_encode($adata);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create-product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->brand_name = $request->brand_name;
        $product->description = $request->description;
        $product->tags = $request->tags;
        $product->product_type = $request->product_type;
        $product->sku_code_type = $request->sku_code_type;
        $product->sku_code = $request->sku_code;

        $product->save();

        $variations = Variation::get()->pluck('id')->toArray();
        $options = Option::get()->pluck('id')->toArray();

        $site_id = Config::get('constants.price_compare_site_inverse.local');

        foreach($options as $key=>$option) {
            $product->variations()->attach($variations[0], array('site_id' => $site_id, 'option_id' => $option , 'qty' => '1', 'cost_price' => '0.0', 'total_price' => '0.0'));
        }
        return redirect()->route('products')->with('success', 'Product Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        /* $supplier = Supplier::find($product->supplier_id);
        $product['supplier'] = $supplier->name;
        foreach($product->variations as $vari) {
            $option_id = $vari->pivot->option_id;
            $option = Option::find($option_id);
            if($vari->name == 'Color')
            $product['color'] = $option['option_value'];
            if($vari->name == 'Size')
            $product['size'] = $option['option_value'];
        }
         */
        $data = [
            'product' => $product
        ];
        return view('products.edit-product')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->product_name = $request->product_name;
        $product->brand_name = $request->brand_name;
        $product->description = $request->description;
        $product->tags = $request->tags;
        $product->product_type = $request->product_type;
        $product->sku_code_type = $request->sku_code_type;
        $product->sku_code = $request->sku_code;
        $product->save();
        return redirect()->route('products.edit', [$id])->with('success', 'Product Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }


    /**
     * search function for products
     *
     */

    public function search(Request $request) {
        $data = $request->all();
        $query = $data['q'];
        $filter_data = Product::select('product_name','id')
                        ->where('product_name', 'LIKE', '%'.$query.'%')
                        ->get();

        return json_encode($filter_data->toArray());
    }


    /**
     * Fetch details a product
     * with pivot table details
     */

    public function fetch(Request $request) {

        $products = Product::with(['options'  => function ($q) {
            $q->orderBy('id', 'asc');
        }])->find($request->id);

        if(count($products->options) == 0) {
            return json_encode($products);
        }

        $options = $products->options;

        $product_data = array();

        foreach($options as $option) {
            $product_data[] = array($products->product_name, $option['option_value'], $option['pivot']['qty'], $products->id, $option['pivot']['option_id'], $option['variation_id'], $option['pivot']['cost_price'], $option['pivot']['total_price']);
        }

        return json_encode($product_data);
    }
}
