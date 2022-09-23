<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Option;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->domain_prefix = "flyfred";

        $this->authorize_url = "https://secure.vendhq.com/connect";
        $this->token_url = "https://".$this->domain_prefix.".vendhq.com/api/1.0/token";

        //	callback URL specified when the application was defined--has to match what the application says
        $this->callback_uri = "http://127.0.0.1:8000/create-transaction";

        $this->test_api_url = "https://".$this->domain_prefix.".vendhq.com/api/2.0/suppliers?page_size=100";

        //	client (application) credentials - located at apim.byu.edu
        $this->client_id = "WJ7yow5G7vP3iZhH7ry9g41c8IYdISRe";
        $this->client_secret = "I8CzH8Mv7qFYsH3mUJBEaR2IkvXEjKLr";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('transactions.show-transactions');
    }

    public function list(Request $request)
    {
        $limit = $request->length;
        $offset = $request->start;
        $search = $request->search['value'];
        $order = $request->order[0]['column'];
        $adod = $request->order[0]['dir'];
        $oby = "id";

        $totfil = $totrec = Transaction::count();

        $totalTransactionSearch =   Transaction::with(['transactionItems', 'transactionItems.options', 'transactionItems.variations', 'suppliers'])
        ->when(!empty($search), function($query) use($search) {
            $query->whereHas('suppliers', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('product_name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('qty', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('cost_price', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('total_cost', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('order_number','LIKE','%'.$search.'%')
            ->orWhere('supplier_invoice_number','LIKE','%'.$search.'%')
            ->orWhere('delivery_date','LIKE','%'.$search.'%')
            ->orWhere('delivery_notes','LIKE','%'.$search.'%');
        })->count();
        $totfil = $totalTransactionSearch; #TOTAL FILTERED RECORDS

        $transactions = Transaction::with(['transactionItems', 'transactionItems.options', 'transactionItems.variations', 'suppliers'])
        ->when(!empty($search), function($query) use($search) {
            $query->whereHas('suppliers', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('product_name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('qty', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('cost_price', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('transactionItems', function($q) use ($search) {
                $q->where('total_cost', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('order_number','LIKE','%'.$search.'%')
            ->orWhere('supplier_invoice_number','LIKE','%'.$search.'%')
            ->orWhere('delivery_date','LIKE','%'.$search.'%')
            ->orWhere('delivery_notes','LIKE','%'.$search.'%');
        })->orderBy($oby, $adod)->skip($offset)->take($limit)->get();

        $adata['recordsTotal'] = $totrec; #TOTAL RECORDS
        $adata['recordsFiltered'] = $totfil; #TOTAL FILTERED RECORDS

        if(!empty($transactions)) {
            /* prepare html of listing - datatable */
            $i = 0;
            foreach($transactions as $transaction) {
                $productNames = $qty = $costPrice = $totalCost = '';
                foreach($transaction->transactionItems as $key=>$items) {
                    $productNames .= '<h5><span class="label label-primary">'.$items['product_name'].'</span></h5>';
                    $qty .= '<h5><span class="label label-primary">'.$items['qty'].'</span></h5>';
                    $costPrice .= '<h5><span class="label label-primary">$'.$items['cost_price'].'</span></h5>';
                    $totalCost .= '<h5><span class="label label-primary">$'.$items['total_cost'].'</span></h5>';
                }

                $dataArray = array(
                    ($offset + $i) + 1,
                    $transaction->order_number,
                    $productNames,
                    $transaction->suppliers['name'],
                    $transaction->supplier_invoice_number,
                    $transaction->delivery_date,
                    $transaction->delivery_notes,
                    $qty,
                    $costPrice,
                    $totalCost,
                    date('d-m-Y', strtotime($transaction->created_at)),
                    '<a class="btn btn-sm btn-info btn-block edit-btn" href="transactions/'. $transaction->id .'/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit cursor-pointer"></i></a>'
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
        return View('transactions.create-transaction');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTransactionRequest $request)
    {
        $supplier = Supplier::firstOrCreate([
            'name' => $request->supplier
        ],['activated' => 1]
        );
        $transaction = new Transaction();
        $transaction->supplier_id = $supplier->id;
        $transaction->supplier_invoice_number = $request->supplier_invoice_number;
        $transaction->delivery_date = $request->delivery_date;
        $transaction->order_number = $request->order_number;
        $transaction->delivery_notes = $request->delivery_note;
        $transaction->save();

        $dataArray = array();
        if(!empty($request->selection)) {
            foreach($request->selection as $key=>$selectedProducts)
            {
                $ids = explode(',',$selectedProducts); // explode products id, options id, variation id
                $dataArray[] = array( 'transaction_id' => $transaction->id, 'product_id' => $ids[0], 'variation_id' => $ids[2], 'option_id' => $ids[1], 'product_name' => $request->product_name[$key], 'qty' => $request->qty[$key], 'cost_price' => $request->cost_price[$key], 'total_cost' => $request->total_cost[$key], 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s') );
            }
            TransactionItem::insert($dataArray);
            return redirect()->route('create-transaction')->with('success', 'Transaction Created Successfully.');
        }
        else
        return redirect()->route('create-transaction')->with('error', 'Please add products into this transaction');

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
        $transaction = Transaction::find($id);
        $supplier = Supplier::find($transaction->supplier_id);
        $transactionItems = TransactionItem::where('transaction_id',$transaction->id)->get();
        $data = [
            'transaction' => $transaction,
            'supplier' => $supplier,
            'transactionItems' => $transactionItems
        ];

        // ->with($data)
        return view('transactions.edit-transaction')->with($data);
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
        $supplier = Supplier::firstOrCreate([
            'name' => $request->supplier
        ],['activated' => 1]
        );
        $transaction = Transaction::find($id);
        $transaction->supplier_id = $supplier->id;
        $transaction->supplier_invoice_number = $request->supplier_invoice_number;
        $transaction->delivery_date = $request->delivery_date;
        $transaction->order_number = $request->order_number;
        $transaction->delivery_notes = $request->delivery_note;
        $transaction->save();

        $dataArray = $conditions = array();
        foreach($request->selection as $key=>$selectedProducts)
        {
            $ids = explode(',',$selectedProducts); // explode products id, options id, variation id
            $conditions = array('transaction_id' => $transaction->id, 'product_id' => $ids[0], 'variation_id' => $ids[2], 'option_id' => $ids[1]);
            $dataArray = array('product_name' => $request->product_name[$key], 'qty' => $request->qty[$key], 'cost_price' => $request->cost_price[$key], 'total_cost' => $request->total_cost[$key], 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s') );
            TransactionItem::updateOrCreate($conditions, $dataArray);
        }
        return redirect()->route('transactions.edit', [$id])->with('success', 'Transaction Updated Successfully.');
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


    /**
     * delete transaction items
     */

     public function deleteItem(Request $request) {
        $id = $request->id;
        $deletedItem = TransactionItem::where('id',$id)->delete();
        $message = 'error';
        if($deletedItem) {
            $message = 'success';
        }
        return $message;
     }
}
