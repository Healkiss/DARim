$(document).ready(function() {
    var editType = '';
    $(document).on('click', '.editable', function() {
        $('.edit_block').remove();
        $('.editable').show();
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
            var selectPicker = false;
            var slider = false;
            var addedStyle = '';
            var tag="input";
            switch($(self).data('name')) {
                case 'clients':
                    input_edit = displaySelect('get_clients', ['client_name', 'client_ref2', 'client_ref'], 'client_id', true, false);
                    selectPicker = true;
                    btnEditNeeded = false;
                    break;
                case 'activityTypes':
                    input_edit = displaySelect('get_activityTypes', ['activityType_name'], 'activityType_id', false, true);
                    addedStyle = "float:right;";
                    selectPicker = true;
                    btnEditNeeded = false;
                    break;
                case 'commentary':
                    addedStyle = "width:auto;";
                    tag = "textarea";
                    break;
                case 'timeRange':
                    input_edit = constructTimeRange();
                    slider = true;
                    break;
                default:
                    break;
            }
            if(input_edit === '') {
                var input_edit = "<"+tag+" class='edit_input' type='"+type+"' style='position:relative;height:40px;"+addedStyle+"'></"+tag+">";
            }
            var btn_edit = "<button class='btn btn-info btn_edit_activity' style='z-index:999;position:absolute;width:40px;height:40px'><i class='glyphicon glyphicon-pencil'></i></button>"
            $input_edit = $(input_edit);
            $btn_edit = $(btn_edit);
            $input_edit.val(oldValue);

            var edition_block = "<span class='edit_block' style='position:relative;"+addedStyle+"'></span>";
            $edition_block = $(edition_block);
            $edition_block.insertAfter($(self));

            $edition_block.append($input_edit);
            if(btnEditNeeded) {
                $edition_block.append($btn_edit);
            }


            $(self).hide();
            $('.selectpicker').selectpicker({
                width: "auto"
            });        
            $input_edit.addClass('inEdition');
            $input_edit.data('field', field);
            $input_edit.focus().select();
            if(selectPicker){
                var selectpickerButton = $('button[data-id="selectEditing"]');
                $(selectpickerButton).trigger('click');
            }
            if(slider){
                var start = $(self).data('start');
                var starts = start.split(':');
                var end = $(self).data('end');
                var ends = end.split(':');
                console.log(end);
                $("#slider").ionRangeSlider({
                    type: "double",
                    hide_min_max: true,
                    drag_interval: true,
                    min: moment().set('hour', 9).set('minute', 0).format("X"),
                    max: moment().set('hour', 18).set('minute', 0).format("X"),
                    from: moment().set('hour', starts[0]).set('minute', starts[1]).format("X"),
                    to: moment().set('hour', ends[0]).set('minute', ends[1]).format("X"),
                    grid: true,
                    //force_edges: true,
                    prettify: function (num) {
                        var m = moment(num, "X").locale("fr");
                        return m.format("HH:mm");
                    }
                });
            }
        }, 10);
    });
    
    $(document).on('click', '.btn_edit_activity', function() {
        edit_activity($(this).siblings('.edit_input'));
    });
    $(document).on('keypress','tr', function(e) {
        var key = e.which;
        if(key == 13) {
            edit_activity($(this).find('.edit_input'));
        }
    });
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 27) {
            $('.edit_block').remove();
            $('.editable').show();
        }
    });
    $(document).on('change', 'select.inEdition', function() {
        edit_activity(this);
    });

    function displaySelect(ajaxAction, toDisplay, columnId, search, label) {
        ajaxAction = ajaxAction || 'get_activityTypes';
        toDisplay = toDisplay || ['name'];
        columnId = columnId || false;
        search = search || false;
        label = label || false;
        input_edit = '';
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            async: false,
            data: {
                action:ajaxAction,
            },
            error: function() {
                alert('ko');
            },
            complete: function(data) {
                input_edit = constructSelect(data, ajaxAction, toDisplay, columnId, search, label);
            }
        });
        return input_edit;
    }
    //TODO : passer les valuers a afficher, passer les valeurs des datas
    function constructSelect(response, ajaxAction, toDisplay, columnId, search, label) {
        lines = JSON.parse(response.responseText);
        input_edit = '<select id="selectEditing" class="selectpicker selectEditing" title="Client" data-style="btn-default" data-live-search="'+search+'" data-width="auto">';
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
                dataContent += 'data-content="<span class=\'label label-'+line['activityType_color']+'\'>'+content+'</span>"';
            }
            input_edit += '<option value="'+line[columnId]+'" '+dataContent+'>'+content+'</option>';
        };
        input_edit += '</select>';
        return input_edit;
    }

    function constructTimeRange(timeStart, timeEnd) {
        timeStart = timeStart || 540;
        timeEnd = timeEnd || 1080;/*
        input_edit = '<select id="selectEditing" class="selectpicker selectEditing" title="Client" data-style="btn-default" data-live-search="'+search+'" data-width="auto">';
        input_edit += '</select>';*/
        var input_edit = '<div id="slider" style="position:relative;width:120px;"></div>';
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

    function editField(elem, activityId, field, newValue) {
        console.log($(elem));
        console.log(elem);
        if($('.edit_block').parent('td').parent('tr').hasClass('dailyRow')){
            $('#diary tbody').css({'opacity': '0.4','filter': 'alpha(opacity=40)'});
            $('#diary').html($('#diary').html()+'<img src="public/img/giphy.gif" class="reload">');
        }else{
            $('#listActivity tbody').css({'opacity': '0.4','filter': 'alpha(opacity=40)'});
            $('#listActivity').html($('#listActivity').html()+'<img src="public/img/giphy.gif" class="reload">');
        }
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:'edit_activity',
                activityId:activityId,
                field:field,
                newValue:newValue
            },
            error: function() {
                alert('editField ko');
            },
            complete: function() {
                $('.btn_etat').remove();
                location.reload();
            }
        });
    }

    function edit_bounds_activity(elem) {
        var activityId = getActivityId(elem);
        var value = getActivityValue(elem);
        var changeStart = $(elem).hasClass('change_start_time');
        var field = changeStart?'start_time':'end_time';
        var oldTime = changeStart?$(elem).parent('.dailyRow').data('start'):$(elem).parent('.dailyRow').data('end');
        var newTime = oldTime.replace(/\s[0-9]{2}:[0-9]{2}/,' '+value);
        console.log('editer '+ field + ' ' + activityId + ' oldTime ' + oldTime +' newTime ' +newTime);
        $('.btn_etat').html('<img src="http://static.devatics.com/d590/img/spinner.gif"/>');
        editField(elem, activityId, field, newTime);
    }

    function edit_activity(elem) {
        var activityId = getActivityId(elem);
        var field = getField(elem);
        var newValue = getActivityValue(elem);
        editField(elem, activityId, field, newValue);
    }
});