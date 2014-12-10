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
                $field = $_GET['field'];
                        echo $field;
                switch ($field) {
                    case 'start_time':
                        echo "start_time";
                        $utility->change_start_activity($_GET['activityId'], $_GET['newValue']);
                        break;
                    case 'end_time':
                        echo "end_time";
                        $utility->change_end_activity($_GET['activityId'], $_GET['newValue']);
                        break;
                    }
            case 'submit':
                $utility->receiveNewActivity(1);
                echo "submit";
                break;
            case 'submitAndBegin':
                $utility->receiveNewActivity(0);
                echo "submitAndBegin";
                break;
        }
    }
?>