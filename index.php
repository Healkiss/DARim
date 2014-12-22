<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';
require_once 'src/classes/Utility.php';
require_once 'src/classes/Login.php';
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
$yaml = new Parser();

$parameters = $yaml->parse(file_get_contents('app/parameters.yml'));
$parameters = $parameters['parameters'];

$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);
$twig->getExtension('core')->setTimezone('Europe/Paris');


$localBD = new conBDD($parameters['dblocal_host'], $parameters['dblocal_user'],$parameters['dblocal_password'],$parameters['dblocal_name']);
$utility = new Utility($localBD);

if (empty($_GET['data']))
     $_GET['data'] = 'home';
if(!isset($_SESSION['USERID']) || $_GET['data'] == 'login'){
    $bopBD = new conBDD($parameters['dbbop_host'], $parameters['dbbop_user'],$parameters['dbbop_password'],$parameters['dbbop_name']);
    $login = new Login($bopBD);
    echo $twig->render('login.html.twig');
}else{
    if($_GET['data'] == 'admin'){
        echo $twig->render('admin.html.twig', array(
                'clients' => $utility->getClients(),
                'activityTypes' => $utility->getActivityTypes()
            ));
    }else{
        $userId = $_SESSION['USERID'];
        echo $twig->render('darim.html.twig', array(
                'clients' => $utility->getClients(),
                'activityTypes' => $utility->getActivityTypes(),
                'todos' => $utility->getTodos($userId),
                'activities' => $utility->getActivities($userId),
                'worktimeToday' => $utility->getToDayWorktime($userId)
            ));
    }
}

?>