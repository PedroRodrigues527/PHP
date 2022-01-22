<?php
session_start();
if((isset($_SESSION['username']) || !empty($_SESSION['username']))) {
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
                
                <h2>Alterar Reserva</h2>
                <br><br>';
    //Variaveis e a sua definição
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "er_db";
    $conn = mysqli_connect($servername, $username, $password, $db); //Ligação à base de dados pelo mysql em que tem as variáveis de cima
    //Se n ocorrer a conexão à base de dados, retorna uma mensagem de Erro
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $queryIdUser = mysqli_query($conn,'SELECT id FROM user WHERE username = "'.$_SESSION['username'].'"');
    $idresult = mysqli_fetch_array($queryIdUser);
    $iduser = $idresult[0];

    $queryReservList = 'SELECT * FROM reserve WHERE  user_id ="'. $iduser .'"';//id,ini date, end date, user id, bike id
    $queryResult = mysqli_query($conn, $queryReservList);

    if(mysqli_num_rows($queryResult) == 0){
        //Sem registo de reserva
        echo '<p>Não tem registo de reservas pendentes</p>';
    }else{
        //Criar tabela
        echo '<form method="POST" action="pagamento.php" name="cancelform">
              <table>
              <thead>
                <tr>
                  <th>ID</th> 
                  <th>ID Bicicleta</th> 
                  <th>Localidade</th>
                  <th>Data Início</th>
                  <th>Data Final</th>
                  <th>Ação</th>
                </tr>
              <thead>
              <tbody>';
        while($resultReserve = mysqli_fetch_array($queryResult)){
            $querylocalList = 'SELECT location FROM bicycle WHERE  id ="'. $resultReserve[4] .'"';
            $queryResultLocal = mysqli_query($conn, $querylocalList);
            $local = mysqli_fetch_array($queryResultLocal);
            echo'<tr>';
            echo'<td>' . $resultReserve[0] . '</td>'; //id
            echo'<td>' . $resultReserve[4] . '</td>'; //id bike
            echo'<td>' . $local[0] . '</td>'; //localidade
            echo'<td>' . $resultReserve[1] . '</td>';
            echo'<td>' . $resultReserve[2] . '</td>';
            echo'<td style="text-align: center">
                 </td>';
            echo'</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    echo '</div>
            </section>
            <br><br>
            
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

    mysqli_close($conn);
}
else{
    echo '<script>window.location.replace("http://localhost/Engenharia_de_Requisitos/index.html)</script>';
}



?>

