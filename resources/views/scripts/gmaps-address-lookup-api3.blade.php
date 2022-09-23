<script type="text/javascript">
    //FUNCTION TO ASSIST WITH AUTO ADDRESS INPUT USING GOOGLE MAPS API3
    //<![CDATA[
    window.onload=function(){
        if(document.getElementsByClassName("location")[0])
        {
            var input = document.getElementsByClassName('location')[0];
            var options = {types: ['address']};
            var autocomplete = new google.maps.places.Autocomplete(input, options);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();

                for (var i = 0; i < place.address_components.length; i++) {
                    for (var j = 0; j < place.address_components[i].types.length; j++) {

                        if (place.address_components[i].types[j] == "country") {
                            $('.country').val(place.address_components[i].long_name);
                        }

                        if (place.address_components[i].types[j] == "postal_code") {
                            $('.zip').val(place.address_components[i].long_name);
                        }

                        if (place.address_components[i].types[j] == "locality") {
                            $('.city').val(place.address_components[i].long_name);
                        }

                        if (place.address_components[i].types[j] == "administrative_area_level_1") {
                            $('.state').val(place.address_components[i].long_name);
                        }
                    }
                }

            });

        }
     }//]]>
</script>
