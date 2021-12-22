<?php 
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('manage_records'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se existe algum elemento/valor no POST
    if (!empty($_POST))
    {
        //Validar
        if($_POST == "validar"){
            echo "<h3>Dados de registo - validação</h3>";
        }
        //Inserir
        else if($_POST == "inserir"){
            echo "<h3>Dados de registo - inserção</h3>";
        }
    }
    else{
        //Fazer pesquisa de filtragem (query)
        $querystring =
            'SELECT child.name as c_name, birth_date as c_birthdate , tutor_name as t_name, tutor_phone as t_phone, tutor_email as t_email, value.value as v_value, subitem.name as si_name, item.name as i_name, subitem_unit_type.name as sut_name 
                FROM child, value, subitem, item, subitem_unit_type
                WHERE child.id = value.child_id AND value.subitem_id = subitem.id AND subitem.item_id = item.id AND subitem_unit_type.id = subitem.unit_type_id
                ORDER BY c_name ASC';
        $queryresult = mysql_searchquery($querystring);//Query Desejado

        echo "<h3>Dados de registo - introdução</h3>";

        //Verifica se não existem tuplos na tabela subitem_unit_type
        $verifyNotEmpty = mysql_searchquery('SELECT * FROM child'); //Tabela child
        $row = mysqli_fetch_array($verifyNotEmpty, MYSQLI_NUM);

        if(!$row) { //Verifica se linha esta vazia
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

            while($rowTabela = mysqli_fetch_assoc($queryresult)){
                echo "<tr>";
                echo "<td>" . $rowTabela['c_name'] . "</td>";
                echo "<td>" . $rowTabela['c_birthdate'] . "</td>";
                echo "<td>" . $rowTabela['t_name'] . " </td>";
                echo "<td>" . $rowTabela['t_phone'] . " </td>";
                echo "<td>" . $rowTabela['t_email'] . " </td>";
                //echo "<td>" . $rowTabela['v_value'] . " </td>";
                //echo "<td>" . $rowTabela['si_name'] . " </td>";
                echo "<td>" . strtoupper($rowTabela['i_name']) . " :
                    <strong> " . $rowTabela['si_name'] . "</strong> (" . $rowTabela['v_value'] .
                    " " . $rowTabela['sut_name'] . ") ";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    }
}
?>