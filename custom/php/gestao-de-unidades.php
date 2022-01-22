<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_unit_types'))
{
    //Mostrar mensagem de aviso
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if ($_POST["estado"] == "inserir")
    {
        //Título
        echo "<h3>Dados de registo - inserção</h3>";

        //Verifica se foi submetido string(nome_unidade) vazia;
        if($_POST['nome_unidade'] == "" || ctype_space($_POST['nome_unidade'])){
            //Apresentar mensagem de erro Nome vazio ou todos os char sao vazios
            echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade está vazia!</p>";
            go_back_button(); //Botão para voltar à trás
        }
        //Verifica se foi submetido um dado com números ou carateres especiais além das letras ou espaço entre eles
        else if(!preg_match('/^[a-zA-Z/\p{L}]+$/ui', $_POST['nome_unidade']))
        {
            //Apresentar mensagem de erro (Tem caracteres especiais ou contém espaço!)
            echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade só pode ter letras, espaços vazios e acentos!</p>";
            go_back_button(); //Botão para voltar à trás
        }
        //Entra aqui se e só se o 'nome_unidade' inserido seja válido
        else {
            //Query: Inserir nome da unidade na Base de dados
            $insertQuery = "INSERT INTO subitem_unit_type (name) 
                    VALUES('" . $_POST['nome_unidade'] . "')";

            //Caso de sucesso
            if (mysql_searchquery($insertQuery)) {
                //Mensagem de sucesso
                echo "<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>";
                continue_button(); //Botão para continuar
            }
        }
    }
    else{ //Caso de não inserção

        //Fazer pesquisa de filtragem (query)
        //Selecionar id e nome da tabela subitem_unit_type
        $querystring = 'SELECT id as sut_id, name as sut_name FROM subitem_unit_type
                        ORDER BY name ASC';
        $queryresult = mysql_searchquery($querystring);//Executar Query

        //Verifica se não existem tuplos na tabela subitem_unit_type
        //Seleciona id e name da tabela subitem_unit_type
        //$verifyNotEmpty = mysql_searchquery('SELECT id, name FROM subitem_unit_type');
        /*
         * verifyNotEmpty = queryresult
         * Mesma explicação que no ficheiro gestao-de-itens.php
         */

        //Guarda output da query num array
        //$row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(isResultQueryEmpty($queryresult)) { //Caso array esteja vazio não há unidades
            //Mensagem de aviso
            echo "<p>Não há tipos de unidades</p>";
        }
        else { //Caso array não esteja vazio
            //Preenchimento da tabela com o cabeçalho (id, unidade, subitem)
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th><b>id</b></th>
                     <th><b>unidade</b></th>
                     <th><b>subitem</b></th>
                  </tr>';

            //Enquanto houverem resultados: é preenchido a tabela
            while($rowTabela = mysqli_fetch_assoc($queryresult)){
                echo "<tr>";
                echo "<td>" . $rowTabela['sut_id'] . "</td>"; //id do subitem_unit_type **1
                echo "<td>" . $rowTabela['sut_name'] . "</td>"; //unidade do subitem_unit_type

                //Query para saber o nome de item e subitem, associando o tipo de item ao item, e subitem id = subitem id atual **1
                $queryItemSubitem = 'SELECT subitem.name as si_name, item.name as i_name FROM subitem, item, subitem_unit_type 
                                     WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id AND subitem_unit_type.id = ' . $rowTabela['sut_id'];
                //Executar query
                $resultItemSubitem = mysql_searchquery($queryItemSubitem);
                $resultValueString = "";
                echo "<td>";

                //Procura dados do array enquanto houver resultado
                while($rowItemSubitem = mysqli_fetch_array($resultItemSubitem, MYSQLI_NUM)){
                    //Concatenar string para representar formato
                    //Subitem.name (item.name) ...
                    $resultValueString .= $rowItemSubitem[0] . " (" . $rowItemSubitem[1] . "), ";//**2
                }
                //Na ultima posição da string será removida duas posições para apagar a ultima ','; **2
                echo substr_replace($resultValueString ,"", -2);
                echo "</td>";
                echo "</tr>";
            }
            //Fim da tabela
            echo "</tbody></table>";
        }
        echo "<h3>Gestão de unidades - introdução</h3>";

        //Construção do formulário
        echo '<form action="" id="InsertForm" onsubmit="return validateValues(this)" name="InsertForm" method="POST">
                Nome da Unidade: <input type="text" name="nome_unidade"/> 
                <input type="hidden" value="inserir" name="estado"/>
                <input type="submit" value="Inserir tipo de unidade" />
                </form>';
    }
}

?>
