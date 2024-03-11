$(document).ready(function(){
    jQuery('#country').change(function(){
        var id = jQuery(this).val();
        if(id=='-1'){
            jQuery('#state').html('<option value="-1">Select State</option>');
            jQuery('#city').html('<option value="-1">Select City</option>');
            jQuery('#no').val("")
        }
        else{
         jQuery.ajax({
            type:'post',
            url:'../PHP/get_data.php',
            data:'id='+id,
            success:function(result){
                jQuery('#state').html(result);
            }
         });
         jQuery.ajax({
            type:'post',
            url:'../PHP/get_code.php',
            data:'id='+id,
            success:function(result){
                var countryCode = result.startsWith('+') ? result : '+' + result;
                jQuery('#no').val(countryCode);
            }
         });
        }

    });
    jQuery('#state').change(function(){
        var id = jQuery(this).val();
        if(id=='-1'){
            jQuery('#city').html('<option value="-1">Select City</option>');
        }
        else{
         jQuery.ajax({
            type:'post',
            url:'../PHP/get_city.php',
            data:'id='+id,
            success:function(result){
                jQuery('#city').html(result);
            }
         });
        }
    });
});