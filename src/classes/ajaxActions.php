<?php
    session_start();
    
    require_once '../../vendor/autoload.php';
    require_once 'Utility.php';
    use Symfony\Component\Yaml\Parser;
    $yaml = new Parser();
    $parameters = $yaml->parse(file_get_contents('../../app/parameters.yml'));
    $parameters = $parameters['parameters'];

    $localBD = new conBDD($parameters['dblocal_host'], $parameters['dblocal_port'], $parameters['dblocal_user'],$parameters['dblocal_password'],$parameters['dblocal_name']);
    $utility = new Utility($localBD);
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'start_activity':
                $utility->start_activity();
                echo "start_activity";
                break;
            case 'restart_activity':
                $utility->restart_activity($_GET['activityId']);
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
            case 'new_client':
                echo 'new_client';
                $utility->newClient($_GET['name'], $_GET['ref1'], $_GET['ref2']);
                break;
            case 'new_activitytype':
                echo 'new_activitytype';
                $utility->newActivityType($_GET['name'], $_GET['color']);
                break;
            case 'edit_activitytype':
                echo 'edit_activitytype';
                $utility->editActivityType($_GET['id'], $_GET['name'], $_GET['color']);
                break;
            case 'edit_client':
                echo 'edit_client';
                $utility->editClient($_GET['id'], $_GET['name'], $_GET['ref1'], $_GET['ref2']);
                break;
            case 'edit_activity':
                echo 'edit_activity';
                $field = $_GET['field'];
                echo 'field :  ' . $field . '<br/>';
                $functionName = 'change_'.$field.'_activity';
                $utility->$functionName($_GET['activityId'], $_GET['newValue']);
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