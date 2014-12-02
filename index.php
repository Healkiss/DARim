<?php
require_once 'vendor/autoload.php';
require_once 'src/classes/conBdd.php';
$loader = new Twig_Loader_Filesystem('src/views');

$twig = new Twig_Environment($loader);
$twig->getExtension('core')->setTimezone('Europe/Paris');

$conBdd = new conBDD();

function exportDay() {
    //fputcsv();
}
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
        activity.end AS end,
        TIMESTAMPDIFF(SECOND, start, end) AS timeSpend
    FROM activity
    LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
    LEFT JOIN client AS client ON (client.id = activity.client_id)
    WHERE activity.isTodo = 0
    ORDER BY start
';
$response =  $conBdd->connexion->query($query);
$activities = $response->fetchAll();
//get today activities
$diary = array();
foreach($activities as $activity) {
    if (date('Ymd') == date('Ymd', strtotime($activity['start']))){
        $diary[] = $activity;
    }
}

//process today worktime
$response =  $conBdd->connexion->query("SELECT * FROM activity");
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
    LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
    LEFT JOIN client AS client ON (client.id = activity.client_id)
    WHERE activity.isTodo = 1
    ORDER BY start
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
		'activities' => $diary,
        'worktimeToday' => $worktimeToday
	));

?>