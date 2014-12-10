$(document).ready(function() {
    $('.editable').on('click', function() {
        var self = this;
        var btn_etat = '';
        var oldValue = 'err';
        var type = $(this).data('type');
        var oldValue = 'err';
        var value = 'err';
        switch($(this).data('type')) {
            case 'time':
                var changeStart = ($(this).data('name') === 'start_time')?true:false;
                if(changeStart) {
                    btn_etat += "<input class='change_time_input change_start_time edit_box' type='time' name='boundTime' style='position:absolute;right:86px;'>";
                    btn_etat += "<button class='edit_box btn btn-info btn_edit_bound_activity btn_edit_activity' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>";
                } else{
                    btn_etat += "<input class='change_time_input change_end_time edit_box' type='time' name='boundTime' style='position:absolute;right:86px;'>";
                    btn_etat += "<button class='edit_box btn btn-info btn_edit_bound_activity btn_edit_activity' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>";
                }
                oldValue = $(self).text().trim().replace('h',':');
                break;
            default:
                break;
        }
        var $btnEtat = $(btn_etat);
        $btnEtat.val(oldValue);
        $(this).parent('td').parent('.dailyRow').append($btnEtat);
        $btnEtat.select();
    });
    $(document).on('click', ':not(.edit_box)', function() {
       // $('.edit_box').remove();
    });

    $('.dailyRow').on('click', '.btn_edit_activity', function() {
        edit_client_activity($(this).siblings('.change_client_input'));
    });
    $('.dailyRow').on('keypress', '.btn_edit_activity', function(e) {
        var key = e.which;
        if(key == 13) {
            edit_client_activity(this);
        }
    });

    function getActivityId(elem) {
        return $(elem).parent('.dailyRow').data('activityid');
    }

    function getActivityValue(elem) {
        return $(elem).val();;
    }

    function editField(activityId, field, newValue) {
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:'edit_activity',
                activityId:activityId,
                field:field,
                newValue:newValue
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                $('.btn_etat').remove();
                location.reload();
            }
        });
    }

    function edit_bound_activity(elem) {
        var activityId = getActivityId(elem);
        var value = getActivityValue(elem);
        var changeStart = $(elem).hasClass('change_start_time');
        var field = changeStart?'start_time':'end_time';
        var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://localhost/d590/img/spinner.gif"/>');
        editField(activityId, field, newTime);
    }
    function edit_client_activity(elem) {
        var activityId = getActivityId(elem);
        var value = getActivityValue(elem);
        var changeStart = $(elem).hasClass('change_start_time');
        var field = changeStart?'start_time':'end_time';
        var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://localhost/d590/img/spinner.gif"/>');
        editField(activityId, field, newTime);
    }
});