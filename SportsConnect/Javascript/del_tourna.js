$(document).ready(function(){
    var del_btn=document.querySelectorAll('.delete_btn');
    del_btn.forEach(button => {
        button.addEventListener('click',function(){
            var tourna_id=this.getAttribute('data-id');
            if(confirm('Do you Want to delete the tournament?This will delete all info related to that tournament.')){ 
            $.ajax({
                type:'post',
                url:'../support_php/ajax_del_tournament.php',
                data:{tourna_id:tourna_id},
                success:function(){
                    window.location.href='../Org_php/org_home.php';    
                }
            });
            }
        });
    });
});