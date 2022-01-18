Instalação do protótipo “funcional”

Para aceder ao nosso protótipo, seguir os seguintes passos:
1- Transferir o ficheiro zip enviado além do relatório;
2- Descompactar o ficheiro zip para obter a pasta Engenharia_de_Requisitos;
3- Instalar o XAMPP;
4- Depois de instalar o XAMPP, ir ao diretório C:/xampp/htdocs;
5- Copiar a pasta Engenharia_de_Requisitos descompactada e colar no diretório htdocs dito no passo anterior;
6- Abrir o programa XAMPP e clicar nos botões de Start nos serviços Apache e MySQL;
7- Abrir um web browser (preferencialmente Google Chrome) e digitar o URL: http://localhost/phpmyadmin
8- Criar uma nova base de dados denominado por "er_db" no phpmyadmin;
9- Clicar na base de dados "er_db" e escolher a opção "Importar";
10- Escolher o ficheiro .sql que está presente na pasta Engenharia_de_Requisitos descompactada;
11- Executar a importação .sql;
12- Abrir um novo separador web browser e digitar o URL: http://localhost/Engenharia_de_Requisitos/index.html

NOTA: Ao fazer a inserção de registo de uma conta, não é automaticamente implementada uma inserção dos dados de cartão de crédito associado a essa conta/usuário. Logo, tem 2 alternativas:
Inserir manualmente e corretamente os dados de cartão de crédito na base de dados phpmyadmin associado ao id do user recentemente registado.
OU
Fazer login de uma das contas existentes na base de dados que tenham associado a um cartão de crédito.
