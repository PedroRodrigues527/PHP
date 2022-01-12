<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!is_user_logged_in())
{
    echo "<p>Não tem permissão para aceder a esta página.</p>";
}
else {
    if(!empty($_GET["estado"])) {
        if ($_GET["comp"] == 'gestao-de-itens')
        {
            $queryString = 'SELECT * FROM item WHERE id = '.$_GET["id"];
        }
        else if ($_GET["comp"] == 'gestao-de-subitens')
        {
            $queryString = 'SELECT * FROM subitem WHERE id = '.$_GET["id"];
        }
        else if ($_GET["comp"] == 'gestao-de-valores-permitidos')
        {
            $queryString = 'SELECT * FROM subitem_allowed_value WHERE id = '.$_GET["id"];
        }
        else if ($_GET["comp"] == 'gestao-de-registos')
        {
            $queryString = 'SELECT subitem.*, value.value FROM subitem, value WHERE subitem.item_id = '.$_GET["itemid"].' AND value.child_id = '.$_GET["childid"].' AND value.subitem_id = subitem.id ORDER BY subitem.form_field_order ASC';
        }
        else
        {
            $queryString = '';
        }

        $resultQuery = mysql_searchquery($queryString);

        if ($_GET["estado"] == "editar") {
            echo "<h3>Edição de dados - editar</h3>";
            echo '<form action="'.$current_page.'?estado=inserir" method="POST">';
            if ($_GET["comp"] == 'gestao-de-registos')
            {
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
            else
            {
                while($rowRegistos = mysqli_fetch_array($resultQuery, MYSQLI_NUM))
                {

                }
            }
            echo '<input type="hidden" value="inserir" name="estado"/>';
            echo '<input type="submit" value="Editar" />';
            echo '</form>';
        } else if ($_GET["estado"] == "desativar") {
            echo "<h3>Edição de dados - desativar</h3>";
        } else if ($_GET["estado"] == "ativar") {
            echo "<h3>Edição de dados - ativar</h3>";
        } else if ($_GET['estado'] == "inserir") {
            echo "<h3>Edição de dados - inserir</h3>";
        }
    }
    else
    {
        echo "<p>Não tem permissão para aceder a esta página.</p>";
    }
}
?>