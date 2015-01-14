<?php
session_start();
session_cache_expire(60*24*30);
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
    echo $utility->login($twig, $parameters['version']);
}else{
    if($_GET['data'] == 'admin'){
        echo $twig->render('admin.html.twig', array(
                'clients' => $utility->getClients(),
                'activityTypes' => $utility->getActivityTypes(),
                'version' => $parameters['dblocal_port']
            ));
    }else{
        $userId = $_SESSION['USERID'];
        echo $twig->render('darim.html.twig', array(
                'currentDay' => $_SESSION['currentDay'],
                'clients' => $utility->getClients(),
                'activityTypes' => $utility->getActivityTypes(),
                'todos' => $utility->getTodos($userId),
                'activities' => $utility->getActivities($userId),
                'worktimeToday' => $utility->getToDayWorktime($userId),
                'version' => $parameters['version']
            ));
    }
}

?>