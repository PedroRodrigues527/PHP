<?php

//Liga��o ativa por parte do WordPress � BD
/*
function sql_query($parameter)
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $query = $parameter;
    $result = mysqli_query($link,$query);
    return $result;
}
*/

//Obter link a certo caminho da p�gina
global $current_page; $current_page = get_site_url().'/'.basename(get_permalink());
//e no ficheiro .php pretendido, fazer isto:
/*
 * echo '<form method="post" action="'.$current_page.'">';
   echo '<a href="'.$current_page.'?estado=editar&item='.$item_id.'">[editar]</a>';
 */

//Implementa��o do bot�o "Voltar atr�s"
function go_back_button()
{
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>\");</script>
    <noscript>
    <a href='" . $_SERVER['HTTP_REFERER'] . "� class='backLink' title='Voltar atr�s'>Voltar atr�s</a>
    </noscript>";
}

// Mostrar tabela de valores??
function get_enum_values($connection, $table, $column )
{
    $query = " SHOW COLUMNS FROM `$table` LIKE '$column' ";
    $result = mysqli_query($connection, $query );
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