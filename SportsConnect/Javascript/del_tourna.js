$(document).ready(function(){
    $('.delete_btn').click(function(){
        var tournament_list_item =$(this).closest('li');
        var fulltext = tournament_list_item.find('.tournament_name h2').text().trim();
        var parts = fulltext.split(':');
        var tournament_name = parts[1].trim();
        if(confirm("Are You sure you want to delete the tournament'"+tournament_name+"'?")){
            console.log('inside if')
            jQuery.ajax({
                type:'POST',
                url:'../support_php/ajax_del_tournament.php',
                data:{tournament_name : tournament_name},
                success:function(response){
                    console.log(response);
                    location.reload();
                },
                // error:function(xhr,status,error){
                //     console.error(xhr.responseText);
                // }
            });
        }
    });
});