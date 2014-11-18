$(document).ready(function() {
    $(".activityRow").on("mouseover", function() {
        activityId = $(this).data('activityid');
        var btn_etat = '';
        btn_etat += "<button class='btn_etat btn btn-success btn_start_activity' data-activityid="+activityId+" style='position:absolute;right:11px;'>Demarrer</button>";
        btn_etat += "<button class='btn_etat btn btn-info btn_edit_activity' data-activityid="+activityId+" style='position:absolute;left:16px;'>Editer</button>";
        var $btnEtat = $(btn_etat);
        $(this).append($btnEtat);
    });
    $(".activityRow").on("mouseleave", function() {
        $('.btn_etat').remove();
    });
    $(".dailyRow").on("mouseover", function() {
        isFinish = $(this).data('isfinish');
        runId = $(this).data('runid');
        var btn_etat = '';
        btn_etat += "<button class='btn_etat btn btn-info btn_edit_run' data-runid="+runId+" style='position:absolute;right:11px;'>Editer</button>";
        var $btnEtat = $(btn_etat);
        $(this).append($btnEtat);
    });
    $(".dailyRow").on("mouseleave", function() {
        $('.btn_etat').remove();
    });
    $(".btn_start_activity").on("click", function() {
        runRunning = $('.dailyRow:not([data-isfinish=1])').data('runid');
        activityId = $(this).data('activityid');
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
    $(".btn_end_run").on("click", function() {
        runId = $(this).data('runid');
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