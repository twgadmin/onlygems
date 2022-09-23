@extends('layouts.app')

@section('template_title')
    {!! trans('transactions.create-new-transaction') !!}
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">
                        @include('partials.errors')
                        @include('partials.status')
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('transactions.create-new-transaction') !!}
                            <div class="pull-right">
                                <a href="{{ route('transactions') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('transactions.tooltips.back-transactions') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('transactions.buttons.back-to-transactions') !!}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        {!! Form::open(array('route' => 'transactions.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                        {!! csrf_field() !!}

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Delivery Details</legend>


                            <div class="form-group has-feedback row {{ $errors->has('supplier') ? ' has-error ' : '' }}">
                                {!! Form::label('supplier', trans('forms.create_product_label_supplier'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">

                                        {!! Form::text('supplier', NULL, array('id' => 'supplier', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_supplier'))) !!}


                                        <div class="input-group-append">
                                            <label class="input-group-text" for="supplier">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_supplier') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('supplier'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('supplier') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('supplier_invoice_number') ? ' has-error ' : '' }}">
                                {!! Form::label('supplier_invoice_number', trans('forms.create_product_label_supplier_invoice_number'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('supplier_invoice_number', NULL, array('id' => 'supplier_invoice_number', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_supplier_invoice_number'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="supplier_invoice_number">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_supplier_invoice_number') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('supplier_invoice_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('supplier_invoice_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('delivery_date') ? ' has-error ' : '' }}">
                                {!! Form::label('delivery_date', trans('forms.create_product_label_delivery_date'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group datepicker1">
                                        {!! Form::text('delivery_date', NULL, array('id' => 'delivery_date', 'class' => 'form-control datepicker', 'placeholder' => trans('forms.create_product_ph_delivery_date'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="delivery_date">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_delivery_date') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('delivery_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('delivery_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('order_number') ? ' has-error ' : '' }}">
                                {!! Form::label('order_number', trans('forms.create_product_label_order_number'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('order_number', NULL, array('id' => 'order_number', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_order_number'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="order_number">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_order_number') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('order_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('order_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('delivery_note') ? ' has-error ' : '' }}">
                                {!! Form::label('delivery_note', trans('forms.create_product_label_delivery_note'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('delivery_note', NULL, array('id' => 'delivery_note', 'class' => 'form-control', 'rows' => 3, 'cols' => 40, 'placeholder' => trans('forms.create_product_ph_delivery_note'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="delivery_note">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_delivery_note') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('delivery_note'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('delivery_note') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>


                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Product Details</legend>

                            <small>Choose products to receive by searching</small>

                            <table class="table table-striped add-products-to-transaction-table" >
                                <thead>
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Cost Price</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td colspan="6">
                                            <select id="ajax-select" placeholder="&#xF002; Search to add a product" class="selectpicker" style="font-family:Font Awesome 5 Free" data-live-search="true"></select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6">
                                            <p>Add products to this delivery</p>
                                        </td>
                                    </tr>
                                    <tr><td colspan="6" class="products_total">$0.00</td></tr>
                                </tbody>
                            </table>

                        </fieldset>

                        {!! Form::button(trans('forms.create_transaction_button_text'), array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                    {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@include('modals.modal-products')
@endsection

@section('footer_scripts')
@endsection
