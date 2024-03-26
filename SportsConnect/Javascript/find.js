$(document).ready(function(){
    $("#player").on("keyup",function(){
        var search_term = $(this).val();
        if(search_term.trim()!==''){
            jQuery.ajax({
            type:"post",
            url:"../support_php/ajax-live-search.php",
            data : {search:search_term},
            success: function(data){
                jQuery("#player_info").html(data);
                console.log(data)
            }
        });
        }
        else{
            jQuery("#player_info").empty();
        }  
    });
});
