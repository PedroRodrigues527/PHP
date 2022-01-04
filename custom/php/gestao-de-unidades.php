<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_unit_types'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if ($_POST["estado"] == "inserir")
    {
        echo "<h3>Dados de registo - inserção</h3>";

        //Verifica se foi submetido string(nome_unidade) vazia;
        if($_POST['nome_unidade'] == "" || ctype_space($_POST['nome_unidade'])){
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade está vazia!</p>";
            go_back_button();
        }
        //Verifica se foi submetido um dado com números além das letras
        else if(!preg_match('/^[a-zA-Z/\p{L}]+$/ui', $_POST['nome_unidade']))
        {
            //Apresentar mensagem de erro (Tem números!)
            echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade só pode ter letras, espaços vazios e acentos!</p>";
            go_back_button();
        }
        //Entra aqui se e só se o 'nome_unidade' inserido seja válido
        else {
            //Inserir nome da unidade na Base de dados
            $insertQuery = "INSERT INTO subitem_unit_type (name) 
                    VALUES('" . $_POST['nome_unidade'] . "')";

            //Caso de sucesso
            if (mysql_searchquery($insertQuery)) {
                echo "<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>";
                continue_button();
            }
        }
    }
    else{
        //Fazer pesquisa de filtragem (query)
        $querystring = 'SELECT id as sut_id, name as sut_name FROM subitem_unit_type
                        ORDER BY id ASC';
        $queryresult = mysql_searchquery($querystring);//Query Desejado

        //Verifica se não existem tuplos na tabela subitem_unit_type
        $verifyNotEmpty = mysql_searchquery('SELECT id, name FROM subitem_unit_type'); //Tabela subitem type
        $row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há tipos de unidades</p>";
        } else {
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th><b>Id</b></th>
                     <th><b>Unidade</b></th>
                     <th><b>Subitem</b></th>
                  </tr>';

            while($rowTabela = mysqli_fetch_assoc($queryresult)){
                echo "<tr>";
                echo "<td>" . $rowTabela['sut_id'] . "</td>"; //ID
                echo "<td>" . $rowTabela['sut_name'] . "</td>"; //Unidade

                $queryItemSubitem = 'SELECT subitem.name as si_name, item.name as i_name FROM subitem, item, subitem_unit_type 
                                     WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id AND subitem_unit_type.id = ' . $rowTabela['sut_id'];
                $resultItemSubitem = mysql_searchquery($queryItemSubitem);//Query Desejado
                $resultValueString = "";
                echo "<td>";
                //Procura dados enquanto houver resultado
                while($rowItemSubitem = mysqli_fetch_array($resultItemSubitem, MYSQLI_NUM)){
                    $resultValueString .= $rowItemSubitem[0] . " (" . $rowItemSubitem[1] . "), "; //Subitem
                }
                echo substr_replace($resultValueString ,"", -2);
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        echo "<h3>Gestão de unidades - introdução</h3>";

        echo '<form action="" name="InsertForm" method="POST">
                Nome da Unidade: <input type="text" name="nome_unidade"/> 
                <input type="hidden" value="inserir" name="estado"/>
                <input type="submit" value="Inserir tipo de unidade" />
                </form>';
    }
}

?>
