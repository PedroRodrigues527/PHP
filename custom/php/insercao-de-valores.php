<?php
require_once("custom/php/common.php");

//Verifica se user está login e tem certa capability
if(!verify_user('insert_values'))
{
    echo "<p>Não tem autorização para aceder a esta página</p>";
}
else {
    //Verifica se o valor do estado é "escolher_crianca"
    if ($_REQUEST["estado"] == "escolher_crianca") {
        echo "<h3>Inserção de valores - criança - escolher</h3>";
        $queryStringTabelaChild = 'SELECT id, name, birthdate FROM child ORDER BY id ASC';

        if(!($_POST['nome_crianca'] == "" || ctype_space($_POST['nome_crianca'])) && !($_POST['data_crianca'] == "" || ctype_space($_POST['data_crianca'])))
        {

        }
    }
    else if ($_REQUEST["estado"] == "escolher_item") {

    }
    else if ($_REQUEST["estado"] == "introducao") {

    }
    else if ($_REQUEST["estado"] == "validar") {

    }
    else if ($_REQUEST["estado"] == "inserir") {

    }
    else
    {
        echo "<h3>Inserção de valores - criança - procurar</h3>";
        echo '<p>Introduza um dos nomes da criança a encontrar e/ou a data de nascimento dela</p>';
        echo '<form action="" name="InsertForm" method="POST">
                Nome: <input type="text" name="nome_crianca"/> 
                Data de nascimento (AAAA-MM-DD): <input type="text" name="data_crianca"/> 
                <input type="hidden" value="escolher_crianca" name="estado"/>
                <input type="submit" value="Submeter"/>
                </form>';
    }
}
?>