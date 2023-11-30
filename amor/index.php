<?php 
require 'vendor/autoload.php';
//use \Psr\Http\Message\ServerRequestInterface as Request;
//use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
//use PDO;
//use PDOException;


$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

//FONCTIONS

function connexion()
{
   $dbh = new PDO("mysql:host=localhost;port=3307;dbname=amor", 'root', 'root', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
   $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
return $dbh;
}

function getChambre($id){  //obtenir les chambres par l'id
  $sql = "SELECT * FROM chambre WHERE id = $id";
  try{
    $dbh=connexion();
    $statement = $dbh->prepare($sql);
    $statement->execute();
     $result = $statement->fetchAll(PDO::FETCH_CLASS); 
                 return json_encode($result, JSON_PRETTY_PRINT);
  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}

function getdispo($cat, $dated, $datef) {
  try {
    $dbh = connexion();

    // Requête pour récupérer les informations de disponibilité
    $sql = "SELECT c.libelle AS categorie, r.dated AS date_debut, r.datef AS date_fin
            FROM reservation r
            INNER JOIN categorie c ON r.id = c.id
            WHERE c.libelle = :cat
            AND r.dated >= :dated
            AND r.datef <= :datef";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':cat', $cat);
    $stmt->bindParam(':dated', $dated);
    $stmt->bindParam(':datef', $datef);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fermer la connexion à la base de données
    $dbh = null;

    return $result;
  } catch (PDOException $e) {
    // Gérer les erreurs de la base de données
    return false;
  }
}





function checkUser($nom, $mdp /*$id */) {
  try{
    $dbh=connexion();
    $sql = "SELECT count(*) as nb FROM user WHERE nom =:nom AND mdp =:mdp";
    $statement = $dbh->prepare($sql);
    $statement -> bindParam (":nom", $nom);
    $statement -> bindParam (":mdp", $mdp);
    $statement->execute();
    $number_of_rows = $statement -> fetchColumn();
    if ($number_of_rows == 1){
     // $result = $statement->fetchAll(PDO::FETCH_CLASS); 
      //var_dump($result);
      return true;
      //return json_encode($number_of_rows, JSON_PRETTY_PRINT );
    } else {
return false;
      
    }
   
                // return json_encode($result, JSON_PRETTY_PRINT);


  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}

/*$app->get('/user', function(Request $request, Response $response){
  $tb = $request->getQueryParams();
  
   //$id = $tb["id"];
   $nom = $tb["nom"];
   $mdp = $tb["mdp"];
       //fonction de vérification d'utilisateur
if (checkUser($nom, $mdp)==true){
  //echo "hello";
   return $response->withStatus(200)->withJson(["bonjour" => "test"]);
}else{
  //echo "waza";
  return $response->withStatus(401)->withJson("faux");
}
}); */

function DeleteUser($id){ // supprimer l'utilisateur avec l'id
  $sql = "DELETE FROM user WHERE id = $id";
  try{
    $dbh=connexion();
    $statement = $dbh->prepare($sql);
    $statement->execute();
     $result = $statement->fetchAll(PDO::FETCH_CLASS); 
                 return json_encode($result, JSON_PRETTY_PRINT);
  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}

function AddUser($request, $response){

// Connexion à la base de données MySQL
$dbh = connexion();

// Récupération des données du formulaire
$nom = $request->getParsedBody()['nom'];
$prenom = $request->getParsedBody()['prenom'];
$email = $request->getParsedBody()['email'];
$mdp = hash('sha256', $request->getParsedBody()['mdp']); // Hashage du mot de passe avec SHA-256


// Insertion des données dans la base de données
$request = $dbh->prepare("INSERT INTO user (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)");
if ($request->execute(array(
  ':nom' => $nom,
  ':prenom' => $prenom,
  ':email' => $email,
  ':mdp' => $mdp
))) {
  $response->getBody()->write("Inscription réussie !");
  return $response->withStatus(200);
} else {
  $response->getBody()->write("Une erreur s'est produite lors de l'inscription.");
  return $response->withStatus(500);
}

}


function getChambre1(){
  $sql = "SELECT * FROM chambre";
  try{
    $dbh=connexion();
    $statement = $dbh->prepare($sql);
    $statement->execute();
     $result = $statement->fetchAll(PDO::FETCH_CLASS); 
                 return json_encode($result, JSON_PRETTY_PRINT);
  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}


function PutMail($id){
  $sql = " UPDATE user SET email = 'mijose94@gmail.com'  WHERE id = $id";
  try{
    $dbh=connexion();
    $statement = $dbh->prepare($sql);
    $statement->execute();
     $result = $statement->fetchAll(PDO::FETCH_CLASS); 
                 return json_encode($result, JSON_PRETTY_PRINT);
  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}

$app->put('/user/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
       return PutMail($id);
});

// Obtenir le token 
function getTokenJWT(){
  $payload = array(
    "exp" => time() + (60 * 30)
  );
  return JWT::encode($payload, SECRET,'HS256');
}

// Valider le token
function validJWT($token) {
  $res = false;
  try {
      $decoded = JWT::decode($token, new Key(SECRET, 'HS256'));       
  } catch (Exception $e) {
    return $res;
  }
  $res = true;
  return $res;  
}

function verif_user($data) {

  // Récupérer l'email et le mdp
  $email = $data["email"];
  $mdp = $data["mdp"];

  // Vérifier si ils ne sont pas null
  if(is_null($email) || is_null($mdp)) {

    return false;

  }

  // Récupérer l'utilisateur avec l'email donné dans la base de données
  $bdd = connexion();

  $stmt = $bdd->prepare("SELECT * FROM user WHERE email = :email");

  $stmt->execute([
    "email" => $email
  ]);

  $user = $stmt->fetch(PDO::FETCH_OBJ);

  // Si il n'existe pas on return false
  if(!$user) {
    return false;
  }

  // Comparaison du mdp de l'utilisateur dans la base de données avec celui entré dans le formulaire
  if(!($user->mot_de_passe == $mdp)) {
    return false;
  }


  return $user;

}


// APPLICATION + ROUTES

$app->get('/chambre/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
       return getChambre($id);
});

$app->get('/disponibilites', function($request, $response) {
  $queryParams = $request->getQueryParams();
  $cat = $queryParams['cat'];
  $dated = $queryParams['dated'];
  $datef = $queryParams['datef'];

  $result = getdispo($cat, $dated, $datef);

  if ($result) {
    // Retourner les résultats en tant que réponse JSON
    return $response->withJson($result);
  } else {
    // Gérer les erreurs de la base de données
    return $response->withStatus(500)->write('Une erreur s\'est produite lors de la récupération des disponibilités.');
  }
});


$app->delete('/user/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
       return DeleteUser($id);
});


$app->get('/chambre', function(Request $request, Response $response){
  return getChambre1();
});


$app->post('/verifToken', function (Request $request, Response $response){

  $token = $request->getQueryParams()["token"];

  $isValid = validJWT($token);

  if ($isValid == true) {
    
    return $response->withJson($isValid);

  } else {

    return $response->withStatus(401)->withJson([
      "message" => "unauthorized"],401);
  };
});

$app->post('/user', function (Request $request, Response $response) {
  //$nom = $request->getAttribute('nom');
  //$prenom = $request->getAttribute('prenom');
  //$email = $request->getAttribute('email');
  //$mdp = $request->getAttribute('mdp');
        return AddUser($request, $response);
        //$nom,$prenom,$email,$mdp,
  
});

$app->run();

?>
