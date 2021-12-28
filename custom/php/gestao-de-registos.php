<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_records'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if ($_POST["estado"] == "validar")
    {
        echo "<h3>Dados de registo - validação</h3>";
        $noerrors = true;
        //NOME COMPLETO DA CRIANCA
        if($_POST['nc_crianca'] == "")
        {
            echo "<p>ERRO: O dado inserido no formulário do Nome Completo está vazia!</p>";
            $noerrors = false;
        }
        else if(!preg_match('/^[a-zA-Z \p{L}]+$/ui', $_POST['nc_crianca']))
        {
            echo "<p>ERRO: O dado inserido no formulário do Nome Completo só pode ter letras, espaços vazios e acentos especiais!</p>";
            $noerrors = false;
        }
        //DATA NASCIMENTO DA CRIANCA
        if($_POST['dn_crianca'] == "")
        {
            echo "<p>ERRO: O dado inserido no formulário da Data de nascimento está vazia!</p>";
            $noerrors = false;
        }
        else if (!preg_match('/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/', $_POST['dn_crianca'])) {
            echo "<p>ERRO: O dado inserido no formulário da Data de nascimento foi inserida de forma incorreta (AAAA-MM-DD)!</p>";
            $noerrors = false;
        }
        //NOME COMPLETO DO ENC DE EDUCACAO
        if($_POST['nc_tutor'] == "")
        {
            echo "<p>ERRO: O dado inserido no formulário do Nome Completo do Encarregado de Educação está vazia!</p>";
            $noerrors = false;
        }
        else if(!preg_match('/^[a-zA-Z \p{L}]+$/ui', $_POST['nc_tutor']))
        {
            echo "<p>ERRO: O dado inserido no formulário do Nome Completo do Encarregado de Educação só pode ter letras, acentos e espaços vazios!</p>";
            $noerrors = false;
        }
        //TELEFONE DO ENC DE EDUCACAO
        if($_POST['tf_tutor'] == "")
        {
            echo "<p>ERRO: O dado inserido no formulário do Telefone do encarregado de educação está vazia!</p>";
            $noerrors = false;
        }
        else if(!preg_match("/^\d{9}$/", $_POST['tf_tutor']))
        {
            echo "<p>ERRO: O dado inserido no formulário do Telefone do encarregado de educação só pode ter números (0-9)!</p>";
            $noerrors = false;
        }
        //EMAIL DO ENC DE EDUCACAO
        if($_POST['email_tutor'] != "" && !filter_var($_POST['email_tutor'], FILTER_VALIDATE_EMAIL))
        {
            echo "<p>ERRO: O dado inserido no formulário do Email do encarregado de educação é inválido!</p>";
            $noerrors = false;
        }

        if($noerrors)
        {
            echo "<p>Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos?</p>";
            //apresentar lista de dados inseridos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th>Nome</th>
                     <th>Data de nascimento</th>
                     <th>Enc. de educação</th>
                     <th>Telefone do Enc.</th>
                     <th>E-mail</th>
                  </tr>';
            echo "<tr>";
            echo "<td>" . $_POST['nc_crianca'] . "</td>";
            echo "<td>" . $_POST['dn_crianca'] . "</td>";
            echo "<td>" . $_POST['nc_tutor'] . " </td>";
            echo "<td>" . $_POST['tf_tutor'] . " </td>";
            echo "<td>" . $_POST['email_tutor'] . " </td>";
            echo "</tr>";
            echo "</tbody></table>";
            //inserir hiddens dos valores e submeter
            echo '<form action="" name="InsertFormConfirm" method="POST">
                <input type="hidden" value="'. $_POST['nc_crianca'] .'" name="nc_crianca_conf"/> 
                <input type="hidden" value="'. $_POST['dn_crianca'] .'" name="dn_crianca_conf"/> 
                <input type="hidden" value="'. $_POST['nc_tutor'] .'" name="nc_tutor_conf"/> 
                <input type="hidden" value="'. $_POST['tf_tutor'] .'" name="tf_tutor_conf"/> 
                <input type="hidden" value="'. $_POST['email_tutor'] .'" name="email_tutor_conf"/> 
                <input type="hidden" value="inserir" name="estado"/>
                <input type="submit" value="Submeter" />
                </form>';
        }
        else
        {
            go_back_button();
        }
    }
    else if($_POST["estado"] == "inserir")
    {
        echo "<h3>Dados de registo - inserção</h3>";

        $insertQuery = "INSERT INTO child (name, birth_date, tutor_name, tutor_phone, tutor_email) 
                    VALUES('" . $_POST['nc_crianca_conf'] . "','" . $_POST['dn_crianca_conf'] . "','" . $_POST['nc_tutor_conf'] . "','" . $_POST['tf_tutor_conf'] . "','" . $_POST['email_tutor_conf'] . "')";

        //Caso de sucesso
        if (mysql_searchquery($insertQuery)) {
            echo "<p>Inseriu os dados de registo com sucesso.</p>";
            continue_button();
        }
    }
    else{
        //Fazer pesquisa de filtragem da tabela child (query)
        $query_child_string =
            'SELECT * 
             FROM child 
             ORDER BY name ASC';
        $query_child_result = mysql_searchquery($query_child_string);

        if(isResultQueryEmpty($query_child_string)) { //Verifica se linha e consequentemente se tabela 'child' esta vazia
            echo "<p>Não há crianças</p>";
        } else {
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th>Nome</th>
                     <th>Data de nascimento</th>
                     <th>Enc. de educação</th>
                     <th>Telefone do Enc.</th>
                     <th>E-mail</th>
                     <th>Registos</th>
                  </tr>';

            while($rowTabelaChild = mysqli_fetch_assoc($query_child_result)){
                echo "<tr>";
                echo "<td>" . $rowTabelaChild['name'] . "</td>";
                echo "<td>" . $rowTabelaChild['birth_date'] . "</td>";
                echo "<td>" . $rowTabelaChild['tutor_name'] . " </td>";
                echo "<td>" . $rowTabelaChild['tutor_phone'] . " </td>";
                echo "<td>" . $rowTabelaChild['tutor_email'] . " </td>";

                //ver se tem nomes de items: AUTISMO, MEDIDAS, ...
                $query_itemname_string = '
                    SELECT DISTINCT item.name, item.id
                    FROM item, subitem, value 
                    WHERE item.id = subitem.item_id AND subitem.id = value.subitem_id AND value.child_id = ' . $rowTabelaChild['id'] .
                    ' ORDER BY item.name ASC';
                $query_itemname_result = mysql_searchquery($query_itemname_string);
                $resultValueString = "";
                echo "<td>";
                //Procura dados enquanto houver resultado
                while($rowTabelaItem = mysqli_fetch_array($query_itemname_result, MYSQLI_NUM)){
                    $resultValueString .= strtoupper($rowTabelaItem[0]) . ": ";

                    //Ver nomes de subitems e seus valores: altura (104
                    $query_subitem_string = '
                        SELECT subitem.name, value.value, subitem.id
                        FROM subitem, value, item, child
                        WHERE subitem.id = value.subitem_id AND subitem.item_id = item.id AND item.id = ' . $rowTabelaItem[1] . ' AND value.child_id = child.id AND child.id = ' . $rowTabelaChild['id'];
                    $query_subitem_result = mysql_searchquery($query_subitem_string);

                    if(isResultQueryEmpty($query_subitem_string))
                    {
                        $resultValueString = "Não há valores nem registos desta criança";
                    }
                    else
                    {
                        while($rowTabelaSubitem = mysqli_fetch_array($query_subitem_result, MYSQLI_NUM)) {
                            $resultValueString .= "<strong> " . $rowTabelaSubitem[0] . "</strong> (" . $rowTabelaSubitem[1];

                            //Ver tipo de unidade de subitem: cm,kg,...
                            $query_subitemunitype_string = '
                                SELECT subitem_unit_type.name, subitem_unit_type.id
                                FROM subitem_unit_type, subitem
                                WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.id = ' . $rowTabelaSubitem[2];
                            $query_subitemunitype_result = mysql_searchquery($query_subitemunitype_string);

                            if(!isResultQueryEmpty($query_subitemunitype_string))
                            {
                                while($rowTabelaSubitemUnitType = mysqli_fetch_array($query_subitemunitype_result, MYSQLI_NUM)) {
                                    $resultValueString .= " " . $rowTabelaSubitemUnitType[0];
                                }
                            }
                            $resultValueString .= "); ";
                        }
                        $resultValueString = substr_replace($resultValueString ,"", -2);
                        $resultValueString .= "\n";
                    }
                }
                echo $resultValueString;
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";

            echo "<h3>Dados de registo - introdução</h3>";

            echo "<p>Introduza os dados pessoais básicos da criança</p>";

            echo '<form action="" name="InsertForm" method="POST">
                Nome Completo: <input type="text" name="nc_crianca"/> 
                Data de nascimento (AAAA-MM-DD): <input type="text" name="dn_crianca"/> 
                Nome completo do encarregado de educação: <input type="text" name="nc_tutor"/> 
                Telefone do encarregado de educação: <input type="text" name="tf_tutor"/> 
                Endereço de e-mail do tutor (opcional): <input type="text" name="email_tutor"/> 
                <input type="hidden" value="validar" name="estado"/>
                <input type="submit" value="Submeter" />
                </form>';
        }
    }
}
?>