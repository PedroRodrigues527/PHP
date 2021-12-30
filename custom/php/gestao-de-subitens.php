<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_subitems'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if ($_POST["estado"] == "inserir") {
        //Inserir
        echo "<h3>Gestão de subitens - inserção</h3>";
        $noerrors = true;
        //Validar
        if ($_POST['nome_subitem'] == "" || ctype_space($_POST['nome_subitem'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: O dado inserido no formulário do Nome do Subitem está vazia!</p>";
            $noerrors = false;
        } //Verifica se foi submetido um dado com números ou carateres especiais além das letras
        else if (!preg_match('/^[a-zA-Z0-9 \p{L}]+$/ui', $_POST['nome_subitem'])) {
            //Apresentar mensagem de erro (Tem carateres especiais!)
            echo "<p>ERRO: O dado inserido no formulário do Nome do Subitem só pode ter letras, acentos e espaços vazios!</p>";
            $noerrors = false;
        }
        if ($_POST['value_type'] == "" || ctype_space($_POST['value_type'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Tipo de Valor!</p>";
            $noerrors = false;
        }
        if ($_POST['item_name'] == "" || ctype_space($_POST['item_name'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Item!</p>";
            $noerrors = false;
        }
        if ($_POST['form_field_type'] == "" || ctype_space($_POST['form_field_type'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Tipo do Campo do Formulário!</p>";
            $noerrors = false;
        }
        //não tem condição no subitem_unit_type_name
        if ($_POST['form_field_order'] == "" || ctype_space($_POST['form_field_order'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: O dado inserido no formulário da Ordem do campo no formulário está vazia!</p>";
            $noerrors = false;
        }
        else if(!preg_match('/^[1-9]\d*$/', $_POST['form_field_order']))
        {
            echo "<p>ERRO: O dado inserido no formulário da Ordem do campo no formulário só pode ser um número positivo maior que 0!</p>";
            $noerrors = false;
        }
        if ($_POST['mandatory'] == "" || ctype_space($_POST['mandatory'])) {
            //Apresentar mensagem de erro (Nome vazio!)
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Obrigatório!</p>";
            $noerrors = false;
        }
        //Entra aqui se os dados inseridos forem válido
        if($noerrors)
        {
            if($_POST['subitem_unit_type_name'] == "")
            {
                $insertQuery = "INSERT INTO subitem (name, item_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_order, mandatory, state)
                VALUES('" . $_POST['nome_subitem'] . "', '" . $_POST['item_name'] . "', '" . $_POST['value_type'] . "', '', '" . $_POST['form_field_type'] . "', NULL, '" . $_POST['form_field_order'] . "', '" . $_POST['mandatory'] . "', 'active')";
            }
            else {
                $insertQuery = "INSERT INTO subitem (name, item_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_order, mandatory, state)
                VALUES('" . $_POST['nome_subitem'] . "', '" . $_POST['item_name'] . "', '" . $_POST['value_type'] . "', '', '" . $_POST['form_field_type'] . "', '" . $_POST['subitem_unit_type_name'] . "', '" . $_POST['form_field_order'] . "', '" . $_POST['mandatory'] . "', 'active')";
            }
            $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
            //Caso de sucesso
            if (mysqli_query($link,$insertQuery)) {
                $idobtido = mysqli_insert_id($link); //id do subitem
                $queryNomeItem = "SELECT name FROM item WHERE id = '" . $_POST['item_name'] . "'";
                $resultNomeItem = mysql_searchquery($queryNomeItem);
                $rowTabelaNomeItem = mysqli_fetch_array($resultNomeItem, MYSQLI_NUM);
                $NomeItem = $rowTabelaNomeItem[0];
                $NomeItemconcat = substr($NomeItem, 0, 3); //med, aut, ...

                $stringNomeSubitem = preg_replace('/[^a-z0-9_ ]/i', '', $_POST['nome_subitem']); //altura, cr, ...
                $stringNomeSubitem = str_replace(' ', '_', $stringNomeSubitem); //peso_h_2_anos

                $resultadoFINAL = $NomeItemconcat . '-' . $idobtido . '-' . $stringNomeSubitem;

                $updateQuery = "UPDATE subitem SET subitem.form_field_name = '" . $resultadoFINAL . "' WHERE subitem.id = '" . $idobtido . "'";
                //Caso de sucesso
                if (mysql_searchquery($updateQuery)) {
                    echo "<p>Inseriu os dados de novo subitem com sucesso.</p>";
                    continue_button();
                }
            }
        }
        else
        {
            go_back_button();
        }
    } else {
        //Tabela
        //Verifica se não existem tuplos na tabela subitem_unit_type
        $itemQuery = mysql_searchquery('SELECT * FROM subitem'); //Tabela subitem
        $row = mysqli_fetch_array($itemQuery, MYSQLI_NUM);

        if (!$row) { //Verifica se linha esta vazia
            echo "<p>Não há subitens especificados</p>";
        }
        else {
            $tableQuery = mysql_searchquery('SELECT name as item, id FROM item ORDER BY id ASC'); //Query Tabela

            //Construção da tabela
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
            <tbody>
              <tr>
                 <th>item</th>
                 <th>id</th>
                 <th>subitem</th>
                 <th>tipo de valor</th>
                 <th>nome do campo no formulário</th>
                 <th>tipo do campo no formulário</th>
                 <th>tipo de unidade</th>
                 <th>ordem do campo no formulário</th>
                 <th>obrigatório</th>
                 <th>estado</th>
                 <th>ação</th>
              </tr>';

            while ($rowTabela = mysqli_fetch_array($tableQuery, MYSQLI_NUM)) {

                $QueryStringSubitem = 'SELECT subitem.*
                    FROM subitem, item
                    WHERE subitem.item_id = item.id AND  item.id = ' . $rowTabela[1] . '
                    ORDER BY subitem.form_field_order ASC';
                $resultQuery = mysql_searchquery($QueryStringSubitem);
                $resultQuery2 = mysql_searchquery($QueryStringSubitem);
                $resultQuery3 = mysql_searchquery($QueryStringSubitem);

                $rowcount = mysqli_num_rows($resultQuery3);
                if($rowcount == 0)
                {
                    $rowcount = 1;
                }

                echo "<tr>";
                echo "<td rowspan='$rowcount'>" . $rowTabela[0] . "</td>";

                if(!mysqli_fetch_array($resultQuery2, MYSQLI_NUM))
                {
                    echo "<td rowspan='1' colspan='10'>Este item não tem subitens.</td>";
                    echo "</tr>";
                }
                else
                {
                    while ($rowTabela2 = mysqli_fetch_array($resultQuery, MYSQLI_NUM)) {
                        echo "<td>" . $rowTabela2[0] . "</td>";
                        echo "<td>" . $rowTabela2[1] . "</td>";
                        echo "<td>" . $rowTabela2[3] . "</td>";
                        echo "<td>" . $rowTabela2[4] . "</td>";
                        echo "<td>" . $rowTabela2[5] . "</td>";

                        $queryStringsut = 'SELECT subitem_unit_type.name
                                            FROM subitem, subitem_unit_type
                                            WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.id = ' . $rowTabela2[0];
                        $query_subitemunitype_result = mysql_searchquery($queryStringsut);
                        if(!isResultQueryEmpty($queryStringsut))
                        {
                            while($rowTabelaSubitemUnitType = mysqli_fetch_array($query_subitemunitype_result, MYSQLI_NUM)) {
                                echo "<td>" . $rowTabelaSubitemUnitType[0] . "</td>";
                            }
                        }
                        else
                        {
                            echo "<td> - </td>";
                        }

                        echo "<td>" . $rowTabela2[7] . "</td>";
                        if($rowTabela2[8] == 0)
                        {
                            echo "<td>não</td>";
                        }
                        else{
                            echo "<td>sim</td>";
                        }
                        echo "<td>" . $rowTabela2[9] . "</td>";
                        if ($rowTabela2[9] == "active") {
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


        echo "<h3>Gestão de subitens - introdução</h3>";
        //Form
        $allvaluetypes = get_enum_values("subitem", "value_type");
        $allitemnames = get_enum_values("item", "name");
        $allformfieldtype = get_enum_values("subitem", "form_field_type");
        $allsut = get_enum_values("subitem_unit_type", "name");

        echo '<form action="" name="InsertForm" method="POST">
            Nome do subitem: <input type="text" name="nome_subitem"/>
            <p>Tipo de valor:</p>';
        foreach($allvaluetypes as $value)
        {
            echo '<input type="radio" value= "' . $value . '" name="value_type"><label>' . $value . '</label><br>';
        }
        echo '<p>Item:</p>';
        echo '<select name="item_name">';
        echo '<option value=""></option>';
        $itemQuery = mysql_searchquery('SELECT * FROM item'); //Tabela item
        while($row = mysqli_fetch_array($itemQuery, MYSQLI_NUM))
        {
            echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
        }
        echo '</select>';
        echo '<p>Tipo do campo do formulário:</p>';
        foreach($allformfieldtype as $value)
        {
            echo '<input type="radio" value= "' . $value . '" name="form_field_type"><label>' . $value . '</label><br>';
        }
        echo '<p>Tipo de unidade:</p>';
        echo '<select name="subitem_unit_type_name">';
        echo '<option value=""></option>';
        $itemQuery2 = mysql_searchquery('SELECT * FROM subitem_unit_type'); //Tabela subitem_unit_type
        while($row2 = mysqli_fetch_array($itemQuery2, MYSQLI_NUM))
        {
            echo '<option value="' . $row2[0] . '">' . $row2[1] . '</option>';
        }
        echo '</select>';
        echo '<p>Ordem do campo no fomulário:</p>';
        echo '<input type="text" name="form_field_order"/>';
        echo '<p>Obrigatório:</p>
              <input type="radio" name="mandatory" value="1" ><label>sim</label>
              <input type="radio" name="mandatory" value="0" ><label>não</label>
              <input type="hidden" value="inserir" name="estado"/>              
              <input type="submit" value="Inserir item">
              </form>';
    }
}
?>
