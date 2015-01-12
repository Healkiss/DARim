$(document).ready(function() {
    $('#edit_activitytype').change(function() {
        $selected = $(this).find(':selected');
        $('#edit_activitytypename').val($selected.data('name')) ;
        $('#edit_activitytypecolor').selectpicker('val', $selected.data('color'));
    });
    $('#edit_client').change(function() {
        $selected = $(this).find(':selected');
        $('#edit_clientname').val($selected.data('name')) ;
        $('#edit_clientref1').val($selected.data('ref1')) ;
        $('#edit_clientref2').val($selected.data('ref2')) ;
    });
    $('#addClient').click(function() {
        var type = $(this).data('type');
        var name = $('#new_clientname').val();
        var ref1 = $('#new_clientref1').val();
        var ref2 = $('#new_clientref2').val();
        action({'action': 'new_client', 'name': name, 'ref1' :ref1, 'ref2' : ref2});
    });
    $('#addActivityType').click(function() {
        var type = $(this).data('type');
        var name = $('#new_activitytypename').val();
        var color = $('#new_activitytypecolor').val();
        action({'action': 'new_activitytype','name': name, 'color' :color});
    });
    $('#editClient').click(function() {
        var id = $('#edit_client').val();
        var name = $('#edit_clientname').val();
        var ref1 = $('#edit_clientref1').val();
        var ref2 = $('#edit_clientref2').val();
        action({'action': 'edit_client', 'id' : id, 'name': name, 'ref1' :ref1, 'ref2' : ref2});
    });
    $('#editActivityType').click(function() {
        var id = $('#edit_activitytype').val();
        var name = $('#edit_activitytypename').val();
        var color = $('#edit_activitytypecolor').val();
        action({'action': 'edit_activitytype', 'id' : id, 'name': name, 'color' :color});
    });

    function action(data) {
        $.ajax({
            url: '//localhost/DARim/src/classes/ajaxActions.php',
            type: 'GET',
            data: data
            ,
            error: function() {
                alert('ko');
            },
            complete: function() {
                location.reload();
            }
        });
    }

    $('#edit_client').change();
    $('#edit_activitytype').change();
    $('#return').click(function(){
        window.location.href = "/";
    })
});