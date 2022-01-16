<?php
if(empty($_REQUEST)) {
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
            <a href="index.html"><img src="img/logo.png" style="width:140px; height:100px;"></a>
            <div class="nav-links" id="navLinks">
                <i class="fa fa-window-close"></i>
                <ul>
                    <!-- <li> <a href="#">HOME</a></li> -->
                    <li> <a href="index.html">INÍCIO</a></li>
                    <li> <a href="ajuda.php">AJUDA</a></li>
                </ul>
            </div>
            <i class="fa fa-ellipsis-v" onclick="showMenu()"></i>
        </nav>
    
    </section>
    
    <!-- Course -->
    <section class="course" id="course">
        <div class="input-box">
    
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
else
{
    if(preg_match('/^[a-zA-Z0-9_.-]*$/',$_POST['username']) && preg_match('/^[a-zA-Z0-9_.-]*$/',$_POST['password']))
    {
        $queryString = 'SELECT * FROM user WHERE username = "'.$_POST['username'].'" AND password = "'.$_POST['password'].'"';
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
        if(mysqli_num_rows($queryResult) > 0)
        {
            session_start();
            $_SESSION['username'] = $_POST['username'];
            echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/menuprincipal.php")</script>';
        }
        else
        {
            echo '<script>if(confirm("Não foi encontrado a sua conta na base de dados! Verifique se já registou a conta primeiro.")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
              }
              else
                  {
                      window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
                  }</script>';
        }
    }
    else
    {
        echo '<script>if(confirm("Os dados inseridos só podem aceitar números, letras e os carateres _ e -")){
                window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
              }
              else
              {
                window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
              }</script>';
    }
}

