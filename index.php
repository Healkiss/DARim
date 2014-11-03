<?php
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';

$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);

$conBdd = new conBDD();
$reponse =  $conBdd->connexion->query("SELECT * FROM client");
$clients = $reponse->fetchAll();
$reponse =  $conBdd->connexion->query("SELECT * FROM activityType");
$activitiesType = $reponse->fetchAll();
$tasks = array();
$activities = array();


echo $twig->render('base.html.twig', array(
		'clients' => $clients,
		'activitiesType' => $activitiesType,
		'activities' => $activities
	));

?>