
$(function() {

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }


      $(document).keypress(
        function(event){
          if (event.which == '13') {
            event.preventDefault();
          }
      });


/** submitting card hedger form */
$(".add-card-from-cardhedger").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      player_name: "required",
      year: "required",
      set: "required",
      // card_number: "required",
      parralel: "required", 
      grade: "required", 
    },
    // Specify validation error messages
    messages: {
      player_name: "Please enter Player Name",
      year: "Please enter Year",
      set: "Please enter Set",
      // card_number: "Please enter Card Number",
      parralel: "Please enter Parralel",
      grade: "Please select Grade (By selecting Grading Co first)",
      
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {

        $('.add-card-btn').attr('disabled',true);
        $('.add-card-btn').html('Fetching Cards...');
        var formData = new FormData(form);
          $.ajax({
              url: '/fetch-cardhedger-cards-from-api',
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              method: 'POST',
              success: function(data){
                  var parsedData = JSON.parse(data);
                  var list = parsedData['list'];

                  var html = '';
                  if(list.length > 0) {
                  $.each(list, function(index, value) {

                    if(!value.image)
                    value.image = '/images/default.png';

                    if(!value.description)
                    value.description = '--';

                    if(!value.price)
                    value.price = '--';

                    if(!value.number)
                    value.number = '--';

                    html += '<div class="box-card-description"><label class="labl"><input type="radio" data-grade="'+formData.get('grade')+'" data-card="'+value.card_id+'" name="choose_card" class="choose_card" value="'+value.card_id+'"/><div class="cardhedger-card-description col-md-12"><table class="table table-cards-list" width="100%" cellspacing="5" cellpadding="5"><tr><td rowspan="7"><img width="200" src="'+value.image+'"></td></tr><tr class="card-desc"><td width="110px">Player Name:</td><td>'+value.player+'</td></tr><tr class="card-desc"><td>Card Number:</td><td>'+value.number+'</td></tr><tr class="card-desc"><td>Set:</td><td>'+value.set+'</td></tr><tr class="card-desc"><td>Parralel:</td><td>'+value.variant+'</td></tr><tr class="card-desc"><td>Description:</td><td>'+value.description+'</td></tr><tr class="card-desc"><td>Price:</td><td>'+value.price+'</td></tr></table></div></label><input type="hidden" name="vendhq_values" value="'+formData.get('serial_number')+' '+formData.get('grading_co')+' '+formData.get('grading_co_serial_number')+' '+formData.get('year')+' '+formData.get('set')+' '+value.player+' '+value.number+' '+value.variant+' '+formData.get('grade')+'"><input type="hidden" value="'+formData.get('grading_co')+'" name="vend_grading_co_value"><input type="hidden" name="card_form_values" value="'+value.card_id+','+value.player+','+formData.get('set')+','+value.number+','+value.description+','+value.image+','+value.variant+','+formData.get('grading_co')+','+formData.get('grading_co_serial_number')+','+formData.get('year')+','+formData.get('grade')+','+formData.get('category')+','+formData.get('serial_number')+','+value.price+','+value.closing_date+','+value.multiple+'"><div class="error"></div></div>'
                  });

                  $('#cards-list-modal .modal-body').html(html);

                  $("#cards-list-modal").modal();
                  $('#prices-submit-btn,#both-submit-btn').attr('disabled', true);
                  $('.add-card-btn').removeAttr('disabled');
                  $('.add-card-btn').html('Add New Card');
                  
              }
              else
              {
                $('.add-card-btn').removeAttr('disabled');
                $('.add-card-btn').html('Add New Card');
                $('.add-card-btn').click();
                $('.add-card-from-cardhedger').unbind('submit').submit();
                // $('#cards-list-modal .modal-body').html('No cards in the list');
              }
                  
              }
          });
    }
  });



  /** submitting card hedger form */
$(".edit-card-form-cardhedger").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      player_name: "required",
      year: "required",
      set: "required",
      // card_number: "required",
      parralel: "required", 
      grade: "required", 
    },
    // Specify validation error messages
    messages: {
      player_name: "Please enter Player Name",
      year: "Please enter Year",
      set: "Please enter Set",
      // card_number: "Please enter Card Number",
      parralel: "Please enter Parralel",
      grade: "Please select Grade (By selecting Grading Co first)",
      
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        $('.edit-card-btn').attr('disabled',true);
        $('.edit-card-btn').html('Fetching Cards...');
        var formData = new FormData(form);
          $.ajax({
              url: '/fetch-cardhedger-cards-from-api',
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              method: 'POST',
              success: function(data){

                  var parsedData = JSON.parse(data);
                  var list = parsedData['list'];

                  var html = '';

                  if(list.length > 0) {
                  $.each(list, function(index, value) {

                    if(!value.image)
                    value.image = '/images/default.png';

                    if(!value.description)
                    value.description = '--';

                    if(!value.price)
                    value.price = '--';

                    if(!value.number)
                    value.number = '--';

                    html += '<div class="box-card-description"><label class="labl"><input type="radio" data-grade="'+formData.get('grade')+'" data-card="'+value.card_id+'" name="choose_card" class="choose_card" value="'+value.card_id+'"/><div class="cardhedger-card-description col-md-12"><table class="table table-cards-list" width="100%" cellspacing="5" cellpadding="5"><tr><td rowspan="7"><img width="200" src="'+value.image+'"></td></tr><tr class="card-desc"><td width="110px">Player Name:</td><td>'+value.player+'</td></tr><tr class="card-desc"><td>Card Number:</td><td>'+value.number+'</td></tr><tr class="card-desc"><td>Set:</td><td>'+value.set+'</td></tr><tr class="card-desc"><td>Parralel:</td><td>'+value.variant+'</td></tr><tr class="card-desc"><td>Description:</td><td>'+value.description+'</td></tr><tr class="card-desc"><td>Price:</td><td>'+value.price+'</td></tr></table></div></label><input type="hidden" name="vendhq_values" value="'+formData.get('serial_number')+' '+formData.get('grading_co')+' '+formData.get('grading_co_serial_number')+' '+formData.get('year')+' '+formData.get('set')+' '+value.player+' '+value.number+' '+value.variant+' '+formData.get('grade')+'"><input type="hidden" value="'+formData.get('grading_co')+'" name="vend_grading_co_value"><input type="hidden" name="card_form_values" value="'+value.card_id+','+value.player+','+formData.get('set')+','+value.number+','+value.description+','+value.image+','+value.variant+','+formData.get('grading_co')+','+formData.get('grading_co_serial_number')+','+formData.get('year')+','+formData.get('grade')+','+formData.get('category')+','+formData.get('serial_number')+','+value.price+','+value.closing_date+','+value.multiple+'"><div class="error"></div></div>'
                  });

                  $('#cards-list-modal .modal-body').html(html);

                  $("#cards-list-modal").modal();
                  $('#prices-submit-btn,#both-submit-btn').attr('disabled', true);
                  $('.edit-card-btn').removeAttr('disabled');
                  $('.edit-card-btn').html('Edit Card');
                  
              }
              else
              {
                $('.edit-card-btn').removeAttr('disabled');
                $('.edit-card-btn').html('Edit Card');
                $('.edit-card-form-cardhedger').append('<input name="_method" type="hidden" value="PUT">');
                $('.edit-card-form-cardhedger').unbind('submit').submit();
                // $('#cards-list-modal .modal-body').html('No cards in the list');
              }
                  
              }
          });
    }
  });


    /* var edit_grading_co = $('#edit-grading-co').val();

    var gradeHtml = "<option value='' hidden>Select Grade</option>";
        for (var i = 10; i >= .5; i=i-.5) { //starts loop
            gradeHtml += "<option value='"+ edit_grading_co+' '+i+"'>" + edit_grading_co+' '+i+"</option>";
        };
        $('#edit-grade').html(gradeHtml); */


    /** on changing grading co serial number code start */
    $(document).on('change', '#grading_co , #edit-grading-co', function() {
        var gradingValue = $(this).val();
        var html = "<option value='' hidden>Select Grade</option>";
        for (var i = 10; i >= .5; i=i-.5) { //starts loop
            html += "<option value='"+ gradingValue+' '+i+"'>" + gradingValue+' '+i+"</option>";
        };
        $('#grade, #edit-grade').html(html);
    });
    /** code ends */

    /** on checking/unchecking card number checkbox code start */
    $(document).on('click', 'input[name=option_card_number]', function() {
        $('#card_number').val('');
        $("input[name='card_number']").addClass('d-none');
        if($(this).prop("checked") == false)
        {
            $("input[name='card_number']").removeClass('d-none');
        }
    });
    /** code ends */

    /** choose a card from the list of cards code start */
    $(document).on('click', '.choose_card', function() {
        var card = $(this).data('card');
        var grade = $(this).data('grade');
        var selector = $(this);
        $('#prices-submit-btn,#both-submit-btn').removeAttr('disabled');
        /* $.ajax({
            method : 'GET',
            data : { card : card , grade : grade },
            url : '/get-price-list',
            success : function(data) {
                var parsedData = JSON.parse(data);
                var list = parsedData['list'];
                $('.error').html('');
                if(list.length > 0)
                    $('#prices-submit-btn,#both-submit-btn').removeAttr('disabled');
                else {
                    selector.parent('label').next('.error').html('This card does not contain any prices');
                    $('#prices-submit-btn,#both-submit-btn').attr('disabled', true);
                    
                }
            }
        }) */
    });



    $(document).on('click', '#prices-submit-btn', function(e){
        e.preventDefault();
        var selector = $(this);
        $(selector).attr('disabled',true);
        $(selector).html('Saving...');
        var card_form_values = "";
        $('.choose_card').each(function(i, obj) {
            if ($(obj).is(':checked')) {
                card_form_values = $(obj).parents('.box-card-description').find('input[name=card_form_values]').val();
            }
        });

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/save-card-details",
            data: { card_form_values : card_form_values },
            timeout: 800000,
            success: function (data) {
                var response = JSON.parse(data);
                if(response.status == 200 && response.message == 'success') {
                    toastr["success"]("Prices saved successfully");
                    setTimeout(() => {
                        location.reload();                        
                    }, 2000);
                }
                if(response.status == 100)
                toastr["error"]("Something went wrong..please try again later");

                
                $(selector).removeAttr('disabled');
                $(selector).html('Saving...');
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    });


    $(document).on('click', '#both-submit-btn', function(e){
        e.preventDefault();
        var vend_values = vend_grading_co = card_form_values = "";
        $('.choose_card').each(function(i, obj) {
            if ($(obj).is(':checked')) {
                card_form_values = $(obj).parents('.box-card-description').find('input[name=card_form_values]').val();
                vend_values = $(obj).parents('.box-card-description').find('input[name=vendhq_values]').val();
                vend_grading_co = $(obj).parents('.box-card-description').find('input[name=vend_grading_co_value]').val();
            }
        });

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/save-card-details",
            data: { card_form_values : card_form_values },
            timeout: 800000,
            success: function (data) {
                $('#cards-list-modal').modal('hide');
                $('#vend_player_name').val(vend_values);
                $('#vend_product_type').val(vend_grading_co);
                var randomNumber = Math.floor(Math.random()*90000) + 10000000;

                $('#vend_sku').val(randomNumber);
                $('#vendhq-details-modal').modal();
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    });


    /**
     * search card start
     */

    $(document).on('click', '.search-card-details', function(){
        var internal_serial_number = $('#serial_number').val();
        $('.search-type').val('0');
        $.ajax({
            type: "GET",
            url: "/get-card-details",
            data: { serial_number : internal_serial_number },
            timeout: 800000,
            success: function (data)
            {
                    var decodedData = JSON.parse(data);
                    if(decodedData['multiple'] <= 1) {
                        if(decodedData['card'].length > 0) {
                        var playerName = decodedData['card'][0]['name'];
                        var gradingCo = decodedData['card'][0]['grading_co'];
                        var gradingCoSerialNumber = decodedData['card'][0]['grading_co_serial_number'];
                        var year = decodedData['card'][0]['year'];
                        var set = decodedData['card'][0]['set'];
                        var parallel = decodedData['card'][0]['parralel'];
                        var grade = decodedData['card'][0]['grade'];
                        var category = decodedData['card'][0]['category'];
                        var cardNumber = decodedData['card'][0]['card'];

                        var html = "<option value='' hidden>Select Grade</option>";
                        for (var i = 10; i >= .5; i=i-.5) { //starts loop
                            html += "<option value='"+ gradingCo+' '+i+"'>" + gradingCo+' '+i+"</option>";
                        };
                        $('#grade').html(html);

                        $('#player_name').val(playerName);
                        $('#grading_co').val(gradingCo);
                        $('#grading_co_serial_number').val(gradingCoSerialNumber);
                        $('#year').val(year);
                        $('#set').val(set);
                        $('#parralel').val(parallel);
                        $('#grade').val(grade);
                        $('#category').val(category);
                        $('#card_number').val(cardNumber);
                        $('.search-type').val('1');
                    }
                    else {
                        $('.add-card-from-cardhedger')[0].reset();
                    }

                    $('#serial_number').val(internal_serial_number);
                    $('.add-card-details').removeClass('hidden');
                }
                else
                {
                    var html = '';
                    $('.add-card-details').addClass('hidden');
                    if(decodedData.card.length > 0) {
                        $.each(decodedData.card, function(index, value) {

                            if(!value.cardprices.price)
                            value.cardprices.price = '--';

                            if(!value.set)
                            value.set = '--';

                            if(!value.number)
                            value.number = '--';

                            if(value.image == '' || value.image == null)
                            value.image = '/images/default.png';

                            if(value.description == '' || value.description == null)
                            value.description = '--';


                            html += '<div class="box-card-description"><label class="labl"><input type="radio" data-grade="'+value.grade+'" data-card="'+value.cardhedger_internal_serial_number+'" name="choose_card" class="choose_card" value="'+value.cardhedger_internal_serial_number+'"/><div class="cardhedger-card-description col-md-12"><table class="table table-cards-list" width="100%" cellspacing="5" cellpadding="5"><tr><td rowspan="8"><img width="200" src="'+value.image+'"></td></tr><tr class="card-desc"><td width="110px">Internal Serial Number:</td><td>'+internal_serial_number+'</td></tr><tr class="card-desc"><td width="110px">Player Name:</td><td>'+value.name+'</td></tr><tr class="card-desc"><td>Card Number:</td><td>'+value.card+'</td></tr><tr class="card-desc"><td>Set:</td><td>'+value.set+'</td></tr><tr class="card-desc"><td>Parralel:</td><td>'+value.parralel+'</td></tr><tr class="card-desc"><td>Description:</td><td>'+value.description+'</td></tr><tr class="card-desc"><td>Price:</td><td>'+value.cardprices.price+'</td></tr></table></div></label><input type="hidden" name="vendhq_values" value="'+internal_serial_number+' '+value.grading_co+' '+value.grading_co_serial_number+' '+value.year+' '+value.set+' '+value.name+' '+value.card+' '+value.parralel+' '+value.grade+'"><input type="hidden" value="'+value.grading_co+'" name="vend_grading_co_value"><input type="hidden" name="card_form_values" value="'+value.cardhedger_internal_serial_number+','+value.name+','+value.set+','+value.card+','+value.description+','+value.image+','+value.parralel+','+value.grading_co+','+value.grading_co_serial_number+','+value.year+','+value.grade+','+value.category+','+internal_serial_number+','+value.cardprices.price+','+value.cardprices.closing_date+','+decodedData['multiple']+','+value.id+'"><div class="error"></div></div>'
                        });
  
                        $('#cards-list-modal .modal-body').html(html);
                        $('#cards-list-modal .modal-footer #prices-submit-btn').html('Update Cards');
                    }
                    else
                    {
                        $('#cards-list-modal .modal-body').html('No cards in the list');
                    }
                    $("#cards-list-modal").modal();
                    $('#prices-submit-btn,#both-submit-btn').attr('disabled', true);
                    $('.add-card-btn').removeAttr('disabled');
                    $('.add-card-btn').html('Add New Card');
                }                
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    });

    /**
     * search card ends
     */

    /** code ends */

    $('.card-datepicker').datetimepicker({
        format: "YYYY-MM-DD hh:mm:ss",
        useSeconds: true,
    });

    $('.dropdown-menu li a').on('click', function() {
        $('.dropdown-menu li').removeClass('active');
    });

    $('.profile-trigger').on('click', function() {
        $('.panel').alterClass('card-*', 'card-default');
    });

    $('.settings-trigger').on('click', function() {
        $('.panel').alterClass('card-*', 'card-info');
    });

    $('.admin-trigger').on('click', function() {
        $('.panel').alterClass('card-*', 'card-warning');
        $('.edit_account .nav-pills li, .edit_account .tab-pane').removeClass('active');
        $('#changepw')
            .addClass('active')
            .addClass('in');
        $('.change-pw').addClass('active');
    });

    $('.warning-pill-trigger').on('click', function() {
        $('.panel').alterClass('card-*', 'card-warning');
    });

    $('.danger-pill-trigger').on('click', function() {
        $('.panel').alterClass('card-*', 'card-danger');
    });

    $('#user_basics_form').on('keyup change', 'input, select, textarea', function(){
        $('#account_save_trigger').attr('disabled', false).removeClass('disabled').show();
    });

    $('#user_profile_form').on('keyup change', 'input, select, textarea', function(){
        $('#confirmFormSave').attr('disabled', false).removeClass('disabled').show();
    });

    $('#checkConfirmDelete').on('change', function() {
        var submitDelete = $('#delete_account_trigger');
        var self = $(this);

        if (self.is(':checked')) {
            submitDelete.attr('disabled', false);
        }
        else {
            submitDelete.attr('disabled', true);
        }
    });

    $("#password_confirmation").on('keyup', function() {
        checkPasswordMatch();
    });

    $("#password, #password_confirmation").on('keyup', function() {
        enableSubmitPWCheck();
    });


    /** call vendhq label url here */

    $(".save-vendhq-card-details-form").validate({
        // Specify validation rules
        rules: {
          vend_player_name: "required",
          vend_sku: "required",
        },
        // Specify validation error messages
        messages: {
          vend_player_name: "Please enter Name",
          vend_sku: "Please enter SKU",
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
            // $('#vendhq-details-submit-btn').attr('disabled',true);
            var formData = new FormData(form);
              $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "/save-card-data-vendhq",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.code == 200 && response.message == 'success') {
                        // var child = window.open(response.url, "", "width=1000,height=500,top=100,left=170");
                        var intervalID, childWindow;

                        childWindow = window.open(response.url, "", "width=1000,height=500,top=100,left=170");

                        function checkWindow() {
                            if (childWindow && childWindow.closed) {
                                window.clearInterval(intervalID);
                                // alert('closed');
                                location.reload();
                            }
                        }
                        var intervalID = window.setInterval(checkWindow, 500);

                        $('#vendhq-details-modal').modal('hide');
                    }

                    if(response.code == 100 && response.status == 'vend_error')
                    toastr["error"](response.message);
                    
                    if(response.code == 100 && response.status == 'error')
                    toastr["error"]("Something went wrong..please try again later");

                },
                error: function (e) {
                    console.log(e.responseText);
                }
            });
        }
      });

      
});



function exportTasks(_this) {
    let _url = $(_this).data('href');
    window.location.href = _url;
}

function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#password_confirmation").val();
    if (password != confirmPassword) {
        $("#pw_status").html("Passwords do not match!");
    }
    else {
        $("#pw_status").html("Passwords match.");
    }
}

function enableSubmitPWCheck() {
    var password = $("#password").val();
    var confirmPassword = $("#password_confirmation").val();
    var submitChange = $('#pw_save_trigger');
    if (password != confirmPassword) {
        submitChange.attr('disabled', true);
    }
    else {
        submitChange.attr('disabled', false);
    }
}