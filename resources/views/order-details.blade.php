@extends('layouts.app')

@section('template_title')
    {!! trans('usersmanagement.showing-all-users') !!}
@endsection

@section('template_linked_css')
    @if(config('usersmanagement.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('usersmanagement.datatablesCssCDN') }}">
    @endif
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                                @role('admin')
	                                <div class="card">
                    <div class="card-header">
                        @include('partials.errors')
                        @include('partials.status')
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Order Details</span>
                            <div class="row pull-rigt">
                            	<div class="col-sm-12">
	                                <a onclick="exportOrderPDF();" class="btn btn-sm btn-info pull-rigt" href="javascript:;">PDF</a>
                                    @if(isset($orders[0]->orderStatus)&&$orders[0]->orderStatus=="Pending")
                                    &nbsp;&nbsp;<a class="btn btn-sm btn-primary pull-rigt" href="{!! url('order-status/'.$orders[0]->order_id.'/Intake/status') !!}">Approve Order for Intake</a>
                                    @endif
                                    
                                    @if(isset($orders[0]->orderStatus)&&$orders[0]->orderStatus=="Verified")
                                    &nbsp;&nbsp;<a class="btn btn-sm btn-primary pull-rigt" href="{!! url('order-status/'.$orders[0]->order_id.'/Processed/status') !!}">Order Processed</a>                       @endif									                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="card-body">
                        @if(config('transactions.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif

                        <div class="row">
                           <div class="col-md-6">
                            {!! $userinfo->first_name." ".$userinfo->last_name !!}<br />
                            {!! $userinfo->email !!}<br />
                            {!! $userinfo->phone !!}<br />
                            <?php $address  = json_decode($userinfo->address); ?>
                            {!! isset($address->street)&&$address->street!='' ? $address->street : "" !!}<br />
                            {!! isset($address->zip)&&$address->zip!='' ? $address->zip : "" !!}
                            </div>
                            <div class="col-md-6">
                            <div class="pull-right">Order # : <strong>{!! $orders[0]->order_id !!}</strong></div><br />
                            <div class="pull-right">Order Status : <strong>{!! $orders[0]->orderStatus !!}</strong></div>
                           </div>
                            
                        </div>
                        <br />
                        <hr>
                        <div class="table-responsive products-table">
                            <table id="sample1" class="table table-striped table-sm">
                                <thead class="thead">
                                    <tr>
                                            <td class="hidden-sm hidden-xs hidden-md">Name</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Grading Co</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Grading Co Serial Number</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Year</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Set</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Card Number</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Parallel</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Grade</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Category</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Estimated Value</td>
                                        <?php /* @if(isset($orders[0]->orderStatus)&&$orders[0]->orderStatus=="Processed") */ ?>
                                            <td class="hidden-sm hidden-xs hidden-md">Minted</td>
                                            <td class="hidden-sm hidden-xs hidden-md">Sent To Wallet</td>
                                        <?php /* @endif */ ?>
	                                        <td class="hidden-sm hidden-xs hidden-md">Action</td>
                                    </tr>
                                </thead>
                                <tbody>

                                	@foreach($orders as $order)
                                <tr>
                                    <td>{!! $order->name !!}</td>
                                    <td>{!! $order->grading_co !!}</td>
                                    <td>{!! $order->grading_co_serial_number !!}</td>
                                    <td>{!! $order->year !!}</td>
                                    <td>{!! $order->set !!}</td>
                                    <td>{!! $order->card !!}</td>
                                    <td>{!! $order->parralel !!}</td>
                                    <td>{!! $order->grade !!}</td>
                                    <td>{!! $order->category !!}</td>
                                    <td>{!! $order->estimated_value !!}</td>
                                    <td nowrap="nowrap" align="center">
                                    	    @if(isset($order->minted)&&$order->minted!="")
                                                    {!!  $order->minted !!}
                                            @else
                                            	@if($orders[0]->orderStatus=="Processed")
                                            <a href="{!! url('order-product-for-minted/'.$order->order_id.'/'.$order->id.'/Yes/status') !!}" class="btn btn-sm btn-info"><i class="fa fa-check-square"></i></a>&nbsp;<a href="{!! url('order-product-for-minted/'.$order->order_id.'/'.$order->id.'/No/status') !!}" class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i></a>
                                                @endif
                                            @endif
                                    </td>
                                    <td nowrap="nowrap" align="center">

                                            @if(isset($order->sent_to_wallet)&&$order->sent_to_wallet!="")
                                                    {!!  $order->sent_to_wallet !!}
                                            @else
                                                @if($orders[0]->orderStatus=="Processed")
                                            <a href="{!! url('order-product-for-senttowallet/'.$order->order_id.'/'.$order->id.'/Yes/status') !!}" class="btn btn-sm btn-info"><i class="fa fa-check-square"></i></a>&nbsp;<a href="{!! url('order-product-for-senttowallet/'.$order->order_id.'/'.$order->id.'/No/status') !!}" class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i></a>
                                            	@endif
                                        	@endif
                                    </td>
                                    <td nowrap="nowrap" align="center">
                                        @if($orders[0]->orderStatus!="Pending")
                                            @if(isset($order->status)&&$order->status!="")
                                                    {!!  $order->status !!}
                                            @else
                                            <a href="{!! url('order-product/'.$order->order_id.'/'.$order->id.'/verified/status') !!}" class="btn btn-sm btn-info"><i class="fa fa-check-square"></i></a>&nbsp;<a href="{!! url('order-product/'.$order->order_id.'/'.$order->id.'/rejected/status') !!}" class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <?php /*
                        @if(isset($orders[0]->orderStatus)&&$orders[0]->orderStatus=="Verified")
                        <hr>
                        <form action="{!! url('processed-order') !!}" method="post" accept-charset="UTF-8" role="form" id="processedorder">
		                        {!! csrf_field() !!}
                        		<input type="hidden" name="orderid" value="{!! $orders[0]->order_id !!}">	
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">	
                                            <label class="control-label col-md-2">Minted</label>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="minted" id="inlineRadio1" value="Yes">
                                              <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="minted" id="inlineRadio2" value="No">
                                              <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                            <div id="minted_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label col-md-2">Sent To Wallet</label>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="sent_to_wallet" id="inlineRadio3" value="Yes">
                                              <label class="form-check-label" for="inlineRadio3">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="sent_to_wallet" id="inlineRadio4" value="No">
                                              <label class="form-check-label" for="inlineRadio4">No</label>
                                            </div>
                                            <div id="sent_to_wallet_error"></div>
                                        </div>
                                    </div>
                                </div>
                         
                                <div class="form-group">                                        	
                                    <div class="row">
                                        <div class="col-md-12">
                                        <button class="btn btn-sm btn-primary pull-rigt" type="button" name="order_processed" id="order_processed">Order Processed</button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                        @endif
                        */ ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-10">
                            Total Cards : <?php echo ((isset($orders)&&count($orders)>0) ? count($orders) : 0); ?>
                            </div>
                            <?php /*<div class="col-md-2">
                            Change Order Status : 
                            <select class="form-control" id="order_status" name="order_status">
	                            <option value="Pending">Pending</option>
                            	<option value="In Process">In Process</option>
                                <option value="Complete">Complete</option>
                            </select>
                            </div>*/ ?>
                        </div>    
                    </div>
                </div>
								@endrole
								@role('user')
                                    @if($orders[0]->user_id==Auth::user()->id)
									    <div class="card">
                    <div class="card-header">
                        @include('partials.errors')
                        @include('partials.status')

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Order Details</span><a onclick="exportOrderPDF();" class="btn btn-sm btn-info btn-block pull-rigt" style="width:10% !important;" href="javascript:;">PDF</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(config('transactions.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                            {!! $userinfo->first_name." ".$userinfo->last_name !!}<br />
                            {!! $userinfo->email !!}<br />
                            {!! $userinfo->phone !!}<br />
                            <?php $address  = json_decode($userinfo->address); ?>
                            {!! isset($address->street)&&$address->street!='' ? $address->street : "" !!}<br />
                            {!! isset($address->zip)&&$address->zip!='' ? $address->zip : "" !!}
                            </div>
                            <div class="col-md-6">
                            <div class="pull-right">Order # : {!! $orders[0]->order_id; !!}</div><br />
                            </div>
                        </div>
                        <br />
                        <hr>
                        <div class="table-responsive products-table">
                            <table id="sample1" class="table table-striped table-sm">
                                <thead class="thead">
                                    <tr>
                                        <td class="hidden-sm hidden-xs hidden-md">Name</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Grading Co</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Grading Co Serial Number</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Year</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Set</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Card Number</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Parallel</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Grade</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Category</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Estimated Value</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Status</td>
                                    </tr>
                                </thead>
                                <tbody>

                                	@foreach($orders as $order)
                                            <tr>
                                                <td>{!! $order->name !!}</td>
                                                <td>{!! $order->grading_co !!}</td>
                                                <td>{!! $order->grading_co_serial_number !!}</td>
                                                <td>{!! $order->year !!}</td>
                                                <td>{!! $order->set !!}</td>
                                                <td>{!! $order->card !!}</td>
                                                <td>{!! $order->parralel !!}</td>
                                                <td>{!! $order->grade !!}</td>
                                                <td>{!! $order->category !!}</td>
                                                <td>{!! $order->estimated_value !!}</td>
                                                <td>{!! ((isset($order->status)&&$order->status!="")? $order->status : "") !!}</td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                            Total Cards : <?php echo ((isset($orders)&&count($orders)>0) ? count($orders) : 0); ?>
                            </div>
                        </div>    
                    </div>
                </div>
                                    @else:
                                    	No Record Found
                                    @endif
								@endrole
                
            </div>
        </div>
    </div>
	<script type="text/javascript">
        function exportOrderPDF() {
            var orderid = '<?php echo $orders[0]->order_id; ?>';
            var url = "{!! route('export.order.pdf') !!}?";
            var dataString = "mode=export_pdf&orderid=" + orderid;
            window.open(url + dataString);
        }
    </script>
@endsection

@section('footer_scripts')
    {{-- @if ((count($users) > config('usersmanagement.datatablesJsStartCount')) && config('usersmanagement.enabledDatatablesJs')) --}}

    {{-- @endif --}}
    {{-- @include('scripts.datatables') --}}

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @if(config('transactions.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('transactions.enableSearchUsers'))
        @include('scripts.search-users')
    @endif
@endsection

