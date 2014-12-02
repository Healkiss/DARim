<?php
require_once '../../vendor/autoload.php';
require_once 'conBdd.php';
$conBdd = new conBDD();

function receiveNewActivity($conBdd, $isTodo){
    $conBdd->connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    try
    { 
        $stmt =  $conBdd->connexion->prepare('INSERT INTO activity (client_id, user_id, activityType_id, task, commentary, isTodo) VALUES (:client_id, :user_id, :activityType_id, :task, :commentary, :isTodo)');
        $stmt->execute(array(
            'client_id'=> $_GET['client'],
            'user_id'=> 1,
            'activityType_id'=> $_GET['activityType'],
            'task'=> $_GET['task'],
            'commentary'=> $_GET['comment'],
            'isTodo'=> $isTodo
            ))
        ;
    }
    catch (PDOException $e)
    {
        var_dump($e);
    }
}
function start_activity($conBdd) {
    $activity = $_GET['activityId'];
    $runRunning = $_GET['runId'];
    echo $runRunning;
    if($runRunning)
        end_run($conBdd, $runRunning);
    $now = time();
    $stmt =  $conBdd->connexion->prepare('INSERT INTO activity(client_id, activityType_id, user_id, task, commentary, isTodo) SELECT client_id, activityType_id, user_id, task, commentary, 0 FROM activity WHERE id = (:activity_id)');
    $stmt->execute(array(
        'activity_id'=> $activity
        ));
    $stmt =  $conBdd->connexion->prepare('DELETE FROM activity WHERE id = :activity_id');
    $stmt->execute(array(
        'activity_id'=> $activity
        ));
    return true;
}
function end_run($conBdd, $run) {
    echo $run;
    $now = time();
    $stmt =  $conBdd->connexion->prepare('UPDATE activity SET end = NOW() WHERE id = :run_id');
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
function change_start_activity($conBdd, $activity, $newTime) {
    echo $activity;
    $now = time();
    $newTime = strtotime($newTime);
    echo $newTime;
    $stmt =  $conBdd->connexion->prepare('UPDATE activity SET start = FROM_UNIXTIME(:start) WHERE id = :activity_id');
    $stmt->execute(array(
        'activity_id'=> $activity,
        'start'=> $newTime
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
function change_end_activity($conBdd, $activity, $newTime) {
    $now = time();
    $newTime = strtotime($newTime);
    echo $newTime;
    $stmt =  $conBdd->connexion->prepare('UPDATE activity SET end = FROM_UNIXTIME(:end) WHERE id = :activity_id');
    $stmt->execute(array(
        'activity_id'=> $activity,
        'end'=> $newTime
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
        case 'change_start_activity':
            change_start_activity($conBdd, $_GET['activityId'], $_GET['newTime']);
            echo "change_start_activity";
            break;
        case 'change_end_activity':
            change_end_activity($conBdd, $_GET['activityId'], $_GET['newTime']);
            echo "change_end_activity";
            break;
        case 'submit':
            receiveNewActivity($conBdd, 1);
            echo "submit";
            break;
        case 'submitAndBegin':
            receiveNewActivity($conBdd, 0);
            echo "submitAndBegin";
            break;
    }
}


?>