<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_allowed_values'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {

    //Verifica se o valor do estado é "validar"
    if ($_REQUEST["estado"] == "introducao") {

    }
    else if ($_REQUEST["estado"] == "inserir") {
        echo "<h3>Dados de registo - inserção</h3>";
        //Inserção dos valores permitidos na Base de dados
        $insertQuery = "INSERT INTO subitem_allowed_value (name) 
                    VALUES('" . $_POST['Gestão de valores permitidos'] . "')";

        //Caso de sucesso
        if (mysql_searchquery($insertQuery)) {
            echo "<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>";
            continue_button();


        }

    }
    else//fazes no else o query e seu resultado e depois verificar cada caso
    {
        //Fazer pesquisa de filtragem (query)
        $querystring = 'SELECT id, name FROM item ORDER BY name ASC';
        $queryresult = mysql_searchquery($querystring);//Query Desejado
        $row = mysqli_fetch_array($queryresult, MYSQLI_NUM);
        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há itens</p>";
        } else {
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th><b>Item</b></th>
                     <th>Id</th>
                     <th><b>Subitem</b></th>
                     <th>Id</th>
                     <th>>Valores Permitidos</th>
                     <th>Estado</th>
                  </tr>';


            while($rowTabela = mysqli_fetch_assoc($queryresult)) {
                echo "<tr>";
                echo "<td>" . $rowTabela['name'] . "</td>"; //ID

                $querystring2 = 'SELECT subitem.id, subitem.name FROM subitem, item WHERE subitem.item_id = item.id AND subitem.value_type ="enum" AND item.id = ' . $rowTabela['id'] . ' ORDER BY id ASC';
                $queryresult2 = mysql_searchquery($querystring2);//Query Desejado
                $row = mysqli_fetch_array($queryresult2, MYSQLI_NUM);
                if(!$row) { //Verifica se linha esta vazia
                    echo "<p>Não há subitems especificados cujo tipo de valor seja enum. Especificar primeiro novo(s) item(s) e depois voltar a esta opção.</p>";
                }else {
                    while($rowTabela = mysqli_fetch_assoc($queryresult2)) {
                        echo "<td>" . $rowTabela['subitem.id'] . "</td>";
                        echo "<td>" . $rowTabela['subitem.name'] . "</td>";


                    }
                }





            }








        }
        //$querystring2 ='SELECT Subitem.id AND Subitem.name WHERE Subitem.item_id == item.id AND item.id = $querystring(id)';




    }

}