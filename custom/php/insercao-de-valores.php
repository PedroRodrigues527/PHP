<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('insert_values'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se o valor do estado é "escolher_crianca"
    //Procurar crianças
    if ($_REQUEST["estado"] == "escolher_crianca") {
        echo "<h3>Inserção de valores - criança - escolher</h3>";
        $queryStringTabelaChild = 'SELECT id, name, birth_date FROM child ORDER BY id ASC';
        //ctype_space:
        //TRUE if EVERY CHAR in text creates some sort of white space
        // 0 0
        if(($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && ($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            //Display todas as crianças
            $queryStringTabelaChild = 'SELECT id, name, birth_date FROM child ORDER BY id ASC';
        }
        //Contem nome ou char
        //1 0
        else if(!($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && ($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            //Todas as crianças que tem padrao inserido no nome Por exemplo: input: D output: Pedro, Diogo...
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE name LIKE '%" . $_POST['nome_crianca'] . "%' ORDER BY id ASC";
        }
        //data inserida != "" ou nao contem espaços
        //Formato errado -> sem dados
        //0 1
        else if(($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && !($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {
            //Procura número presente nas datas Por exemplo: input: 2 ouput: 2012/3/9 ou 1992/6/1 ou ...
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE birth_date LIKE '%" . $_POST['data_crianca'] . "%' ORDER BY id ASC";
        }
        //Caso tenha char no nome e numeros nas datas
        //1 1
        else
        {
            //Todas as crianças que tenham um certo padrão
            $queryStringTabelaChild = "SELECT id, name, birth_date FROM child WHERE name LIKE '%" . $_POST['nome_crianca'] . "%' AND birth_date LIKE '%" . $_POST['data_crianca'] . "%' ORDER BY id ASC";
        }

        echo '<ul>';
        //Executa a query conforme condição anterior
        $queryresultTabelaChild = mysql_searchquery($queryStringTabelaChild);
        $countChilds = mysqli_num_rows($queryresultTabelaChild); //Quantos tuplos encontrados
        while($rowTabelaChild = mysqli_fetch_array($queryresultTabelaChild, MYSQLI_NUM)) {
            echo '<form method="post" action="'.$current_page.'">';
            //tag a para redirecionar para url para realizar alteração
            echo '<li><a href="'.$current_page.'?estado=escolher_item&crianca='.$rowTabelaChild[0].'">['.$rowTabelaChild[1].']</a> ('.$rowTabelaChild[2].')</li>';//id [nome](data_nascimento)
        }
        echo '</ul>';
        echo '<p> '.$countChilds.' result(s) found.</p>'; //Número de resultados encontradas
        go_back_button();//Botão para retroceder
    }
    else if ($_REQUEST["estado"] == "escolher_item") {
        echo "<h3>Inserção de valores - escolher item</h3>";
        $_SESSION['child_id'] = $_REQUEST["crianca"]; //Guarda na var de sessão, em caso de alteração de dados (ex;introdução/valores de subitens)
        listItemsAndItemTypes('estado=introducao&item=');//Listar item e item_type
    }
    else if ($_REQUEST["estado"] == "introducao") {
        $_SESSION['item_id'] = $_REQUEST["item"]; //Hyperlink carregado anteriormente

        $nomedoitem = mysqli_fetch_array(mysql_searchquery('SELECT name FROM item WHERE id = ' . $_SESSION['item_id']), MYSQLI_NUM); //Busca do nome do item em questao
        $_SESSION['item_name'] = $nomedoitem[0]; //Atribuir item.name a var de sessão

        //Query para selecionar id do tipo de item que estaja associado ao item atual
        $iddotipodeitem = mysqli_fetch_array(mysql_searchquery('SELECT item_type.id FROM item_type, item WHERE item.item_type_id = item_type.id AND item.id = ' . $_SESSION['item_id']), MYSQLI_NUM);
        $_SESSION['item_type_id'] = $iddotipodeitem[0];//Atribuir item_type.name a var de sessão

        echo "<h3>Inserção de valores - " . $_SESSION['item_name'] . "</h3>";//Título

        echo '<form action="'.$current_page.'?estado=validar&item=' . $_SESSION['item_id'] . '" name="item_type_' . $_SESSION['item_type_id'] . '_item_' . $_SESSION['item_id'] . '" method="POST" id="InsertForm" onsubmit="return validateValues(this)" name="InsertForm">';
        //Query: todos os atributos de subitem que estao associado ao item atual + tenha estado ativo
        $queryStringSubitemActive = 'SELECT subitem.* FROM subitem, item WHERE subitem.item_id = item.id AND item.id = ' . $_SESSION['item_id'] . ' AND subitem.state = "active" ORDER BY subitem.form_field_order ASC';
        $queryResultSubitemActive = mysql_searchquery($queryStringSubitemActive);

        while($rowTabelaSubitemActive = mysqli_fetch_array($queryResultSubitemActive, MYSQLI_NUM)) {
            echo '<p>' . $rowTabelaSubitemActive[1] . ':</p>'; //subitem.name
            switch ($rowTabelaSubitemActive[3]) //Item_value_type: Verifica tipo
            {
                case "text":
                    //text or textbox
                    echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '"/>';//form field type;
                    break;
                case "bool":
                    //radio
                    //form_field_name
                    echo '<input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="1" checked>
                            <label>sim</label><br>
                            <input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="0">
                            <label>não</label><br>';
                    break;
                case "int":
                    //text
                case "double":
                    //text
                    echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '"/>';//form field type; form_field_name
                    break;
                case "enum":
                    //radio, checkbox or selectbox
                    $queryStringValues = 'SELECT subitem_allowed_value.value FROM subitem_allowed_value INNER JOIN subitem ON subitem.id = subitem_allowed_value.subitem_id AND subitem.id = ' . $rowTabelaSubitemActive[0] . ' ORDER BY subitem_allowed_value.id ASC';
                    $queryResultValues = mysql_searchquery($queryStringValues);
                    switch ($rowTabelaSubitemActive[5])//form field type
                    {
                        case "radio":
                            echo '<input type="radio" style="display: none;" value="" name="' . $rowTabelaSubitemActive[4] . '" checked>'; //form field name
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<input type="radio" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '"><label>' . $rowTabelaValues[0] . '</label><br>'; //subitem_allowed_value.value; form_field_name ;subitem_allowed_value.value
                            }
                            break;
                        case "checkbox":
                            echo '<input type="checkbox" style="display: none;" value="" name="' . $rowTabelaSubitemActive[4] . '" checked>'; //form_field_name
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<input type="checkbox" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '"><label>' . $rowTabelaValues[0] . '</label><br>'; //subitem_allowed_value.value; form_field_name; subitem_allowed_value.value
                            }
                            break;
                        case "selectbox":
                            echo '<select name="' . $rowTabelaSubitemActive[4] . '">';//form_field_name
                            while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                            {
                                echo '<option value="' . $rowTabelaValues[0] . '">' . $rowTabelaValues[0] . '</option>';//subitem_allowed_value.value;
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
            if($rowTabelaSubitemActive[6] != NULL) //unit_type_id
            {
                $queryStringTipoUnidade = 'SELECT name FROM subitem_unit_type WHERE id = ' . $rowTabelaSubitemActive[6]; //unit_type_id
                $queryResultTipoUnidade = mysql_searchquery($queryStringTipoUnidade);
                while($rowTabelaTipoUnidade = mysqli_fetch_array($queryResultTipoUnidade, MYSQLI_NUM)) {
                    echo $rowTabelaTipoUnidade[0];//subitem_unit_type.name
                }
            }
        }
        echo '<input type="hidden" value="validar" name="estado"/>
              <input type="submit" value="Submeter"/>
              </form>';
    }
    else if ($_REQUEST["estado"] == "validar") {
        echo "<h3>Inserção de valores - " . $_SESSION['item_name'] . " - validar</h3>";
        
        $SubItemFormName = NULL;//Validar campos vazio; Assume nome do formulario que esta incorreto
        foreach ($_POST as $key => $value) {
            if($value == "" || ctype_space($value))
            {
                $SubItemFormName = $key; //Nome do campo
                break;//Sai do for each
            }
            $queryResultVerifyIntDouble = mysql_searchquery('SELECT value_type FROM subitem WHERE form_field_name = "'.$key.'"');
            $rowIntDouble = mysqli_fetch_array($queryResultVerifyIntDouble);
            if($rowIntDouble[0] == 'int' || $rowIntDouble[0] == 'double')
            {
                if(!preg_match('/^[0-9]+$/',$value) && $rowIntDouble[0] == 'int') //Caso não tenha apenas numeros e seja inteiro
                {
                    $SubItemFormName = $key;
                    break;//sai do for each
                }
                //Caso seja double e não não esteja nestes formatos (ex: 49 ou 15.1 ou 131.15)
                else if($rowIntDouble[0] == 'double' && !(preg_match('/^[0-9]+.[0-9]+$/',$value) || preg_match('/^[0-9]+$/',$value) ))
                {
                    $SubItemFormName = $key;
                    break; //Sai do for each
                }
            }
        }
        if($SubItemFormName != NULL) //Houve erro! (nome do campo que ocorreu erro especificado)
        {
            $queryStringSubitemName = 'SELECT name FROM subitem WHERE form_field_name = "'.$SubItemFormName.'"'; //Nome do subitem que tem erro
            $queryResultSubitemName = mysql_searchquery($queryStringSubitemName);
            $rowSubitemName = mysqli_fetch_array($queryResultSubitemName, MYSQLI_NUM);

            //Encontra o PRIMEIRO campo com erros
            echo "<p>ERRO: Há um formulário no campo do subitem " . $rowSubitemName[0] . " que ainda não foi preenchido ou foi incorretamente preenchido (por exemplo, inserir letras num subitem do tipo inteiro/double)!</p>";
            go_back_button();//Retorna a pagina anterior
        }
        else//Em caso de sucesso
        {
            //Aviso de alteração de dados
            echo "<p>Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos?</p>";
            echo '<ul>';
            foreach ($_POST as $key => $value) {
                if($key != "estado") {
                    $queryStringSubitemName = 'SELECT name FROM subitem WHERE form_field_name = "'.$key.'"'; //Nome do subitem no campo correto ($key)
                    $queryResultSubitemName = mysql_searchquery($queryStringSubitemName);
                    $rowSubitemName = mysqli_fetch_array($queryResultSubitemName, MYSQLI_NUM);
                    echo '<li>' . $rowSubitemName[0] . ': ' . $value . '</li>'; //Listagem das alterações
                }
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
        $insertQuery = "";//Inicio da string para inserção dos valores na base de dados
        foreach ($_POST as $key => $value) {
            if ($key != "estado" && $key != "item") { //Campos do form retirando os valores do estado e item
                $insertQuery .= "INSERT INTO value (child_id, subitem_id, value, date, time, producer)
                                VALUES "; //Query para inserção
                $queryStringGetSubItemID = 'SELECT id FROM subitem WHERE form_field_name = "'.$key.'"';//id do subitem a ser inserido
                $queryResultGetSubItemID = mysql_searchquery($queryStringGetSubItemID);
                while($rowTabelaIDSubitem = mysqli_fetch_array($queryResultGetSubItemID, MYSQLI_NUM)) {
                    $current_user = wp_get_current_user();//nome do usuário do wordpress
                    $insertQuery .= "('" . $_SESSION['child_id'] . "','" . $rowTabelaIDSubitem[0] . "','" . $value . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','" . $current_user->display_name . "'); 
                    ";//Valores para inserção na query
                }
            }
        }
        //para inserir vários tuplos
        //Aviso da inserção de valores
        if (mysql_searchseveralquery($insertQuery)) { //Insere vários tuplos
            mysql_transacao(true);//Transação de forma correta: commit; caso contrario: rollback
            //Confirmação
            echo "<p>Inseriu o(s) valor(es) com sucesso.</p>";
            echo "<p>Clique em Voltar para voltar ao início da inserção de valores ou em Escolher item se quiser continuar a inserir valores associados a esta criança</p>";
            echo '<form action="' . $current_page . '" name="Voltar" method="POST">
                  <input type="submit" value="Voltar"/>
                  </form>';
            echo '<form action="' . $current_page . '?estado=escolher_item&crianca=' . $_SESSION['child_id'] . '" name="EscolherItem" method="POST">
                  <input type="submit" value="Escolher item"/>
                  </form>';
        }
        else //Em caso de falha faz rollback
        {
            mysql_transacao(false);//rollback
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