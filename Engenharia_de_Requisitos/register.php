<?php
if(empty($_REQUEST)){
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
    
                <div class="inputBox">
                    <label><b>Username: </b></label>
                    <input type="text" placeholder="Enter Username" name="username" maxlength="16" required>
                    <br> <br>
    
                    <label><b>Password: </b></label>
                    <input type="password" placeholder="Enter Password" id="password" name="password" maxlength="16" required>
                    <br><br>
                    
                    <label><b>Confirmar Password: </b></label>
                    <input type="password" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" maxlength="16" required>
                    <br><br>
    
                    <label><b>Email:     </b></label>
                    <input type="email" placeholder="Enter Email" name="e-mail" required>
                    <br><br>
    
                    <div class="btn-login">
                        <button id="submit" type="submit" onclick="return Validate()">Register</button>
                        <script>
                        function Validate() {
                            var password = document.getElementById("password").value;
                            var confirmPassword = document.getElementById("confirmpassword").value;
                            if (password != confirmPassword) {
                                alert("Passwords do not match.");
                                return false;
                            }
                            return true;
                        }
                        </script>
                    </div>
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
    
    </body>
    </html>';
}else{
    if(preg_match('/^[a-zA-Z0-9_-]*$/',$_POST['username']) && preg_match('/^[a-zA-Z0-9_-]*$/',$_POST['password']) && filter_var($_POST['e-mail'], FILTER_VALIDATE_EMAIL)) //Inserção válida no formulário
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "er_db";
        $conn = mysqli_connect($servername, $username, $password, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $queryStringUnique = 'SELECT id, username FROM user WHERE username ="'.$_POST['username'].'"';
        $queryResult = mysqli_query($conn, $queryStringUnique);
        if(mysqli_num_rows($queryResult) == 0) {
            //Ligação a base de dados;
            $queryString = 'INSERT INTO user (username, password, email) VALUES ("' . $_POST['username'] . '", "' . $_POST['password'] . '", "' . $_POST['e-mail'] . '");';
            $queryResult = mysqli_query($conn, $queryString);
            mysqli_close($conn);
            if ($queryResult) {
                echo '<script>if(confirm("Foi registado com sucesso!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
              }
              else
                  {
                      window.location.replace("http://localhost/Engenharia_de_Requisitos/login.php");
                  }</script>';
            } else {
                echo '<script>if(confirm("Erro ao registar a conta!")){
                        window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
              }
              else
                  {
                      window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
                  }</script>';
            }
        }
        else
        {
            echo '<script>if(confirm("O nome do usuário já foi usado. Por favor escolhe outro nome de usuário")){
                window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
              }
              else
              {
                window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
              }</script>';
        }
    }else{

        echo '<script>if(confirm("Os dados inseridos só podem aceitar números, letras e os carateres _ e -")){
                window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
              }
              else
              {
                window.location.replace("http://localhost/Engenharia_de_Requisitos/register.php");
              }</script>';
    }
}
?>
