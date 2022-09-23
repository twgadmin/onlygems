@extends('layouts.app')

@section('template_title')
    {!! trans('products.create-new-product') !!}
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
                            {!! trans('products.create-new-product') !!}
                            <div class="pull-right">
                                <a href="{{ route('users') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('usersmanagement.tooltips.back-users') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('products.buttons.back-to-products') !!}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        {!! Form::open(array('route' => 'products.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation create-product-form')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('product_name') ? ' has-error ' : '' }}">
                                {!! Form::label('product_name', trans('forms.create_product_label_name'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('product_name', NULL, array('id' => 'product_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_name'))) !!}
                                        <div class="input-group-append">
                                            <label for="product_name" class="input-group-text">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_name') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('product_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('product_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('brand_name') ? ' has-error ' : '' }}">
                                {!! Form::label('brand_name', trans('forms.create_product_label_brand_name'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('brand_name', NULL, array('id' => 'brand_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_brand_name'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="brand_name">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_brand_name') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('brand_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('brand_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            {{-- <div class="form-group has-feedback row {{ $errors->has('seller_name') ? ' has-error ' : '' }}">
                                {!! Form::label('seller_name', trans('forms.create_product_label_seller_name'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('seller_name', NULL, array('id' => 'seller_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_seller_name'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="seller_name">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_seller_name') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('seller_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('seller_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('upc_code') ? ' has-error ' : '' }}">
                                {!! Form::label('upc_code', trans('forms.create_product_label_upc_code'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('upc_code', NULL, array('id' => 'upc_code', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_upc_code'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="upc_code">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_upc_code') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('upc_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('upc_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('style_number') ? ' has-error ' : '' }}">
                                {!! Form::label('style_number', trans('forms.create_product_label_style_number'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('style_number', NULL, array('id' => 'style_number', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_style_number'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="style_number">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_style_number') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('style_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('style_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('color') ? ' has-error ' : '' }}">
                                {!! Form::label('color', trans('forms.create_product_label_color'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('color',NULL, array('id' => 'color', 'class' => 'form-control ', 'placeholder' => trans('forms.create_product_ph_color'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="color">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_color') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('color'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('color') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div> --}}

                            <div class="form-group has-feedback row {{ $errors->has('size') ? ' has-error ' : '' }}">
                                {!! Form::label('size', trans('forms.create_product_label_size'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('size', '4-14', array('id' => 'size', 'class' => 'form-control', 'readonly' => 'true', 'placeholder' => trans('forms.create_product_ph_size'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="size">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_size') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('size'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('size') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            {{-- <div class="form-group has-feedback row {{ $errors->has('country_of_origin') ? ' has-error ' : '' }}">
                                {!! Form::label('country_of_origin', trans('forms.create_product_label_country_of_origin'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('country_of_origin', NULL, array('id' => 'country_of_origin', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_country_of_origin'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="country_of_origin">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_country_of_origin') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('country_of_origin'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country_of_origin') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('products_in_purchase') ? ' has-error ' : '' }}">
                                {!! Form::label('products_in_purchase', trans('forms.create_product_label_products_in_purchase'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('products_in_purchase', NULL, array('id' => 'products_in_purchase', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_products_in_purchase'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="products_in_purchase">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_products_in_purchase') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('products_in_purchase'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('products_in_purchase') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('purchase_price') ? ' has-error ' : '' }}">
                                {!! Form::label('purchase_price', trans('forms.create_product_label_purchase_price'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('purchase_price', NULL, array('id' => 'purchase_price', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_purchase_price'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="purchase_price">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_purchase_price') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('purchase_price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('purchase_price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('estimated_resell_value') ? ' has-error ' : '' }}">
                                {!! Form::label('estimated_resell_value', trans('forms.create_product_label_estimated_resell_value'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('estimated_resell_value', NULL, array('id' => 'estimated_resell_value', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_estimated_resell_value'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="estimated_resell_value">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_estimated_resell_value') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('estimated_resell_value'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('estimated_resell_value') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('shipping_cost') ? ' has-error ' : '' }}">
                                {!! Form::label('shipping_cost', trans('forms.create_product_label_shipping_cost'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('shipping_cost', NULL, array('id' => 'shipping_cost', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_shipping_cost'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="shipping_cost">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_shipping_cost') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('shipping_cost'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shipping_cost') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('other_costs') ? ' has-error ' : '' }}">
                                {!! Form::label('other_costs', trans('forms.create_product_label_other_costs'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('other_costs', NULL, array('id' => 'other_costs', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_other_costs'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="other_costs">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_other_costs') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('other_costs'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('other_costs') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('estimated') ? ' has-error ' : '' }}">
                                {!! Form::label('estimated', trans('forms.create_product_label_estimated'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('estimated', NULL, array('id' => 'estimated', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_estimated'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="estimated">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_estimated') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('estimated'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('estimated') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div> --}}


                            <div class="form-group has-feedback row {{ $errors->has('description') ? ' has-error ' : '' }}">
                                {!! Form::label('description', trans('forms.create_product_label_description'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('description', NULL, array('id' => 'description', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_description'))) !!}
                                        {{-- <div class="input-group-append">
                                            <label class="input-group-text" for="description">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_description') }}" aria-hidden="true"></i>
                                            </label>
                                        </div> --}}
                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('tags') ? ' has-error ' : '' }}">
                                {!! Form::label('tags', trans('forms.create_product_label_tags'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('tags', NULL, array('data-role' =>'tagsinput sometext', 'id' => 'tags', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_tags'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="tags">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_tags') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('tags'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tags') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('product_type') ? ' has-error ' : '' }}">
                                {!! Form::label('product_type', trans('forms.create_product_label_product_type'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control" name="product_type" id="product_type">
                                            <option value="hidden">{{ trans('forms.create_product_ph_product_type') }}</option>
                                            <option value="Accessories">Accessories</option>
                                            <option value="Apparel">Apparel</option>
                                            <option value="Caps">Caps</option>
                                            <option value="Cards">Cards</option>
                                            <option value="Collectables">Collectables</option>
                                            <option value="Consign">Consign</option>
                                            <option selected value="Shoes">Shoes</option>
                                            <option value="Toy">Toy</option>

                                        </select>
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="product_type">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_product_type') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('product_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('product_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('sku_code_type') ? ' has-error ' : '' }}">
                                {!! Form::label('sku_code_type', trans('forms.create_product_label_sku_code_type'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">

                                        <select class="custom-select form-control" name="sku_code_type" id="sku_code_type">
                                            <option value="hidden">{{ trans('forms.create_product_ph_sku_code_type') }}</option>
                                            <option selected value="Auto-generated">Auto-generated</option>
                                            <option value="Custom">Custom</option>
                                            <option value="EAN">EAN</option>
                                            <option value="ISBN">ISBN</option>
                                            <option value="ITF">ITF</option>
                                            <option value="JAN">JAN</option>
                                            <option value="UPC">UPC</option>
                                        </select>


                                        <div class="input-group-append">
                                            <label class="input-group-text" for="sku_code_type">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_sku_code_type') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('sku_code_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sku_code_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('sku_code') ? ' has-error ' : '' }}">
                                {!! Form::label('sku_code', trans('forms.create_product_label_sku_code'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('sku_code', NULL, array('id' => 'sku_code', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_sku_code'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="sku_code">
                                                <i class="fa fa-fw {{ trans('forms.create_product_icon_sku_code') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('sku_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sku_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            {!! Form::button(trans('forms.create_product_button_text'), array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right create-product-btn','type' => 'submit' )) !!}
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection
