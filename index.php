<?php
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';
require_once 'src/classes/Utility.php';

$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);
$twig->getExtension('core')->setTimezone('Europe/Paris');

$utility = new Utility();

echo $twig->render('base.html.twig', array(
		'clients' => $utility->getClients(),
		'activityTypes' => $utility->getActivityTypes(),
        'todos' => $utility->getTodos(),
		'activities' => $utility->getActivities(),
        'worktimeToday' => $utility->getToDayWorktime()
	));

?>