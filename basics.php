<?php
//echo "Hello php!" //output message;

$var = 3; //declare variable(global scope)
//&$varPTR = $var
$var1 = "numero";
//echo $var . $var1 . "SOME TEXT!"; //Output multiples variables and texts

function myFunc(){
    //echo $var // nao é possivel usar a var criada anteriormente;
    $localVar = 6;
    echo $localVar;
}

//myFunc(); call the fucntion

//Como acessar var globar?
function globalFunc(){
    global $var; //definida anteriormente. Permite usar var. global na funcção
    $GLOBALS['var']; // outra maneira de acessar variavel global
    echo $var;
    echo $GLOBALS['var'];
}

//globalFunc();

function staticVarFunc(){
    static $stVar = 1; //Cria uma variavel que ao terminar a função NAO APAGA A VARIAVEL, ISTO E, É ATUALIZADO O SEU VALOR
    echo $stVar;
    $stVar++;
}
//staticVarFunc(); //Output: 1
//staticVarFunc(); //Output: 2
//staticVarFunc(); //Output: 3

//echo VS print -----------------------------
//echo nao faz return; Multiplos argumentos (caso seja necessário)
//print retorna 1; Usa apenas 1 argumento

//Criar tag html em PHP -----------------------
function htmlFunc(){
    $title = "Some title";
    $text = "some text to put on html";
    echo "<h2>" . $title . "</h2>";
    echo "<p>" . $text . "</p>";
}
//htmlFunc();
$int = 1;
$float = 1.7;
$string = "abacate";
//var_dump -> return type and value of the var
//echo var_dump($int);
//echo var_dump($float);
//echo var_dump($string);
$array = array("um","dois","tres"); //declarar array
//echo strlen("") Comprimento da uma string
//echo $array[0];

//Criação de uma classe carro POO
class Carro{
    public $cor;
    public $modelo;
    public function __construct($cor, $modelo)
    {
        $this -> cor = $cor;
        $this -> modelo = $modelo;
    }
    public function msg(){
        return "Carro: Cor-> " . $this ->cor . " Modelo -> ". $this->modelo;
    }
}

//$meuCarro = new Carro("preto", "opel");
//echo $meuCarro -> msg();

//Random Numbers (int)
//echo(rand(10,100)); //Numeros [10,100]

//Constantes are global
define("NUMERO", 3); //define("NOME_DA_CONSTANTE", VALOR, CASE-INSENSITIVE(BOOL));
function constFunc(){
    echo NUMERO;
    //echo numero; //apenas se define("NUMERO", 3 , True)
}
//constFunc();
$intV = 3;
$intS = "teste";
/*
var_dump($intV==$intS); //CMP value
var_dump($intV===$intS); //CMP type
var_dump($intV==2); //false
var_dump($intV===3); //true
*/

$text=" quero ";
$text1 = "ferias";

//echo $text.$text1; //concatenação

//$text .= $text1; //append
//echo $text;

//IFs

$a = 3;
if($a > 3){
    //echo "maior ou igual que 3";
}elseif ($a = 3){
    //echo "igual";
} else{
    //echo "qualquer coisa";
}

//Case
$favcolor = "red";
/*
switch ($favcolor) {
    case "red":
        echo "Your favorite color is red!";
        break;
    case "blue":
        echo "Your favorite color is blue!";
        break;
    case "green":
        echo "Your favorite color is green!";
        break;
    default:
        echo "Your favorite color is neither red, blue, nor green!";
}*/


$x = 1;
//While
while($x < 10){
    //echo $x;
    $x++;
}

//For
for($i = 0; $i <= 10; $i++){
    //echo "algo ";
}

//Associative array
$age = array("a"=>"1","b"=>"2","c"=>"3");

foreach($age as $k => $k_val){
    //echo "chave: " .$k. " valor: ". $k_val;
}

/*
sort() - sort arrays in ascending order
rsort() - sort arrays in descending order
asort() - sort associative arrays in ascending order, according to the value
ksort() - sort associative arrays in ascending order, according to the key
arsort() - sort associative arrays in descending order, according to the value
krsort() - sort associative arrays in descending order, according to the key
*/


?>

