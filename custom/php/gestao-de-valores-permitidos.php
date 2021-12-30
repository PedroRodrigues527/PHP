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

        //Query Duplicado para só verificar a primeira linha e se esta está vazia
        $querystringcopy = 'SELECT id, name FROM item ORDER BY name ASC';
        $queryresultcopy = mysql_searchquery($querystringcopy);

        $row = mysqli_fetch_array($queryresultcopy, MYSQLI_NUM);
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
                $queryNum = 'SELECT subitem_allowed_value.* FROM subitem_allowed_value, subitem, item WHERE subitem_allowed_value.subitem_id = subitem.id AND subitem.item_id = item.id AND item.id = ' . $rowTabela['id'];
                $resultsQueryNum = mysql_searchquery($queryNum);
                $rowCount = mysqli_num_rows($resultsQueryNum); //Quantos items associados a um tipo de item
                if($rowCount == 0)
                {
                    $rowCount = 1;
                }

                echo "<tr>";
                echo "<td>" . $rowTabela['name'] . "</td>"; //ID

                $querystring2 = 'SELECT subitem.id, subitem.name FROM subitem, item WHERE subitem.item_id = item.id AND subitem.value_type ="enum" AND item.id = ' . $rowTabela['id'] . ' ORDER BY id ASC';
                $queryresult2 = mysql_searchquery($querystring2);//Query Desejado

                $queryresult2dup = mysql_searchquery($querystring2);
                $row = mysqli_fetch_array($queryresult2dup, MYSQLI_NUM);
                if(!$row) { //Verifica se linha esta vazia
                    echo "<td>Não há subitems especificados cujo tipo de valor seja enum. Especificar primeiro novo(s) item(s) e depois voltar a esta opção.</td>";
                }else {
                    while($rowTabela2 = mysqli_fetch_array($queryresult2, MYSQLI_NUM)) {
                        echo "<td>" . $rowTabela2[0] . "</td>";
                        echo "<td>" . $rowTabela2[1] . "</td>";
                        $querystring3= 'SELECT subitem.name as subitem, subitem.id as id FROM subitem WHERE subitem.value_type = "enum" ORDER BY name ASC ';
                        $queryresult3 = mysql_searchquery($querystring3);//Query Desejado
                        $queryresult3dup = mysql_searchquery($querystring3);
                        $row = mysqli_fetch_array($queryresult3dup, MYSQLI_NUM);

                        $rowcount = mysqli_num_rows($queryresult3);
                        if($rowcount == 0)
                        {
                            $rowcount = 1;
                        }

                        echo "<tr>";
                        echo "<td rowspan='$rowcount'>" . $rowTabela[0] . "</td>";

                        if(!$row) { //Verifica se está vazio
                            echo "<p>Não há itens</p>";
                        }else{
                            while($rowTabela3 = mysqli_fetch_array($queryresult3, MYSQLI_NUM)) {
                                echo "<td>" . $rowTabela3[0] . "</td>"; //subitem
                                echo "<td>" . $rowTabela3[1] . "</td>"; //id
                                echo "<td rowspan=".$rowcount ." colspan='1'>" . $rowTabela[0] . "</td>";
                            }

                        }



                    }



                }





            }








        }
        //$querystring2 ='SELECT Subitem.id AND Subitem.name WHERE Subitem.item_id == item.id AND item.id = $querystring(id)';




    }

}