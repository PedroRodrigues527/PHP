<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_unit_types'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if (!empty($_POST))
    {
        //Inserir
        if($_POST == "inserir"){
            echo "<h3>Gestão de unidades - inserção</h3>";

            //Fazer formulário para cada campo,
            //Se tiver errado ou incompleto informar
            //Apresentar uma ligação para voltar ao passo anterior, caso contrário executar o que se segue
            //construir uma string com o comando SQL necessário para inserção dos dados na tabela subitem_unit_type e executá-lo
            //Apresentar, em caso de sucesso da inserção:
                //    Inseriu os dados de novo tipo de unidade com sucesso.
                //    Clique em Continuar para avançar
                //    em que Continuar é uma ligação para esta mesma página
        }
    }
    else{
        //Fazer pesquisa de filtragem (query)
        $querystring = 'SELECT subitem_unit_type.id as sut_id, subitem_unit_type.name as sut_name , subitem.name as si_name, item.name as i_name FROM subitem_unit_type,subitem, item WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id';
        $queryresult = mysql_searchquery($querystring);//Query Desejado

        echo "<h3>Gestão de unidades - introdução</h3>";

        //Verifica se não existem tuplos na tabela subitem_unit_type
        $verifyNotEmpty = mysql_searchquery('SELECT id, name FROM subitem_unit_type'); //Tabela subitem type
        $row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há tipos de unidades</p>";
        } else {
            //Tem tuplos
            echo '<table>
               <tbody>
                  <tr>
                     <td><b>Id</b></td>
                     <td><b>Unidade</b></td>
                     <td><b>Subitem</b></td>
                  </tr>';

            while($rowTabela = mysqli_fetch_assoc($queryresult)){
                echo "<tr>";
                echo "<td>" . $rowTabela['sut_id'] . "</td>"; //ID
                echo "<td>" . $rowTabela['sut_name'] . "</td>"; //Unidade
                $queryItemSubitem = 'SELECT item.id, item.name, subitem.id, subitem.name, subitem_unit_type.id, subitem_unit_type.name FROM item, subitem, subitem_unit_type WHERE item.id = subitem.item_id AND subitem.unit_type_id = subitem_unit_type.id';
                $resultItemSubitem = mysql_searchquery($queryItemSubitem);//Query Desejado
                $resultValueString = "";
                //Procura dados enquanto houver resultado
                while($rowItemSubitem = mysqli_fetch_array($resultItemSubitem, MYSQLI_NUM)){
                    $resultValueString .= "<td>" . $rowItemSubitem[3] . " (" . $rowItemSubitem[1] . "), "; //Subitem
                }
                echo substr_replace($resultValueString ,"", -2);
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    }
}

?>
