<?php
require_once '../../vendor/autoload.php';
require_once 'conBdd.php';
$conBdd = new conBDD();

function start_activity($conBdd) {
    $activity = $_GET['activityId'];
    $runRunning = $_GET['runId'];
    echo $runRunning;
    if($runRunning)
        end_run($conBdd, $runRunning);
    $now = time();
    $stmt =  $conBdd->connexion->prepare('INSERT INTO run (activity_id) VALUES (:activity_id)');
    $stmt->execute(array(
        'activity_id'=> $activity
        ));
    return true;
}
function end_run($conBdd, $run) {
    echo $run;
    $now = time();
    $stmt =  $conBdd->connexion->prepare('UPDATE run SET end = NOW() WHERE id = :run_id');
    $stmt->execute(array(
        'run_id'=> $run
        ));
    $response =  $conBdd->connexion->query("SELECT * FROM run");
    $runs = $response->fetchAll();
    $diary = array();
    $worktimeToday = '0';
    foreach($runs as $run) {
        if (date('Ymd') == date('Ymd', strtotime($run['start'])) && $run['start'] != 0 ){
            $diary[] = $run;
        }
    }
    foreach($diary as $dayrun) {
        $worktimeToday = $dayrun['end'] - $dayrun['end'];
    }
    return $worktimeToday;
}

if(isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'edit_activity':
            echo "edit_activity";
            break;
        case 'start_activity':
            start_activity($conBdd);
            echo "start_activity";
            break;
        case 'end_run':
            end_run($conBdd, $_GET['runId']);
            echo "end_run";
            break;
    }
}


?>