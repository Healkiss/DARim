$(document).ready(function() {
    //boundtime
    
    var old = 0;
    $('.timebound').each(function( index ) {
        var value = parseInt($( this ).data('value').replace(':',''));
        //console.log('compare : ' + value + ' to ' + old);
        if(value < old) {
            // $( this ).css({'color':'red'});
            // $( oldThis ).css({'color':'red'})
            $( this ).addClass('error');
            $( oldThis ).addClass('error');
        }
        old = parseInt($( this ).data('value').replace(':',''));
        oldThis = this;
    });
    //TODOTABLE
    $(document).on('click','.btn_start_activity',function() {
        activityId = $(this).parent('td').parent('.todoRow').data('activityid');
        actionEdit({'action':'start_activity', 'activityId':activityId});
    });
    //ACTIVITYTABLE
    $(document).on('click','.btn_restart_activity',function() {
        activityId = $(this).parent('td').parent('.dailyRow').data('activityid');
        actionEdit({'action':'restart_activity', 'activityId':activityId});
    });
    $(document).on('click','.btn_end_activity',function() {
        activityId = $(this).parent('td').parent('tr').data('activityid');
        actionEdit({'action':'end_run'});
    });
    $(document).on('click','.btn_todo_activity',function() {
        activityId = $(this).parent('td').parent('.dailyRow').data('activityid');
        actionEdit({'action':'todo_activity', 'activityId':activityId});
    });
    $(document).on('click','.btn_predelete_activity', function() {
        $('#modalDelete').modal()
        console.log($(this).data('activityid'));
        $('#btn_delete_activity').data('activityid', $(this).data('activityid'))
        // activityId = $(this).parent('td').parent('.activity').data('activityid');
        // actionEdit({'action':'delete_activity', 'activityId':activityId});
    });
    $(document).on('click','#btn_delete_activity',function() {
        activityId = $(this).data('activityid');
        actionEdit({'action':'delete_activity', 'activityId':activityId});
    });

    $(document).on('click','#admin',function(){
        window.location.href = "admin";
    });
    $(document).on('click','#logout',function(){
        $.ajax({url: 'src/classes/ajaxActions.php',data: {'action':'logout'}});
        window.location.href = "login";
    });
    ///////////////////
    //DATE MANIPULATION
    ///////////////////
    $(document).on('click','.preChangeDay',function() {
        var inputDate = '<input class="changeDay" type="date"></input>';
        $inputDate = $(inputDate);
        $inputDate.insertAfter($(this));
        console.log($(this).data('day'));
        $inputDate.val($(this).data('day'));
        console.log($inputDate.val());
        $(this).hide();
        $inputDate.focus().select();
    });
    $(document).on('click','.changeDay',function() {
        console.log($(this).val());
        change_day($(this).val());
    });
    $(document).on({
        mouseenter: function() {
            $('<span class="glyphicon glyphicon-pencil editicon" style="color:#0088cc;font-size :20px;margin-left:-20px;margin-right:-7px;"></span>').insertAfter($(this));
        },
        mouseleave: function() {
            $('.editicon').remove();
        }
    }, '.preChangeDay');
    function goToYesterday() {
        console.log(new Date($('.preChangeDay').data('day')));
        console.log(new Date(new Date($('.preChangeDay').data('day')).getTime()));
        console.log(new Date(new Date($('.preChangeDay').data('day')).getTime() - 24 * 60 * 60 * 1000));
        change_day(new Date(new Date($('.preChangeDay').data('day')).getTime() - 24 * 60 * 60 * 1000));
    }
    function goToTomorrow() {
        change_day(new Date(new Date($('.preChangeDay').data('day')).getTime() + 24 * 60 * 60 * 1000));
    }
    function goToToday() {
        change_day(new Date());
    }
    $(document).on('click','#changeDayBefore', function(){goToYesterday();});
    $(document).on('click','#changeDayAfter', function(){goToTomorrow();});
    $(document).on('click','#changeDayToday', function(){goToToday();});
    function change_day(newDay){
        data = {'action':'change_day', 'newDay':newDay};
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('change_day ko');
            },
            complete: function() {
                location.reload();
            }
        });
    }
    ///////////////////////
    //SUBMISSION & DELETION
    ///////////////////////
    function actionEdit(data) {
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('');
            },
            complete: function(response) {
                location.reload();
            }
        });
    }
    $(document).on('click','btn_delete_activity', function() {
        var $divBottom = $(this).parent('td').parent('tr');
        var $divOverlay = $('#divOverlay');
        var bottomTop = $divBottom.attr('offsetTop');
        var bottomLeft = $divBottom.attr('offsetLeft');
        var bottomWidth = $divBottom.css('width');
        var bottomHeight = $divBottom.css('height');
        var rowPos = $divBottom.position();
        bottomTop = rowPos.top;
        bottomLeft = rowPos.left;
        //
        $divOverlay.css({
            position: 'absolute',
            top: bottomTop,
            left: bottomLeft,
            width: bottomWidth,
            height: bottomHeight
        });

        $('#info').text('Top: ' + bottomTop + ' Left: ' + bottomLeft);
    });
    var submitButtonPressed;
    $(document).on("click", ".submit", function (event) {
          buttonpressed = $(this).val()
    })
    $(document).on("click", ".submit", function (event) {

        var todo = false;
        if(buttonpressed != 'submit'){
            $('#diary tbody').addClass('reloading');
            $('#diary').html($('#diary').html()+'<img src="public/img/giphy.gif" class="reload">');
        }else{
            $('#listActivity tbody').addClass('reloading');
            $('#listActivity').html($('#listActivity').html()+'<img src="public/img/giphy.gif" class="reload">');
            todo = true;
        }
        var self = this;
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                action:         buttonpressed,
                client:         $("#form-input-client").val(),
                activityType:   $("#form-input-activityType").val(),
                task:           $("#form-input-task").val(),
                comment:        $("#form-input-comment").val()
            },
            dataType: 'html',
            error: function() {
                alert('submit form ko');
            },
            success: function(data) {
                $('.reloading').removeClass('reloading');
                $('.reload').remove();
                if(buttonpressed != 'submit'){
                    $('#diary').html(data);
                }else{
                    $('#listActivity').html(data);
                }
            }
        });
        event.preventDefault();
    })
    $('.selectpicker').selectpicker();
    ///////////////////
    // EXPORTS
    ///////////////////
    function getActivities(element, gettime) {
        var darObject = {};
        $(element).each(function(index, obj) {
            var clientName = $(obj).find('.activityClientName').text().trim();
            var task = $(obj).find('.activityTask').text().trim();
            // console.log('clientName : ' + clientName);
            // console.log('task : ' + task);
            var comment = $(obj).find('.activityComment').text().trim();
            if(task == 'Ajouter une tache'){
                task = '(...)';
            }
            if(comment == 'Ecrire un commentaire'){
                comment = '(...)';
            }
            var minutesSpend = 0;
            if(gettime){
                var timeSpend = $(obj).find('.activitytimeSpend').text().trim();
                if(timeSpend){
                    minutesSpend = parseInt(timeSpend.split('h')[0])*60 + parseInt(timeSpend.split('h')[1]);
                    // console.log("hour" + parseInt(timeSpend.split('h')[0]));
                    // console.log("minutes" + timeSpend.split('h')[1]);
                    // console.log("minutesSpend" + minutesSpend);
                }
            }
            if(!darObject.hasOwnProperty(clientName)){
                darObject[clientName] = {};
            }
            if(!darObject[clientName].hasOwnProperty(task)){
                darObject[clientName][task] = {};
            }
            if(!darObject[clientName][task].hasOwnProperty(comment)){
                darObject[clientName][task][comment] = minutesSpend;
                // console.log(darObject[clientName][task][comment]);
            }else{
                darObject[clientName][task][comment] += minutesSpend;
                // console.log(darObject[clientName][task][comment]);
            }
        });
        return darObject;
    }
    function copy() {
        var darObject = getActivities('.dailyRow', 1);

        var dar = 'Aujourd\'hui';
        for (var client in darObject) {
            dar += ' \n' + client;
            for (var task in darObject[client]) {
                dar += '\n\t- ' +task;
                for (var comment in darObject[client][task]) {
                    dar += '\n\t\t- ' + comment;
                    //console.log('minutesSpend' + darObject[client][task][comment]);
                    //console.log('minutesSpend '+ parseInt(darObject[client][task][comment]) + 'modulo ' + Math.floor(parseInt(darObject[client][task][comment])/90));
                    var modulo = Math.floor(parseInt(darObject[client][task][comment])/90);
                    for(var i = 0; i < modulo; i++){
                        dar += ' (*)';
                    }
                }
            }
        }                    
        darObject = getActivities('.todoRow', 0);
        dar += '\n\nDemain \n';
        for (var client in darObject) {
            dar += ' \n' + client;
            for (var task in darObject[client]) {
                dar += '\n\t- ' +task;
                for (var comment in darObject[client][task]) {
                    dar += '\n\t\t- ' + comment;
                }
            }
        }
        console.log('DAR copied. Paste it now!');
        $('#info-alert').show();
        $('#info-alert').text('DAR copied. Paste it now!');
        $('#info-alert').fadeOut( "slow", function() {});
        return dar;
    }
    $('button#copy').on('click', function(e) {
            e.preventDefault();
        });
    $('button#copy').clipboard({
        path: 'public/js/jquery.clipboard.swf',
        copy: function() {
            return copy();
        }
    });
    function exportCSV() {
        var data = {'action':'get_csv'};
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('change_day ko');
            },
            complete: function() {
                console.log('CSV exported!');
                $('#info-alert').show();
                $('#info-alert').text('CSV exported!');
                $('#info-alert').fadeOut( "slow", function() {});
            }
        });
    }
    $('button#export').click(function(){
        exportCSV();
    });
    if($('.error').length > 0){
        $('#export').prop('disabled', true);
    }else{

    }
    ///////////////////
    //RACCOURCIS
    ///////////////////
    //Enter 
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 13) {
            if($('.changeDay').val())
                change_day($('.changeDay').val());
        }
    });
    //Escape 
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 27) {
            $('.changeDay').remove();
            $('.preChangeDay').show();
        }
    });
    //Shift + / commencer ajout activité
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 191 && e.shiftKey && tag != 'input' && tag != 'textarea') {
            $('[data-id="form-input-client"]').focus().select();
        }
    });
    //Ctrl + fleche droite aujourd'hui
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 39 && e.ctrlKey && tag != 'input' && tag != 'textarea') {
            goToToday();
        }
    });
    //Ctrl + fleche haute jour suivant
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 38 && e.ctrlKey && tag != 'input' && tag != 'textarea') {
            goToTomorrow();
        }
    });
    //Ctrl + fleche basse precedent
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 40 && e.ctrlKey && tag != 'input' && tag != 'textarea') {
            goToYesterday();
        }
    });
    //Shift + I Stopper l'activité en cours
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 73 && e.shiftKey && tag != 'input' && tag != 'textarea') {
            actionEdit({'action':'end_run'});
        }
    });
    //Ctrl + Enter Start activity
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 13 && e.ctrlKey) {
            $('#submitAndStart').click();
        }
    });
    //Alt + Enter Todo activity
    $(document).keyup(function(e) {
        var tag = e.target.tagName.toLowerCase();
        var key = e.which;
        if(key == 13 && e.altKey) {
           $('#submitTodo').click();
        }
    });
    //alt + C copy DAR on clipboard
    /*$(document).keyup(function(e) {
        var key = e.which;
        if(key == 67 && e.shiftKey) {
            console.log('test copy');
            $('button#copy').trigger('clipboard');
            $('#copy').focus().select().click();
        }
    });*/
    //alt + C export CSV
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 69 && e.shiftKey) {
            exportCSV();
        }
    });
});