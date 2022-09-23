<div class="modal fade" id="vendhq-details-modal" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {!! Form::open(array('method' => 'POST', 'role' => 'form', 'class' => 'needs-validation save-vendhq-card-details-form', 'enctype' => 'multipart/form-data')) !!}
        <!-- {!! csrf_field() !!} -->
        <div class="modal-header">
          <h4 class="modal-title">
            {{ trans('modals.cards_modal_vendhq_title') }}
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>
            {!! csrf_field() !!}

            <div class="form-group has-feedback row {{ $errors->has('vend_player_name') ? ' has-error ' : '' }}">
                {!! Form::label('vend_player_name', trans('forms.vendhq_card_label_name'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <!-- <div class="input-group"> -->
                        {!! Form::text('vend_player_name', NULL, array('id' => 'vend_player_name', 'class' => 'form-control', 'placeholder' => trans('forms.vendhq_card_ph_name'))) !!}
                        <!-- <div class="input-group-append">
                            <label class="input-group-text" for="vend_player_name">
                                <i class="fa fa-fw {{ trans('forms.add_card_icon_name') }}" aria-hidden="true"></i>
                            </label>
                        </div> -->
                    <!-- </div> -->
                    @if ($errors->has('vend_player_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vend_player_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group has-feedback row {{ $errors->has('vend_brand_name') ? ' has-error ' : '' }}">
                {!! Form::label('vend_brand_name', trans('forms.vendhq_card_label_brand_name'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <!-- <div class="input-group"> -->
                        {!! Form::text('vend_brand_name', NULL, array('id' => 'vend_brand_name', 'class' => 'form-control', 'placeholder' => trans('forms.vendhq_card_ph_brand_name'))) !!}
                        <!-- <div class="input-group-append">
                            <label class="input-group-text" for="vend_brand_name">
                                <i class="fa fa-fw {{ trans('forms.add_card_icon_name') }}" aria-hidden="true"></i>
                            </label>
                        </div> -->
                    <!-- </div> -->
                    @if ($errors->has('vend_brand_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vend_brand_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group has-feedback row {{ $errors->has('vend_description') ? ' has-error ' : '' }}">
                {!! Form::label('vend_description', trans('forms.vendhq_card_label_description'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                        {!! Form::text('vend_description', NULL, array('id' => 'vend_description', 'class' => 'form-control', 'placeholder' => trans('forms.vendhq_card_ph_description'))) !!}
                    @if ($errors->has('vend_description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vend_description') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group has-feedback row {{ $errors->has('vend_product_type') ? ' has-error ' : '' }}">
                {!! Form::label('vend_product_type', trans('forms.vendhq_card_label_product_type'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <select class="custom-select form-control" name="vend_product_type" id="vend_product_type">
                        <option value="" hidden>Select Product Type</option>
                        <option value="BGS">BGS</option>
                        <option value="CGC">CGC</option>
                        <option value="CSG">CSG</option>
                        <option value="PSA">PSA</option>
                        <option value="SGC">SGC</option>
                    </select>
                    @if ($errors->has('vend_product_type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vend_product_type') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group has-feedback row {{ $errors->has('vend_sku') ? ' has-error ' : '' }}">
                {!! Form::label('vend_sku', trans('forms.vendhq_card_label_sku'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                        {!! Form::text('vend_sku', NULL, array('id' => 'vend_sku', 'class' => 'form-control', 'placeholder' => trans('forms.vendhq_card_label_sku'))) !!}
                    @if ($errors->has('vend_sku'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vend_sku') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


          </p>
        </div>
        <div class="modal-footer">
          {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('modals.cards_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
          
          {!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.cards_modal_default_btn_confirm'), array('class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'vendhq-details-submit-btn' )) !!}

          <div class="error"></div>

        </div>

        {!! Form::close() !!}
    </div>
  </div>
</div>