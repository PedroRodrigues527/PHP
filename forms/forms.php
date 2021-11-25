<html>
<body>

<!-- When receive input, sends this .php file -->
<!--
Nome Inserido: <?php //echo$_POST["name"];?> --> <!-- <br> <input type="text" name="name"><br> -->
<!-- E-mail inserido: <?php //echo $_POST["email"]?>
-->


<!-- $_SERVER["PHP_SELF"] super global var. (Pode ser acessada por todos)
Returns the filename of the currently exectuing script, same page -->
<!-- htmlspecialchar() ->  function converts special characters to HTML entities. This means that it will replace HTML characters like < and > with &lt; and &gt;. This prevents attackers from exploiting the code by injecting HTML or Javascript code (Cross-site Scripting attacks) in forms.
-->

<?php //Se nome == "" será enviado uma mensagem ($nameErr)
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["none"])) {
        $nameErr = "Nome obrigatorio";
    } else {
        $name = test_input($_POST["nome"]);
    }
}
?>

<p>FORM VALIDATION!</p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
    Nome: <input type="text" name="nome"> <?php echo $nameErr; ?> <br>
    Email: <input type="text" name="email"> <br>
    Website: <input type="text" name="website"> <br>
    Comments: <textarea name="comment" rows="5" cols="40"></textarea>
    Sexo:
    <input type="radio" name="sexo" value="mas."> Male
    <input type="radio" name="sexo" value="fem."> Female
    <input type="radio" name="sexo" value="other"> Other
    <input type="submit">
</form>

</body>
</html>



