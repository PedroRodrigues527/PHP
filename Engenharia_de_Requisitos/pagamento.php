<?php
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username']) && (!isset($_SESSION['paginaanterior']) || empty($_SESSION['paginaanterior']))){
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html)</script>';
}
else if(isset($_POST['preco'])){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "er_db";
    $conn = mysqli_connect($servername, $username, $password, $db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryCardUser = 'SELECT id FROM user WHERE username = "' . $_POST['username'] . '"';
    $queryResult = mysqli_query($conn, $queryCardUser);

    $mes = substr($_POST['validade'], 5);

    if($mes < 10){
        $mes = $mes % 10;
    }


    $iduser= mysqli_fetch_array($queryResult);
    $queryString = 'SELECT * FROM credit_card WHERE number = "' . $_POST['card_num'] . '" AND month_val ="'.$mes.'" AND year_val = "'.substr($_POST['validade'], 0, -3).'" AND PIN ="' . $_POST['pin'] . '" AND user_id ="' . $iduser[0] . '"';
    //echo $queryString;
    $queryResult2 = mysqli_query($conn, $queryString);
    if(mysqli_num_rows($queryResult2) > 0){
        $rowCredit = mysqli_fetch_array($queryResult2);
        if($rowCredit[6] < $_POST['preco']){
            echo '<script>if(confirm("Saldo insuficiente!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
              }
              else
                  {
                      window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
                  }</script>';
        }
        else{
            $queryString = 'UPDATE credit_card SET saldo = saldo -"'. $_POST['preco'] .'" WHERE id = "'. $rowCredit[0] .'"';
            if(!mysqli_query($conn, $queryString)){
                echo '<script>if(confirm("Erro inesperado na ligação à base de dados!")){
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
                  }</script>';
            }else{
                $datasub = date("Y-m-d", strtotime("+1 year"));
                $queryUpdate = 'UPDATE user SET is_pro = 1 , data_pro = "'. $datasub .'" WHERE username ="'. $_SESSION['username'].'"';
                if(mysqli_query($conn, $queryUpdate)){
                        echo '<script>
                                if(confirm("Pagamento com sucesso! Data de validade: ' . $datasub .'")){
                                    window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                              }
                              else{
                                    window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                                  }</script>';
                }
            }
        }

    }else{
        echo '<script>
                        if(confirm("Dados de cartão de crédito inseridos estão inválidos!")){
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
                        }else{
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/pagamento.php");
                        }</script>';
    }
    mysqli_close($conn);//te


}
else{
    //Conectar BD
    $acederPag = 0;
    if($_SESSION['paginaanterior'] == 'subscricaoanual') {
        $queryString = 'SELECT is_pro, data_pro FROM user WHERE username = "' . $_SESSION['username'] . '"';
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
        if (mysqli_num_rows($queryResult) > 0) {
            $row = mysqli_fetch_array($queryResult);
            if ($row[0] == 0 && $row[1] == NULL) {
                //Nao tem subscrição
                $acederPag = 1;
            } else {
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

    if($acederPag>0){
        //html
        if($acederPag == 1){
            $preco=49.99;
        }
        echo'<!DOCTYPE html>
            <html>
            
            <head>
                <title> GOBikes </title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="shortcut icon" href="img/logo.png" />
                <link rel="stylesheet" href="style.css"/>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            </head>
            
            <body>
            
            <section class= "header">
                </div>
                <nav>
                    <a href="index.html"><img src="img/logo.png" style="width:140px; height:100px;"></a>
                    <div class="nav-links" id="navLinks">
                        <i class="fa fa-window-close"></i>
                        <ul>
                            <!-- <li> <a href="#">HOME</a></li> -->
                            <li> <a href="menuprincipal.php">PÁGINA PRINCIPAL</a></li>
                            <li> <a href="">AJUDA</a></li>
                        </ul>
                    </div>
                    <i class="fa fa-ellipsis-v" onclick="showMenu()"></i>
                </nav>
            
            </section>
            
            <!-- Course -->
            <section class="course" id="course">
                <div class="input-box" >
            
                    <p id="desc">Descrição pagamento:'.$_SESSION['paginaanterior'].' </p>
                    <p id="val">Valor:'. $preco.' </p>
                    <br>
            
                    <form action="" method="post">
                        <label><b>Número do cartão: </b></label>
                        <input type="number" placeholder="Número cartão" name="card_num" required>
                        <br> <br>
            
                        <label><b>Validade: </b></label>
                        <input id="month" type="month" name="validade" required>
                        <br> <br>
            
                        <label><b>PIN: </b></label>
                        <input type="number" placeholder="PIN" name="pin" required>
                        <br><br>
            
                        <input type="hidden" value="'.$preco .'" name="preco">
                        <input type="hidden" value="'.$_SESSION['username'] .'" name="username">
            
                        <div class="btn-login">
                            <button type="submit">Pagar</button>
                        </div>
            
                    </form>
           
                </div>
            </section>
            
            <!--Footer -->
            <section class="footer">
                <br><br><br><br>
                <!-- <h4>Footer</h4> -->
                <p>Social Media Contacts</p>
                <div class="icons">
                    <!-- <i class="fa fa-star"></i> -->
                    <i class="fa fa-facebook"></i>
                    <i class="fa fa-twitter"></i>
                    <i class="fa fa-instagram"></i>
                    <!--<i class="fa fa-linkedin"></i>-->
                </div>
                <p>Feito por Grupo 1 de Engenharia de Requisitos (2021/2022)</p>
            </section>
            
            <script>
            
            </script>
            </body>
            </html>';
    }
}
?>