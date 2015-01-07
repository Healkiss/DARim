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


$localBD = new conBDD($parameters['dblocal_host'], $parameters['dblocal_port'], $parameters['dblocal_user'],$parameters['dblocal_password'],$parameters['dblocal_name']);
$utility = new Utility($localBD);

if (empty($_GET['data']))
     $_GET['data'] = 'home';
$_SESSION['USERID'] = 1;
if(!isset($_SESSION['USERID']) || $_GET['data'] == 'login'){
    $ds=ldap_connect("pilot.devatics.com");
    // Search login entry
       /* $sr=ldap_search($ds, "dc=devatics, dc=com", "uid=fschneider");  
        $info = ldap_get_entries($ds, $sr);
        echo "dn is: " . $info[0]["dn"] . "<br />";
        echo "nom: " . $info[0]["cn"][0] . "<br />";
        echo "mail: " . $info[0]["mail"][0] . "<br />";
        echo '<hr />all infos with search :<br/>';
        echo '<hr />all infos with anonymous login :<br/>';
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        if ($bind = ldap_bind($ds)) {
          echo "login success!";
        } else {
          echo "login error!";
        }
        echo '<hr />test login :<br/>';
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        if ($bind = ldap_bind($ds, 'uid=fschneider,ou=people,dc=devatics,dc=com' , 'Fullauto00')) {
          echo "login success!";
        } else {
          echo "login error!";
        }
        ldap_close($ds);*/
    $bopBD = new conBDD($parameters['dbbop_host'], $parameters['dbbop_port'], $parameters['dbbop_user'],$parameters['dbbop_password'],$parameters['dbbop_name']);
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
        if(!isset($_SESSION['currentDay'])){
            $_SESSION['currentDay'] = date('Y-m-d');
        }
        echo $twig->render('darim.html.twig', array(
                'currentDay' => $_SESSION['currentDay'],
                'clients' => $utility->getClients(),
                'activityTypes' => $utility->getActivityTypes(),
                'todos' => $utility->getTodos($userId),
                'activities' => $utility->getActivities($userId),
                'worktimeToday' => $utility->getToDayWorktime($userId)
            ));
    }
}

?>