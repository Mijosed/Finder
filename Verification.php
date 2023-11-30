<?php session_start();
if(
   isset($_GET['email']) 
&& isset($_GET['mdp'])
){
   //echo "ok";
   $dsn='mysql:dbname=amor;host=127.0.0.1:3307';
$user='root';
$mdp='root';
try{
    $dbh=new PDO($dsn,$user,$mdp); 
}catch(PDOException $e){
    echo'Connexion échouée:'.$e->getMessage(); 
}
   $sql = "SELECT count(*) as nb FROM user WHERE email=:email AND mdp=:mdp";
   //$sql = "SELECT count(*) FROM user WHERE email=:email AND mdp=TO_BASE64(AES_ENCRYPT(:mdp ,SHA2('pirate',512)))";
   // INSERT INTO user ( email, mdp ) VALUES ( "dorcas95c@gmail.com", TO_BASE64(AES_ENCRYPT( 'pass' ,SHA2('pirate',512)))); 
   //echo $sql;
   $resultats = $dbh->prepare($sql);
   $email = $_GET['email'];
   $mdp = $_GET['mdp'];
   $resultats->bindParam(":email", $email);
   $resultats->bindParam(":mdp", $mdp);
   $resultats->execute();  
   $number_of_rows = $resultats->fetchColumn(); 
   //echo $number_of_rows;
   if($number_of_rows == 1){
      echo "ok";
   }
   else{
    $_SESSION['erreur']=true;
    //header('Location: http://localhost/finder1/connexion.php');
   }
}else{
        $_SESSION['erreur']=true;
        //header('Location: http://localhost/finder1/connexion.php');
}