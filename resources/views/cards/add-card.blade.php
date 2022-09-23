@extends('layouts.app')

@section('template_title')
    {!! trans('cards.add-new-card') !!}
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
                            {!! trans('cards.add-new-card') !!}
                            <div class="pull-right">
                                <a href="{{ route('cards-inventory') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('cards.tooltips.back-cards') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('cards.buttons.back-to-cards') !!}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        {!! Form::open(array('route' => 'cards.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation add-card-from-cardhedger', 'enctype' => 'multipart/form-data', 'name' => 'add-card-form')) !!}

                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row {{ $errors->has('serial_number') ? ' has-error ' : '' }}">
                            {!! Form::label('serial_number', trans('forms.add_card_label_serial_number'), array('class' => 'col-md-3 control-label')) !!}
                            <div class="col-md-9">
                                {!! Form::text('serial_number', $serial_number, array('id' => 'serial_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_serial_number'))) !!}
                                @if ($errors->has('serial_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('serial_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="button" class="btn btn-default pull-right search-card-details">Search</button>
                        </div>

                        <div class="add-card-details hidden">
                            <div class="form-group has-feedback row {{ $errors->has('player_name') ? ' has-error ' : '' }}">
                                {!! Form::label('player_name', trans('forms.add_card_label_name'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('player_name', NULL, array('id' => 'player_name', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_name'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="player_name">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_name') }}" aria-hidden="true"></i>
                                            </label>
                                        </div> -->
                                    <!-- </div> -->
                                    @if ($errors->has('player_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('player_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('grading_co') ? ' has-error ' : '' }}">
                                {!! Form::label('grading_co', trans('forms.add_card_label_grading_co'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        <!-- {!! Form::text('grading_co', NULL, array('id' => 'grading_co', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_grading_co'))) !!}
                                        CSG BGS PSA SGC HGA -->

                                        <select class="custom-select form-control" name="grading_co" id="grading_co">
                                            <option value="" hidden>Select Grading Co</option>
                                            <option value="CSG">CSG</option>
                                            <option value="BGS">BGS</option>
                                            <option value="PSA">PSA</option>
                                            <option value="SGC">SGC</option>
                                            <option value="HGA">HGA</option>
                                        </select>


                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="grading_co">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_grading_co') }}" aria-hidden="true"></i>
                                            </label>
                                        </div> -->
                                    <!-- </div> -->
                                    @if ($errors->has('grading_co'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('grading_co') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('grading_co_serial_number') ? ' has-error ' : '' }}">
                                {!! Form::label('grading_co_serial_number', trans('forms.add_card_label_grading_co_serial_number'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('grading_co_serial_number', NULL, array('id' => 'grading_co_serial_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_grading_co_serial_number'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="grading_co_serial_number">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_grading_co_serial_number') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('grading_co_serial_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('grading_co_serial_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('year') ? ' has-error ' : '' }}">
                                {!! Form::label('year', trans('forms.add_card_label_year'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('year', NULL, array('id' => 'year', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_year'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="year">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_year') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('year'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('year') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('set') ? ' has-error ' : '' }}">
                                {!! Form::label('set', trans('forms.add_card_label_set'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('set', NULL, array('id' => 'set', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_set'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="set">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_set') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('set'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('set') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('parralel') ? ' has-error ' : '' }}">
                                {!! Form::label('parralel', trans('forms.add_card_label_parralel'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('parralel', NULL, array('id' => 'parralel', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_parralel'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="parralel">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_parralel') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('parralel'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('parralel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('grade') ? ' has-error ' : '' }}">
                                {!! Form::label('grade', trans('forms.add_card_label_grade'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        <!-- {!! Form::text('grade', 'PSA 10', array('id' => 'grade', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_grade'))) !!} -->

                                        <select class="custom-select form-control" name="grade" id="grade">
                                            <option value="" hidden>Select Grade</option>
                                        </select>

                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="grade">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_grade') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('grade'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('grade') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('category') ? ' has-error ' : '' }}">
                                {!! Form::label('category', trans('forms.add_card_label_category'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        {!! Form::text('category', NULL, array('id' => 'category', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_category'))) !!}
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="category">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_category') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('category'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('card_number') ? ' has-error ' : '' }}">
                                <div class="col-md-3">    
                                    {{ Form::checkbox('option_card_number', 'yes', false , array('id'=>'option_card_number')) }}
                                    {!! Form::label('option_card_number', trans('forms.add_card_label_card'), array('class' => 'control-label')) !!}
                                </div>
                                <div class="col-md-9">
                                    <!-- <div class="input-group"> -->
                                        <!-- {!! Form::checkbox('card_number', '92', array('id' => 'card_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_card'))) !!} -->
                                        
                                        {!! Form::text('card_number', NULL, array('id' => 'card_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_card'))) !!}
                                    
                                        <!-- <div class="input-group-append">
                                            <label class="input-group-text" for="card_number">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_card') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div> -->
                                    @if ($errors->has('card_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('card_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="search_status" class="search-type">

                            {!! Form::button(trans('forms.add_card_button_text'), array('class' => 'btn btn-success add-card-btn margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modals.modal-show-cards-list')
@include('modals.modal-show-vendhq-details')
@endsection


@section('footer_scripts')
@endsection
