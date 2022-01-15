<?php
session_start();
if(empty($_POST)){
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html)</script>';
    echo "nop :P";
}
else{
    $_SESSION['paginaanterior'] = $_POST['paginaanterior'];
    //echo "Yep :D";
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php")</script>';
}

?>