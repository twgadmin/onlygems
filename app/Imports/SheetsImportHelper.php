<?php

namespace App\Imports;

use App\Models\Card;
use App\Models\CardPrice;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

class SheetsImportHelper implements ToCollection, WithStartRow, ToModel, WithBatchInserts, ShouldQueue
{
    public function collection(Collection $rows)
    {
        // $multiple_rows = array(); 
        // $single_rows = array();

        // echo '<pre>';
        /* foreach($rows as $row) {
            $result = Card::with('cardprices')->where('internal_serial_number', $row[1])->get()->toArray();
            if(count($result) == 1) {
                foreach($result as $dbRow) {
                    $row[17] = $dbRow['card'];
                    $row[18] = $dbRow['parralel'];
                    $row[19] = $dbRow['cardprices']['price'];
                    $row[20] = $dbRow['cardhedger_internal_serial_number'];

                    $single_rows[] = $row;
                }
            }
        } */
            /* foreach($rows as $row) {
                $result = Card::with('cardprices')->where('internal_serial_number', $row[1])->count();
                if($result > 1) {
                    $dbRows = Card::with('cardprices')->where('internal_serial_number', $row[1])->get()->toArray();
                    foreach($dbRows as $i => $dbRow) {
                        $record = [];
                        $record = $row->toArray();
                        $record[9] = $dbRow['card'];
                        $record[10] = $dbRow['parralel'];
                        $record[13] = $dbRow['cardhedger_internal_serial_number'];
                        $record[14] = $dbRow['cardprices']['price'];
                        $multiple_rows[] = $record;
                    }
                }
            } */
        // }
        /* $excluded = array();
        foreach($rows as $row) {
            $result = Card::where('internal_serial_number','LIKE','%'.$row[1].'%')->get();
            if(empty($result) || count($result) == 0 )
            {
                $excluded[] = $row;
            }
        } */
        // print_r($excluded);
        // die;
        
        // $this->exportCsv($multiple_rows);
        // 
        return null;
    }


    public function exportCsv($rows)
    {
        // dd($rows[0][5]);
        // echo 'here';
        /* $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ); */
//  'Cardheadger Serial Number', , 'Price'
        $columns = array('Serial Number', 'Cardheadger Serial Number', 'Name', 'Grading Co', 'Grading Co Serial Number', 'Year', 'Set', 'Card Number', 'Parallel', 'Grade', 'Category', 'Price');
        // $file = fopen(storage_path('sheet/excluded_records.csv'), 'a');
        // $file = fopen(storage_path('sheet/excluded_new_sheet1.csv'), 'a');
        $file = fopen(storage_path('sheet/multiple_rows_sheet3.csv'), 'a');
        fputcsv($file, $columns);
        // $callback = function() use($rows, $columns) {

            foreach ($rows as $row_data) {
                $row['Serial Number'] = $row_data[1];
                $row['Cardheadger Serial Number'] = $row_data[13];
                $row['Name']    = $row_data[8];
                $row['Grading Co']  = $row_data[4];
                $row['Grading Co Serial Number']  = $row_data[5];
                $row['Year']    = $row_data[6];
                $row['Set']    = $row_data[7];
                $row['Card Number']    = $row_data[9];
                $row['Parallel']    = $row_data[10];
                $row['Grade']    = $row_data[11];
                $row['Category']    = $row_data[12];
                $row['Price']    = $row_data[14];

                fputcsv($file, array($row['Serial Number'], $row['Cardheadger Serial Number'], $row['Name'], $row['Grading Co'], $row['Grading Co Serial Number'], $row['Year'], $row['Set'], $row['Card Number'], $row['Parallel'], $row['Grade'], $row['Category'], $row['Price']));
            }
            fclose($file);
        // };
        return 1;
        // return response()->stream($callback, 200, $headers);
    }


    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {

        if(empty($row[12]))
        $row[10] = 'Base';

        $searchString = $row[6]." ".$row[7]." ".$row[8]." ".$row[9]." ".$row[10];
            $pubCards = $this->_getPubCards($searchString);
            if(count($pubCards) > 0) {
                $arr = [];
                foreach($pubCards as $pubCard) {
                    $checkCardExist = $this->_checkCardExist($pubCard, $row);
                    if($checkCardExist == 1) {
                        $card = $this->_createCard($pubCard, $row);
                        break;
                    }
                    $arr[] = $pubCard;
                    $card = $this->_createCard($pubCard, $row);
                }
            }
            DB::beginTransaction();
            try {
                $excluded = array();
                    $result = Card::where('internal_serial_number','LIKE','%'.$row[1].'%')->get();
                    if(empty($result) || count($result) == 0 )
                    {
                        $cardObj = new Card();
                        $cardObj->internal_serial_number = $row[1];
                        $cardObj->name = $row[8];
                        $cardObj->grading_co = $row[4];
                        $cardObj->grading_co_serial_number = $row[5];
                        $cardObj->year = $row[6];
                        $cardObj->set = $row[7];
                        $cardObj->card = $row[9];
                        $cardObj->parralel = $row[10];
                        $cardObj->grade = $row[11];
                        $cardObj->category = $row[12];
                        $cardObj->save();

                        $cardPrice = array('card_id' => $cardObj->id, 'closing_date' => '','price' => '', 'created_at' => date('Y-m-d h:i:s') );

                        DB::table('card_prices')->where('card_id',$cardObj->id)->delete();
                        DB::table('card_prices')->insert($cardPrice);
                    }
                // DB::commit();
            }
            catch(\Exception $e) {
                DB::rollback();
                Log::error('Error While Saving Card on '. $e->getLine(). ' message: '. $e->getMessage());
            }


        /* if(empty($row[10]))
        $row[10] = 'Base'; 

        $searchString = $row[6]." ".$row[7]." ".$row[8]." ".$row[9]." ".$row[10];

            $pubCards = $this->_getPubCards($searchString);

            if(count($pubCards) > 0) {
                foreach($pubCards as $pubCard) {
                    $checkCardExist = $this->_checkCardExist($pubCard, $row);
                    $card = $this->_createCard($pubCard, $row);
                }
            }

            DB::beginTransaction();
            try {
                $excluded = array();
                    $result = Card::where('internal_serial_number','LIKE','%'.$row[1].'%')->get();
                    if(empty($result) || count($result) == 0 )
                    {
                        $cardObj = new Card();
                        $cardObj->internal_serial_number = $row[1];
                        $cardObj->name = $row[8];
                        $cardObj->grading_co = $row[4];
                        $cardObj->grading_co_serial_number = $row[5];
                        $cardObj->year = $row[6];
                        $cardObj->set = $row[7];
                        $cardObj->card = $row[9];
                        $cardObj->parralel = $row[10];
                        $cardObj->grade = $row[11];
                        $cardObj->category = $row[12];
                        $cardObj->save();

                        $cardPrice = array('card_id' => $cardObj->id, 'closing_date' => '','price' => '', 'created_at' => date('Y-m-d h:i:s') );

                        DB::table('card_prices')->where('card_id',$cardObj->id)->delete();
                        DB::table('card_prices')->insert($cardPrice);
                    }
                DB::commit();
            }
            catch(\Exception $e) {
                DB::rollback();
                Log::error('Error While Saving Card on '. $e->getLine(). ' message: '. $e->getMessage());
            } */
        // return null;
    }

    public function batchSize(): int
    {
        return 15;
    }


    private function _getPubCards($searchString)
    {
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

        // echo curl_error($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        return $data;
    }

    private function _checkCardExist($pubCard, $row) {
        $card = Card::where([['cardhedger_internal_serial_number', $pubCard['card_id']], ['grading_co_serial_number', $row[5]]])->exists();
        return $card;
    }

    private function _createCard($pubCard, $row)
    {
        try {
            if(isset($pubCard['card_id'])) {
            /* If Cards exits add it else create one. */
            $card = (Card::where([['cardhedger_internal_serial_number', $pubCard['card_id']], ['grading_co_serial_number', $row[5]]])->exists()
                        ? Card::where([['cardhedger_internal_serial_number', $pubCard['card_id']], ['grading_co_serial_number', $row[5]]])->first()
                        : new Card());

            // (!Card::where([['cardhedger_internal_serial_number', $pubCard['card_id']], ['grading_co_serial_number', $row[5]]])->exists() ? $card->internal_serial_number = $this->_getNewInternalSerialNumber() : '' );

            $card->internal_serial_number = $row[1];
            $card->cardhedger_internal_serial_number = $pubCard['card_id'];
            $card->name = $pubCard['player'];
            $card->grading_co = $row[4];
            $card->grading_co_serial_number = $row[5];
            $card->year = $row[6];
            $card->set = $row[7];
            $card->card = $pubCard['number'];
            $card->parralel = $pubCard['variant'];
            $card->grade = $row[11];
            $card->category = $pubCard['category'];
            $card->image = $pubCard['image'];
            $card->description = $pubCard['description'];


            $pubCardPrice = $this->_getPubCardLatestPrice($card->cardhedger_internal_serial_number, $card->grade);
            $card->save();

            $cardPrice = array('card_id' => $card->id, 'closing_date' => "",'price' => '', 'created_at' => date('Y-m-d h:i:s') );

            if(!empty($pubCardPrice)) {

                if(!isset($pubCardPrice[0]['closing_date']))
                $pubCardPrice[0]['closing_date'] = "";


                if(!isset($pubCardPrice[0]['price']))
                $pubCardPrice[0]['price'] = "";

                $cardPrice = array(
                    'card_id' => $card->id,
                    'closing_date' => $pubCardPrice[0]['closing_date'],
                    'price' => $pubCardPrice[0]['price'],
                    'created_at' => date('Y-m-d h:i:s')
                );
            }

            DB::table('card_prices')->where('card_id',$card->id)->delete();
            DB::table('card_prices')->insert($cardPrice);

            DB::commit();    
        }        
        }  catch (\Throwable $th) {
            throw $th;
        }
    }


    public function _getPubCardLatestPrice($cardId, $grade) {
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


    private function _getNewInternalSerialNumber()
    {
        $serialNumber = (Card::latest('internal_serial_number')->first() == "" ? config('constants.initital_internal_serial_number') : Card::latest()->value('internal_serial_number') + 1);
        return $serialNumber;
    }
    
}

?>