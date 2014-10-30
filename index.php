<?php
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';

$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);

$conBdd = new conBDD();
$reponse =  $conBdd->connexion->query("SELECT * FROM client WHERE 1 = 1");
$clients = $reponse->fetchAll();

echo $twig->render('index.html.twig', array('clients' => $clients));

?>