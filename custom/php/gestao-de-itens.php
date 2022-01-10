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

            //Apresentar mensagem de erro Nome vazio ou todos os char sao vazios
            echo "<p>ERRO: O dado inserido no formulário do Nome do Item está vazia!</p>";
            $noerrors = false;
        }
        //Verifica se foi submetido um dado com números ou carateres especiais além das letras ou espaço entre eles
        else if(!preg_match ('/^[a-zA-Z0-9 \p{L}]+$/ui', $_POST['nome_item'])) //1 se conter char especiais, 0 caso contrario
        {
            //Apresentar mensagem de erro (Tem caracteres especiais!)
            echo "<p>ERRO: O dado inserido no formulário do Nome da Item só pode ter letras, acentos e espaços vazios!</p>";
            $noerrors = false;
        }
        if ($_POST['tipo_de_item'] == "" || ctype_space($_POST['tipo_de_item'])) {
            //Apresentar mensagem de erro Nome vazio ou todos os char sao vazios
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Tipo de Item!</p>";
            $noerrors = false;
        }
        if ($_POST['state'] == "" || ctype_space($_POST['state'])) {
            //Apresentar mensagem de erro Nome vazio ou todos os char sao vazios
            echo "<p>ERRO: Não foi escolhido nenhuma opção do dado Estado!</p>";
            $noerrors = false;
        }

        //Entra aqui se os dados inseridos forem válido
        if($noerrors){
            //Query para inserir nome da unidade, tipo e estado na Base de dados
            $insertQuery = "INSERT INTO item (name, item_type_id, state ) 
                VALUES('" . $_POST['nome_item'] . "', '" . $_POST['tipo_de_item'] . "', '" . $_POST['state'] . "')";

            //Caso de sucesso de inserçao na Base de Dados
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
        $row = mysqli_fetch_array($itemQuery, MYSQLI_NUM); //Array com output da query

        if(!$row) { //Verifica se array esta vazio
            echo "<p>Não há itens</p>";
        }
        else{
            //$tableQuery = mysql_searchquery('SELECT item.id, item.name, item.state, item_type.id, item_type.name FROM item, item_type WHERE item.id = item.item_type_id'); //Query Tabela
            $typeItemQuery = mysql_searchquery('SELECT item_type.id, item_type.name FROM item_type'); //Lista dos id e nome da tabela item_type;

            //Construção da tabela com os respetivos atributos (tipo de item, id, nome do item, estado e ação)
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2" >
               <tbody>
                  <tr>
                     <th>tipo de item</th>
                     <th>id</th>
                     <th>nome do item</th>
                     <th>estado</th>
                     <th>ação</th>
                  </tr>';

            //Enquanto houver resultados no array(do output da query)
            while($rowType = mysqli_fetch_array($typeItemQuery, MYSQLI_NUM)) {

                //query: todos os atributos de item
                //Verifica se tipo de item esta associado ao item id ($rowType[0])
                $queryNum = 'SELECT item.* FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0];

                $resultsQueryNum = mysql_searchquery($queryNum); //Executa a query
                $rowCount = mysqli_num_rows($resultsQueryNum); //Verifica quantas linhas tem o output da query

                if($rowCount == 0) //Caso não devolva nada
                {
                    $rowCount = 1;
                }

                echo "<tr>";
                //Rowspan = número de linhas do output do resultado
                echo "<td rowspan='$rowCount' colspan='1' >" . $rowType[1] . "</td>"; //$rowType[1]= item_type.name

                //Query do resto da tabela desejada (tabela item - id, name, state)
                $restTableQuery = mysql_searchquery('SELECT item.id, item.name, item.state FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0]); //Lista do resto da tabela
                $restTableQuery2 = mysql_searchquery('SELECT item.id, item.name, item.state FROM item, item_type WHERE item.item_type_id = item_type.id AND item_type.id = ' . $rowType[0]); //Lista do resto da tabela

                //Se não existir resultado da query
                if(!mysqli_fetch_array($restTableQuery2, MYSQLI_NUM))
                {
                    echo "<td rowspan='1' colspan='4'>Este tipo de item não tem itens.</td>";
                    echo "</tr>";
                }
                else {
                    //Enquanto houver output da query
                    while ($rowTabela = mysqli_fetch_array($restTableQuery, MYSQLI_NUM)) {
                        //Preenchimento da tabela
                        echo "<td>" . $rowTabela[0] . "</td>"; //id
                        echo "<td>" . $rowTabela[1] . "</td>"; //nome do item
                        echo "<td>" . $rowTabela[2] . "</td>"; //estado

                        if ($rowTabela[2] == "ative") { //ação
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

        //Array com valores enum da tabela item da coluna state
        $allactivetypes = get_enum_values("item", "state");

        //Formulário
        echo '<form action="" name="InsertForm" method="POST">
            Nome: <input type="text" name="nome_item"/>
            <p>Tipo:</p>';

        //Query: todos os atributos da tabela item_type
        $itemQuery = mysql_searchquery('SELECT * FROM item_type');

        //Enquanto houverem resultados: construir input do tipo radio com o valor do item  (do tipo enum)
        while($row = mysqli_fetch_array($itemQuery, MYSQLI_NUM))
        {
            //input com respetivo nome e id
            echo '<input type="radio" name="tipo_de_item" value="' . $row[0] . '"><label>' . $row[1] . '</label><br>';
        }
        echo '<p>Estado:</p>
            <!-- ativo = $allactivetypes[0] -->
            <input type="radio" name="state" value="' . $allactivetypes[0] . '" ><label>ativo</label>
            <!-- inativo = $allactivetypes[1] -->
            <input type="radio" name="state" value="' . $allactivetypes[1] . '" ><label>inativo</label>
            
            <input type="hidden" value="inserir" name="estado"/>               
            <input type="submit" value="Inserir item">
            </form>';
    }
}
?>
