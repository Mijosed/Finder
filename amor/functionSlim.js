$( document ).ready(function() {

    $('#btn-new-liste').click(function(){ 
        let idx=$('#idx').val();
    $.ajax({ 
          type: "GET",
          contentType: 'application/json; charset=utf-8',
          url: "http://localhost/finder/amor/index.php/chambre/"+idx,
         success: function(data){
            $("#result").html(data);
          }
     });
    });

    $('#btn-valide1').click(function(){ 
        let id=$('#id').val();
        let nom=$('#nom').val();
        let mdp=$('#mdp').val();
        $.ajax({ 
              type: "POST",
              contentType: 'application/json; charset=utf-8',
              url: "http://localhost/finder/amor/index.php/user?mdp="+mdp+"&nom="+nom,
             success: function(data){
                 alert(data);
              }
         });
        });

        $( document ).ready(function() {
            $('#btn-valide').click(function(){ 
            $.ajax({ 
                  type: "GET",
                  contentType: 'application/json; charset=utf-8',
                  url: "http://localhost/finder/amor/index.php/bonjour",
                 success: function(data){
                     alert(data);
                  }
             });
            });
             });
     });