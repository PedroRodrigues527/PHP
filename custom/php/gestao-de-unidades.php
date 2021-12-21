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
        //ERRO AO DETETAR QUERY, PENSA QUE É OBJETO E NAO STRING
        $querystring = 'SELECT subitem_unit_type.id as id, subitem_unit_type.name as Unidade , subitem.name as subitem, item.name as item FROM subitem_unit_type,subitem, item WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id';
        //$query = sql_query($querystring);//Query Desejado
        $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $query = mysqli_query($conn,'SELECT subitem_unit_type.id as id, subitem_unit_type.name as Unidade , subitem.name as subitem, item.name as item FROM subitem_unit_type,subitem, item WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id');
        echo "<h3>Gestão de unidades - introdução</h3>";

        //Verifica se não existem tuplos na tabela subitem_unit_type
        //$verifyNotEmpty = sql_query('SELECT id, name FROM subitem_unit_type'); //Tabela subitem type
        $verifyNotEmpty = mysqli_query($conn,'SELECT id, name FROM subitem_unit_type');

        $row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há tipos de unidades</p>";

        } else {

            //Tem conteúdo
            //echo "<p>TEM!</p>";
            //criar tabela

            //$resultTabelaItem = mysqli_query($conn,$query);
            $resultTabelaItem = $query;
            echo '<table>
               <tbody>
                  <tr>
                     <td><b>id</b></td>
                     <td><b>Unidade</b></td>
                     <td><b>Subitem</b></td>
                     <td><b>Item</b></td>
                  </tr>';
            while($rowTabela = mysqli_fetch_assoc($resultTabelaItem)){
                echo "<tr>";
                echo "<td>" . $rowTabela['id'] . "</td>";
                echo "<td>" .$rowTabela['Unidade'] . "</td>";
                echo "<td>" . $rowTabela['subitem'] . " </td>";
                echo "<td>" . $rowTabela['item'] . " </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    }
}

?>
