<?php
require_once("custom/php/common.php");
//require_once("custom/js/script.js");

//FALTA:
    //Adicionar Capability Manage items

//Verifica se user está login e tem certa capability
if(!verify_user('manage_items'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if (!empty($_POST))
    {
        //Inserir
        if($_POST == "inserir"){
            echo "<h3>Gestão de itens - inserção</h3>";

            //Validar
            if($_POST['nome_item'] == ""){ //Falta as outras condições
                //Apresentar mensagem de erro (Nome vazio!)
                echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade está vazia!</p>";
                go_back_button();
            }

            //Verifica se foi submetido um dado com números ou carateres especiais além das letras
            else if(!preg_match ("/^[a-zA-z]*$/", $_POST['nome_unidade']))
            {
                //Apresentar mensagem de erro (Tem números!)
                echo "<p>ERRO: O dado inserido no formulário do Nome da Unidade só pode ter letras!</p>";
                go_back_button();
            }

            //Entra aqui se os dados inseridos forem válido
            else {
                //Inserir nome da unidade na Base de dados
                $insertQuery = "INSERT INTO item (name, state ) 
                    VALUES('" . $_POST['nome_unidade'] . "', '" . $_POST['estado'] . "')";

                //Caso de sucesso
                if (mysql_searchquery($insertQuery)) {
                    echo "<p>Inseriu os dados de novo tipo de item com sucesso.</p>";
                    continue_button();
                }
            }
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
            $restTableQuery = mysqli_searchquery('SELECT item.id, item.name, item.state, item_type.id FROM item, item_type WHERE item.id = item.item_type_id'); //Lista do resto da tabela
            //Construção da tabela
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2" >
               <tbody>
                  <tr>
                     <td><b>tipo de item</b></td>
                     <td><b>id</b></td>
                     <td><b>nome do item<b></td>
                     <td><b>estado<b></td>
                     <td><b>ação<b></td>
                  </tr>';

            while($rowType = mysqli_fetch_array($typeItemQuery, MYSQLI_NUM)) {

                $queryNum = 'SELECT item_type.id, item_type.name FROM item_type WHERE item_type = $$rowType[1] ';//query: items associados a um tipo de item
                $rowCount = mysqli_num_rows(mysql_searchquery($queryNum)); //Quantos items associados a um tipo de item
                echo "<tr>";
                echo "<td rowspan='$rowCount' colspan='1'>" . $rowType[1] . "</td>"; //Tipo de item

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
            echo "</tbody></table>";
        }


        echo "<h3>Gestão de itens - introdução</h3>";
        //Form
        echo '<form action="" name="InsertForm" method="POST" onsubmit="return validateform(document.InsertForm.nome_unidade.value)">
                Nome: <input type="text" name="nome_item"/>
                <p> <?php echo $nameErr;?> </p>
                <p>Tipo:</p>
                <!-- FALTA ALTERAR VALUE/ VALUE PARA ID CORRESPONDENTE!!!! -->
                <input type="radio" name= "tipo" value="ID"><label>dado de criança</label>
                <input type="radio" name= "tipo" value="ID" ><label>diagnóstico</label>
                <input type="radio" name= "tipo" value="ID" ><label>intervenção</label>
                <input type="radio" name= "tipo" value="ID" ><label>avaliação</label>
                 <!-- FALTA VERIFICAR SE FALTA MAIS -->
                 
                 <!-- FALTA ALTERAR VALUE/ VALUE PARA O valor do respetivo atributo state que é do tipo ENUM!!!! -->
                <p>Estado:</p>
                <input type="radio" name= "estado" value="ENUM" > <label>ativo</label>
                <input type="radio" name= "estado" value="ENUM" ><label>inativo</label> 
                
                <input type="hidden" value="inserir" />               
                <input type="submit" value="Inserir item" >
                </form>';
    }
}
?>
