<?php

//Dá como resultado a pesquisa de uma query
function mysql_searchquery($querystring)
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    // Check connection
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $result = mysqli_query($link,$querystring);
    mysqli_close($link);
    return $result;
}

//Dá como resultado a pesquisa de várias queries
function mysql_searchseveralquery($querystring)
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    // Check connection
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $result = mysqli_multi_query($link,$querystring);
    mysqli_close($link);
    return $result;
}

//Dá como resultado true/false dependendo do resultado ser vazio ou não
function isResultQueryEmpty($querystring)
{
    return !mysqli_fetch_array(mysql_searchquery($querystring), MYSQLI_NUM);
}

//Obter link a certo caminho da pagina
global $current_page;
$current_page = get_site_url().'/'.basename(get_permalink());
//e no ficheiro .php pretendido, fazer isto:
/*
 * echo '<form method="post" action="'.$current_page.'">';
   echo '<a href="'.$current_page.'?estado=editar&item='.$item_id.'">[editar]</a>';
 */

//Implementacao do botao "Voltar atras"
function go_back_button()
{
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Voltar atrás'>Voltar atrás</a>\");</script>
    <noscript>
    <a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Voltar atrás'>Voltar atrás</a>
    </noscript>";
}

//Implementação do botao "Continuar"
function continue_button()
{
    echo "<p>Clique em <strong>Continuar</strong> para avançar</p>";
    echo '<form action="" name="Continuar" method="POST">
          <input type="hidden" value="" name="estado"/>
          <input type="submit" value="Continuar"/>
          </form>';
}

// Mostrar array de valores de uma coluna da tabela - ['text','bool',...]
function get_enum_values($table, $column)
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $query = " SHOW COLUMNS FROM `$table` LIKE '$column' ";
    $result = mysqli_query($link, $query );
    $row = mysqli_fetch_array($result , MYSQLI_NUM );
    #extract the values
    #the values are enclosed in single quotes
    #and separated by commas
    $regex = "/'(.*?)'/";
    preg_match_all( $regex , $row[1], $enum_array );
    $enum_fields = $enum_array[1];
    return( $enum_fields );
}

//Verificar se o user fez login e tem capability pretendida
//$clientsideval=0;
function verify_user($capability)
{
    return is_user_logged_in() && current_user_can($capability);
}

//Reutilização de código acerca da listagem de items e tipos de itens: em insercao-de-valores.php e pesquisa.php
function listItemsAndItemTypes($url)
{
    $queryStringTabelaItemType = 'SELECT id, name FROM item_type ORDER BY id ASC';
    $queryresultTabelaItemType = mysql_searchquery($queryStringTabelaItemType);
    echo '<ul>';
    while($rowTabelaItemType = mysqli_fetch_array($queryresultTabelaItemType, MYSQLI_NUM)) {
        echo '<li>' . $rowTabelaItemType[1];
        echo '<ul>';
        $queryStringTabelaItem = 'SELECT item.id, item.name FROM item INNER JOIN item_type ON item.item_type_id = item_type.id AND item_type.id = ' . $rowTabelaItemType[0] . ' ORDER BY id ASC';
        $queryresultTabelaItem = mysql_searchquery($queryStringTabelaItem);
        while($rowTabelaItem = mysqli_fetch_array($queryresultTabelaItem, MYSQLI_NUM)) {
            $queryVerifyItem = 'SELECT subitem.id FROM subitem INNER JOIN item ON subitem.item_id = item.id AND item.id = ' . $rowTabelaItem[0] . ' ORDER BY subitem.id ASC';
            $queryresultTabelaVerifyItem = mysql_searchquery($queryVerifyItem);
            $SubitensCount = mysqli_num_rows($queryresultTabelaVerifyItem);
            if($SubitensCount > 0)
            {
                echo '<form method="post" action="'.$current_page.'">';
                echo '<li><a href="'.$current_page.'?'.$url.''.$rowTabelaItem[0].'">';
                echo '['.$rowTabelaItem[1].']';
                echo '</a></li>';
            }
        }
        echo '</ul>';
        echo '</li>';
    }
    echo '</ul>';
}

//Reutilização das colunas "ação" em componentes
function colunaAcao($ifative, $id)
{
    echo '<form method="GET" action="'.get_site_url().'/edicao-de-dados">';
    echo "<td>";
    echo '<a href="'.get_site_url().'/edicao-de-dados?estado=editar&comp='.basename(get_permalink()).'&id='.$id.'">[editar] </a>';
    if ($ifative == "active") { //ação
        echo '<a href="'.get_site_url().'/edicao-de-dados?estado=desativar&comp='.basename(get_permalink()).'&id='.$id.'">[desativar]</a>';
    } else {
        echo '<a href="'.get_site_url().'/edicao-de-dados?estado=ativar&comp='.basename(get_permalink()).'&id='.$id.'">[ativar]</a>';
    }
    echo "</td>";
    echo '</form>';
}

?>
