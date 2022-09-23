<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Models\Cardhedger;
use Illuminate\Http\Request;

class CardhedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('cardhedger.cardhedger-list');
    }

    public function list(Request $request)
    {
        $limit = $request->length;
        $offset = $request->start;
        $search = $request->search['value'];
        $order = $request->order[0]['column'];
        $adod = $request->order[0]['dir'];
        $oby = "id";

        $totfil = $totrec = Cardhedger::count();

        $totalCardSearch =   Cardhedger::when(!empty($search), function($query) use($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('grade', 'LIKE', '%' . $search . '%')
            ->orWhere('price', 'LIKE', '%' . $search . '%')
            ->orWhere('card_desc', 'LIKE', '%' . $search . '%')
            ->orWhere('card_number', 'LIKE', '%' . $search . '%')
            ->orWhere('card_variant', 'LIKE', '%' . $search . '%')
            ->orWhere('sale_date', 'LIKE', '%' . $search . '%');
            })->count();
        $totfil = $totalCardSearch; #TOTAL FILTERED RECORDS

        $cardhedgerList = Cardhedger::when(!empty($search), function($query) use($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('grade', 'LIKE', '%' . $search . '%')
            ->orWhere('price', 'LIKE', '%' . $search . '%')
            ->orWhere('card_desc', 'LIKE', '%' . $search . '%')
            ->orWhere('card_number', 'LIKE', '%' . $search . '%')
            ->orWhere('card_variant', 'LIKE', '%' . $search . '%')
            ->orWhere('sale_date', 'LIKE', '%' . $search . '%');
            })->orderBy($oby, $adod)->skip($offset)->take($limit)->get();

        $adata['recordsTotal'] = $totrec; #TOTAL RECORDS
        $adata['recordsFiltered'] = $totfil; #TOTAL FILTERED RECORDS

        if(!empty($cardhedgerList)) {
            /* prepare html of listing - datatable */
            $i = 0;
            foreach($cardhedgerList as $cardhedger) {
                $dataArray = array(
                    ($offset + $i) + 1,
                    $cardhedger->name,
                    $cardhedger->grade,
                    $cardhedger->price,
                    $cardhedger->card_desc,
                    $cardhedger->card_number,
                    $cardhedger->card_variant,
                    $cardhedger->sale_date,
                    date('d-m-Y', strtotime($cardhedger->created_at))
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
        return View('cardhedger.add-new-card');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCardRequest $request)
    {
            /*********** Api Working *****************/
            $date =  $request->date_time;
            
            $responsedate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($date)));
            $data = array(
                     'token' => 'onlygems-je9UQrb2tARhkLWVm9s',
                     'timestamp'=> $responsedate
                 );

                 $payload = json_encode($data);

                 // Prepare new cURL resource
                 $ch = curl_init('https://www.cardhedger.com/api/1.1/wf/get-pub-price-updates');
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
                 echo curl_error($ch);
                 curl_close($ch);

                 $data =json_decode($result, true);

                 /* echo "<pre>";
                 print_r($data);
                 die; */
                   $y = "400";

                 if(isset($data['statusCode']) && $data['statusCode'] == $y){
                     return redirect()->back()->with('warning','INVALID DATA');
                  } else {
                      foreach ($data as $key => $val) {

                        $UpdateDetails = Cardhedger::where('card_number', $val['card_number'])->first();
                         if (isset($UpdateDetails) && !empty($UpdateDetails))
                         {
                             /*********** Update Data Api *************/

                             $UpdateDetails->name = $val['player'];
                             $UpdateDetails->grade = $val['grade'];
                             $UpdateDetails->price = $val['price'];
                             $UpdateDetails->sale_date = $val['sale_date'];
                             $UpdateDetails->card_desc = $val['card_desc'];
                             $UpdateDetails->card_number = $val['card_number'];
                             $UpdateDetails->card_variant = $val['variant'];
                             $UpdateDetails->category = '';
                             $UpdateDetails->save();

                             /*********** Update Data Api *************/

                         }
                         else
                         {
                             /*********** Save Data Api *************/
                           Cardhedger::create(['name'=>$val['player'],'grade'=>$val['grade'],'price'=>$val['price'],'sale_date'=>$val['sale_date'],'card_desc'=>$val['card_desc'],'card_number'=>$val['card_number'],'card_variant'=>$val['variant'],'category'=>'']);
                           /*********** Save Data Api *************/
                         }

                         }
                   return redirect()->route('cardhedger-list')->with('success', 'Card Added Successfully.');
                 }

             /*********** Api Working *****************/
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
