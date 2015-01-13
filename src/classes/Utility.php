<?php
require_once 'conBdd.php';

class Utility {
    protected $conBdd;

    function __construct($conBdd){
        $this->conBdd = $conBdd;
        $currentTime = new \DateTime();
        $currentTime = $currentTime->format('H:i:s');
        $currentDay = $_SESSION['currentDay'];
        $currentDay = date("Y-m-d", strtotime($currentDay));
        $this->currentDateTime = $currentDay . ' ' . $currentTime;
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

    function stopRunningActivity($userId) {
        echo $userId; 
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET end = :end WHERE user_id = :user_id AND end = \'0000-00-00 00:00:00\'');
        $stmt->execute(array(
            'user_id'=> $userId,
            'end'=> $this->currentDateTime,
            ));
    }
    function deleteActivity($activityId) {
        $stmt =  $this->conBdd->connexion->prepare('DELETE FROM activity WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activityId
            ));
    }
    function copyActivity($activityId, $isTodo) {
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity(client_id, activityType_id, user_id, task, commentary, isTodo) SELECT client_id, activityType_id, user_id, task, commentary, '.$isTodo.' FROM activity WHERE id = (:activity_id)');
        $stmt->execute(array(
            'activity_id'=> $activityId
            ));
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
        $currentDay = $_SESSION['currentDay'];
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
            if (date('Ymd', strtotime($currentDay)) == date('Ymd', strtotime($activity['start']))){
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
        $this->stopRunningActivity($userId);
        $stmt =  $this->conBdd->connexion->prepare('INSERT INTO activity (client_id, user_id, activityType_id, task, commentary, isTodo, start) VALUES (:client_id, :user_id, :activityType_id, :task, :commentary, :isTodo, :start)');
        $stmt->execute(array(
            'client_id'=> $_GET['client'],
            'user_id'=> $userId,
            'activityType_id'=> $_GET['activityType'],
            'task'=> $_GET['task'],
            'commentary'=> $_GET['comment'],
            'isTodo'=> $isTodo,
            'start'=> $this->currentDateTime,
            ))
        ;
    }
    function start_activity($userId) {
        $activityId = $_GET['activityId'];
        $this->stopRunningActivity($userId);
        $this->copyActivity($activityId, 0);
        $this->deleteActivity($activity);
    }
    function restart_activity($activity, $userId) {
        $activityId = $_GET['activityId'];
        $this->stopRunningActivity($userId);
        $this->copyActivity($activityId, 0);
    }
    function todo_activity($activityId, $userId) {
        $this->copyActivity($activityId, 1);
    }
    function end_activity($userId) {
        $this->stopRunningActivity($userId);
    }
    function delete_activity($activity) {
        $this->deleteActivity($activity);
    }
    function change_start_time_activity($activity, $newTime) {
        $newTime = strtotime($newTime);
        echo $newTime;
        $stmt =  $this->conBdd->connexion->prepare('UPDATE activity SET start = FROM_UNIXTIME(:start) WHERE id = :activity_id');
        $stmt->execute(array(
            'activity_id'=> $activity,
            'start'=> $newTime
            ));
    }
    function change_end_time_activity($activity, $newTime) {
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
    function generateCSV($userId){
        $currentDay = $_SESSION['currentDay'];
        $query = '
            SELECT
                activity.commentary AS comment,
                activity.task AS task,
                activityType.name AS activityTypeName,
                client.name AS client_name,
                client.ref AS client_ref,
                client.ref2 AS client_ref2,
                activity.start AS start,
                activity.end AS end,
                user.login AS login,
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
        $array = array();
        foreach($activities as $activity) {
            if (date('Ymd', strtotime($currentDay)) == date('Ymd', strtotime($activity['start']))){
                $array[] = $activity;
            }
        }
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $now = new DateTime('now');
        $df = fopen("/tmp/".$_SESSION['login']."of".$_SESSION['currentDay']."at".$now->format('Y-m-d H:i:s').".csv", 'w+');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
    function login($twig){
        //after connexion :
        $ds=ldap_connect("pilot.devatics.com");
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        if(isset($_POST['login'])){
            //check if user exist in ldap and can connect
            error_reporting(0);
            if ($bind = ldap_bind($ds, 'uid='.$_POST['login'].',ou=people,dc=devatics,dc=com' , ''.$_POST['password'].'')) {
                //check if user exist in localBd
                $query = '
                    SELECT *
                    FROM user
                    WHERE user.login = "'.$_POST['login'].'"
                ';
                $_SESSION['login'] = $_POST['login'];
                $response =  $this->conBdd->connexion->query($query);
                // var_dump($response);
                $user = $response->fetchAll();
                // var_dump($user);
                //if not exist create user
                if(!$user){
                    $stmt =  $this->conBdd->connexion->prepare('INSERT INTO user (login) VALUES (:login)');
                    $stmt->execute(array('login'=> $_POST['login']));
                    $userId = $this->conBdd->connexion->lastInsertId();
                    $_SESSION['USERID'] = $userId;
                //else go to main page
                }else{
                    $userId = $user[0]['id'];
                    $_SESSION['USERID'] = $userId;
                }
                header("Location: home");
                exit(0);   
            } else {
                return $twig->render('login.html.twig',array('error' => "bad credentials"));
            }
        }else{
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
                if ($bind = ldap_bind($ds, 'uid=fschneider,ou=people,dc=devatics,dc=com' , 'Fullauto00')) {
                  echo "login success!";
                } else {
                  echo "login error!";
                }
                ldap_close($ds);*/
            //$bopBD = new conBDD($parameters['dbbop_host'], $parameters['dbbop_port'], $parameters['dbbop_user'],$parameters['dbbop_password'],$parameters['dbbop_name']);
            //$login = new Login($bopBD);
            return $twig->render('login.html.twig',array('error' => ""));
        }
    }
    function logout(){
        unset($_SESSION['USERID']);
    }
}
?>