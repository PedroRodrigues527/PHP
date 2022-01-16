<?php
session_start();
if((isset($_SESSION['username']) || !empty($_SESSION['username']))) {
    unset($_SESSION['username']);
}
echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html")</script>';
?>