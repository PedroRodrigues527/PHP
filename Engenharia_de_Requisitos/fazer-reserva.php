<?php
session_start();
if((isset($_SESSION['username']) || !empty($_SESSION['username']))) {
    if(empty($_POST)) {
        echo '
        <!DOCTYPE html>
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
            <div class="input-box">
                <!--
                <form action="" method="post">
                    <label><b>Username: </b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>
                    <br> <br>
        
                    <label><b>Password: </b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>
                    <br><br>
                    <div class="btn-login">
                        <button type="submit">Login</button>
                    </div>
        
                </form>
                -->
                <h2>Criar nova Reserva</h2>
                <br><br>
                <!-- para inserir links de outros ficheiros.php do menu principal -->
                <div class="inputBox">
                    
                    <form action="pagamento.php" method="post">
                    <label><b>Posto e número de bicicleta: </b></label>
                    <select name="bikeid" id="bikes">
                    ';
        //Conexão à BD
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "er_db";
        $conn = mysqli_connect($servername, $username, $password, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $queryBikes = 'SELECT id, location FROM bicycle WHERE state = "1"';
        $queryResult = mysqli_query($conn, $queryBikes);
        while($rowByke = mysqli_fetch_array($queryResult))
        {
            echo '<option value="'.$rowByke[0].'">'.$rowByke[1].' - '.$rowByke[0].'</option>';
        }
        echo '      </select>
                    <br> <br>
        
                    <label><b>Data e Hora de Início da Reserva: </b></label>
                    <!-- CORRIGIR DATA MINIMA -->
                    <input type="datetime-local" name="timebeg" min="'.date("Y-m-d",strtotime(' +2 days')).'T'.date("h:i").'" required>
                    <br><br>
                    
                    <label><b>Duração da Reserva: </b></label>
                    <select name="duracao" id="duracao">
                        <option value="+1 hours">1 hora</option>
                        <option value="+2 hours">2 horas</option>
                        <option value="+3 hours">3 horas</option>
                        <option value="+4 hours">4 horas</option>
                    </select>
                    <br><br>
                    <input type="hidden" name="paginaanterior" value="Nova Reserva">
                    <div class="btn-login">
                        <button type="submit">Inserir reserva</button>
                    </div>
        
                </form>
                    
                </div>
        
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
        
        
        <!-- JS for the media query menu -->
        <script>
            /*
            var a = document.getElementById("navLinks");
        
            function hideMenu(){
               navLinks.style.transition = "1s";
               navLinks.style.display = "inline";
               navLinks.style.right = "-200px";
            }
        
            function showMenu(){
               navLinks.style.transition = "1s";
               navLinks.style.display = "inline";
               navLinks.style.right = "0px";
            }*/
        </script>
        </body>
        </html>';
    }
}
else
{
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html")</script>';
}