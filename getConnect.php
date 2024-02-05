<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Credentials: true");


//fonction connexion à la base de données.
function getConnect(){
   
 
   // $dsn = "mysql:host=localhost;dbname=coupart;port=3308";
   // $username = "coupart";
   // $password = "GDDWWMECFENTRIII3A";

   $dsn = "mysql:host=u28rhuskh0x5paau.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=phomygib7ia7sn9a;port=3306";
   $username = "dwjs72dh2f3c3375";
   $password = "z99yrq9bc69hq9w5";

    try {
       $pdo = new PDO($dsn,$username,$password);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       return $pdo;
    } catch (PDOException $e) {
       // l'erreur de connexion.
       throw new Exception("Erreur de connexion à la base de données" .$e->getMessage());
      
    }catch(Exception $e){
      throw new Exception("Erreur de connexion à la base de données" .$e->getMessage());
     
    }
   }
  