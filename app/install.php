<?php
    require_once dirname(__FILE__) . '/../library/Core.php';
    $core = new Core();
    createBdd($core);

    function createBdd($core){
        $db_host = $core->parameters['db_host'];
        $db_user = $core->parameters['db_user'];
        $db_password= $core->parameters['db_password'];
        $db_name = $core->parameters['db_name'];
        $conn = mysql_connect($db_host, $db_user, $db_password);
        if(! $conn )
        {
          die('Could not connect: ' . mysql_error() .'\n');
        }
        echo 'Connected successfully \n';
        $sql = 'CREATE Database '.$db_name.'';
        $retval = mysql_query( $sql, $conn );
        if(! $retval )
        {
          die('Could not create database: ' . mysql_error().'\n');
        }
        echo "Database test_db created successfully\n";
        mysql_close($conn);
    }
?>