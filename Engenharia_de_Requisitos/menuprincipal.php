<?php
session_start();
if (empty($_SESSION)) {
    echo 'nope';
}
else
{
    if(preg_match('/^[a-zA-Z0-9_.-]*$/',$_SESSION['username']) && preg_match('/^[a-zA-Z0-9_.-]*$/',$_SESSION['password']))
    {
        $queryString = 'SELECT * FROM user WHERE username = "'.$_SESSION['username'].'" AND password = "'.$_SESSION['password'].'"';
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
        if(!mysqli_num_rows($queryResult))
        {
            echo 'nope3';//teste
        }
        else
        {
            echo 'yep';
        }
    }
    else
    {
        echo 'nope2';
    }
}