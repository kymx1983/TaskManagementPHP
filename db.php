<?php

class DB {
  private $User = "root";
  private $PW = "rvog0197";
  private $dnsinfo = "mysql:dbname=taskmanagement;host=localhost;charset=utf8";

  private Function ConnectDB(){
    try{
      $pdo = new PDO($this->dnsinfo, $this->User, $this->PW);
      return $pdo;
    } catch (Exception $ex) {
      echo $ex;
      return false;
    }
  }

  public function executeSQL($sql, $array){
    try{
      $pdo = $this->ConnectDB();
      if(!$pdo){
        return false;
      }
      //}
      $stmt = $pdo->prepare($sql);
      $stmt->execute($array);
      return $stmt;
    } catch(Exception $ex){
      echo $ex;
      return false;
    }
  }
}
?>
