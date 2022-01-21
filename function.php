<?php

/*
Used to send sql queries to sql server through pdo. 
->accepts:
$query: the actual text query
$placeholders: array of placeholders for pdo
->returns:
$data = array()
On failed: 
 $data['error'] // contains the error details. 
On success: 
 $data['result'] // contains the result array
 $data['count'] // contains the total count
*/

function sql_query($query, $placeholders = false){
  $data = array();
  
  global $pdo;
  $letter = substr($query, 0, 1);
  
  try{
    $stmt = $pdo->prepare($query);
    //echo "Successfully Prepared<br/>";
  }
  catch(PDOException $e){
    $data['error'] = "Prepare Failed: ".$e->getMessage();
    return $data;
  }
  
  if(!$stmt){
    $data['error'] = "Query Failed";
    return $data;
  }
  
  try{
    if($placeholders){
      $stmt->execute($placeholders);
    }
    else{
      $stmt->execute();
    }
    
    $data['count'] = $stmt->rowCount();
    
    if($letter === "S"){
      if($data['count'] > 1){
        $data['result'] = $stmt->fetchAll();
      }else{
        $data['result'][] = $stmt->fetch();
      }
    }
    
  }
  catch(PDOException $e){
    $data['error'] = 'Connection failed: ' . $e->getMessage();
  }  
  return $data;
}

// function usage:

$result = sql_query("SELECT * FROM table WHERE id=:id", ['id'=>$id]);




?>
