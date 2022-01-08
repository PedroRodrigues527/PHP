<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('search'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    if ($_REQUEST["estado"] == "escolha") {
        $_SESSION['item_id'] = $_REQUEST['item'];
        $queryResultItemName = mysql_searchquery('SELECT name FROM item WHERE id = '.$_REQUEST['item']);
        $rowItemName = mysqli_fetch_array($queryResultItemName, MYSQLI_NUM);
        $_SESSION['item_name'] = $rowItemName[0];
        echo "<h3>Pesquisa</h3>";
        //tabela atributos de child
        $queryResultChildAttributes = mysql_searchquery('SHOW COLUMNS FROM child');
        echo '<form action="" name="InsertForm" method="POST">';
        echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th>Atributo</th>
                     <th>Obter</th>
                     <th>Filtro</th>
                  </tr>';
        while($rowChildAttribute = mysqli_fetch_array($queryResultChildAttributes, MYSQLI_NUM))
        {
            echo '<tr>';
            echo "<td>" . $rowChildAttribute[0] . "</td>";
            echo '<td><input type="checkbox" value="'.$rowChildAttribute[0].'" name="obteratr[]" /></td>';
            echo '<td><input type="checkbox" value="'.$rowChildAttribute[0].'" name="filtroatr[]" /></td>';
            echo '</tr>';
        }
        echo '</tr></tbody></table>';
        //tabela subitens
        $queryResultNameSubitems = mysql_searchquery('SELECT name FROM subitem WHERE item_id = '.$_SESSION['item_id']);
        echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
               <tbody>
                  <tr>
                     <th>Subitem</th>
                     <th>Obter</th>
                     <th>Filtro</th>
                  </tr>';
        while($rowNameSubitem = mysqli_fetch_array($queryResultNameSubitems, MYSQLI_NUM))
        {
            echo '<tr>';
            echo "<td>" . $rowNameSubitem[0] . "</td>";
            echo '<td><input type="checkbox" value="'.$rowNameSubitem[0].'" name="obtersub[]" /></td>';
            echo '<td><input type="checkbox" value="'.$rowNameSubitem[0].'" name="filtrosub[]" /></td>';
            echo '</tr>';
        }
        echo '</tr></tbody></table>
        <input type="hidden" value="escolher_filtros" name="estado"/>
        <input type="submit" value="Submeter" />
        </form>';
    }
    else if ($_REQUEST["estado"] == "escolher_filtros") {
        $idattribute = 0;
        $_SESSION['obteratr_name'] = $_REQUEST['obteratr'];
        foreach($_REQUEST['obteratr'] as $obteratr){
            $_SESSION['obteratr_id'] = $idattribute;
            $idattribute++;
        }
        $idattribute = 0;
        $_SESSION['filtroatr_name'] = $_REQUEST['filtroatr'];
        foreach($_REQUEST['filtroatr'] as $filtroatr){
            $_SESSION['filtroatr_id'] = $idattribute;
            $idattribute++;
        }
        $_SESSION['obtersub_name'] = $_REQUEST['obtersub'];
        foreach($_REQUEST['obtersub'] as $obtersub){
            $queryStringIdSubitem = 'SELECT id FROM subitem WHERE name = "'.$obtersub.'"';
            $queryResultIdSubitem = mysql_searchquery($queryStringIdSubitem);
            $rowSubitemID = mysqli_fetch_array($queryResultIdSubitem, MYSQLI_NUM);
            $_SESSION['obtersub_id'] = $rowSubitemID[0];
        }
        $_SESSION['filtrosub_name'] = $_REQUEST['filtrosub'];
        foreach($_REQUEST['filtrosub'] as $filtrosub){
            $queryStringIdSubitem = 'SELECT id FROM subitem WHERE name = "'.$filtrosub.'"';
            $queryResultIdSubitem = mysql_searchquery($queryStringIdSubitem);
            $rowSubitemID = mysqli_fetch_array($queryResultIdSubitem, MYSQLI_NUM);
            $_SESSION['filtrosub_id'] = $rowSubitemID[0];
        }
        echo "<h3>Pesquisa - escolher filtros</h3>";
        echo "<p>Irá ser realizada uma pesquisa que irá obter, como resultado, uma listagem de, para cada criança, dos seguintes dados pessoais escolhidos:</p>";
        echo '<form action="" name="InsertForm" method="POST">';
        echo "<ul>";
        foreach($_SESSION['filtroatr_name'] as $filtroatr) {
            echo "<li>".$filtroatr;
            echo "\n";
            echo "Operador: ";
            echo '<select name="operador">';
            if($filtroatr != "id" && $filtroatr != "birth_date" && $filtroatr != "tutor_phone")
            {
                //text attributes
                echo '<option value="="> = </option>';
                echo '<option value="!="> != </option>';
                echo '<option value="LIKE"> LIKE </option>';
            }
            else
            {
                //number attributes
                //echo '<option value=">">'.">".'</option>';
                //echo '<option value=">'.">".'</option>';
                //este
                echo '<option value=">">&gt;</option>';
                //echo '<option value=">=">'.">=".'</option>';
                //echo '<option value=">='.">=".'</option>';
                //echo '<option value=">='.">" . "=".'</option>';
                echo '<option value = " >= ">'. ">" . "=" .'</option>';
                echo '<option value="=">'."=".'</option>';
                //echo '<option value="='."=".'</option>';
                echo '<option value="<">'."<".'</option>';
                echo '<option value="<=">'."<=".'</option>';
                echo '<option value="!=">'."!=".'</option>';
                //echo '<option value="LIKE">LIKE</option>';
            }
            echo '</select>';
            echo "\n";
            echo $filtroatr.": ";
            echo '<input type="text" name="'.$filtroatr.'"/>';
            echo "</li>";
        }
        foreach($_SESSION['obteratr_name'] as $obteratr) {
            if(!in_array($obteratr, $_SESSION['filtroatr_name']))
            {
                echo "<li>".$obteratr;
                echo "</li>";
            }
        }
        echo "</ul>";
        echo '<input type="hidden" value="execucao" name="estado"/>
        <input type="submit" value="Submeter" />
        </form>';
    }
    else if ($_REQUEST["estado"] == "execucao") {

    }
    else
    {
        echo "<h3>Pesquisa - escolher item</h3>";
        //REUTILIZAR CODIGO DA LISTAGEM NO COMMON.PHP
        listItemsAndItemTypes('estado=escolha&item=');
    }
}
?>