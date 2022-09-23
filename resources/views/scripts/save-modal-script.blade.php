<script type="text/javascript">
    $(function(){
	    // CONFIRMATION SAVE MODEL
        $('.confirmSaveBtn').on('click', function (e) {
            var message = $(e.target).attr('data-message');
            var title = $(e.target).attr('data-title');
            var form = $(e.target).closest('form');
            $(e.target).parents('.container').next('div.modal').find('.modal-body p').text(message);

            $(this).parents('.container').next('div.modal').find('.modal-title').text(title);
            $(this).parents('.container').next('div.modal').find('.modal-footer .confirm-modal-btn').data('form', form);
        });
        $('.confirmSave').find('.modal-footer .confirm-modal-btn').on('click', function(){
            $(this).data('form').submit();
        });
    });

</script>
