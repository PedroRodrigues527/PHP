<?php
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
    //Ficheiro html
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html)</script>';
}
else if(!isset($_SESSION['paginaanterior']) || empty($_SESSION['paginaanterior'])){
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html)</script>';
}
else{
    //Conectar BD
    $queryString = 'SELECT is_pro, data_pro FROM user WHERE username = "'.$_SESSION['username'].'"';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "er_db";
    $conn = mysqli_connect($servername, $username, $password, $db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryResult = mysqli_query($conn, $queryString);
    mysqli_close($conn);
    //query para verificar sub anual
    if(mysqli_num_rows($queryResult) > 0)
    {
        $row = mysqli_fetch_array($queryResult);
        if($row[0] == 0 && $row[1] == NULL ){
            //Nao tem subscrição
        }else{
            //Tem sub
            echo '<script>if(confirm("Ja possui subscrição anual!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
              }
              else
                  {
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                  }</script>';
        }
    }

    //Tratamento dos dados
    if($_SESSION['paginaanterior'] == 'subscricaoanual'){
        //Conectar BD
        //Verificar se tem subscrição -> aviso -> entra
        //Nao? -> pagamento
    }
    else if ($_SESSION['paginaanterior'] == 'reserva'){
        //Reserva - pagamento
    }
    else if(false){ //Cancelamento reservar
        //Verificar se paga taxa
    }
    else if(false){ //Modificação reservar
        //Verificar se paga taxa
    }
}
?>