<!-- Ficheiro formulario.php -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Validacao dados</title>
</head>
<?php
//busca as variaveis utilizando o array associative $_POST
$nome = $_POST['nome'];
$apelido = $_POST['apelido'];
$telefone = $_POST['telefone'];
$morada = $_POST['morada'];
$email = $_POST['email'];
//valida o telefone e o email
if(!preg_match('291[0-9]{6}', $telefone)){
    die('O telefone terá que ser no formato 291XXXXXX');
}
else if(!preg_match('@',$email)){
    die('O formato do email está incorreto');
}

$link = mysqli_connect(localhost, user, password);
if(!link)
{
    die("Erro ao ligar à base de dados!");
}
$bd = mysqli_select_db(nova_bd, $link);
if(!bd)
{
    die("Erro ao aceder à base de dados!");
}
//Constroi a query
$query = sprintf("INSERT INTO cliente values (%s,%s,%s,%s,%s);",
    mysqli_real_escape_string($nome),
    mysqli_real_escape_string($apelido),
    mysqli_real_escape_string($telefone),
    mysqli_real_escape_string($morada),
    mysqli_real_escape_string($email));
//executa a query
$resultado = mysqli_query($query);

if(!resultado){
    die("Erro ao inserir na base de dados!");
}

//Indica mensagem de sucesso e finaliza
?>
<body>
<h3>A introdução dos dados foi feita com sucesso!</h3>
Nome: <? $nome ?>
Apelido: <? $apelido ?>
Morada: <? $morada ?>
Telefone: <? $telefone ?>
E-mail: <? $email ?>
</body>
</html>