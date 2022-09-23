<?php

namespace App\Http\Controllers;

use App\Imports\CardSheetImport;
use App\Models\Card;
use App\Models\CardPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class CardController extends Controller
{

    public function __construct()
    {
        $this->domain_prefix = env('VEND_DOMAIN_PREFIX');;

        /* $this->authorize_url = "https://secure.vendhq.com/connect";
        $this->token_url = "https://".$this->domain_prefix.".vendhq.com/api/1.0/token"; */

        //	callback URL specified when the application was defined--has to match what the application says
        $this->callback_uri = "https://onlygems.stagingwebsites.info/testing";

        $this->test_api_url = "https://".$this->domain_prefix.".vendhq.com/api/2.0/products";

        //	client (application) credentials - located at apim.byu.edu
        $this->client_id = env('VEND_CLIENT_ID');
        $this->client_secret = env('VEND_CLIENT_SECRET');
        $this->personal_access_token = env('VEND_PERSONAL_ACCESS_TOKEN');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('cards.cards-list');
    }

    public function list(Request $request)
    {
        $limit = $request->length;
        $offset = $request->start;
        $search = $request->search['value'];
        $order = $request->order[0]['column'];
        $adod = $request->order[0]['dir'];
        $oby = "internal_serial_number";
        $card_category = $request->card_category;
        $cards_sort = $request->cards_sort;

        if(!empty($cards_sort))
        $adod = $cards_sort;

        $totfil = $totrec = Card::count();

        $totalCardSearch = Card::with('cardprices')
                            ->when(!empty($search), function($query) use($search) {
                                    $query->where('name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('internal_serial_number', 'LIKE', '%' . $search . '%')
                                    ->orWhere('grade', 'LIKE', '%' . $search . '%')
                                    ->orWhere('grading_co', 'LIKE', '%' . $search . '%')
                                    ->orWhere('grading_co_serial_number', 'LIKE', '%' . $search . '%')
                                    ->orWhere('year', 'LIKE', '%' . $search . '%')
                                    ->orWhere('set', 'LIKE', '%' . $search . '%')
                                    ->orWhere('card', 'LIKE', '%' . $search . '%')
                                    ->orWhere('parralel', 'LIKE', '%' . $search . '%')
                                    ->orWhere('category', 'LIKE', '%' . $search . '%');
                                $query->whereHas('cardprices', function($q) use ($search){
                                    $q->orWhere('price', 'LIKE', '%'.$search.'%');
                                });
                            })
                            ->when(!empty($card_category), function($query) use($card_category){
                                $query->where('category', $card_category);
                            })->count();
            $totfil = $totalCardSearch; #TOTAL FILTERED RECORDS

            $CardList = Card::with('cardprices')
                    ->when(!empty($search), function($query) use($search) {
                            $query->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('internal_serial_number', 'LIKE', '%' . $search . '%')
                            ->orWhere('grade', 'LIKE', '%' . $search . '%')
                            ->orWhere('grading_co', 'LIKE', '%' . $search . '%')
                            ->orWhere('grading_co_serial_number', 'LIKE', '%' . $search . '%')
                            ->orWhere('year', 'LIKE', '%' . $search . '%')
                            ->orWhere('set', 'LIKE', '%' . $search . '%')
                            ->orWhere('card', 'LIKE', '%' . $search . '%')
                            ->orWhere('parralel', 'LIKE', '%' . $search . '%')
                            ->orWhere('category', 'LIKE', '%' . $search . '%');
                        $query->whereHas('cardprices', function($q) use ($search){
                            $q->orWhere('price', 'LIKE', '%'.$search.'%');
                        });
                    })
                    ->when(!empty($card_category), function($query) use ($card_category){
                        $query->where('category', $card_category);
                    })
                    ->orderBy($oby, $adod)->skip($offset)->take($limit)->get();
            $adata['recordsTotal'] = $totrec; #TOTAL RECORDS
            $adata['recordsFiltered'] = $totfil; #TOTAL FILTERED RECORDS

        if(!empty($CardList)) {
            /* prepare html of listing - datatable */
            $i = 0;
            foreach($CardList as $Card) {
                $closingDate = Carbon::parse($Card->closing_date);

                if(!$Card->image)
                $Card->image = '/images/default.png';

                $dataArray = array(
                    ($offset + $i) + 1,
                    '<img src="'.$Card->image.'" width="120">',
                    $Card->internal_serial_number,
                    ((($Card->cardhedger_internal_serial_number == "") || ($Card->cardhedger_internal_serial_number == 'null')|| ($Card->cardhedger_internal_serial_number == NULL)) ? "--" : $Card->cardhedger_internal_serial_number),
                    $Card->name,
                    ($Card->cardprices->price == "" ? "--" : $Card->cardprices->price),
                    ($Card->grading_co == "" ? "--" : $Card->grading_co),
                    ($Card->grading_co_serial_number == "" ? "--" : $Card->grading_co_serial_number),
                    $Card->year,
                    $Card->set,
                    ($Card->card == "" ? "--" : $Card->card),
                    $Card->parralel,
                    $Card->grade,
                    ($Card->category == "" ? "--" : $Card->category),
                    ($Card->description == "" ? "--" : $Card->description),
                    $closingDate->format('m/d/Y h:i:s'),
                    date('m/d/Y', strtotime($Card->created_at)),
                    '<a href="/cards/'.$Card->id.'/edit" data-toggle="tooltip" data-placement="top" title="Edit Card" class="btn btn-info btn-sm cursor-pointer"><i class="fas fa-edit"></i></a>
                    <div class="btnGroup"><form method="post" class="delete-btn-form" action="/cards/'.$Card->id.'" >
                '.csrf_field().method_field('DELETE').'
                <button data-toggle="modal" data-target="#confirmDelete" data-title="Delete Card" data-message="Are you sure you want to delete this card?" type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                '.View('modals.modal-delete').'</form>'.View('scripts.delete-modal-script')
                );
                $adata['data'][] = $dataArray;
                $i++;
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
        $internal_serial_number = $this->_getNewInternalSerialNumber();
        return View('cards.add-card')->with('serial_number', $internal_serial_number);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cardFormValues = array('', $request->player_name, $request->set, $request->card_number, '', '', $request->parralel, $request->grading_co, $request->grading_co_serial_number, $request->year, $request->grade, $request->category, $request->serial_number, '', '', '0');
        DB::beginTransaction();
        try {

            $card = $this->_createCard($cardFormValues);
            if($card)
            {
                $this->add_test_card_data($cardFormValues);
            }
            return redirect()->back()->with('success', 'Prices Saved Successfully!!');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::error('Error While Saving Card on '. $e->getLine(). ' message: '. $e->getMessage());
            return redirect()->back()->withErrors('Something went wrong. Please try again.');
        }

    }


    public function save_card_details(Request $request)
    {
        DB::beginTransaction();
        try {
            $cardFormValues = explode(',', $request->card_form_values);

            // dd($cardFormValues);

            if($cardFormValues[15] > 1) {
                $updateCard = $this->_updateCards($cardFormValues);
            }

            $card = $this->_createCard($cardFormValues);
            
            // return redirect()->back()->with('success', 'Prices Saved Successfully!!');
            return json_encode(array("status"=>"200", "message" => "success"));
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::error('Error While Saving Card on '. $e->getLine(). ' message: '. $e->getMessage());
            // return redirect()->back()->withErrors('Something went wrong. Please try again.');
            return json_encode(array('status' => '100', 'message' => $e->getMessage()));
        }
    }

    private function _updateCards($card) {
       $cards = Card::where('internal_serial_number', $card[12])->get();
       foreach($cards as $c){
            $c->cardprices->delete();
            $c->delete();
       }
    }


    public function save_card_data_vendhq(Request $request) {
        try {
            $handle = str_replace(' ', '-', $request->vend_player_name); // Replaces all spaces with hyphens.

            $handle = preg_replace('/[^A-Za-z0-9\-]/', '-', $handle); // Removes special chars.

            $handle = strtolower($handle);

            $header = array("Accept: application/json", "Authorization: Bearer {$this->personal_access_token}", "Content-Type: application/json");

            $content = json_encode(array("name"=>$request->vend_player_name, "handle"=>$handle, "sku"=>$request->vend_sku, "type" => array("name"=>$request->vend_product_type), "brand" => array("name" => $request->vend_brand_name), "description" => $request->vend_description));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->test_api_url,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content
            ));
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            $response = curl_exec($curl);

            $response_arr = json_decode($response,true);
            // print_r($response_arr); die;
            curl_close($curl);
            if(isset($response_arr['data'])) {
                $url = "https://".$this->domain_prefix.".vendhq.com/barcode/select_count?product_id=".$response_arr['data'][0];
                return json_encode(array("code"=>"200", "message" => "success", "url" => $url));
            }
            if(isset($response_arr['error']))
            return json_encode(array("code"=>"100", "status" => "vend_error", "message" => $response_arr['error']));

        }
        catch(\Exception $e)
        {
            return json_encode(array('code' => '100', "status" => "error", 'message' => $e->getMessage()));
        }

    }


    private function _getPubCards($searchString)
    {

        /*********** Api Working *****************/
        // $date =  $request->date_time;
        // $responsedate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($date)));
        $data = array(
            'token' => 'onlygems-je9UQrb2tARhkLWVm9s',
            'search'=> $searchString
        );
        $payload = json_encode($data);
        // Prepare new cURL resource
        $ch = curl_init('https://www.cardhedger.com/api/1.1/wf/get-pub-cards');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        if(count($data) == 0)
        $data = array();

        return $data;
    }

    public function _getPubCardPrices($cardId, $grade) {
        try {
            $payloadData = array(
                'grade' => (string)$grade,
                'token' => 'onlygems-je9UQrb2tARhkLWVm9s',
                'card_id'=> $cardId
            );

            $newpayload = json_encode($payloadData);

            // Prepare new cURL resource
            $cha = curl_init('https://www.cardhedger.com/api/1.1/wf/get-pub-latest-price-by-card');
            curl_setopt($cha, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cha, CURLINFO_HEADER_OUT, true);
            curl_setopt($cha, CURLOPT_POST, true);
            curl_setopt($cha, CURLOPT_POSTFIELDS, $newpayload);
            curl_setopt($cha, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, 0);

            // Set HTTP Header for POST request
            curl_setopt($cha, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($newpayload))
            );

            $result1 = curl_exec($cha);
            // echo curl_error($cha);
            curl_close($cha);

            $resp = json_decode($result1, true);
            return $resp;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function _createCard($pubCard)
    {
        try {

            // DB::enableQueryLog();

            $cardId = $pubCard[0];

            /* If Cards exits add it else create one. */
            /* $card = (Card::when(!empty($cardId), function($query) use($cardId) {
                $query->where('cardhedger_internal_serial_number', $cardId);
            })
            ->orWhere([['internal_serial_number', $pubCard[12]],['grading_co_serial_number', $pubCard[8]]])->exists()
                        ? Card::when(!empty($cardId), function($query) use($cardId) {
                            $query->where('cardhedger_internal_serial_number', $cardId);
                        })
                        ->orWhere([['internal_serial_number', $pubCard[12]],['grading_co_serial_number', $pubCard[8]]])->first()
                        : new Card()); */


            $card = (Card::where([['internal_serial_number', $pubCard[12]],['grading_co_serial_number', $pubCard[8]]])->exists()
                        ? Card::where([['internal_serial_number', $pubCard[12]],['grading_co_serial_number', $pubCard[8]]])->first()
                        : new Card());
                        

                        // dd($card);
            /* print_r(DB::getQueryLog());

             */
            // (!Card::where('cardhedger_internal_serial_number', $pubCard[0])->exists() ? $card->internal_serial_number = $this->_getNewInternalSerialNumber() : '' );

            $card->cardhedger_internal_serial_number = $pubCard[0];
            $card->internal_serial_number = $pubCard[12];
            $card->name = $pubCard[1];
            $card->grading_co = $pubCard[7];
            $card->grading_co_serial_number = $pubCard[8];
            $card->year = $pubCard[9];
            $card->set = $pubCard[2];
            $card->card = $pubCard[3];
            $card->parralel = $pubCard[6];
            $card->grade = $pubCard[10];
            $card->category = $pubCard[11];
            $card->image = $pubCard[5];
            $card->description = $pubCard[4];

            $card->save();
            $cardPrices[] = [
                'card_id' => $card->id,
                'closing_date' => $pubCard[14],
                'price' => $pubCard[13],
                'created_at' => date('Y-m-d h:i:s')
            ];

            if(!empty($cardPrices))
            {
                DB::table('card_prices')->where('card_id',$card->id)->delete();
                DB::table('card_prices')->insert($cardPrices);

                DB::commit();
            }
        }  catch (\Throwable $th) {
            throw $th;
        }
    }

    private function _getNewInternalSerialNumber()
    {
        $serialNumber = (Card::latest('internal_serial_number')->first() == "" ? config('constants.initital_internal_serial_number') : Card::latest()->value('internal_serial_number') + 1);
        return $serialNumber;
    }


    public function getCardsFromApi(Request $request)
    {
        try {
            if($request->search_status == '1') {
                $card = $this->getCardDetails($request);

                $list = array(array('card_id'=> $card[0]->cardhedger_internal_serial_number, 'player' => $card[0]->name, 'image' => $card[0]->image, 'number' => $card[0]->card, 'set' => $card[0]->set, 'variant' => $card[0]->parralel, 'description' => $card[0]->description, 'year' => $card[0]->year, 'price' => $card[0]->cardprices['price'], 'closing_date' => $card[0]->cardprices['closing_date']));

                return json_encode(array('status' => '200', 'message' => 'success', 'list' => $list));
            }

            if(empty($request->parralel))
            $request->parralel = "Base";

            $searchString = $request->year." ".$request->set." ".$request->player_name." ".$request->card_number." ".$request->parralel;
            $pubCards = $this->_getPubCards($searchString);

            // dd($pubCards);
            

            $cards = array();
            $pubCardCount = count($pubCards);

            if($pubCardCount > 0) {

            foreach($pubCards as $pubCard) {
                $pubCard['grade'] = $request->grade;
                $pubCard['multiple'] = $pubCardCount;
                $price = $this->getPriceList($pubCard);
                
                if(count($price) > 0 && (!empty($price[0]['price']) && !empty($price[0]['closing_date']))) {
                    $pubCard['price'] = $price[0]['price'];
                    $pubCard['closing_date'] = $price[0]['closing_date'];
                }
                $cards[] = $pubCard;
            }
            return json_encode(array('status' => '200', 'message' => 'success', 'list' => $cards));
        }
        else
        {
            return json_encode(array('status' => '200', 'message' => 'success', 'list' => array()));
        }
        }
        catch (\Throwable $th) {
            Log::error('Error While Fetching Card on '. $th->getLine(). ' message: '. $th->getMessage());
            return json_encode(array('status' => '100', 'message' => $th->getMessage(), 'list' => array()));
        }
    }


    public function getPriceList($pubCard) {
        try {
            $cardId = $pubCard['card_id'];
            $grade = $pubCard['grade'];
            $cardPrices = $this->_getPubCardPrices($cardId, $grade);
            return $cardPrices;
        }
        catch(\Throwable $th) {
            Log::error('Error While Fetching Prices of card on '. $th->getLine(). ' message: '. $th->getMessage());
            return json_encode(array('status' => '100', 'message' => $th->getMessage(), 'list' => array()));
        }
        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $card = Card::where('id',$id)->get();

        $data = [
            'card'        => $card[0],
        ];

        return view('cards.edit-card')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $card = Card::where('id',$id)->first();

            if($card->internal_serial_number != $request->serial_number)
            {
                $existSerialNumber = Card::where('internal_serial_number', $request->serial_number)->exists();
                if($existSerialNumber == true)
                return redirect()->back()->withErrors('Internal Serial Number already exists');
            }
            $card->internal_serial_number = $request->serial_number;
            $card->name = $request->player_name;
            $card->grading_co = $request->grading_co;
            $card->grading_co_serial_number = $request->grading_co_serial_number;
            $card->year = $request->year;
            $card->set = $request->set;
            $card->card = $request->card_number;
            $card->parralel = $request->parralel;
            $card->grade = $request->grade;
            $card->category = $request->category;
            $card->description = $request->description;
            $card->save();
            DB::commit();
            if($card)
            {
                return redirect()->back()->with('success', 'Card Details Updated Successfully!!');
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::error('Error While Updating Card on '. $e->getLine(). ' message: '. $e->getMessage());
            return redirect()->back()->withErrors('Something went wrong. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::where('id',$id)->get();
        // $ipAddress = new CaptureIpTrait();

        // if ($user->id !== $currentUser->id) {
            // $card->deleted_ip_address = $ipAddress->getClientIp();
            // $card->save();
            $card[0]->cardprices->delete();
            $card[0]->delete();
            return redirect('/cards-inventory')->with('success', trans('cards.deleteSuccess'));
        // }

        return back()->with('error', trans('cards.deleteSelfError'));
    }


    public function exportCsv(Request $request)
    {
        $fileName = 'cards.csv';
        $tasks = Card::with(['cardprices' => function($q) {
            // $q->where('cardhedger_internal_serial_number','1607573786691x531997312005439500');
        }])
        ->get();
        // ->where('card_id','1')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Internal Serial Number', 'Card ID', 'Name', 'Price', 'Grading Co', 'Grading Co Serial Number', 'Year', 'Set', 'Card Number', 'Parralel', 'Grade', 'Category', 'Image', 'Description', 'Closing Date');

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tasks as $task) {
                $row['Internal Serial Number']  = $task->internal_serial_number;
                $row['Card ID']    = $task->cardhedger_internal_serial_number;
                $row['Name']    = $task->name;
                $row['Price'] = $task->cardprices->price;
                $row['Grading Co']  = $task->grading_co;
                $row['Grading Co Serial Number']  = $task->grading_co_serial_number;
                $row['Year']    = $task->year;
                $row['Set']    = $task->set;
                $row['Card Number']    = $task->card;
                $row['Parralel']    = $task->parralel;
                $row['Grade']    = $task->grade;
                $row['Category']    = $task->category;
                $row['Image']    = $task->image;
                $row['Description']    = $task->description;
                $row['Closing Date'] = $task->cardprices->closing_date;

                fputcsv($file, array($row['Internal Serial Number'], $row['Card ID'], $row['Name'], $row['Price'], $row['Grading Co'], $row['Grading Co Serial Number'], $row['Year'], $row['Set'], $row['Card Number'], $row['Parralel'], $row['Grade'], $row['Category'], $row['Image'], $row['Description'], $row['Closing Date']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function getSheet() {
        $inputFileName = 'dummy_sheet.xlsx';
        $file = storage_path('sheet/'.$inputFileName);
        $sheet = Excel::import(new SheetsImport, $file);
        exit;
    }


    public function getSheetForCardID() {
        $inputFileName = 'cardhedger_id_update_sheet1.xlsx';
        $file = storage_path('sheet/'.$inputFileName);
        $sheet = Excel::import(new CardSheetImport, $file);
        exit;
    }
    

    public function getCardDetails(Request $request) {
        $internalSerialNumber = $request->serial_number;
        $card = Card::with('cardprices')->where('internal_serial_number', $internalSerialNumber)->get();
        $cardCount = $card->count();
        if($request->search_status == '1')
        return $card;
        
        return json_encode(array('message'=>'success' ,'card'=>$card, 'multiple'=> $cardCount, 'code' => 200));
    }


    public function schedulePriceUpdate() {
        try {
            DB::beginTransaction();
            Log::info("start time : ".time());
            $cards = Card::chunk(100, function($cards) {
                foreach ($cards as $key=>$card) {
                    $api = $this->_updatePubCardPrices($card->internal_serial_number, $card->cardhedger_internal_serial_number, $card->grade);
                    echo $card->internal_serial_number.'<br>';    
                    print_r($api);
                }
            });
            DB::commit();
            Log::info("end time : ".time());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error on price update: ". $e->getMessage(). " on line number ". $e->getLine());
        }
    }


    public function _updatePubCardPrices($internalSerialNumber,$cardId, $grade) {
        try {
            $payloadData = array(
                'external_id' => $internalSerialNumber,
                'grade' => (string)$grade,
                'token' => 'onlygems-je9UQrb2tARhkLWVm9s',
                'card_id'=> $cardId
            );

            $newpayload = json_encode($payloadData);

            // Prepare new cURL resource
            $cha = curl_init('https://www.cardhedger.com/api/1.1/wf/subscribe-pub-card-by-id');
            curl_setopt($cha, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cha, CURLINFO_HEADER_OUT, true);
            curl_setopt($cha, CURLOPT_POST, true);
            curl_setopt($cha, CURLOPT_POSTFIELDS, $newpayload);
            curl_setopt($cha, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, 0);

            // Set HTTP Header for POST request
            curl_setopt($cha, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($newpayload))
            );

            $result1 = curl_exec($cha);
            // echo curl_error($cha);
            curl_close($cha);

            $resp = json_decode($result1, true);
            return $resp;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function subscribe_api_response(Request $request) {

        try {

            // Log::info('request - '. $request->all(), true);

            $card_id = $request->card_id;
            $internal_serial_number = $request->external_id;
            $price = $request->price;

            if(count($request->all()) > 0 ) {

                $card = Card::where([['internal_serial_number', $internal_serial_number],['cardhedger_internal_serial_number', $card_id]])->get();

                if(count($card) > 0) {

                    $cardprice = Cardprice::where('card_id', $card[0]->id)->get();
                        if(count($cardprice) > 0) {
                            $cardprice[0]->price = $price;
                            $cardprice[0]->save();
                            Log::info('price updated - '. $internal_serial_number);
                            return json_encode(array('status'=> 200, 'message'=> 'Price updated successfully'));
                        }
                        return json_encode(array('status'=> 200, 'message'=> 'No Price'));
                }
        }
            Log::info('empty - '. $internal_serial_number);
        
            return json_encode(array('status'=> 200, 'message'=> 'No Price'));
    }
    catch(\Exception $e)
        {
            Log::error('Error While Saving Card on '. $e->getLine(). ' message: '. $e->getMessage());
            // return redirect()->back()->withErrors('Something went wrong. Please try again.');
            return json_encode(array('status' => '100', 'message' => $e->getMessage()));
        }
    }

    // ,['cardhedger_internal_serial_number', $card_id]

}
