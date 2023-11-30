<?php session_start();?>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<?php
/*
if(isset($_SESSION['erreur']) && $_SESSION['erreur'] ){
  //echo "erreur";
  echo '<script>
    $( document ).ready(function() {
    $(".form-control").addClass("is-invalid");
    });
    </script>';
}
$_SESSION['erreur']=false;
*/
?>
<div class="container">
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
       <div class="invalid-feedback">
          utilisateur et/ou mot de passe invalide
        </div>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
    </div>
    
    <button  class="btn btn-primary">Submit</button>
  
</div>
<div id="result"></div>
<script>
$(document).ready(function () {
    $('.btn').click(function () {
        $("#result").html("");
        let email=$( "#exampleInputEmail1" ).val();
        let password=$( "#exampleInputPassword1" ).val();
        $.ajax("http://finder/Verification2.php",
            {
                type: "GET",
                data: 'email=' + email + '&password=' + password,
                success: function (data, textStatus, jqXHR) {
                    console.log(jqXHR.status);
                    //jquery
                    $("#exampleInputEmail1").css("border-color", "black");
                    $("#exampleInputPassword1").css("border-color", "black");
                },
                error: function (xhr,status,error) {
                    console.log(xhr.status);
                    //jquery
                    $("#exampleInputEmail1").css("border-color", "red");
                    $("#exampleInputPassword1").css("border-color", "red");
                }
                
            });
    });
});
</script>
</html>