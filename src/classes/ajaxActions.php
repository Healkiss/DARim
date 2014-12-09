<?php
    require_once 'Utility.php';
    $utility = new Utility();
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'edit_activity':
                echo "edit_activity";
                break;
            case 'start_activity':
                $utility->start_activity();
                echo "start_activity";
                break;
            case 'end_run':
                $utility->end_activity($_GET['activityId']);
                echo "end_run";
                break;
            case 'change_start_activity':
                $utility->change_start_activity($_GET['activityId'], $_GET['newTime']);
                echo "change_start_activity";
                break;
            case 'change_end_activity':
                $utility->change_end_activity($_GET['activityId'], $_GET['newTime']);
                echo "change_end_activity";
                break;
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