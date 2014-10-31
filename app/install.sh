#!/bin/bash
function file_exists { 
    if [ ! -f $1 ]
    then
        echo $1 "is missing." 
        while true; do
            read -p "Do you want to copy the default file ?(yes or no)" yn
            case $yn in
                [Yy]* ) cp $1.dist $1; break;;
                [Nn]* ) exit 1;;
                * ) echo "Please answer yes or no.";;
            esac
        done
        echo ''
    fi
}
function disp { 
    echo "$" "$@"  
    echo ''
    echo ''
}
function ex { 
    echo "$" "$@"  
    $@
    echo ''
    echo ''
}  
# check for configuration files
disp 'Check configuration files'
file_exists "config/parameters.yml"

php config/install.php

echo 'Instalation terminé'
