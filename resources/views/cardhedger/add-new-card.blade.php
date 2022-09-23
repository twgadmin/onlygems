@extends('layouts.app')

@section('template_title')
    {!! trans('cardhedger.add-new-card') !!}
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
                            {!! trans('cardhedger.add-new-card') !!}
                            <div class="pull-right">
                                <a href="{{ route('cardhedger-list') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('cardhedger.tooltips.back-cardhedger') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('cardhedger.buttons.back-to-cardhedger') !!}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        {!! Form::open(array('route' => 'cardhedger.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                        {!! csrf_field() !!}


                            <div class="form-group has-feedback row {{ $errors->has('date_time') ? ' has-error ' : '' }}">
                                {!! Form::label('date_time', trans('forms.add_card_label_date_time'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group datepicker1">
                                        {!! Form::text('date_time', NULL, array('id' => 'date_time', 'class' => 'form-control card-datepicker', 'placeholder' => trans('forms.add_card_ph_date_time'))) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="date_time">
                                                <i class="fa fa-fw {{ trans('forms.add_card_icon_date_time') }}" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('date_time'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('date_time') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        {!! Form::button(trans('forms.add_card_button_text'), array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                    {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection
