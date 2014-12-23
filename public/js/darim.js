$(document).ready(function() {
    buttonDisplayed = false; //avoid multiple add elemnts
    $('.todoRow').on('mouseover', function() {
        if(!buttonDisplayed) {
            buttonDisplayed = true;
            var btn_etat = '';
            btn_etat += "<button class='btn_etat btn btn-success btn_start_activity' style='position:absolute;left:15px;height:40px;'><i class='glyphicon glyphicon-play'></i></button>";
            btn_etat += "<button class='btn_etat btn btn-danger btn_delete_activity' style='position:absolute;right:15px;'><i class='glyphicon glyphicon-trash'></i></button>";
            var $btnEtat = $(btn_etat);
            $(this).append($btnEtat);
        }
    });
    $('.todoRow').on('mouseleave', function() {
        buttonDisplayed = false;
        //hide button bin/stop/start
        $('.btn_etat').remove();
        //hide edit input and buttons
        //$('.edit_box').remove();
        //show old value
        $('.editable').show();
    });
    $('.dailyRow').on('mouseleave', function() {
        buttonDisplayed = false;
        //hide button bin/stop/start
        $('.btn_etat').remove();
        //hide edit input and buttons
        //$('.edit_box').remove();
        //show old value
        //$('.editable').show();
    });
    
    //TODOTABLE
    $('.btn_start_activity').click(function() {
        activityId = $(this).parent('td').parent('.todoRow').data('activityid');
        action({'action':'start_activity', 'activityId':activityId});
    });
    $('.btn_end_activity').click(function() {
        activityId = $(this).parent('td').parent('.todoRow').data('activityid');
        action({'action':'end_run', 'activityId':activityId});
    });
    //ACTIVITYTABLE
    $('.btn_restart_activity').click(function() {
        activityId = $(this).parent('td').parent('.dailyRow').data('activityid');
        action({'action':'restart_activity', 'activityId':activityId});
    });
    $('.btn_end_activity').click(function() {
        activityId = $(this).parent('td').parent('.dailyRow').data('activityid');
        action({'action':'end_run', 'activityId':activityId});
    });
    $('.btn_delete_activity').click(function() {
        activityId = $(this).parent('td').parent('.activity').data('activityid');
        action({'action':'delete_activity', 'activityId':activityId});
    });
    function action(data) {
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    }
    var submitButtonPressed;
    $('.submit').click(function() {
          buttonpressed = $(this).val()
    })
    $(document).on("click", ".submit", function (event) {
        var self = this;
        submitButtonPressed
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:         buttonpressed,
                client:         $("#form-input-client").val(),
                activityType:   $("#form-input-activityType").val(),
                task:           $("#form-input-task").val(),
                comment:        $("#form-input-comment").val()
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                console.log('ok');
                location.reload();
            }
        });
        event.preventDefault();
    })
});