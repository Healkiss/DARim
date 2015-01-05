<?php
require_once 'conBdd.php';

class Utility {
    protected $conBdd;

    function __construct($conBdd){
        $this->conBdd = $conBdd;
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
    function getActivities($userId) {
        $query = '
            SELECT
                activity.commentary AS commentary,
                activity.task AS task,
                activity.id AS id,
                activityType.name AS activityType_name,
                activityType.id AS activityType_id,
                activityType.color AS activityType_color,
                client.name AS client_name,
                client.id AS client_id,
                client.ref AS client_ref,
                client.ref2 AS client_ref2,
                activity.start AS start,
                activity.end AS end,
                TIMESTAMPDIFF(SECOND, start, end) AS timeSpend
            FROM activity
            LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
            LEFT JOIN client AS client ON (client.id = activity.client_id)
            LEFT JOIN user AS user ON (user.id = activity.user_id)
            WHERE activity.isTodo = 0 AND user.id = '.$userId.'
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

    function getToDayWorktime($userId) {
        $activities = $this->getActivities($userId);
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

    function getTodos($userId) {
        $query = '
            SELECT
                activity.commentary AS commentary,
                activity.task AS task,
                activity.id AS id,
                activityType.name AS activityType_name,
                activityType.id AS activityType_id,
                activityType.color AS activityType_color,
                client.name AS client_name,
                client.id AS client_id,
                client.ref AS client_ref,
                client.ref2 AS client_ref2
            FROM activity
            LEFT JOIN activityType AS activityType ON (activityType.id = activity.activityType_id)
            LEFT JOIN client AS client ON (client.id = activity.client_id)
            LEFT JOIN user AS user ON (user.id = activity.user_id)
            WHERE activity.isTodo = 1 AND user.id = '.$userId.'
            ORDER BY start
        ';
        $response =  $this->conBdd->connexion->query($query);
        $todos = $response->fetchAll();
        return $todos;
    }

    function receiveNewActivity($isTodo, $userId){
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = NOW() WHERE user_id = :user_id AND end = \'0000-00-00 00:00:00\'');
        $stmt->execute(array(
            'user_id'=> $userId
            ));
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity (client_id, user_id, activityType_id, task, commentary, isTodo) VALUES (:client_id, :user_id, :activityType_id, :task, :commentary, :isTodo)');
        $stmt->execute(array(
            'client_id'=> $_GET['client'],
            'user_id'=> $userId,
            'activityType_id'=> $_GET['activityType'],
            'task'=> $_GET['task'],
            'commentary'=> $_GET['comment'],
            'isTodo'=> $isTodo
            ))
        ;
    }
    function start_activity($userId) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = NOW() WHERE user_id = :user_id AND end = \'0000-00-00 00:00:00\'');
        $stmt->execute(array(
            'user_id'=> $userId
            ));
        $activity = $_GET['activityId'];
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
    function restart_activity($activity, $userId) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = NOW() WHERE user_id = :user_id AND end = \'0000-00-00 00:00:00\'');
        $stmt->execute(array(
            'user_id'=> $userId
            ));
        $activity = $_GET['activityId'];
        $now = time();
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity(client_id, activityType_id, user_id, task, commentary, isTodo) SELECT client_id, activityType_id, user_id, task, commentary, 0 FROM activity WHERE id = (:activity_id)');
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
    function change_start_time_activity($activity, $newTime) {
        $now = time();
        $newTime = strtotime($newTime);
        echo $newTime;
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET start = FROM_UNIXTIME(:start) WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'start'=> $newTime
            ));
    }
    function change_end_time_activity($activity, $newTime) {
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
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET activityType_id = :activityType WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'activityType'=> $activityType
            ));
    }
    function change_client_activity($activity, $client) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET client_id = :client WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'client'=> $client
            ));
    }
    function change_task_activity($activity, $task) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET task = :task WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'task'=> $task
            ));
    }
    function change_commentary_activity($activity, $commentary) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET commentary = :commentary WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'commentary'=> $commentary
            ));
    }
    function editClient($id, $name, $ref1, $ref2) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE client SET name = :name, ref = :ref1, ref2 = :ref2  WHERE id = :clientId');
        $stmt->execute(array(
            'clientId'=> $id,
            'name'=> $name,
            'ref1'=> $ref1,
            'ref2'=> $ref2
            ));
    }
    function editActivityType($id, $name, $color) {
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activityType SET name = :name, color = :color WHERE id = :activityTypeId');
        $stmt->execute(array(
            'activityTypeId'=> $id,
            'name'=> $name,
            'color'=> $color
            ));
    }
    function newClient($name, $ref1, $ref2) {
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO client (name, ref, ref2) VALUES (:name, :ref1, :ref2)');
        $stmt->execute(array(
            'name'=> $name,
            'ref1'=> $ref1,
            'ref2'=> $ref2
            ));
    }
    function newActivityType($name, $color) {
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activityType (name, color) VALUES (:name, :color)');
        $stmt->execute(array(
            'name'=> $name,
            'color'=> $color
            ));
    }
}
?>