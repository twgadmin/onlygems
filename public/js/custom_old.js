$(function() {

    var data =
  '[{ "value": 1, "text": "Task 1", "continent": "Task" }, { "value": 2, "text": "Task 2", "continent": "Task" }, { "value": 3, "text": "Task 3", "continent": "Task" }, { "value": 4, "text": "Task 4", "continent": "Task" }, { "value": 5, "text": "Task 5", "continent": "Task" }, { "value": 6, "text": "Task 6", "continent": "Task" } ]';

//get data pass to json
var task = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: JSON.parse(data) //your can use json type
});

task.initialize();

var elt = $("#tags");
elt.tagsinput();

var random = Math.floor(Math.random()*90000) + 10000;

$('.create-product-form #sku_code').val(random);


$(".create-product-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
        // $("#btnSearch").attr('value');
        //add more buttons here
        return false;
    }
});


$('.selectpicker').selectpicker().ajaxSelectPicker({

    ajax: {

      // data source
      url: 'products-search',

      // ajax type
      type: 'POST',

      // data type
      dataType: 'json',

      // Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
      // automatically replace it with the value of the search query.
      data: {
        q: '{{{q}}}'
      }
    },
    locale: {
        emptyTitle: "Search a product to add"
    },
    // function to preprocess JSON data
    preprocessData: function (data) {

      var i, l = data.length, array = [];
      if (l) {
          for (i = 0; i < l; i++) {
              array.push($.extend(true, data[i], {
                  text : data[i].product_name,
                  value: data[i].id,
                  data : {
                    //   subtext: data[i].id
                  }
              }));
          }
      }
      // You must always return a valid array when processing data. The
      // data argument passed is a clone and cannot be modified directly.

      return array;

    },

  });

  $("#ajax-select").on("changed.bs.select",
      function(e, clickedIndex, newValue, oldValue) {
          var productId = $(this).val();
        // console.log(this.value, clickedIndex, newValue, oldValue)
        if(clickedIndex != undefined) {
            $.ajax({
                url : 'fetch-product-details',
                type : 'POST',
                data : { id : productId },
                success : function (data) {
                    var html = '';
                    data = JSON.parse(data);
                    $('#myModal .modal-title .product-title').html(data[0][0]);
                    data.forEach(element => {
                        html += '<tr><td><input class="check-variant" type="checkbox"></td><td>'+element[0]+' <input type="hidden" value="'+element[3]+','+element[4]+','+element[5]+'" name="selection[]"><input type="hidden" value="0.00" name="total_cost[]" class="total-cost-input w-50"><input type="hidden" name="product_name[]" value="'+element[0]+'"></td><td>'+element[1]+'</td><td><input type="number" value="1" min="1" class="form-control qty w-50" name="qty[]"></td><td><div class="inner-addon left-addon"><i class="glyphicon glyphicon-usd"></i><input type="number" value="0" min="0" step="any" name="cost_price[]" class="form-control w-75 cost_price" /></div></td><td class=""total_cost>$0.00</td></tr>';
                    });
                    $('#myModal .modal-body .products-modal-tbody').html(html);
                    $("#myModal").modal();
                }
            })
        }
    });

    $(document).on('change keyup', '.cost_price , .qty', function() {
        // var cost_price = $(this).val();
        var qty = $(this).closest('tr').children('td:nth-child(4)').children('.qty').val();
        var cost_price = $(this).closest('tr').children('td:nth-child(5)').find('.cost_price').val();
        var total = (parseInt(qty)*parseFloat(cost_price)).toFixed(2);
        $(this).closest('tr').children('td:nth-child(6)').html('$'+total);
        $(this).closest('tr').children('td:nth-child(2)').children('input:nth-child(2)').val(total);

        var checked = $(this).closest('tr').children('td:nth-child(1)').children('input').prop('checked');

        var total_cost = 0;
        $('.add-products-to-transaction-table .total-cost-input').each(function() {
            var cost = $(this).val();
            total_cost = parseFloat(total_cost)+parseFloat(cost);
        })
        $('.products_total').html('$'+total_cost);

        if( (qty == '' || cost_price == '') || (qty == 0) || (checked == false)) {
            $('.add-variants-to-order').attr('disabled',true);
        }
        else
        $('.add-variants-to-order').removeAttr('disabled');
    });


    $(document).on('change', '.products-modal-tbody .check-variant', function() {
        // var checked = false;
        var checked = 0;
        $('.products-modal-tbody .check-variant:checked').each(function() {
            checked = parseInt(checked) + 1;
        });
        if ( checked > 0 ) {
            $('.add-variants-to-order').removeAttr('disabled');
            var qty = $(this).closest('tr').children('td:nth-child(4)').children('.qty').val();
            var cost_price = $(this).closest('tr').children('td:nth-child(5)').find('.cost_price').val();
            var total = (parseInt(qty)*parseFloat(cost_price)).toFixed(2);
            $(this).closest('tr').children('td:nth-child(2)').children('input:nth-child(2)').val(total);

            if((qty == '' || cost_price == '') || (qty == 0)) {
                $('.add-variants-to-order').attr('disabled',true);
                return;
            }
            $('.variants-selected-text').html(checked+' variant(s) has been selected');
        }
        else {
            $('.add-variants-to-order').attr('disabled',true);
            $('.variants-selected-text').html('No variant(s) have been selected');
        }
    });


    $(document).on('click', '.add-variants-to-order', function() {
        var tr = '';
        $('.products-modal-tbody .check-variant:checked').each(function() {
            // var tr = $(this).closest('TR:has(td)');
            tr = $(this).closest('TR:has(td)').clone();
            tr.find('td:first-child').hide();
            tr.append('<td><i class="fa fa-trash cursor-pointer remove-selected-product"></i></td>');
            $('.add-products-to-transaction-table tbody').prepend(tr);
        });


        var total_cost = 0;
        $('.add-products-to-transaction-table .total-cost-input').each(function() {
            var cost = $(this).val();
            total_cost = parseFloat(total_cost)+parseFloat(cost);
        })
        $('.products_total').html('$'+total_cost);

        $("#myModal").modal('hide');


    });

    $(document).on('click', '.remove-selected-product', function() {
        console.log('clicked remove btn');
        $(this).closest('tr').remove();

        var total_cost = 0;
        $('.add-products-to-transaction-table .total-cost-input').each(function() {
            var cost = $(this).val();
            total_cost = parseFloat(total_cost)+parseFloat(cost);
        })
        $('.products_total').html('$'+total_cost);

    })

//insert data to input in load page
/* elt.tagsinput("add", {
  value: 1,
  text: "task 1",
  continent: "task"
}); */

$('.datepicker').datepicker({
    format: 'mm-dd-yyyy',
});

$('#tags').on('change', function() {
    $('.create-product-btn').removeAttr('disabled');
});

tinymce.init({
    selector: 'textarea#description',
    height: 300
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

// $('#password, #password_confirmation').hidePassword(true);

/* $('#password').password({
    shortPass: 'The password is too short',
    badPass: 'Weak - Try combining letters & numbers',
    goodPass: 'Medium - Try using special charecters',
    strongPass: 'Strong password',
    containsUsername: 'The password contains the username',
    enterPass: false,
    showPercent: false,
    showText: true,
    animate: true,
    animateSpeed: 50,
    username: false, // select the username field (selector or jQuery instance) for better password checks
    usernamePartialMatch: true,
    minimumLength: 6
}); */

})

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
