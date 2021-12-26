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
            $tableQuery = mysql_searchquery('SELECT item.id, item.name, item.state, item_type.id, item_type.name FROM item, item_type WHERE item.id = item.item_type_id'); //Query Tabela

            //Construção da tabela
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <td><b>tipo de item</b></td>
                     <td><b>id</b></td>
                     <td><b>nome do item<b></td>
                     <td><b>estado<b></td>
                     <td><b>ação<b></td>
                  </tr>';

            while($rowTabela = mysqli_fetch_assoc($tableQuery)) {
                echo "<tr>";
                echo "<td>" . $rowTabela['item_type.name'] . "</td>";
                $previousItemType = $rowTabela['item_type.name'];
                //FALTA JUNTAR LINHA NO ITEM_TYPE SE TIVER O MESMO NOME;
                //Verificar se tem o mesmo item type
                echo "<td>" . $rowTabela['item.id'] . "</td>";
                echo "<td>" . $rowTabela['item.name'] . "</td>";
                echo "<td>" . $rowTabela['item.state'] . "</td>";
                if($rowTabela['item.state'] == "ativado"){
                    echo "<td> [editar] [desativar] </td>";
                }
                else{
                    echo "<td> [editar] [ativar] </td>";
                }
                echo "</tr>";
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
                <input type="radio" value="ID"><label>dado de criança</label>
                <input type="radio" value="ID" ><label>diagnóstico</label>
                <input type="radio" value="ID" ><label>intervenção</label>
                <input type="radio" value="ID" ><label>avaliação</label>
                 <!-- FALTA VERIFICAR SE FALTA MAIS -->
                 
                 <!-- FALTA ALTERAR VALUE/ VALUE PARA O valor do respetivo atributo state que é do tipo ENUM!!!! -->
                <p>Estado:</p>
                <input type="radio" value="ENUM" > <label>ativo</label>
                <input type="radio" value="ENUM" ><label>inativo</label> 
                <input type="hidden" value="inserir" />               
                <input type="submit" value="Inserir item" >
                </form>';
    }
}
?>