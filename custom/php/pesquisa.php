<?php
require_once("custom/php/common.php");

require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            echo '<select name="operadoratr_'.$filtroatr.'">';
            if($filtroatr != "id" && $filtroatr != "birth_date" && $filtroatr != "tutor_phone")
            {
                //text attributes
                echo '<option value="&#61;">&#61;</option>';
                echo '<option value="!="> != </option>';
                echo '<option value="LIKE"> LIKE </option>';
            }
            else
            {
                //number attributes
                echo '<option value="&#62;">&#62;</option>';
                echo '<option value="&#62;&#61;">&#62;&#61;</option>';
                echo '<option value="&#61;">'."&#61;".'</option>';
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
        echo "<p>e do item: ".$_SESSION["item_name"]." uma listagem dos valores dos subitens:</p>";
        echo "<ul>";
        foreach($_SESSION['filtrosub_name'] as $filtrosub) {
            echo "<li>".$filtrosub;
            echo "\n";
            echo "Operador: ";
            echo '<select name="operadorsub_'.$filtrosub.'">';
            $queryvaluetype = mysql_searchquery('SELECT value_type FROM subitem WHERE name = "'.$filtrosub.'"');
            $valuetypesubitem = mysqli_fetch_array($queryvaluetype);
            if($valuetypesubitem[0] == "text")
            {
                //text attributes
                echo '<option value="&#61;">&#61;</option>';
                echo '<option value="!="> != </option>';
                echo '<option value="LIKE"> LIKE </option>';
            }
            else if($valuetypesubitem[0] == "enum")
            {
                //text attributes
                echo '<option value="&#61;">&#61;</option>';
                echo '<option value="!="> != </option>';
                //echo '<option value="LIKE"> LIKE </option>';
            }
            else
            {
                //number attributes
                echo '<option value="&#62;">&#62;</option>';
                echo '<option value="&#62;&#61;">&#62;&#61;</option>';
                echo '<option value="&#61;">'."&#61;".'</option>';
                echo '<option value="<">'."<".'</option>';
                echo '<option value="<=">'."<=".'</option>';
                echo '<option value="!=">'."!=".'</option>';
                //echo '<option value="LIKE">LIKE</option>';
            }
            echo '</select>';
            echo "\n";
            echo $filtrosub.": ";
            echo '<input type="text" name="'.$filtrosub.'"/>';
            echo "</li>";
        }
        foreach($_SESSION['obtersub_name'] as $obtersub) {
            if(!in_array($obtersub, $_SESSION['filtrosub_name']))
            {
                echo "<li>".$obtersub;
                echo "</li>";
            }
        }
        echo "</ul>";
        echo '<input type="hidden" value="execucao" name="estado"/>
        <input type="submit" value="Submeter" />
        </form>';
    }
    else if ($_REQUEST["estado"] == "execucao") {
        echo "<h3>Pesquisa - resultado</h3>";

        if(empty($_SESSION['obteratr_name']))
        {
            echo "<p>Não é possível fazer a procura de crianças sem nenhum atributo da criança a obter</p>";
            go_back_button();
        }
        else {

            $dinamicQueryString = 'SELECT ';
            foreach ($_SESSION['obteratr_name'] as $obteratr) {
                $dinamicQueryString .= 'child.' . $obteratr . ", ";
            }
            $dinamicQueryString .= 'subitem.name as sub_name, value.value, count(*) FROM subitem, child, item, value 
                                WHERE item.id = subitem.item_id AND value.subitem_id = subitem.id AND value.child_id = child.id AND item.id = "' . $_SESSION['item_id'] . '" AND item.name = "' . $_SESSION['item_name'] . '" ';

            foreach ($_SESSION['filtroatr_name'] as $filtroatr) {
                $dinamicQueryString .= 'AND child.' . $filtroatr . ' ';
                if ($_REQUEST["operadoratr_" . $filtroatr] == "LIKE") {
                    $dinamicQueryString .= 'LIKE "%' . $_REQUEST[$filtroatr] . '%" ';
                } else {
                    $dinamicQueryString .= $_REQUEST["operadoratr_" . $filtroatr] . ' "' . $_REQUEST[$filtroatr] . '" ';
                }
            }
            if (!empty($_SESSION['filtrosub_name'])) {
                $dinamicQueryString .= 'AND ( ';
                foreach ($_SESSION['filtrosub_name'] as $filtrosub) {
                    $dinamicQueryString .= '(subitem.name = "' . $filtrosub . '" AND value.value ';
                    if ($_REQUEST["operadorsub_" . $filtrosub] == "LIKE") {
                        $dinamicQueryString .= 'LIKE "%' . $_REQUEST[$filtrosub] . '%" ) ';
                    } else {
                        $dinamicQueryString .= $_REQUEST["operadorsub_" . $filtrosub] . ' "' . $_REQUEST[$filtrosub] . '" ) ';
                    }
                    $dinamicQueryString .= 'OR ';
                }
                $dinamicQueryString = substr_replace($dinamicQueryString, ")", -4);
            }
            //$dinamicQueryString .= ' GROUP BY child.id HAVING count(*) = '.count($_SESSION['filtrosub_name']);
            $dinamicQueryString .= ' GROUP BY child.id ';
            if (!empty($_SESSION['filtrosub_name'])) {
                $dinamicQueryString .= 'HAVING count(*) = ' . count($_SESSION['filtrosub_name']);
            }
            //echo $dinamicQueryString;//TESTE
            $queryResultFilters = mysql_searchquery($dinamicQueryString);
            if (mysqli_num_rows($queryResultFilters) > 0) {
                //adicionar frase
                $fraseQuery = '<p>Foi executado com sucesso a pesquisa do(s) registo(s) selecionando a(s) coluna(s)';
                if(!empty($_SESSION['obteratr_name'])) {
                    foreach ($_SESSION['obteratr_name'] as $obteratr) {
                        $fraseQuery .= ' '.$obteratr.',';
                    }
                    $fraseQuery = substr_replace($fraseQuery, "", -1);
                }
                $fraseQuery .= ' da tabela child e uma coluna que contém a listagem de subitem(ns)';
                if(!empty($_SESSION['obtersub_name'])) {
                    foreach ($_SESSION['obtersub_name'] as $obtersub) {
                        $fraseQuery .= ' '.$obtersub . ',';
                    }
                    $fraseQuery = substr_replace($fraseQuery, "", -1);
                }
                $fraseQuery .= ' pertencente(s) ao item '.$_SESSION['item_name'].'';
                if(!empty($_SESSION['filtroatr_name']) || !empty($_SESSION['filtrosub_name'])) {
                    $fraseQuery .= ' usando o(s) filtro(s):';
                    foreach ($_SESSION['filtroatr_name'] as $filtroatr) {
                        $fraseQuery .= ' atributo ' . $filtroatr . ', que tem como operador de condição ' . $_REQUEST['operadoratr_' . $filtroatr] . ' e o valor a verificar (' . $_REQUEST[$filtroatr] . '); ';
                    }
                    foreach ($_SESSION['filtrosub_name'] as $filtrosub) {
                        $fraseQuery .= ' subitem ' . $filtrosub . ', que tem como operador de condição ' . $_REQUEST['operadorsub_' . $filtrosub] . ' e o valor a verificar (' . $_REQUEST[$filtrosub] . '); ';
                    }
                    $fraseQuery = substr_replace($fraseQuery, "", -2);
                }
                $fraseQuery .= '</p>';
                echo $fraseQuery;

                //adicionar tabela
                echo '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
                <tbody>
                  <tr>';
                $htmlString = '<table class="mytable" style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
                <tbody>
                  <tr>';
                foreach ($_SESSION['obteratr_name'] as $obteratr) {
                    echo '<th>' . $obteratr . '</th>';
                    $htmlString .= '<th>' . $obteratr . '</th>';
                }
                echo '<th>Subitens e seus valores</th>';
                $htmlString .= '<th>Subitens e seus valores</th>';
                echo '</tr>';
                $htmlString .= '</tr>';

                while ($row = mysqli_fetch_assoc($queryResultFilters)) {
                    $queryStringSubitemsValues = 'SELECT ';
                    $queryStringSubitemsValues .= 'subitem.name, value.value FROM subitem, child, item, value 
                                WHERE item.id = subitem.item_id AND value.subitem_id = subitem.id AND value.child_id = child.id AND item.id = "' . $_SESSION['item_id'] . '" AND item.name = "' . $_SESSION['item_name'] . '" ';
                    foreach ($_SESSION['obteratr_name'] as $obteratr) {
                        $queryStringSubitemsValues .= 'AND child.' . $obteratr . '= "' . $row[$obteratr] . '" ';
                    }

                    if (!empty($_SESSION['obtersub_name'])) {
                        $queryStringSubitemsValues .= 'AND ( ';
                        foreach ($_SESSION['obtersub_name'] as $obtersub) {
                            $queryStringSubitemsValues .= 'subitem.name = "' . $obtersub . '" ';
                            $queryStringSubitemsValues .= 'OR ';
                        }
                        $queryStringSubitemsValues = substr_replace($queryStringSubitemsValues, ")", -3);
                    }
                    //echo $queryStringSubitemsValues;//TESTE
                    $queryResultSubitemsValues = mysql_searchquery($queryStringSubitemsValues);


                    if (mysqli_num_rows($queryResultSubitemsValues) > 0) {
                        echo '<tr>';
                        $htmlString .= '<tr>';
                        foreach ($_SESSION['obteratr_name'] as $obteratr) {
                            echo '<td>' . $row[$obteratr] . '</td>';
                            $htmlString .= '<td>' . $row[$obteratr] . '</td>';
                        }
                        echo '<td>';
                        $htmlString .= '<td>';
                        while ($row2 = mysqli_fetch_assoc($queryResultSubitemsValues)) {
                            echo $row2['name'] . ': ' . $row2['value'] . '<br>';
                            $htmlString .= $row2['name'] . ': ' . $row2['value'] . '<br>';
                        }
                        echo '</td>';
                        echo '</tr>';
                        $htmlString .= '</td></tr>';
                    }
                }
                echo "</tbody></table>";
                $htmlString .= "</tbody></table>";

                //EXCEL - FALTA

                $spreadsheet = new Spreadsheet();

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
                //echo $htmlString; //TESTE
                $spreadsheet = $reader->loadFromString($htmlString);

                $sheet = $spreadsheet->getActiveSheet();
                $sheet->insertNewRowBefore(1);
                $sheet->setCellValue('A1', substr($fraseQuery,3,-4));

                $writer = new Xlsx($spreadsheet);
                $stringFileName = 'exceltable_grupoE02.xlsx';
                $writer->save($stringFileName);

                echo '<a href="../../'.$stringFileName.'" download>';
                echo 'TRANSFERIR EXCEL';
                echo '</a>';
            }
        }
    }
    else
    {
        echo "<h3>Pesquisa - escolher item</h3>";
        //REUTILIZAR CODIGO DA LISTAGEM NO COMMON.PHP
        listItemsAndItemTypes('estado=escolha&item=');
    }
}
?>