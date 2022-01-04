<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_items'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if ($_POST["estado"] == "inserir")
    {
        echo "<h3>Gestão de itens - inserção</h3>";
        $noerrors = true;
        //Validar
        if($_POST['nome_item'] == "" || ctype_space($_POST['nome_item'])){
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: O dado inserido no formulário do Nome do Item está vazia!</p>";
            $noerrors = false;
        }
        //Verifica se foi submetido um dado com números ou carateres especiais além das letras
        else if(!preg_match ('/^[a-zA-Z0-9 \p{L}]+$/ui', $_POST['nome_item']))
        {
            //Apresentar mensagem de erro (Tem números!)
            echo "<p>ERRO: O dado inserido no formulário do Nome da Item só pode ter letras, acentos e espaços vazios!</p>";
            $noerrors = false;
        }
        if ($_POST['tipo_de_item'] == "" || ctype_space($_POST['tipo_de_item'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Tipo de Item!</p>";
            $noerrors = false;
        }
        if ($_POST['state'] == "" || ctype_space($_POST['state'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Estado!</p>";
            $noerrors = false;
        }

        //Entra aqui se os dados inseridos forem válido
        if($noerrors){
            //Inserir nome da unidade na Base de dados
            $insertQuery = "INSERT INTO item (name, item_type_id, state ) 
                VALUES('" . $_POST['nome_item'] . "', '" . $_POST['tipo_de_item'] . "', '" . $_POST['state'] . "')";

            //Caso de sucesso
            if (mysql_searchquery($insertQuery)) {
                echo "<p>Inseriu os dados de novo tipo de item com sucesso.</p>";
                continue_button();
            }
        }
        else
        {
            go_back_button();
        }
    }
    else {
        //Tabela
        //Verifica se não existem tuplos na tabela subitem_unit_type
        $itemQuery = mysql_searchquery('SELECT id, name FROM item'); //Tabela item
        $row = mysqli_fetch_array($itemQuery, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há itens</p>";
        }
        else{
            //$tableQuery = mysql_searchquery('SELECT item.id, item.name, item.state, item_type.id, item_type.name FROM item, item_type WHERE item.id = item.item_type_id'); //Query Tabela
            $typeItemQuery = mysql_searchquery('SELECT item_type.id, item_type.name FROM item_type'); //Lista dos tipo de item.

            //Construção da tabela
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2" >
               <tbody>
                  <tr>
                     <th>tipo de item</th>
                     <th>id</th>
                     <th>nome do item</th>
                     <th>estado</th>
                     <th>ação</th>
                  </tr>';

            while($rowType = mysqli_fetch_array($typeItemQuery, MYSQLI_NUM)) {

                $queryNum = 'SELECT item.* FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0];//query: items associados a um tipo de item
                $resultsQueryNum = mysql_searchquery($queryNum);
                $rowCount = mysqli_num_rows($resultsQueryNum); //Quantos items associados a um tipo de item
                if($rowCount == 0)
                {
                    $rowCount = 1;
                }//teste
                echo "<tr>";
                echo "<td rowspan='$rowCount' colspan='1' >" . $rowType[1] . "</td>"; //Tipo de item

                $restTableQuery = mysql_searchquery('SELECT item.id, item.name, item.state FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0]); //Lista do resto da tabela
                $restTableQuery2 = mysql_searchquery('SELECT item.id, item.name, item.state FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0]); //Lista do resto da tabela
                if(!mysqli_fetch_array($restTableQuery2, MYSQLI_NUM))
                {
                    echo "<td rowspan='1' colspan='4'>Este tipo de item não tem itens.</td>";//nada
                    echo "</tr>";
                }
                else {
                    while ($rowTabela = mysqli_fetch_array($restTableQuery, MYSQLI_NUM)) {
                        echo "<td>" . $rowTabela[0] . "</td>"; //id
                        echo "<td>" . $rowTabela[1] . "</td>"; //nome do item
                        echo "<td>" . $rowTabela[2] . "</td>"; //estado

                        if ($rowTabela[2] == "ativado") { //ação
                            echo "<td> [editar] [desativar] </td>";
                        } else {
                            echo "<td> [editar] [ativar] </td>";
                        }
                        echo "</tr>";
                    }
                }
            }
            echo "</tbody></table>";
        }


        echo "<h3>Gestão de itens - introdução</h3>";
        //Form
        $allactivetypes = get_enum_values("item", "state");
        echo '<form action="" name="InsertForm" method="POST">
            Nome: <input type="text" name="nome_item"/>
            <p>Tipo:</p>';
        $itemQuery = mysql_searchquery('SELECT * FROM item_type'); //Tabela item_type
        while($row = mysqli_fetch_array($itemQuery, MYSQLI_NUM))
        {
            echo '<input type="radio" name="tipo_de_item" value="' . $row[0] . '"><label>' . $row[1] . '</label><br>';
        }
        echo '<p>Estado:</p>
            <input type="radio" name="state" value="' . $allactivetypes[0] . '" ><label>ativo</label>
            <input type="radio" name="state" value="' . $allactivetypes[1] . '" ><label>inativo</label>
            
            <input type="hidden" value="inserir" name="estado"/>               
            <input type="submit" value="Inserir item">
            </form>';
    }
}
?>
