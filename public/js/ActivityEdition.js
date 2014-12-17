$(document).ready(function() {
    var editType = '';
    $('.editable').on('click', function() {
        var self = this;
        var oldValue = 'err';
        var type = $(this).data('type');
        var oldValue = $(this).data('value');
        var field = $(this).data('field');
        var value = 'err';
        var input_edit = '';
        var btnEditNeeded = true;
        console.log($(this).data('type'));
        switch($(this).data('name')) {
            case 'clients':
                input_edit = displaySelect('get_clients', ['name', 'ref2', 'ref'], {'name' : 'clients'}, true, false);
                btnEditNeeded = false;
                break;
            case 'activityTypes':
                input_edit = displaySelect('get_activityTypes', ['name'], {'name' : 'activityTypes'}, false, true);
                btnEditNeeded = false;
                break;
            default:
                break;
        }
        if(input_edit === '') {
            var input_edit = "<input class='change_client_input edit_input edit_box' type='"+type+"' style=''>";
        }
        var btn_edit = "<button class='btn btn-info btn_edit_activity edit_box' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>"
        $(input_edit).val(oldValue);
        $(input_edit).insertAfter($(this));
        $(input_edit).data('field', field);
        $(input_edit).addClass('inEdition');
        if(btnEditNeeded)
            $(btn_edit).insertAfter($(this));
        $(this).hide();
        $('.selectpicker').selectpicker({
            width: "auto"
        });
        $(input_edit).focus().select();
    });
    $(document).on('click', ':not(.edit_box)', function() {
        console.log('click');
        //console.log(this);
        $('.edit_box').remove();
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
    $('.dailyRow').on('change', '#input-edit-activityTypes', function() {
        console.log($(this).val());
        edit_client_activity(this);
    });

    function displaySelect(ajaxAction, toDisplay, datas, search, label) {
        ajaxAction = ajaxAction || 'get_activityTypes';
        toDisplay = toDisplay || ['name'];
        datas = datas || {'name' : 'activityTypes'};
        search = search || false;
        label = label || false;
        input_edit = '';
        console.log('displaySelect');
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            async: false,
            data: {
                action:ajaxAction,
            },
            error: function() {
                alert('ko');
            },
            complete: function(data) {
                input_edit = constructSelect(data, ajaxAction, toDisplay, datas, search, label);
            }
        });
        return input_edit;
    }
    //TODO : passer les valuers a afficher, passer les valeurs des datas
    function constructSelect(response, ajaxAction, toDisplay, datas, search, label) {
        lines = JSON.parse(response.responseText);
        input_edit = '<select class="selectpicker edit_box" id="input-edit-'+datas['name']+'" name="'+datas['name']+'" title="Client" data-style="btn-default" data-live-search="'+search+'" data-width="auto">';
        for(var i = 0; i < lines.length; ++i) {
            line = lines[i];
            var content = '';
            for(var j = 0; j < toDisplay.length; ++j) {
                /*console.log('j ' + j);
                console.log('toDisplay[j] ' + toDisplay[j]);
                console.log('line[toDisplay[j]] ' + line[toDisplay[j]]);*/
                content += ' ' + line[toDisplay[j]];
            }
            var dataContent = '';
            if(label){
                dataContent += 'data-content="<span class=\'label label-'+line['color']+'\'>'+content+'</span>"';
            }
            input_edit += '<option value="'+line['id']+'" '+dataContent+'>'+content+'</option>';
        };
        input_edit += '</select>';
        return input_edit;
    }

    function getActivityId(elem) {
        return $(elem).parent('td').parent('tr').data('activityid');
    }

    function getActivityValue(elem) {
        return $(elem).val();
    }

    function getField(elem) {
        return $(elem).data('field');
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
        console.log('activityId' + activityId);
        var field = getField(elem);
        var newValue = getActivityValue(elem);
        //var changeStart = $(elem).hasClass('change_start_time');
        /*var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://localhost/d590/img/spinner.gif"/>');*/
        //var newValue = 
        editField(activityId, field, newValue);
    }
});