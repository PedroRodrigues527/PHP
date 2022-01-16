<?php
session_start();
if((isset($_SESSION['username']) || !empty($_SESSION['username']))) {
    //CONTINUAR AQUI
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
                    <li> <a href="remover_sessao.php">LOGOUT</a></li>
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
            <h2>Bem-vindo '.$_SESSION['username'].'!</h2>
            <br><br>
            <!-- para inserir links de outros ficheiros.php do menu principal -->
            <div class="inputBox">
                
                <form action="fazer-reserva.php" method="post">
                    <div class="btn-login">
                        <button type="submit">Fazer Reserva</button>
                    </div>
                </form>
                <form action="listar-reserva.php" method="post">
                    <div class="btn-login">
                        <button type="submit">Listar Reserva</button>
                    </div>
                </form>
                <form action="alterar-reserva.php" method="post">
                    <div class="btn-login">
                        <button type="submit">Modificar Reserva</button>
                    </div>
                </form>
                <form action="cancelar-reserva.php" method="post">
                    <div class="btn-login">
                        <button type="submit">Cancelar Reserva</button>
                    </div>
                </form>
                <br>
                <form action="pagamento.php" method="post">
                    <input type="hidden" name="paginaanterior" value="Subscrição Anual">
                    <div class="btn-login">
                        <button type="submit">Subscrição Anual</button>
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
else//te
{
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html")</script>';
}