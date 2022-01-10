<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_allowed_values'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se o valor do estado é "validar"
    if ($_REQUEST["estado"] == "introducao") {
        //VARIAVEL DE SESSAO
        $_SESSION['subitem_id'] = $_REQUEST["subitem"];
        //Formulário
        echo "<h3>Dados de registo - introdução</h3>";
        echo '<form action="" name="InsertForm" method="POST">
                Valor: <input type="text" name="valor_permitido"/> 
                <input type="hidden" value="inserir" name="estado"/>
                <input type="submit" value="Inserir valor permitido" />
                </form>';
    }
    else if ($_REQUEST["estado"] == "inserir") { //Verifica se o estado é "inserir"
        echo "<h3>Dados de registo - inserção</h3>";

        //Verifica se os dados estão vazio ou contém sequencia de char vazios
        if($_REQUEST['valor_permitido'] == "" || ctype_space($_REQUEST['valor_permitido'])){
            //Mensagem de erro: Nome vazio ou todos os char sao vazios
            echo "<p>ERRO: O dado inserido no formulário do Nome do Valor Permitido está vazia!</p>";
            go_back_button(); //Botão para retroceder
        }
        else { //Caso dados sejam validos
            //Inserção dos valores permitidos na Base de dados
            $insertQuery = "INSERT INTO subitem_allowed_value (subitem_id, value, state) 
                    VALUES('" . $_SESSION['subitem_id'] . "', '" . $_REQUEST["valor_permitido"] . "', 'active')";

            //Caso de inserção com sucesso
            if (mysql_searchquery($insertQuery)) {
                echo "<p>Inseriu os dados de novo valor permitido com sucesso.</p>";
                continue_button(); //Botão para avançar
            }
        }
    }
    else//fazes no else o query e seu resultado e depois verificar cada caso
    {
        //Query para fazer pesquisa de filtragem (query)
        $querystring = 'SELECT id, name FROM item ORDER BY name ASC';
        $queryresult = mysql_searchquery($querystring);//Query Desejado

        //Query Duplicado para só verificar a primeira linha e se esta está vazia -> caso explicado no gestao-de-itens linha 106
        $querystringcopy = 'SELECT id, name FROM item ORDER BY name ASC';
        $queryresultcopy = mysql_searchquery($querystringcopy);

        //Guarda no array o output do query
        $row = mysqli_fetch_array($queryresultcopy, MYSQLI_NUM);
        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há itens</p>";
        } else { //caso não esteja
            //Criar tabela, cabeçalhos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2"> 
               <tbody>
                  <tr>
                     <th><b>item</b></th>
                     <th>id</th>
                     <th><b>subitem</b></th>
                     <th>id</th>
                     <th>valores permitidos</th>
                     <th>estado</th>
                     <th>ação</th>
                  </tr>';

            //Enquanto houver dados
            while($rowTabela = mysqli_fetch_assoc($queryresult)) {

                //Query: selecionar todos os atributos do subitem, usando left-join EXPLICAR MELHOR...
                $queryNum = 'SELECT subitem.* 
                                FROM subitem 
                                LEFT JOIN subitem_allowed_value ON subitem_allowed_value.subitem_id = subitem.id AND subitem.value_type = "enum"
                                INNER JOIN item ON subitem.value_type = "enum" AND subitem.item_id = item.id AND item.id = ' . $rowTabela['id'];
                $resultsQueryNum = mysql_searchquery($queryNum);//Executa query
                $rowCount = mysqli_num_rows($resultsQueryNum); //Quantos items associados a um tipo de item||Quantas linhas no output da query
                if($rowCount == 0) //Sem linhas
                {
                    $rowCount = 1;
                }

                echo "<tr>";
                echo "<td rowspan='".$rowCount . "' >" . $rowTabela['name'] . "</td>"; //id

                //Query: subitem associado corretamente ao item em questão
                $querystring2 = 'SELECT subitem.id, subitem.name FROM subitem, item WHERE subitem.item_id = item.id AND subitem.value_type ="enum" AND item.id = ' . $rowTabela['id'] . ' ORDER BY id ASC';
                $queryresult2 = mysql_searchquery($querystring2);//Executar query

                $queryresult2dup = mysql_searchquery($querystring2); //Criação de uma query duplicada para só verificar a primeira linha e se esta está vazia
                $row = mysqli_fetch_array($queryresult2dup, MYSQLI_NUM); //Guardar output da query no array

                if(!$row) { //Verifica se linha esta vazia
                    echo "<td colspan = '6' rowspan = '1'> Não há subitems especificados cujo tipo de valor seja enum. Especificar primeiro novo(s) item(s) e depois voltar a esta opção.</td>";
                    echo "</tr>";
                }else { //Caso nao esteja vazio
                    while($rowTabela2 = mysqli_fetch_array($queryresult2, MYSQLI_NUM)) {
                        $queryNum= 'SELECT subitem_allowed_value.id, subitem_allowed_value.value, subitem_allowed_value.state FROM subitem_allowed_value, subitem WHERE subitem_allowed_value.subitem_id = subitem.id AND subitem.id = '. $rowTabela2[0] .  ' ORDER BY subitem_allowed_value.id ASC ';
                        $resultsQueryNum = mysql_searchquery($queryNum);
                        $rowCount = mysqli_num_rows($resultsQueryNum); //Quantos items associados a um tipo de item
                        if($rowCount == 0)
                        {
                            $rowCount = 1;
                        }

                        echo "<td rowspan ='".$rowCount ."'>" . $rowTabela2[0] . "</td>";
                        $word= '[' . $rowTabela2[1] . ']';
                        echo "<form method='POST' action='".$current_page."'>";
                        echo "<td rowspan ='".$rowCount ."'><a href='" . $current_page . "?estado=introducao&subitem=" . $rowTabela2[0] . "'>". $word . "</a></td>";
                        $querystring3= 'SELECT subitem_allowed_value.id, subitem_allowed_value.value, subitem_allowed_value.state FROM subitem_allowed_value, subitem WHERE subitem_allowed_value.subitem_id = subitem.id AND subitem.id = '. $rowTabela2[0] .  ' ORDER BY subitem_allowed_value.id ASC ';
                        $queryresult3 = mysql_searchquery($querystring3);//Query Desejado
                        $queryresult3dup = mysql_searchquery($querystring3);
                        $row = mysqli_fetch_array($queryresult3dup, MYSQLI_NUM);


                        $rowcount = mysqli_num_rows($queryresult3);//Quantos items associados a um tipo de item
                        if($rowcount == 0) //se está a 0 passa para 1 a variavel contador
                        {
                            $rowcount = 1;
                        }

                        if(!$row) { //Verifica se está vazio
                            echo "<td colspan = '4' rowspan = '1'>Não há valores permitidos definidos</td>";
                            echo "</tr>";
                        }else{
                            while($rowTabela3 = mysqli_fetch_array($queryresult3, MYSQLI_NUM)) { //Implementação da tabela conforme pedido no enunciado
                                echo "<td>" . $rowTabela3[0] . "</td>"; //id do subitem_allowed_value
                                echo "<td>" . $rowTabela3[1] . "</td>"; //valor permitido
                                echo "<td>" . $rowTabela3[2] . "</td>"; //Estado do subitem_allowed_value
                                if ($rowTabela3[2] == "active") {// Verifica se o estado é active ou não
                                    echo "<td> [editar] [desativar] </td>";
                                } else {
                                    echo "<td> [editar] [ativar] </td>";
                                }
                                echo "</tr>";
                            }
                        }
                    }
                }
            }
            echo "</tbody></table>";
        }
    }
}
