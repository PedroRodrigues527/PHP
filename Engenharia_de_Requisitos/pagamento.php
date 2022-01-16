<?php
session_start();
if((!isset($_SESSION['username']) || empty($_SESSION['username'])) && (!isset($_POST['paginaanterior']) || empty($_POST['paginaanterior']))){
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

    $queryCardUser = 'SELECT id FROM user WHERE username = "' . $_SESSION['username'] . '"';
    $queryResult = mysqli_query($conn, $queryCardUser);

    $mes = substr($_POST['validade'], 5);

    $iduser = mysqli_fetch_array($queryResult);
    $queryString = 'SELECT * FROM credit_card WHERE number = "' . $_POST['card_num'] . '" AND month_val ="' . $mes . '" AND year_val = "' . substr($_POST['validade'], 0, -3) . '" AND PIN ="' . $_POST['pin'] . '" AND user_id ="' . $iduser[0] . '"';
    //echo $queryString;
    $queryResult2 = mysqli_query($conn, $queryString);
    if (mysqli_num_rows($queryResult2) > 0) {
        $rowCredit = mysqli_fetch_array($queryResult2);
        if ($rowCredit[6] < $_POST['preco']) {
            echo '<script>if(confirm("Saldo insuficiente!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
        } else {
            $queryString = 'UPDATE credit_card SET saldo = saldo -"' . $_POST['preco'] . '" WHERE id = "' . $rowCredit[0] . '"';
            if (!mysqli_query($conn, $queryString)) {
                echo '<script>if(confirm("Erro inesperado na ligação à base de dados!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
            } else {
                if($_POST['paginaanterior'] == 'Subscrição Anual') {
                    $datasub = date("Y-m-d", strtotime("+1 year"));
                    $queryUpdate = 'UPDATE user SET is_pro = 1 , data_pro = "' . $datasub . '" WHERE username ="' . $_SESSION['username'] . '"';
                    if (mysqli_query($conn, $queryUpdate)) {
                        echo '<script>
                            if(confirm("Pagamento com sucesso! Data de validade: ' . $datasub . '")){
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }
                          else{
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                              }</script>';
                    }
                }
                else if($_POST['paginaanterior'] == 'Nova Reserva') {
                    $endDate = strtotime($_SESSION['duracao'],strtotime($_SESSION['timebeg']));
                    $queryStringReserva = 'INSERT INTO reserve (inicial_date, end_date, user_id, bicycle_id) 
                                        VALUES ("'.$_SESSION['timebeg'].'","'.date("Y-m-d H:i",$endDate).'","'.$iduser[0].'","'.$_SESSION['bikeid'].'")';

                    if(mysqli_query($conn,$queryStringReserva)) {
                        unset($_SESSION['timebeg'], $_SESSION['duracao'], $_SESSION['bikeid']);
                        echo '<script>if(confirm("A reserva foi inserida com sucesso!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
                    }

                    else
                    {
                        echo '<script>if(confirm("Erro na inserção da reserva!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/fazer-reserva.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/fazer-reserva.php");
                          }</script>';
                    }
                }
                else if($_POST['paginaanterior'] == 'Cancelamento da Reserva') {
                    $queryStringDelReserva = 'DELETE FROM reserve WHERE id = "'.$_SESSION['reservaid'].'"';
                    if(mysqli_query($conn,$queryStringDelReserva)) {
                        unset($_SESSION['reservaid']);
                        echo '<script>if(confirm("A reserva foi cancelada com sucesso!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
                    }
                    else
                    {
                        echo '<script>if(confirm("Erro no cancelamento da reserva!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/cancelar-reserva.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/cancelar-reserva.php");
                          }</script>';
                    }
                }
            }
        }
    } else {
        echo '<script>if(confirm("Dados de cartão de crédito inseridos estão inválidos!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
    }
    mysqli_close($conn);//te
}
else{
    //Conectar BD
    $acederPag = 0;
    if($_POST['paginaanterior'] == 'Subscrição Anual') {
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

        //query para verificar sub anual
        if (mysqli_num_rows($queryResult) > 0) {
            $row = mysqli_fetch_array($queryResult);
            if ($row[0] == 0 && $row[1] == NULL) {
                //Nao tem subscrição
                $acederPag = 1;
            } else {
                //Tem sub
                $querySubData = 'SELECT data_pro FROM user WHERE username ="'. $_SESSION['username'] .'"';
                $resultSubData = mysqli_query($conn, $querySubData);
                $SubData = mysqli_fetch_array($resultSubData);

                echo '<script>if(confirm("Ainda possui subscrição anual até '.$SubData[0].'")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
              }
              else
                  {
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                  }</script>';
            }
        }
        mysqli_close($conn);
    }
    else if ($_POST['paginaanterior'] == 'Nova Reserva'){
        //Reserva - pagamento
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
        //query para verificar sub anual
        if (mysqli_num_rows($queryResult) > 0) {
            $row = mysqli_fetch_array($queryResult);
            if ($row[0] == 0 && $row[1] == NULL) {
                //Nao tem subscrição, precisa de pagar a reserva
                $acederPag = 2;
            } else {
                //INSERIR RESERVA
                $queryIdUser = mysqli_query($conn,'SELECT id FROM user WHERE username = "'.$_SESSION['username'].'"');
                $UserID = mysqli_fetch_array($queryIdUser);
                $endDate = strtotime($_POST['duracao'],strtotime($_POST['timebeg']));
                $queryStringReserva = 'INSERT INTO reserve (inicial_date, end_date, user_id, bicycle_id) 
                                        VALUES ("'.$_POST['timebeg'].'","'.date("Y-m-d H:i",$endDate).'","'.$UserID[0].'","'.$_POST['bikeid'].'")';
                if(mysqli_query($conn,$queryStringReserva)) {
                    echo '<script>if(confirm("Ja possui subscrição anual, logo a reserva foi inserida com sucesso!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
                }
                else
                {
                    echo '<script>if(confirm("Erro na inserção da reserva!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/fazer-reserva.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/fazer-reserva.php");
                          }</script>';
                }
            }
        }
        mysqli_close($conn);
    }
    else if($_POST['paginaanterior'] == "Cancelamento da Reserva"){ //Cancelamento reservar
        //Verificar se paga taxa
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "er_db";
        $conn = mysqli_connect($servername, $username, $password, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $queryStringDataReserva = mysqli_query($conn,'SELECT * FROM reserve WHERE id = "'.$_POST['reservaid'].'"');
        $rowReserva = mysqli_fetch_array($queryStringDataReserva);

        $date1=date_create(date("Y-m-d H:i:s"));
        $date2=date_create($rowReserva[1]);

        $interval = date_diff($date1, $date2);
        //$interval->format('%a days');
        if($interval->format('%a days') == '1 days' || $interval->format('%a days') == '0 days')
        {
            //pagar taxa extra 5 euros
            $acederPag = 3;
        }
        else
        {
            //cancelar a reserva
            $queryStringDelReserva = 'DELETE FROM reserve WHERE id = "'.$_POST['reservaid'].'"';
            if(mysqli_query($conn,$queryStringDelReserva)) {
                echo '<script>if(confirm("A reserva foi cancelada com sucesso!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php");
                          }</script>';
            }
            else
            {
                echo '<script>if(confirm("Erro no cancelamento da reserva!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/cancelar-reserva.php");
                      }
                      else
                          {
                                window.location.replace("http://localhost/Engenharia_de_Requisitos/cancelar-reserva.php");
                          }</script>';
            }
        }

        mysqli_close($conn);
    }
    else if(false){ //Modificação reservar
        //Verificar se paga taxa
    }

    if($acederPag>0){
        //html
        if($acederPag == 1){
            $preco=45.99;
        }
        else if($acederPag == 2) {
            $_SESSION['timebeg'] = $_POST['timebeg'];
            $_SESSION['duracao'] = $_POST['duracao'];
            $_SESSION['bikeid'] = $_POST['bikeid'];
            if ($_POST['duracao'] == '+1 hours') {
                $preco = 4.99;
            } else if ($_POST['duracao'] == '+2 hours') {
                $preco = 8.99;
            } else if ($_POST['duracao'] == '+3 hours') {
                $preco = 22.99;
            } else if ($_POST['duracao'] == '+4 hours') {
                $preco = 39.99;
            }
        }
        else if($acederPag == 3)
        {
            $preco=5.00;
            $_SESSION['reservaid'] = $_POST['reservaid'];
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
                    <img src="img/logo.png" style="width:140px; height:100px;">
                    <div class="nav-links" id="navLinks">
                        <i class="fa fa-window-close"></i>
                        <ul>
                            <!-- <li> <a href="#">HOME</a></li> -->
                            <li> <a href="menuprincipal.php">PÁGINA PRINCIPAL</a></li>
                            <li> <a href="ajuda.php">AJUDA</a></li>
                        </ul>
                    </div>
                    <i class="fa fa-ellipsis-v" onclick="showMenu()"></i>
                </nav>
            
            </section>
            
            <!-- Course -->
            <section class="course" id="course">
                <div class="input-box" >
            
                    <p id="desc">Descrição pagamento: '.$_POST['paginaanterior'].' </p>
                    <p id="val">Valor: '. $preco.' &#128;</p>
                    <br>
            
                    <form action="" method="post">
                        <div class="input-form">
                            <label><b>Número do cartão: </b></label>
                            <input type="text" inputmode="numeric" placeholder="Número cartão crédito" name="card_num" maxlength="16" required>
                            <br> <br>
                
                            <label><b>PIN: </b></label>
                            <input type="password" inputmode="numeric" minlength="4" maxlength="4" placeholder="PIN" name="pin" required>
                            <br><br>
                            
                            <label><b>Validade: </b></label>
                            <input id="month" type="month" name="validade" style="width: 25%;" required>
                            <br> <br>
                        </div>
            
                        <input type="hidden" value="'.$preco .'" name="preco">
                        <input type="hidden" value="'.$_POST['paginaanterior'].'" name="paginaanterior">
                
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
