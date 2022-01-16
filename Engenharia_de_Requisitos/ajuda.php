<?php
session_start();
echo '<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <title> GOBikes </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="shortcut icon" href="img/logo.png" />
    <link rel="stylesheet" href="style.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<section class= "header">
    <nav>
        <a href="index.html"><img src="img/logo.png" style="width:140px; height:100px;"></a>
        <div class="nav-links" id="navLinks">
            <i class="fa fa-window-close"></i>
            <ul>
                <!-- <li> <a href="#">HOME</a></li> -->';
if((isset($_SESSION['username']) || !empty($_SESSION['username']))) {
    echo '<li> <a href="menuprincipal.php">PÁGINA PRINCIPAL</a></li>';
}
else
{
    echo '<li> <a href="index.html">INÍCIO</a></li>';
}
echo '</ul>
        </div>
        <!--<i class="fa fa-ellipsis-v" onclick="showMenu()"></i>-->
    </nav>
    <!--
    <div class="text-box">
        <h1>Some text in here</h1>
        <p>This web site was used with css, html and some javascript </p>
        <a  href="" class="hero-btn">Visit us to know more</a>
    </div>-->
</section>

<!-- Course -->
<section class="course" id="course">

    <h2 id="ajuda" >Ajuda</h2>

</section>

<!--Call to action -->
<!--
<section class="cta" id="cta">
	<h1>Call to action title </h1>
	<a href="" class="hero-btn">CONTACT US!!</a>
</section>-->

<!--Footer -->
<section class="footer" id="contact">
    <!-- <h4>Footer</h4> -->
    <p>Contactos</p>
    <div class="icons">
        <!-- <i class="fa fa-star"></i> -->
        <i class="fa fa-facebook"></i>
        <i class="fa fa-twitter"></i>
        <i class="fa fa-instagram"></i>
        <!--<i class="fa fa-linkedin"></i>-->
    </div>
    <p>Feito por Grupo 1 de Engenharia de Requisitos (2021/2022)</p>
</section>

<!--
<section class="contact" id="contact">
	<div class=" comment-box">
		<h3>CONTACT</h3>
		<form class="comment-form">

		</form>
	</div>
</section> -->

</body>
</html>
';