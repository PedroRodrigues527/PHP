<?php

//AINDA NAO FUNCIONA!
$severname = "localhost";
$username = "username";
$password = "password";

//Conectar DB
$conn = mysqli_connect($severname, $username, $password);

//Verifica conexão
if($conn -> connect_error){
    die("connection failed: ". $conn->connect_error);
}
echo "Connected: ";

?>
