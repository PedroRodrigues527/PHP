<?php 
require_once("custom/php/common.php");
echo "<h1>HELLO WORLD</h1>";

if(!verify_user('manage_records'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //echo "<p>Tem autorização para aceder a esta página</p>";
    if (!empty($_POST))
    {
        //tem algum post
    }
    else{
        $allvalues = sql_query("SELECT name, birth_date, tutor_name, tutor_phone, tutor_email FROM child");

        if($allvalues == NULL)
    }
}
?>