<?php

//Ligacao ativa por parte do WordPress a BD
function mysql_searchquery($query)
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $result = mysqli_query($link,$query);
    return $result;
}

//Obter link a certo caminho da pagina
global $current_page; $current_page = get_site_url().'/'.basename(get_permalink());
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

// Mostrar array de valores de uma coluna da tabela - '1','kg','altura' (?)
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

?>