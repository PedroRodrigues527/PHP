<?php
session_start();
if(!empty($_SESSION)) {
    //CONTINUAR AQUI
    echo $_SESSION['username'];

}
else
{
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html")</script>';
}