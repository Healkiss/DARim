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
        $('.edit_box').remove();
        //show old value
        $('.editable').show();
    });
    $('.dailyRow').on('mouseover', function() {
        if(!buttonDisplayed) {
            buttonDisplayed = true;
            isFinish = $(this).data('isfinish');
            var btn_etat = '';
            if(!isFinish) {
                btn_etat += "<button class='btn_etat btn btn-danger btn_end_activity' style='position:absolute;left:15px;height:40px;'><i class='glyphicon glyphicon-stop'></i></button>";
            }else{
                btn_etat += "<button class='btn_etat btn btn-success btn_start_activity' style='position:absolute;left:15px;height:40px;'><i class='glyphicon glyphicon-play'></i></button>";
            }
            btn_etat += "<button class='btn_etat btn btn-danger btn_delete_activity' style='position:absolute;right:15px;height:40px;'><i class='glyphicon glyphicon-trash'></i></button>";
            var $btnEtat = $(btn_etat);
            $(this).append($btnEtat);
        }
    });
    $('.dailyRow').on('mouseleave', function() {
        buttonDisplayed = false;
        //hide button bin/stop/start
        $('.btn_etat').remove();
        //hide edit input and buttons
        $('.edit_box').remove();
        //show old value
        $('.editable').show();
    });
    
    $('.todoRow').on('click', '.btn_start_activity', function() {
        runRunning = $('.dailyRow:not([data-isfinish=1])').data('runid');
        activityId = $(this).parent('.todoRow').data('activityid');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:'start_activity',
                activityId:activityId,
                runId:runRunning
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    });
    $('.dailyRow').on('click', '.btn_end_activity', function() {
        activityId = $(this).parent('.dailyRow').data('activityid');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:'end_run',
                activityId:activityId
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    });
    $(document).on('click', '.btn_delete_activity', function() {
        activityId = $(this).parent('.activity').data('activityid');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:'delete_activity',
                activityId:activityId
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    });
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