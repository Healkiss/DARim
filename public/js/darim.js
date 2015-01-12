$(document).ready(function() {
    //boundtime
    var old = 0;
    $('.timebound').each(function( index ) {
        var value = parseInt($( this ).data('value').replace(':',''));
        //console.log('compare : ' + value + ' to ' + old);
        if(value < old) {
            /*$( this ).css({'color':'red'});
            $( oldThis ).css({'color':'red'})*/
            $( this ).addClass('error');
            $( oldThis ).addClass('error');
        }
        old = parseInt($( this ).data('value').replace(':',''));
        oldThis = this;
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
    $('.btn_todo_activity').click(function() {
        activityId = $(this).parent('td').parent('.dailyRow').data('activityid');
        action({'action':'todo_activity', 'activityId':activityId});
    });
    $('.btn_predelete_activity').click(function() {
        $('#modalDelete').modal()
        console.log($(this).data('activityid'));
        $('#btn_delete_activity').data('activityid', $(this).data('activityid'))
        /*
        activityId = $(this).parent('td').parent('.activity').data('activityid');
        action({'action':'delete_activity', 'activityId':activityId});
        */
    });
    $('#btn_delete_activity').click(function() {
        activityId = $(this).data('activityid');
        action({'action':'delete_activity', 'activityId':activityId});
    });
    $('.preChangeDay').click(function() {
        var inputDate = '<input class="changeDay" type="date"></input>';
        $inputDate = $(inputDate);
        $inputDate.insertAfter($(this))
        $inputDate.val($(this).data('day'));
        $(this).hide();
        $inputDate.focus().select();
    });
    $('.changeDay').click(function() {
        console.log($(this).val());
        change_day($(this).val());
    });
    $('.preChangeDay').hover(function(){$('<span class="glyphicon glyphicon-pencil editicon" style="color:#0088cc;font-size :20px;"></span>').insertAfter($(this));},function(){$('.editicon').remove();} );
    function change_day(newDay){
        data = {'action':'change_day', 'newDay':newDay};
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
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
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 13) {
            console.log($('.changeDay').val());
            if($('.changeDay').val())
                change_day($('.changeDay').val());
        }
    });
    $(document).keyup(function(e) {
        var key = e.which;
        if(key == 27) {
            $('.changeDay').remove();
            $('.preChangeDay').show();
        }
    });
    function action(data) {
        $.ajax({
            url: 'src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('');
            },
            complete: function() {
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
                alert('submit form ko');
            },
            complete: function() {
                console.log('ok');
                location.reload();
            }
        });
        event.preventDefault();
    })
});