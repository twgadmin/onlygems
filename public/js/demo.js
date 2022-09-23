$(function() {

    var random = Math.floor(Math.random()*90000) + 10000000;

    $('.sku').val(random);

    /** ajax for adding product to vendhq */

    $('.test-add-card-form').submit(function(e){
        e.preventDefault();
        console.log('submitted');
        // Get form
        var form = $('.test-add-card-form')[0];
 
       // Create an FormData object 
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/add-test-card-data",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                var response = JSON.parse(data);
                window.open(response.url, "", "width=1000,height=500,top=100,left=170");
            },
            error: function (e) {
                alert(e.responseText);
            }
        });

    });
});