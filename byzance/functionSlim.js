$( document ).ready(function() {
    $('#btn-valide').click(function(){ 
    $.ajax({ 
          type: "GET",
          contentType: 'application/json; charset=utf-8',
          url: "http://localhost:8080/bonjour",
         success: function(data){
             alert(data);
          }
     });
    });
     });