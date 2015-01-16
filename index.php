<?php
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 60*60*24*31);
session_start();
date_default_timezone_set('Europe/Paris');
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

if(!isset($_SESSION['currentDay']) || $_SESSION['currentDay'] == null){
    $_SESSION['currentDay'] = date('Y-m-d');
}
$localBD = new conBDD($parameters['dblocal_host'], $parameters['dblocal_port'], $parameters['dblocal_user'],$parameters['dblocal_password'],$parameters['dblocal_name']);
$utility = new Utility($localBD);

if (empty($_GET['data']))
     $_GET['data'] = 'home';
if(!isset($_SESSION['USERID']) || $_GET['data'] == 'login'){
    echo $utility->login($twig);
}else{
    if($_GET['data'] == 'admin'){
        $userId = $_SESSION['USERID'];
        echo $twig->render('admin.html.twig', array(
                'clients' => $utility->getClients($userId),
                'activityTypes' => $utility->getActivityTypes($userId),
            ));
    }else{
        $userId = $_SESSION['USERID'];
        echo $twig->render('darim.html.twig', array(
            'currentDay' => $_SESSION['currentDay'],
            'clients' => $utility->getClients($userId),
            'activityTypes' => $utility->getActivityTypes($userId),
            'todos' => $utility->getTodos($userId),
            'activities' => $utility->getActivities($userId),
            'worktimeToday' => $utility->getToDayWorktime($userId)
        ));
    }
}

?>