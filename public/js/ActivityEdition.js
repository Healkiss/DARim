$(document).ready(function() {
    var editType = '';
    $('.editable').on('click', function() {
        var self = this;
        var oldValue = 'err';
        var type = $(this).data('type');
        var oldValue = $(this).data('value');
        var value = 'err';
        var input_edit = '';
        console.log($(this).data('type'));
        switch($(this).data('name')) {
            case 'clients':
                $.ajax({
                    url: '//localhost/DARim/src/classes/ajaxActions.php',
                    async: false,
                    data: {
                        action:'get_clients',
                    },
                    error: function() {
                        alert('ko');
                    },
                    complete: function(data) {
                        constructSelectPicker(data);
                    }
                });
                function constructSelectPicker(clients) {
                    clients = JSON.parse(clients.responseText);
                    console.log(clients);
                    input_edit += '<select class="selectpicker" id="input-edit-client" name="client" title="Client" data-style="btn-default" data-live-search="true">';
                    for(var i = 0; i < clients.length; ++i) {
                        client = clients[i];
                        input_edit += '<option value="'+client['id']+'">'+client['name']+'('+client['ref2']+'"-'+client['ref']+')</option>';
                    };
                    input_edit += '</select>';
                }
                break;
            case 'activityTypes':
                input_edit = displaySelect();
                break;
            default:
                break;
        }
        if(input_edit === '') {
            var input_edit = "<input class='btn_etat change_client_input edit_input' type='"+type+"' style='position:absolute;right:86px;'>";
        }
        var btn_edit = "<button class='btn_etat btn btn-info btn_edit_activity' style='position:absolute;right:16px;width:70px;height:40px'>Editer</button>"
        $(input_edit).val(oldValue);
        // $(this).parent('td').parent('.dailyRow').append($(input_edit));
        $(this).removeClass('editable');
        $(this).html($(input_edit));
        $('.selectpicker').selectpicker({
            size: false
        });
        // $(this).parent('td').parent('.dailyRow').append($(btn_edit));
        //$(this).html($(this).html($(btn_edit)));
        $(input_edit).focus();
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

    function displaySelect(ajaxAction, toDisplay, datas, search) {
        ajaxAction = ajaxAction || 'get_activityTypes';
        toDisplay = toDisplay || ['name'];
        datas = datas || {'name' : 'activityTypes'};
        search = search || false;
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
                input_edit = constructSelect(data, ajaxAction, toDisplay, datas, search);
            }
        });
        return input_edit;
    }
    //TODO : passer les valuers a afficher, passer les valeurs des datas
    function constructSelect(response, ajaxAction, toDisplay, datas, search) {
        lines = JSON.parse(response.responseText);
        input_edit = '<select class="selectpicker" id="input-edit-'+datas['name']+'" name="'+datas['name']+'" title="Client" data-style="btn-default" data-live-search="'+datas['name']+'">';
        for(var i = 0; i < lines.length; ++i) {
            line = lines[i];
            input_edit += '<option value="'+line['id']+'">';
            for(var j = 0; j < toDisplay.length; ++j) {
                line[toDisplay[j]]
            }
            input_edit +='</option>';
        };
        input_edit += '</select>';
        return input_edit;
    }
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
        /*var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://localhost/d590/img/spinner.gif"/>');*/
        var newValue = 
        editField(activityId, field, newValue);
    }
});