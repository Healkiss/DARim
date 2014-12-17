<?php
    require_once 'Utility.php';
    $utility = new Utility();
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'start_activity':
                $utility->start_activity();
                echo "start_activity";
                break;
            case 'end_run':
                $utility->end_activity($_GET['activityId']);
                echo "end_run";
                break;
            case 'delete_activity':
                $utility->delete_activity($_GET['activityId']);
                echo "delete activity";
                break;
            case 'edit_activity':
                echo 'edit_activity : ';
                $field = $_GET['field'];
                        echo $field;
                switch ($field) {
                    case 'start_time':
                        echo "start_time<br/>";
                        $utility->change_start_activity($_GET['activityId'], $_GET['newValue']);
                        break;
                    case 'end_time':
                        echo "end_time<br/>";
                        $utility->change_end_activity($_GET['activityId'], $_GET['newValue']);
                        break;
                    case 'activityType':
                        echo "activityType <br/>";
                        $utility->change_activityType_activity($_GET['activityId'], $_GET['newValue']);
                        break;
                    default:
                        echo "<br/>Champ edition non géré<br/>";
                        break;
                    }
                break;
            case 'submit':
                $utility->receiveNewActivity(1);
                echo "submit";
                break;
            case 'submitAndBegin':
                $utility->receiveNewActivity(0);
                echo 'submitAndBegin';
                break;
            case 'get_clients':
                echo json_encode($utility->getClients());
                break;
            case 'get_activityTypes':
                echo json_encode($utility->getActivityTypes());
                break;
            default:
                echo 'requete ajax non suppportée';
                break;
        }
    }
?>