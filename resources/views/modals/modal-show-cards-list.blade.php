<div class="modal fade" id="cards-list-modal" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {!! Form::open(array('route' => 'cards.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation save-new-card-form', 'enctype' => 'multipart/form-data')) !!}
        <!-- {!! csrf_field() !!} -->
        <div class="modal-header">
          <h4 class="modal-title">
            {{ trans('modals.cards_modal_default_title') }}
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>
            {{ trans('modals.form_modal_default_message') }}
          </p>
        </div>
        <div class="modal-footer">
          
          <table width="100%">
            <tbody>
              <tr><td>{!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.cards_modal_default_btn_submit'), array('class' => 'btn btn-primary card-list-modal-submit-btn','disabled'=>true, 'type' => 'submit', 'id' => 'prices-submit-btn')) !!}
</td><td>{!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.cards_modal_default_btn_submit_both'), array('class' => 'btn btn-primary card-list-modal-submit-btn','disabled'=>true, 'type' => 'submit', 'id' => 'both-submit-btn')) !!}</td>
              <td>{!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('modals.cards_modal_default_btn_cancel'), array('class' => 'btn btn-secondary card-list-modal-cancel-btn', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}</td></tr>
            </tbody>
          
</table>

        </div>

        {!! Form::close() !!}
    </div>
  </div>
</div>