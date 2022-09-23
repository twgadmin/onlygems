@extends('layouts.app')

@section('template_title')
    {!! trans('cards.editing-card') !!}
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
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
                            {!! trans('cards.editing-card') !!}
                            <div class="pull-right">
                                <a href="{{ route('cards-inventory') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="top" title="{{ trans('cards.tooltips.back-cards') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('cards.buttons.back-to-cards') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                        

                        {!! Form::open(array('route' => ['cards.update', $card->id], 'method'=>'POST', 'role' => 'form', 'class' => 'needs-validation m-t-20 edit-card-form-cardhedger')) !!}

                            {!! csrf_field() !!}

                            <!-- @method('PUT') -->


                            <div class="form-group has-feedback row {{ $errors->has('serial_number') ? ' has-error ' : '' }}">
                                {!! Form::label('serial_number', trans('forms.add_card_label_serial_number'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                    {!! Form::text('serial_number', $card->internal_serial_number, array('id' => 'serial_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_serial_number'))) !!}
                                    @if ($errors->has('serial_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('serial_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('player_name') ? ' has-error ' : '' }}">
                                {!! Form::label('player_name', trans('forms.add_card_label_name'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                        {!! Form::text('player_name', $card->name, array('id' => 'player_name', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_name'))) !!}
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
                                        <?php
                                            $grading_co = $card->grading_co;
                                        ?>
                                        <select class="custom-select form-control" name="grading_co" id="edit-grading-co">
                                            <option value="" hidden>Select Grading Co</option>
                                            <option value="CSG" <?php echo ($grading_co == 'CSG' ? 'selected' : '') ?> >CSG</option>
                                            <option value="BGS" <?php echo ($grading_co == 'BGS' ? 'selected' : '') ?>>BGS</option>
                                            <option value="PSA" <?php echo ($grading_co == 'PSA' ? 'selected' : '') ?>>PSA</option>
                                            <option value="SGC" <?php echo ($grading_co == 'SGC' ? 'selected' : '') ?>>SGC</option>
                                            <option value="HGA" <?php echo ($grading_co == 'HGA' ? 'selected' : '') ?>>HGA</option>
                                        </select>
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
                                        {!! Form::text('grading_co_serial_number', $card->grading_co_serial_number, array('id' => 'grading_co_serial_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_grading_co_serial_number'))) !!}
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
                                        {!! Form::text('year', $card->year, array('id' => 'year', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_year'))) !!}
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
                                        {!! Form::text('set', $card->set, array('id' => 'set', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_set'))) !!}
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
                                        {!! Form::text('parralel', $card->parralel, array('id' => 'parralel', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_parralel'))) !!}
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
                                        <select class="custom-select form-control" name="grade" id="edit-grade">
                                            <option value="" hidden>Select Grade</option>
                                            <?php
                                                for($i=10; $i >= .5; $i=$i-.5) {
                                                    $selected = ($card->grade == $card->grading_co.' '.$i ? 'selected' : '' );
                                                    echo "<option ".$selected." value='".$card->grading_co.' '.$i."'>".$card->grading_co.' '.$i."</option>";
                                                }
                                            ?>
                                        </select>
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
                                        {!! Form::text('category', $card->category, array('id' => 'category', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_category'))) !!}
                                    @if ($errors->has('category'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('card_number') ? ' has-error ' : '' }}">
                                <div class="col-md-3">    
                                    <!-- {{ Form::checkbox('option_card_number', 'yes', false , array('id'=>'option_card_number')) }} -->
                                    {!! Form::label('option_card_number', trans('forms.add_card_label_card'), array('class' => 'control-label')) !!}
                                </div>
                                <div class="col-md-9">
                                        {!! Form::text('card_number', $card->card, array('id' => 'card_number', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_card'))) !!}
                                    @if ($errors->has('card_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('card_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('description') ? ' has-error ' : '' }}">
                                {!! Form::label('description', trans('forms.add_card_label_description'), array('class' => 'col-md-3 control-label')) !!}
                                <div class="col-md-9">
                                        {!! Form::text('description', $card->description, array('id' => 'description', 'class' => 'form-control', 'placeholder' => trans('forms.add_card_ph_description'))) !!}
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                         
                            <div class="row">
                            <div class="offset-md-5 col-md-3 col-sm-6">
                                {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success edit-card-btn margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                
                                <!-- 
                                    {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-3 mb-2 btn-save confirmSaveBtn','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                                 -->
                                 </div>
                            </div>

                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-show-cards-list')
    @include('modals.modal-save')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.check-changed')
@endsection
