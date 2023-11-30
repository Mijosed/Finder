<?php session_start();
if(
   isset($_GET['email']) 
&& isset($_GET['password'])
){
   //echo "ok";
   $dsn='mysql:dbname=finder;host=127.0.0.1:3307';
$user='root';
$password='root';
try{
    $dbh=new PDO($dsn,$user,$password); 
}catch(PDOException $e){
    echo'Connexion échouée:'.$e->getMessage(); 
}
   $sql = "SELECT count(*) FROM user WHERE email=:email AND password=TO_BASE64(AES_ENCRYPT(:password ,SHA2('pirate',512)))";
   // INSERT INTO user ( email, password ) VALUES ( "dorcas95c@gmail.com", TO_BASE64(AES_ENCRYPT( 'pass' ,SHA2('pirate',512))));
   //echo $sql;
   $resultats = $dbh->prepare($sql);
   $email = $_GET['email'];
   $password = $_GET['password'];
   $resultats->bindParam(":email", $email);
   $resultats->bindParam(":password", $password);
   $resultats->execute();  
   $number_of_rows = $resultats->fetchColumn(); 
   //echo $number_of_rows;
   if($number_of_rows == 1){
      http_response_code(200);
   }
   else{
      http_response_code(403);
    //$_SESSION['erreur']=true;
    //header('Location: http://localhost/finder1/connexion.php');
   }
}else{
   http_response_code(403);
        //$_SESSION['erreur']=true;
        //header('Location: http://localhost/finder1/connexion.php');
}