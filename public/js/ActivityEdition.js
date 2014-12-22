$(document).ready(function() {
    var editType = '';
    $('.editable').on('click', function() {
        var self = this;
        setTimeout(function() 
        {
            $('.btn_etat').remove();
            $('.edit_box').remove();
            $('.editable').show();
            var type = $(self).data('type');
            var oldValue = $(self).data('value');
            var field = $(self).data('field');
            var input_edit = '';
            var btnEditNeeded = true;
            switch($(self).data('name')) {
                case 'clients':
                    input_edit = displaySelect('get_clients', ['name', 'ref2', 'ref'],  "margin-left:40px;", true, false);
                    btnEditNeeded = false;
                    break;
                case 'activityTypes':
                    input_edit = displaySelect('get_activityTypes', ['name'], "float:right;margin-right:20px;", false, true);
                    btnEditNeeded = false;
                    break;
                default:
                    break;
            }
            if(input_edit === '') {
                var input_edit = "<input class='edit_input' type='"+type+"' style='position:relative;height:40px;'>";
            }
            var btn_edit = "<button class='btn btn-info btn_edit_activity' style='position:absolute;width:70px;height:40px'>Editer</button>"
            $input_edit = $(input_edit);
            $btn_edit = $(btn_edit);
            $input_edit.val(oldValue);

            var edition_block = "<span class='edit_block' style='position:relative;'></span>";
            $edition_block = $(edition_block);
            $edition_block.insertAfter($(self));

            $edition_block.append($input_edit);
            //$input_edit.insertAfter($(self));
            if(btnEditNeeded) {
                // $btn_edit.insertAfter($input_edit);
                $edition_block.append($btn_edit);
            }


            $(self).hide();
            $('.selectpicker').selectpicker({
                width: "auto"
            });        
            $input_edit.addClass('inEdition');
            $input_edit.data('field', field);
            $input_edit.focus().select();
        }, 10);
    });
    $(document).on('click', ':not(.edit_block *)', function() {
        //console.log('click');
        //console.log(this);
        $('.edit_block').remove();
        $('.editable').show();
        //$('.edit_box').remove();
    });

    $('.dailyRow').on('click', '.btn_edit_activity', function() {
        edit_activity($(this).siblings('.edit_input'));
    });
    $('.dailyRow').on('keypress', '.btn_edit_activity', function(e) {
        var key = e.which;
        if(key == 13) {
            edit_activity(this);
        }
    });
    $('.dailyRow').on('change', 'select.inEdition', function() {
        edit_activity(this);
    });

    function displaySelect(ajaxAction, toDisplay, specStyle, search, label) {
        ajaxAction = ajaxAction || 'get_activityTypes';
        toDisplay = toDisplay || ['name'];
        specStyle = specStyle || '';
        search = search || false;
        label = label || false;
        input_edit = '';
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
                input_edit = constructSelect(data, ajaxAction, toDisplay, specStyle, search, label);
            }
        });
        return input_edit;
    }
    //TODO : passer les valuers a afficher, passer les valeurs des datas
    function constructSelect(response, ajaxAction, toDisplay, specStyle, search, label) {
        lines = JSON.parse(response.responseText);
        input_edit = '<select class="selectpicker" title="Client" data-style="btn-default" data-live-search="'+search+'" data-width="auto" style="'+specStyle+'">';
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
        return $(elem).parent('span').parent('td').parent('tr').data('activityid');
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

    /*function edit_bound_activity(elem) {
        var activityId = getActivityId(elem);
        var value = getActivityValue(elem);
        var changeStart = $(elem).hasClass('change_start_time');
        var field = changeStart?'start_time':'end_time';
        var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://localhost/d590/img/spinner.gif"/>');
        editField(activityId, field, newTime);
    }*/

    function edit_activity(elem) {
        var activityId = getActivityId(elem);
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