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

?>

