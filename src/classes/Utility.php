<?php
require_once 'conBdd.php';

class Utility {
    protected $conBdd;

    function __construct(){
        $this->conBdd = new conBDD();
    }

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

    function getClients() {
        $response =  $this->conBdd->connexion->query("SELECT * FROM client");
        $clients = $response->fetchAll();
        return $clients;
    }

    function getActivityTypes() {
        $response =  $this->conBdd->connexion->query("SELECT * FROM activityType");
        $activitiesType = $response->fetchAll();
        return $activitiesType;
    }

    //get today activities
    function getActivities() {
        $query = '
            SELECT
                activity.commentary AS commentary,
                activity.task AS task,
                activity.id AS id,
                activityType.name AS activityType_name,
                activityType.color AS activityType_color,
                client.name AS client_name,
                client.ref AS client_ref,
                client.ref2 AS client_ref2,
                activity.start AS start,
                activity.end AS end
            FROM activity
            LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
            LEFT JOIN client AS client ON (client.id = activity.client_id)
            WHERE activity.isTodo = 0
            ORDER BY start
        ';
        $response =  $this->conBdd->connexion->query($query);
        $activities = $response->fetchAll();
        $diary = array();
        foreach($activities as $activity) {
            if (date('Ymd') == date('Ymd', strtotime($activity['start']))){
                $diary[] = $activity;
            }
        }
        return $diary;
    }

    function getToDayWorktime() {
        $activities = $this->getActivities();
        $diary = array();
        $worktimeToday = '0';
        foreach($activities as $activity) {
            if (date('Ymd') == date('Ymd', strtotime($activity['start'])) && $activity['end'] !== "0000-00-00 00:00:00"){
                $diary[] = $activity;
            }
        }
        foreach($diary as $dayrun) {
            $worktimeToday += strtotime($dayrun['end']) - strtotime($dayrun['start']);
        }
        $worktimeToday = $this->toTime($worktimeToday);
        return $worktimeToday;
    }

    function getTodos() {
        $query = '
            SELECT
                activity.commentary AS commentary,
                activity.task AS task,
                activity.id AS id,
                activityType.name AS activityType_name,
                client.name AS client_name,
                client.ref AS client_ref,
                client.ref2 AS client_ref2
            FROM activity
            LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
            LEFT JOIN client AS client ON (client.id = activity.client_id)
            WHERE activity.isTodo = 1
            ORDER BY start
        ';
        $response =  $this->conBdd->connexion->query($query);
        $todos = $response->fetchAll();
        return $todos;
    }

    function receiveNewActivity($isTodo){
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity (client_id, user_id, activityType_id, task, commentary, isTodo) VALUES (:client_id, :user_id, :activityType_id, :task, :commentary, :isTodo)');
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
    function start_activity() {
        $activity = $_GET['activityId'];
        $runRunning = $_GET['runId'];
        if($runRunning)
            end_run($this->conBdd, $runRunning);
        $now = time();
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity(client_id, activityType_id, user_id, task, commentary, isTodo) SELECT client_id, activityType_id, user_id, task, commentary, 0 FROM activity WHERE id = (:activity_id)');
        $stmt->execute(array(
            'activity_id'=> $activity
            ));
        $stmt =  $this->conBdd->connexion->prepare('DELETE FROM activity WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity
            ));
    }
    function end_activity($run) {
        $now = time();
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = NOW() WHERE id = :run_id');
        $stmt->execute(array(
            'run_id'=> $run
            ));
    }
    function delete_activity($activity) {
        $now = time();
        $stmt =  $this->conBdd->connexion->prepare('DELETE FROM activity WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity
            ));
    }
    function change_start_activity($activity, $newTime) {
        $now = time();
        $newTime = strtotime($newTime);
        echo $newTime;
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET start = FROM_UNIXTIME(:start) WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'start'=> $newTime
            ));
    }
    function change_end_activity($activity, $newTime) {
        $now = time();
        $newTime = strtotime($newTime);
        echo $newTime;
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = FROM_UNIXTIME(:end) WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'end'=> $newTime
            ));
    }
    function change_activityType_activity($activity, $activityType) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET activityType = :activityType WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'activityType'=> $activityType
            ));
    }
}
?>