<?php
require_once("custom/php/common.php");
//require_once("custom/js/script.js");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_subitems'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if (!empty($_POST)) {
        //Inserir
        if ($_POST == "inserir") {
            echo "<h3>Gestão de subitens - inserção</h3>";

            /*//Validar
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
        }*/
        } else {
            //Tabela
            //Verifica se não existem tuplos na tabela subitem_unit_type
            $itemQuery = mysql_searchquery('SELECT P, K FROM L'); //Tabela subitem
            $row = mysqli_fetch_array($itemQuery, MYSQLI_NUM);

            if (!$row) { //Verifica se linha esta vazia
                echo "<p>Não há subitens especificados</p>";
            } else {
                $tableQuery = mysql_searchquery('SELECT X FROM Y WHERE Z'); //Query Tabela

                //Construção da tabela
                //Tem tuplos
                echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <td><b>item</b></td>
                     <td><b>id</b></td>
                     <td><b>subitem<b></td>
                     <td><b>tipo de valor</td>
                     <td><b>nome do campo no formulário<b></td>
                     <td><b>tipo do campo no formulário<b></td>
                     <td><b>tipo de unidade<b></td>
                     <td><b>ordem do campo no formulário<b></td>
                     <td><b>obrigatório<b></td>
                     <td><b>estado<b></td>
                     <td><b>ação<b></td>
                  </tr>';
                /*
                while ($rowTabela = mysqli_fetch_array($tableQuery, MYSQLI_NUM)) {
                    echo "<tr>";
                    echo "<td>" . $rowTabela[4] . "</td>";
                    $previousItemType = $rowTabela[4];
                    //FALTA JUNTAR LINHA NO ITEM_TYPE SE TIVER O MESMO NOME;
                    //Verificar se tem o mesmo item type
                    //corrigido
                    echo "<td>" . $rowTabela[0] . "</td>";
                    echo "<td>" . $rowTabela[1] . "</td>";
                    echo "<td>" . $rowTabela[2] . "</td>";
                    if ($rowTabela[2] == "ativado") {
                        echo "<td> [editar] [desativar] </td>";
                    } else {
                        echo "<td> [editar] [ativar] </td>";
                    }
                    echo "</tr>";
                }*/
                echo "</tbody></table>";
            }


            echo "<h3>Gestão de subitems - introdução</h3>";
            //Form
            echo '<form action="" name="InsertForm" method="POST" onsubmit="return validateform(document.InsertForm.nome_unidade.value)">
                Nome do subitem: <input type="text" name="nome_item"/>
                <p>Tipo de valor:</p>
                <!-- Todos os tipo de valores presentes no atributo value_type! -->
                <!-- Usar função php no ficheiro common.php get_enum_values ^ -->
                <input type="radio" name= "tipo" value="ID"><label>text</label>
                <input type="radio" name= "tipo" value="ID" ><label>int</label>
                <input type="radio" name= "tipo" value="ID" ><label>...</label>
                                
                 <!-- Select box (Item) -->
                 <!-- Nomes todos os itens presentes na tabela item -->
                 
                 <!-- Lista com todos os tipos de campos em form_field_type -->
                <p>Tipo do campo de formulário:</p>
                <input type="radio" name= "estado" value="ENUM" > <label>text</label>
                <input type="radio" name= "estado" value="ENUM" ><label>text box</label>
                <input type="radio" name= "estado" value="ENUM" ><label>...</label>  
                
                <!-- Select box (subitem_unit_type) (OPCIONAL) -->
                <!-- Nomes todos os tipos de unidades presentes na tabela subitem_unit_type -->
                
                Ordem do campo no fomulário: <input type="text" name="nome_item"/>
                
                <p>Obrigatório:</p>
                <input type="radio" name= "estado" value="1" > <label>sim</label>
                <input type="radio" name= "estado" value="0" ><label>não</label> 
                
                <input type="hidden" value="inserir" />               
                <input type="submit" value="Inserir item" >
                </form>';
        }
    }
}
?>
