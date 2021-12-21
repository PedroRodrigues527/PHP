<?php 
require_once("custom/php/common.php");

if(!verify_user('manage_unit_types'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {

    if (!empty($_POST))
    {
        //Inserir
        if($_POST == "inserir"){
            echo "<h3>Gestão de unidades - inserção</h3>";
        }
    }
    else{
        $allvalues = sql_query("SELECT id, unidade, subitem FROM child");//Query Desejado

        $verifyNotEmpty = sql_query("SELECT id, name FROM subitem_unit_type"); //Tabela subitem type
        echo "<h3>Gestão de unidades - introdução</h3>";

        //Verifica se não existem tuplos na tabela subitem_unit_type
        $result = sql_query($verifyNotEmpty);//conecta à db
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        if(! $row) {
            echo "<p>Não há tipos de unidades</p>";
        } else {
            //Tem conteúdo
            //echo "<p>TEMMM!</p>";
            //criar tabela
            echo " <table> ";
            echo " <th> id </th>"

        }
    }
}

?>