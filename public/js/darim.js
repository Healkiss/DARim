$(document).ready(function() {
    buttonDisplayed = false; //avoid multiple add elemnts
    $('.todoRow').on('mouseover', function() {
        if(!buttonDisplayed) {
            buttonDisplayed = true;
            var btn_etat = '';
            btn_etat += "<button class='btn_etat btn btn-success btn_start_activity' style='position:absolute;right:16px;'>Demarrer</button>";
            btn_etat += "<button class='btn_etat btn btn-info btn_edit_activity' style='position:absolute;left:16px;'>Editer</button>";
            var $btnEtat = $(btn_etat);
            $(this).append($btnEtat);
        }
    });
    $('.todoRow').on('mouseleave', function() {
        buttonDisplayed = false;
        $('.btn_etat').remove();
    });
    $('.dailyRow').on('mouseover', function() {
        if(!buttonDisplayed) {
            buttonDisplayed = true;
            isFinish = $(this).data('isfinish');
            var btn_etat = '';
            if(!isFinish) {
                btn_etat += "<button class='btn_etat btn btn-warning btn_end_run' style='position:absolute;left:16px;'>Terminer</button>";
            }
            var $btnEtat = $(btn_etat);
            $(this).append($btnEtat);
        }
    });
    $('.dailyRow').on('mouseleave', function() {
        buttonDisplayed = false;
        $('.btn_etat').remove();
    });
    $('.timebound').on('mouseover', function() {
        $(this).css('cursor', 'pointer');
    });
    $('.timebound').on('click', function() {
        var changeStart = $(this).hasClass('startTime');
        var btn_etat = '';
        if(changeStart) {
            btn_etat += "<input class='change_time_input change_start_time edit_box' type='time' name='boundTime' style='position:absolute;right:86px;'>";
            btn_etat += "<button class='edit_box btn btn-info btn_edit_bound_activity btn_edit_start_activity' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>";
        } else{
            btn_etat += "<input class='change_time_input change_end_time edit_box' type='time' name='boundTime' style='position:absolute;right:86px;'>";
            btn_etat += "<button class='edit_box btn btn-info btn_edit_bound_activity btn_edit_end_activity' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>";
        }
        var $btnEtat = $(btn_etat);
        $(this).parent('td').parent('.dailyRow').append($btnEtat);
        var time = $(this).text().trim().replace('h',':');
        $btnEtat.val(time);
        $btnEtat.select();
        $btnEtat.select();
    });
    $(document).on('click', ':not(.edit_box)', function() {
       // $('.edit_box').remove();
    });
    function edit_bound_activity(elem) {
        var activityId = $(elem).parent('.dailyRow').data('activityid');
        var changeStart = $(elem).hasClass('change_start_time');
        var action = changeStart?'change_start_activity':'change_end_activity';
        var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        console.log('oldTime ' + oldTime );
        var value = $(elem).val();
        console.log('value ' + value );
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ action + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').remove();
        $.ajax({
            url: '//localhost/DARim/src/classes/ajax.php',
            type: 'GET',
            data: {
                action:action,
                activityId:activityId,
                newTime:newTime
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    }
    $('.dailyRow').on('click', '.btn_edit_bound_activity', function() {
        edit_bound_activity($(this).siblings('.change_time_input'));
    });
    $('.dailyRow').on('keypress', '.change_time_input', function(e) {
        var key = e.which;
        if(key == 13) {
            edit_bound_activity(this);
        }
    });
    $('.todoRow').on('click', '.btn_start_activity', function() {
        runRunning = $('.dailyRow:not([data-isfinish=1])').data('runid');
        activityId = $(this).parent('.todoRow').data('activityid');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajax.php',
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
    $('.dailyRow').on('click', '.btn_end_run', function() {
        runId = $(this).parent('.dailyRow').data('activityid');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajax.php',
            type: 'GET',
            data: {
                action:'end_run',
                runId:runId
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    });
});