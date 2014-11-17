<?php
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';
$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);
$twig->getExtension('core')->setTimezone('Europe/Paris');

$conBdd = new conBDD();

function toTime($tmp){
    $retour['second'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $retour['second']) /60 );
    $retour['minute'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $retour['minute'])/60 );
    $retour['hour'] = $tmp % 24;
 
    $tmp = floor( ($tmp - $retour['hour'])  /24 );
    $retour['day'] = $tmp;
 
    return $retour;
}

function receiveNewActivity($conBdd){
    $conBdd->connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    try
    { 
    $stmt =  $conBdd->connexion->prepare('INSERT INTO activity (client_id, user_id, activityType_id, task, commentary) VALUES (:client_id, :user_id, :activityType_id, :task, :commentary)');
    $stmt->execute(array(
        'client_id'=> $_GET['client'],
        'user_id'=> 1,
        'activityType_id'=> $_GET['activityType'],
        'task'=> $_GET['task'],
        'commentary'=> $_GET['comment']
        ));
    }
    catch (PDOException $e)
    {
            var_dump($e);
    }
    header("Location: index.php");
}
if(isset($_GET['submit'])) {
    receiveNewActivity($conBdd);
}

$response =  $conBdd->connexion->query("SELECT * FROM client");
$clients = $response->fetchAll();
$response =  $conBdd->connexion->query("SELECT * FROM activityType");
$activitiesType = $response->fetchAll();
//activities
$query = '
    SELECT
        activity.commentary AS commentary,
        activity.task AS task,
        activity.id AS id,
        activityType.name AS activityType_name,
        client.name AS client_name,
        client.ref AS client_ref,
        client.ref2 AS client_ref2,
        activity.start AS start,
        activity.end AS end
    FROM activity
    LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id AND activity.isTodo = 0)
    LEFT JOIN client AS client ON (client.id = activity.client_id)
';
$response =  $conBdd->connexion->query($query);
$activities = $response->fetchAll();
$diary = array();
foreach($activities as $activity) {
    if (date('Ymd') == date('Ymd', strtotime($activity['start']))){
        $diary[] = $activity;
    }
}

//process today worktime
$response =  $conBdd->connexion->query("SELECT * FROM run");
$runs2 = $response->fetchAll();
$diary2 = array();
$worktimeToday = '0';
foreach($runs2 as $run) {
    //var_dump($run);
    if (date('Ymd') == date('Ymd', strtotime($run['start'])) && $run['end'] !== "0000-00-00 00:00:00"){
        $diary2[] = $run;
    }
}
foreach($diary2 as $dayrun) {
    //echo strtotime($dayrun['end']) ." - ". strtotime($dayrun['start']);
    $worktimeToday += strtotime($dayrun['end']) - strtotime($dayrun['start']);
}
$worktimeToday = toTime($worktimeToday);
//tasks
$tasks = array();

//todos
$query = '
    SELECT
        activity.commentary AS commentary,
        activity.task AS task,
        activity.id AS id,
        activityType.name AS activityType_name,
        client.name AS client_name,
        client.ref AS client_ref,
        client.ref2 AS client_ref2,
        activity.start AS start,
        activity.end AS end
    FROM activity
    LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id AND activity.isTodo = 1)
    LEFT JOIN client AS client ON (client.id = activity.client_id)
';
try
{ 
$response =  $conBdd->connexion->query($query);
$todos = $response->fetchAll();
}
catch (PDOException $e)
{
        var_dump($e);
}

echo $twig->render('base.html.twig', array(
		'clients' => $clients,
		'activitiesType' => $activitiesType,
        'todos' => $todos,
		'activities' => $activities,
        'worktimeToday' => $worktimeToday
	));

?>