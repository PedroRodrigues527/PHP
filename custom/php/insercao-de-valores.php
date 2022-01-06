<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('insert_values'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se o valor do estado é "escolher_crianca"
    if ($_REQUEST["estado"] == "escolher_crianca") {
        echo "<h3>Inserção de valores - criança - escolher</h3>";
        $queryStringTabelaChild = 'SELECT id, name, birth_date FROM child ORDER BY id ASC';
        if(($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && ($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            $queryStringTabelaChild = 'SELECT id, name, birth_date FROM child ORDER BY id ASC';
        }
        else if(!($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && ($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE name LIKE '%" . $_POST['nome_crianca'] . "%' ORDER BY id ASC";
        }
        else if(($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && !($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE birth_date LIKE '%" . $_POST['data_crianca'] . "%' ORDER BY id ASC";
        }
        else
        {
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE name LIKE '%" . $_POST['nome_crianca'] . "%' AND birth_date LIKE '%" . $_POST['data_crianca'] . "%' ORDER BY id ASC";
        }

        echo '<ul>';
        $queryresultTabelaChild = mysql_searchquery($queryStringTabelaChild);
        $countChilds = mysqli_num_rows($queryresultTabelaChild);
        while($rowTabelaChild = mysqli_fetch_array($queryresultTabelaChild, MYSQLI_NUM)) {
            echo '<form method="post" action="'.$current_page.'">';
            echo '<li><a href="'.$current_page.'?estado=escolher_item&crianca='.$rowTabelaChild[0].'">['.$rowTabelaChild[1].']</a> ('.$rowTabelaChild[2].')</li>';
        }
        echo '</ul>';
        echo '<p> '.$countChilds.' result(s) found.</p>';
        go_back_button();
    }
    else if ($_REQUEST["estado"] == "escolher_item") {
        echo "<h3>Inserção de valores - escolher item</h3>";
        $_SESSION['child_id'] = $_REQUEST["crianca"];
        $queryStringTabelaItemType = 'SELECT id, name FROM item_type ORDER BY id ASC';
        $queryresultTabelaItemType = mysql_searchquery($queryStringTabelaItemType);
        echo '<ul>';
        while($rowTabelaItemType = mysqli_fetch_array($queryresultTabelaItemType, MYSQLI_NUM)) {
            echo '<li>' . $rowTabelaItemType[1];
            echo '<ul>';
            $queryStringTabelaItem = 'SELECT item.id, item.name FROM item INNER JOIN item_type ON item.item_type_id = item_type.id AND item_type.id = ' . $rowTabelaItemType[0] . ' ORDER BY id ASC';
            $queryresultTabelaItem = mysql_searchquery($queryStringTabelaItem);
            while($rowTabelaItem = mysqli_fetch_array($queryresultTabelaItem, MYSQLI_NUM)) {
                $queryVerifyItem = 'SELECT subitem.id FROM subitem INNER JOIN item ON subitem.item_id = item.id AND item.id = ' . $rowTabelaItem[0] . ' ORDER BY subitem.id ASC';
                $queryresultTabelaVerifyItem = mysql_searchquery($queryVerifyItem);
                $SubitensCount = mysqli_num_rows($queryresultTabelaVerifyItem);
                if($SubitensCount > 0)
                {
                    //echo '<ul>';
                    echo '<form method="post" action="'.$current_page.'">';
                    echo '<li><a href="'.$current_page.'?estado=introducao&item='.$rowTabelaItem[0].'">';
                    echo '['.$rowTabelaItem[1].']';
                    echo '</a></li>';
                    //echo '</ul>';
                }
            }
            echo '</ul>';
            echo '</li>';
        }
        echo '</ul>';
    }
    else if ($_REQUEST["estado"] == "introducao") {
        $_SESSION['item_id'] = $_REQUEST["item"];
        $nomedoitem = mysqli_fetch_array(mysql_searchquery('SELECT name FROM item WHERE id = ' . $_SESSION['item_id']), MYSQLI_NUM);
        $_SESSION['item_name'] = $nomedoitem[0];
        $iddotipodeitem = mysqli_fetch_array(mysql_searchquery('SELECT item_type.id FROM item_type, item WHERE item.item_type_id = item_type.id AND item.id = ' . $_SESSION['item_id']), MYSQLI_NUM);
        $_SESSION['item_type_id'] = $iddotipodeitem[0];
        echo "<h3>Inserção de valores - " . $_SESSION['item_name'] . "</h3>";
        echo '<form action="'.$current_page.'?estado=validar&item=' . $_SESSION['item_id'] . '" name="item_type_' . $_SESSION['item_type_id'] . '_item_' . $_SESSION['item_id'] . '" method="POST">';
        $queryStringSubitemActive = 'SELECT subitem.* FROM subitem, item WHERE subitem.item_id = item.id AND item.id = ' . $_SESSION['item_id'] . ' AND subitem.state = "active" ORDER BY subitem.form_field_order ASC';
        $queryResultSubitemActive = mysql_searchquery($queryStringSubitemActive);
        while($rowTabelaSubitemActive = mysqli_fetch_array($queryResultSubitemActive, MYSQLI_NUM)) {
            echo '<p>' . $rowTabelaSubitemActive[1] . ':</p>';
            switch ($rowTabelaSubitemActive[3])
            {
                case "text":
                    //text or textbox
                    echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '"/>';
                    break;
                case "bool":
                    //radio
                    echo '<input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="1" checked>
                            <label>sim</label><br>
                            <input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="0">
                            <label>não</label><br>';
                    break;
                case "int":
                    //text
                case "double":
                    //text
                    echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '"/>';
                    break;
                case "enum":
                    //radio, checkbox or selectbox
                    $queryStringValues = 'SELECT subitem_allowed_value.value FROM subitem_allowed_value INNER JOIN subitem ON subitem.id = subitem_allowed_value.subitem_id AND subitem.id = ' . $rowTabelaSubitemActive[0] . ' ORDER BY subitem_allowed_value.id ASC';
                    $queryResultValues = mysql_searchquery($queryStringValues);
                    switch ($rowTabelaSubitemActive[5])
                    {
                        case "radio":
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<input type="radio" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '"><label>' . $rowTabelaValues[0] . '</label><br>';
                            }
                            break;
                        case "checkbox":
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<input type="checkbox" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '"><label>' . $rowTabelaValues[0] . '</label><br>';
                            }
                            break;
                        case "selectbox":
                            echo '<select name="' . $rowTabelaSubitemActive[4] . '">';
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<option value="' . $rowTabelaValues[0] . '">' . $rowTabelaValues[0] . '</option>';
                            }
                            echo '</select>';
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
            //inserir tipo de unidade
            //$rowTabelaSubitemActive[6] -> id do tipo de unidade
            if($rowTabelaSubitemActive[6] != NULL)
            {
                $queryStringTipoUnidade = 'SELECT name FROM subitem_unit_type WHERE id = ' . $rowTabelaSubitemActive[6];
                $queryResultTipoUnidade = mysql_searchquery($queryStringTipoUnidade);
                while($rowTabelaTipoUnidade = mysqli_fetch_array($queryResultTipoUnidade, MYSQLI_NUM)) {
                    echo $rowTabelaTipoUnidade[0];
                }
            }
        }
        echo '<input type="hidden" value="validar" name="estado"/>
              <input type="submit" value="Submeter"/>
              </form>';
    }
    else if ($_REQUEST["estado"] == "validar") {
        echo "<h3>Inserção de valores - " . $_SESSION['item_name'] . " - validar</h3>";
        //HÁ UM ERRO NA DETEÇÃO/VERIFICAÇÃO DOS DADOS INSERIDOS DO TIPO RADIO, CHECKBOX E SELECTBOX!!
        $SubItemName = NULL;
        foreach ($_POST as $key => $value) {
            echo $key . ' -> ' . $value;
            if($value == "" || ctype_space($value))
            {
                $SubItemName = $key;
                break;
            }
        }
        if($SubItemName != NULL)
        {
            echo "<p>ERRO: Há um formulário no campo do subitem " . substr($SubItemName,6) . " que ainda não foi preenchido!</p>";
            go_back_button();
        }
        else
        {
            echo "<p>Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos?</p>";

            echo '<ul>';
            foreach ($_REQUEST as $key => $value) {
                echo '<li>' . $key . ': ' . $value . '</li>';
            }
            echo '</ul>';
            //inserir hiddens dos valores e submeter
            echo '<form action="'.$current_page.'?estado=inserir&item=' . $_SESSION['item_id'] . '" name="InsertFormConfirm" method="POST">';
            foreach ($_REQUEST as $key => $value) {
                echo '<input type="hidden" value="' . $value . '" name="' . $key . '"/>';
            }
            echo '<input type="hidden" value="inserir" name="estado"/>
                <input type="submit" value="Submeter"/>
                </form>';
        }
    }
    else if ($_REQUEST["estado"] == "inserir") {
        echo "<h3>Inserção de valores - " . $_SESSION['item_name'] . " - inserção</h3>";
        foreach ($_REQUEST as $key => $value) {
            $queryStringGetSubItemID = 'SELECT id FROM subitem WHERE form_field_name = ' . $key;
            $queryResultGetSubItemID = mysql_searchquery($queryStringGetSubItemID);
            while($rowTabelaIDSubitem = mysqli_fetch_array($queryResultGetSubItemID, MYSQLI_NUM))
            {
                $insertQuery = "INSERT INTO value (child_id, subitem_id, value, date, time, producer)
                                VALUES('" . $_SESSION['child_id'] . "','" . $rowTabelaIDSubitem[0] . "','" . $value . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','" . DB_USER . "')";

                //Caso de sucesso
                if (mysql_searchquery($insertQuery)) {
                    echo "<p>Inseriu o(s) valor(es) com sucesso.</p>";
                    echo "<p>Clique em Voltar para voltar ao início da inserção de valores ou em Escolher item se quiser continuar a inserir valores associados a esta criança</p>";
                    echo '<form action="" name="Voltar" method="POST">
                          <input type="submit" value="Voltar"/>
                          </form>';
                    echo '<form action="'.$current_page.'?estado=escolher_item&crianca=' . $_SESSION['child_id'] . '" name="EscolherItem" method="POST">
                          <input type="submit" value="Escolher item"/>
                          </form>';
                }
            }
        }
    }
    else
    {
        echo '<h3>Inserção de valores - criança - procurar</h3>';
        echo '<p>Introduza um dos nomes da criança a encontrar e/ou a data de nascimento dela</p>';
        echo '<form action="" name="InsertForm" method="POST">
                Nome: <input type="text" name="nome_crianca"/> 
                Data de nascimento (AAAA-MM-DD): <input type="text" name="data_crianca"/> 
                <input type="hidden" value="escolher_crianca" name="estado"/>
                <input type="submit" value="Submeter"/>
                </form>';
    }
}
?>