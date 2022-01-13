<?php
require_once("custom/php/common.php");

//Verifica se user está login
if(!is_user_logged_in())
{
    echo "<p>Não tem permissão para aceder a esta página.</p>";
}
else {
    if ($_GET["estado"] == "editar") {
        if ($_GET["comp"] == 'gestao-de-itens')
        {
            $queryString = 'SELECT * FROM item WHERE id = '.$_GET["idbef"];
            $_SESSION['idbefore'] = $_GET['idbef'];
        }
        else if ($_GET["comp"] == 'gestao-de-subitens')
        {
            $queryString = 'SELECT * FROM subitem WHERE id = '.$_GET["idbef"];
            $_SESSION['idbefore'] = $_GET['idbef'];
        }
        else if ($_GET["comp"] == 'gestao-de-valores-permitidos')
        {
            $queryString = 'SELECT * FROM subitem_allowed_value WHERE id = '.$_GET["idbef"];
            $_SESSION['idbefore'] = $_GET['idbef'];
        }
        else if ($_GET["comp"] == 'gestao-de-registos')
        {
            $queryString = 'SELECT subitem.*, value.value FROM subitem, value WHERE subitem.item_id = '.$_GET["itemid"].' AND value.child_id = '.$_GET["childid"].' AND value.subitem_id = subitem.id ORDER BY subitem.form_field_order ASC';
            $_SESSION['itemid'] = $_GET['itemid'];
            $_SESSION['childid'] = $_GET['childid'];
        }
        else
        {
            $queryString = '';
        }

        $resultQuery = mysql_searchquery($queryString);
        echo "<h3>Edição de dados - editar</h3>";
        echo '<form action="'.get_site_url().'/edicao-de-dados'.'" method="POST">';

        if ($_GET["comp"] == 'gestao-de-registos')
        {
            $nomeTabela = 'value';
            while($rowTabelaSubitemActive = mysqli_fetch_array($resultQuery, MYSQLI_NUM))
            {
                echo '<p>' . $rowTabelaSubitemActive[1] . ':</p>';
                switch ($rowTabelaSubitemActive[3])
                {
                    case "text":
                        //text or textbox
                        echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '" value="'.$rowTabelaSubitemActive[10].'"/>';
                        break;
                    case "bool":
                        //radio
                        echo '<input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="1" ';
                        if ($rowTabelaSubitemActive[10] == 1)
                        {
                            echo 'checked';
                        }
                        echo '>
                        <label>sim</label><br>
                        <input type="radio" name="' . $rowTabelaSubitemActive[4] . '" value="0" ';
                        if ($rowTabelaSubitemActive[10] == 0)
                        {
                            echo 'checked';
                        }
                        echo '>
                        <label>não</label><br>';
                        break;
                    case "int":
                        //text
                    case "double":
                        //text
                        echo '<input type="' . $rowTabelaSubitemActive[5] . '" name="' . $rowTabelaSubitemActive[4] . '" value="'.$rowTabelaSubitemActive[10].'"/>';
                        break;
                    case "enum":
                        //radio, checkbox or selectbox
                        $queryStringValues = 'SELECT subitem_allowed_value.value FROM subitem_allowed_value INNER JOIN subitem ON subitem.id = subitem_allowed_value.subitem_id AND subitem.id = ' . $rowTabelaSubitemActive[0] . ' ORDER BY subitem_allowed_value.id ASC';
                        $queryResultValues = mysql_searchquery($queryStringValues);
                        switch ($rowTabelaSubitemActive[5])
                        {
                            case "radio":
                                echo '<input type="radio" style="display: none;" value="" name="' . $rowTabelaSubitemActive[4] . '" checked>';
                                while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                                {
                                    echo '<input type="radio" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '" ';
                                    if ($rowTabelaSubitemActive[10] == $rowTabelaValues[0])
                                    {
                                        echo 'checked';
                                    }
                                    echo '><label>' . $rowTabelaValues[0] . '</label><br>';
                                }
                                break;
                            case "checkbox":
                                echo '<input type="checkbox" style="display: none;" value="" name="' . $rowTabelaSubitemActive[4] . '" checked>';
                                while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                                {
                                    echo '<input type="checkbox" value= "' . $rowTabelaValues[0] . '" name="' . $rowTabelaSubitemActive[4] . '" ';
                                    if ($rowTabelaSubitemActive[10] == $rowTabelaValues[0])
                                    {
                                        echo 'checked';
                                    }
                                    echo '><label>' . $rowTabelaValues[0] . '</label><br>';
                                }
                                break;
                            case "selectbox": //ERRO DEFAULT
                                echo '<select name="' . $rowTabelaSubitemActive[4] . '">';
                                while($rowTabelaValues = mysqli_fetch_array($queryResultValues, MYSQLI_NUM))
                                {
                                    echo '<option value="' . $rowTabelaValues[0] . '" ';
                                    if ($rowTabelaSubitemActive[10] == $rowTabelaValues[0])
                                    {
                                        echo 'selected';
                                    }
                                    echo '>' . $rowTabelaValues[0] . '</option>';
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
        }
        else if ($_GET["comp"] == 'gestao-de-valores-permitidos' || $_GET["comp"] == 'gestao-de-subitens' || $_GET["comp"] == 'gestao-de-itens')
        {
            if ($_GET["comp"] == 'gestao-de-itens')
            {
                $nomeTabela = 'item';
            }
            else if ($_GET["comp"] == 'gestao-de-subitens')
            {
                $nomeTabela = 'subitem';
            }
            else
            {
                $nomeTabela = 'subitem_allowed_value';
            }
            $queryResultAtr = mysql_searchquery("SHOW COLUMNS FROM ".$nomeTabela);
            $rowRegistos = mysqli_fetch_assoc($resultQuery);
            while($rowAtr = mysqli_fetch_assoc($queryResultAtr))
            {
                echo '<p>' . $rowAtr['Field'] . ':</p>';
                echo '<input type="text" name="'.$nomeTabela.'.'.$rowAtr['Field'].'" value="'.$rowRegistos[$rowAtr['Field']].'"';
                if($rowAtr['Field'] == 'id' || $rowAtr['Field'] == 'state')
                {
                    echo ' readonly';
                }
                echo ' />';
            }
        }
        echo '<p>';
        echo '<input type="hidden" value="inserir" name="estado"/>';
        echo '<input type="hidden" value="'.$nomeTabela.'" name="nometabela"/>';
        echo '<input type="submit" value="Editar" />';
        echo '</p>';
        echo '</form>';
    } else if ($_GET["estado"] == "desativar" || $_GET["estado"] == "ativar") {
        $_SESSION['id'] = $_REQUEST['idbef'];
        if($_GET["estado"] == "desativar")
        {
            $_SESSION['state'] = 'inactive';
            $alterar = "desativar";
        }
        else
        {
            $_SESSION['state'] = 'active';
            $alterar = "ativar";
        }
        echo "<h3>Edição de dados - ".$alterar."</h3>";
        echo '<p>Deseja '.$alterar.' o elemento ';
        if ($_GET["comp"] == 'gestao-de-itens')
        {
            $nomeTabela = 'item';
        }
        else if ($_GET["comp"] == 'gestao-de-subitens')
        {
            $nomeTabela = 'subitem';
        }
        else
        {
            $nomeTabela = 'subitem_allowed_value';
        }
        $_SESSION['nometabela'] = $nomeTabela;
        echo $nomeTabela.' com id '.$_SESSION['id'].' ?</p>';
        echo '<form action="'.get_site_url().'/edicao-de-dados'.'" method="POST">';
        echo '<p>';
        echo '<input type="hidden" value="mudarestado" name="estado"/>';
        echo '<input type="submit" value="Atualizar estado" />';
        echo '</p>';
        echo '</form>';
    } else if ($_POST["estado"] == "mudarestado") {
        echo "<h3>Edição de dados - mudar estado</h3>";
        $queryUpdate = 'UPDATE '.$_SESSION['nometabela'].' SET state = "'.$_SESSION['state'].'" WHERE id = '.$_SESSION['id'];
        if(mysql_searchquery($queryUpdate))
        {
            echo "<p>Alterou o estado do elemento com sucesso.</p>";
        }
        else
        {
            echo "<p>Ocorreu um erro na alteração do estado. Verifique se está corretamente ligado à base de dados.</p>";
        }
        go_back_button();
    } else if ($_REQUEST["estado"] == "inserir") {
        echo "<h3>Edição de dados - inserir</h3>";

        $isEmpty = false;
        foreach($_POST as $key => $value)
        {
            if(($value == "" || ctype_space($value)) && $key != 'unit_type_id')
            {
                $isEmpty = true;
            }
        }
        if(!$isEmpty) {
            $queryUpdate = 'UPDATE ' . $_POST['nometabela'] . ' SET ';
            if ($_POST['nometabela'] != 'value') {
                $queryUpdate = 'UPDATE ' . $_POST['nometabela'] . ' SET ';
                foreach ($_POST as $key => $value) {
                    if ($key != 'nometabela' && $key != 'estado') {
                        $queryUpdate .= $key . ' = "' . $value . '", ';
                    }
                }
                $queryUpdate = substr_replace($queryUpdate ,"", -2);
                $queryUpdate .= ' WHERE id = ' . $_SESSION['idbefore'];
                //echo $queryUpdate; //TESTE
                if(mysql_searchquery($queryUpdate))
                {
                    echo "<p>Editou os dados do formulário com sucesso.</p>";
                }
                else
                {
                    echo "<p>Ocorreu um erro na alteração do registo. Verifique se todos os dados estão corretos e no formato pretendido.</p>";
                }
            }
            else
            {
                foreach ($_POST as $key => $value) {
                    if ($key != 'nometabela' && $key != 'estado') {
                        $queryNameSubitem = mysql_searchquery('SELECT id, name FROM subitem WHERE form_field_name = "'.$key.'"');
                        $NameSubitem = mysqli_fetch_array($queryNameSubitem, MYSQLI_NUM);
                        $queryUpdate = 'UPDATE ' . $_POST['nometabela'] . ' SET ';
                        $queryUpdate .= 'value = "' . $value . '" WHERE child_id = ' . $_SESSION['childid'] . ' AND subitem_id = '. $NameSubitem[0];

                        if(mysql_searchquery($queryUpdate))
                        {
                            echo "<p>Editou o dado do formulário ".$NameSubitem[1]." com sucesso.</p>";
                        }
                        else
                        {
                            echo "<p>Ocorreu um erro ao alterar o dado ".$NameSubitem[1]."</p>";
                        }
                    }
                }
            }
        }
        else
        {
            echo "<p>Todos os dados do formulário devem ser preenchidos obrigatoriamente.</p>";
        }
        go_back_button();

    }
    else
    {
        echo "<p>Não é possível aceder a esta página desta forma.</p>";
        go_back_button();
    }
}
?>