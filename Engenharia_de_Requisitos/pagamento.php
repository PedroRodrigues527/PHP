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
    $acederPag = false;
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
                $acederPag = true;
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

    if($acederPag){
        //html
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
                    <div class="input-box">
                
                        <p id="desc">Descrição pagamento: </p>
                        <p id="val">Valor: </p>
                        <br>
                
                        <form action="" method="post">
                            <label><b>Numero do cartão: </b></label>
                            <input type="number" placeholder="Número cartão" name="username" required>
                            <br> <br>
                
                            <label><b>Validade: </b></label>
                            <input id="month" type="month" name="bday-month" required>
                            <br> <br>
                
                            <label><b>PIN: </b></label>
                            <input type="number" placeholder="Enter Password" name="password" required>
                            <br><br>
                
                            <label><b>Dígito de segurança: </b></label>
                            <input type="number" placeholder="Enter Password" name="password" required>
                            <br><br>
                            <div class="btn-login">
                                <button type="submit">Continuar</button>
                            </div>
                
                        </form>
                
                
                        <!--<h2>Bem-vindo '.$_SESSION['username'].'!</h2>
                        <br><br>-->
                        <!-- para inserir links de outros ficheiros.php do menu principal -->
                        <!--
                        <div class="inputBox">
                
                            <form action="fazer-reserva.php" method="post">
                                <input type="hidden" name="username" value="'.$_SESSION['username'].'">
                                <div class="btn-login">
                                    <button type="submit">Fazer Reserva</button>
                                </div>
                            </form>
                            <form action="listar-reserva.php" method="post">
                                <input type="hidden" name="username" value="'.$_SESSION['username'].'">
                                <div class="btn-login">
                                    <button type="submit">Listar Reserva</button>
                                </div>
                            </form>
                            <form action="modificar-reserva.php" method="post">
                                <input type="hidden" name="username" value="'.$_SESSION['username'].'">
                                <div class="btn-login">
                                    <button type="submit">Modificar Reserva</button>
                                </div>
                            </form>
                            <form action="cancelar-reserva.php" method="post">
                                <input type="hidden" name="username" value="'.$_SESSION['username'].'">
                                <div class="btn-login">
                                    <button type="submit">Cancelar Reserva</button>
                                </div>
                            </form>
                            <br>
                            <form action="pagamento.php" method="post">
                                <input type="hidden" name="username" value="'.$_SESSION['username'].'">
                                <input type="hidden" name="paginaanterior" value="subscricaoanual">
                                <div class="btn-login">
                                    <button type="submit">Subscrição Anual</button>
                                </div>
                            </form>
                
                        </div>-->
                
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