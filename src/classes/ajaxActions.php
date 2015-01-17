<?php
    session_start();
    error_reporting (E_ALL);
    require_once '../../vendor/autoload.php';
    require_once 'Utility.php';
    use Symfony\Component\Yaml\Parser;
    $yaml = new Parser();
    $parameters = $yaml->parse(file_get_contents('../../app/parameters.yml'));
    $parameters = $parameters['parameters'];

    $loader = new Twig_Loader_Filesystem('../views');

    $twig = new Twig_Environment($loader);
    $twig->getExtension('core')->setTimezone('Europe/Paris');

    $localBD = new conBDD($parameters['dblocal_host'], $parameters['dblocal_port'], $parameters['dblocal_user'],$parameters['dblocal_password'],$parameters['dblocal_name']);
    $utility = new Utility($localBD);
    if(isset($_SESSION['USERID'])){
        $userId = $_SESSION['USERID'];
    }else{
        if(!isset($_GET['action']) && $_GET['action'] != 'loginFb'){
            //spoof
            echo "ko";
            return false;
        }
    }
    function display($utility, $twig, $todo, $userId) {
        if($todo){
            echo $twig->render('listTodos.html.twig', array(
                'todos' => $utility->getTodos($userId),
            ));
        }else{
            echo $twig->render('listActivities.html.twig', array(
               'currentDay' => $_SESSION['currentDay'],
               'clients' => $utility->getClients($userId),
               'activityTypes' => $utility->getActivityTypes($userId),
               'activities' => $utility->getActivities($userId),
               'worktimeToday' => $utility->getToDayWorktime($userId)
           ));
        }
    }
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'start_activity':
                $utility->start_activity($_SESSION['USERID']);
                echo "start_activity";
                break;
            case 'restart_activity':
                $utility->restart_activity($_GET['activityId'], $_SESSION['USERID']);
                echo "restart_activity";
                break;
            case 'todo_activity':
                $utility->todo_activity($_GET['activityId'], $_SESSION['USERID']);
                echo "todo_activity";
                break;
            case 'end_run':
                $utility->end_activity($_SESSION['USERID']);
                echo "end_run";
                break;
            case 'delete_activity':
                $utility->delete_activity($_GET['activityId']);
                echo "delete activity";
                break;
            case 'new_client':
                echo 'new_client';
                $utility->newClient($_SESSION['USERID'], $_GET['name'], $_GET['ref1'], $_GET['ref2']);
                break;
            case 'new_activitytype':
                echo 'new_activitytype';
                $utility->newActivityType($_SESSION['USERID'], $_GET['name'], $_GET['color']);
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
                $field = $_GET['field'];
                $functionName = 'change_'.$field.'_activity';
                $utility->$functionName($_GET['activityId'], $_GET['newValue']);
                $todo = false;
                if($_GET['todo'] == 'true'){
                    $todo = true;
                }
                display($utility, $twig, $todo, $userId);
                break;
            case 'submit':
                $utility->receiveNewActivity(1, $_SESSION['USERID']);
                $userId = $_SESSION['USERID'];
                display($utility, $twig, true, $userId);
                break;
            case 'submitAndBegin':
                $utility->receiveNewActivity(0, $_SESSION['USERID']);
                $userId = $_SESSION['USERID'];
                display($utility, $twig, false, $userId);
                break;
            case 'get_clients':
                echo json_encode($utility->getClients($_SESSION['USERID']));
                break;
            case 'get_activityTypes':
                echo json_encode($utility->getActivityTypes($_SESSION['USERID']));
                break;
            case 'change_day':
                $_SESSION['currentDay'] = date("Y-m-d", strtotime($_GET['newDay']));;
                break;
            case 'get_csv':
                return($utility->generateCSV($_SESSION['USERID']));
                break;
            case 'logout':
                $utility->logout();
                return true;
                break;
            case 'loginFb':
                $utility->loginFb($_GET['signed_request'], $_GET['user_name'], $_GET['user_id'], $parameters);
                return true;
                break;
            default:
                echo 'requete ajax non suppportée';
                break;
        }
    }
?>