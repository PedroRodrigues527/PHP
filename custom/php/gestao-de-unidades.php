<?php
require_once("custom/php/common.php");
//require_once("custom/js/script.js");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_unit_types'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if (!empty($_POST))
    {
        //Validar cada campo e indicar o problema;
        //String SQL inserção de dados na tabela subitem_unit_type FEITO
        //Em caso de sucesso;
            //Apresentar os dados de novo tipo de unidade com sucesso;
            //Apresentar Continuar para a mesma página;

        //Verifica se foi submetido string vazia;
        if($_POST['nome_unidade'] != ""){
            //Apresentar mensagem de erro (Nome vazio!)
            $nameErr = "Por favor preencha o nome (Campo Obrigatório)";
        }
        else{
            //Inserir nome da unidade na Base de dados
            $nameForm = $_POST['nome_unidade'];
            $insertQuery = 'INSERT INTO subitem_unit_type (name) 
                    VALUES(' . $nameForm . ')';
        }


    }
    else{

        //Fazer pesquisa de filtragem (query)
        $querystring = 'SELECT id as sut_id, name as sut_name FROM subitem_unit_type
                        ORDER BY id ASC';
        $queryresult = mysql_searchquery($querystring);//Query Desejado

        //Verifica se não existem tuplos na tabela subitem_unit_type
        $verifyNotEmpty = mysql_searchquery('SELECT id, name FROM subitem_unit_type'); //Tabela subitem type
        $row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
            echo "<p>Não há tipos de unidades</p>";
        } else {
            //Tem tuplos
            echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <td><b>Id</b></td>
                     <td><b>Unidade</b></td>
                     <td><b>Subitem</b></td>
                  </tr>';

            while($rowTabela = mysqli_fetch_assoc($queryresult)){
                echo "<tr>";
                echo "<td>" . $rowTabela['sut_id'] . "</td>"; //ID
                echo "<td>" . $rowTabela['sut_name'] . "</td>"; //Unidade

                $queryItemSubitem = 'SELECT subitem.name as si_name, item.name as i_name FROM subitem, item, subitem_unit_type 
                                     WHERE subitem_unit_type.id = subitem.unit_type_id AND subitem.item_id = item.id AND subitem_unit_type.id = ' . $rowTabela['sut_id'];
                $resultItemSubitem = mysql_searchquery($queryItemSubitem);//Query Desejado
                $resultValueString = "";
                echo "<td>";
                //Procura dados enquanto houver resultado
                while($rowItemSubitem = mysqli_fetch_array($resultItemSubitem, MYSQLI_NUM)){
                    $resultValueString .= $rowItemSubitem[0] . " (" . $rowItemSubitem[1] . "), "; //Subitem
                }
                echo substr_replace($resultValueString ,"", -2);
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        echo "<h3>Gestão de unidades - introdução</h3>";

        echo '<form action="" name="InsertForm" method="POST" onsubmit="return validateform(document.InsertForm.nome_unidade.value)">
                Nome da Unidade: <input type="text" name="nome_unidade"/>
                <p> <?php echo $nameErr;?> </p>
                <input type="hidden" value="inserir" />
                <input type="submit" value="Inserir tipo de unidade" />
                </form>
                <script type="text/javascript" src="../js/script.js">
                </script>';

        //Fazer formulário para cada campo,
        //Se tiver errado ou incompleto informar
        //Apresentar uma ligação para voltar ao passo anterior, caso contrário executar o que se segue
        //construir uma string com o comando SQL necessário para inserção dos dados na tabela subitem_unit_type e executá-lo
        //Apresentar, em caso de sucesso da inserção:
        //    Inseriu os dados de novo tipo de unidade com sucesso.
        //    Clique em Continuar para avançar
        //    em que Continuar é uma ligação para esta mesma página
    }
}

?>