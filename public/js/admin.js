$(document).ready(function() {
    $('.addClient').click(function() {
        var type = $(this).data('type');
        var name = $('#new_clientname').val();
        var ref1 = $('#new_clientref1').val();
        var ref2 = $('#new_clientref2').val();
        action({'action': 'new_client', 'name': name, 'ref1' :ref1, 'ref2' : ref2});
    });
    $('.addActivityType').click(function() {
        var type = $(this).data('type');
        var name = $('#new_activitytypename').val();
        var color = $('#new_activitytypecolor').val();
        action({'action': 'new_activitytype','name': name, 'color' :color});
    });
    $('.editClient').click(function() {
        var type = $(this).data('type');
        var name = $('#new_clientname').val();
        var ref1 = $('#new_clientref1').val();
        var ref2 = $('#new_clientref2').val();
        action({'action': 'edit_client','name': name, 'ref1' :ref1, 'ref2' : ref2});
    });
    $('.editActivityType').click(function() {
        var type = $(this).data('type');
        var name = $('#new_activitytypename').val();
        var color = $('#new_activitytypecolor').val();
        action({'action': 'edit_ActivityType', 'name': name, 'color' :color});
    });

    function action(action, data) {
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: {
                data
            },
            error: function() {
                alert('ko');
            },
            complete: function() {
                //location.reload();
            }
        });
    }
});