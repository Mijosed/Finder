//bts_blanc ( baignoire )
function getChambre_b(){
  $sql = "SELECT * FROM chambre WHERE baignoire = 1 ";
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

$app->get('/chambreb', function(Request $request, Response $response){
       return getChambre_b();
});

//bts_blanc ( nombre chambre par etage )
function PutChambre($id, $etage, $nbcouchage, $porte, $idcategorie, $prixbase, $baignoire){
  $sql = " UPDATE user SET etage =:etage , nbcouchage=:nbcouchage , porte =:porte , idcategorie =:idcategorie , prixbase = :prixbase , baignoire =:baignoire  WHERE id = $id";
  try{
    $dbh=connexion();
    $statement = $dbh->prepare($sql);
    $statement -> bindParam (":etage", $etage);
    $statement -> bindParam (":nbcouchage", $nbcouchage);
    $statement -> bindParam (":porte", $porte);
    $statement -> bindParam (":idcategorie", $idcategorie);
    $statement -> bindParam (":prixbase", $prixbase);
    $statement -> bindParam (":baignoire", $baignoire);
    $statement->execute();
     $result = $statement->fetchAll(PDO::FETCH_CLASS); 
                 return json_encode($result, JSON_PRETTY_PRINT);
  } catch(PDOException $e){
    return '{"error":'.$e->getMessage().'}';
  }
}

$app->put('/chambre', function(Request $request, Response $response){

  $tb = $request->getQueryParams();
  
  //$id = $tb["id"];
  $id = $tb["id"];
  $etage = $tb["etage"];
  $nbcouchage = $tb["nbcouchage"];
  $porte = $tb["porte"];
  $idcategorie = $tb["idcategorie"];
  $prixbase = $tb["prixbase"];
  $baignoire = $tb["baignoire"];
       return PutChambre($id, $etage, $nbcouchage, $porte, $idcategorie, $prixbase, $baignoire);
});
//bts_blanc ( nombre chambre par etage )

function getChambre_ne(){
  $sql = "SELECT count( * ) as nb FROM chambre GROUP BY etage ";
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

$app->get('/chambrene', function(Request $request, Response $response){
       return getChambre_ne();
});