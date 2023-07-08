function get_Notification() {
    return;
    $.ajax({
        url: 'ajax/get_notification_ajax.php',
        type: 'get',
        data: {
            'status': 1
        },
        success: function(response) {
            if (response != 0) {
                var array = JSON.parse(response);
                alert(array['title'] + '\n' + array['message']);
                updateNotification(array['id']);
            }
        }
    })
    setTimeout(function() { get_Notification(); }, 2000);
}

get_Notification();

function updateNotification(id) {

    $.ajax({
        url: 'ajax/get_notification_ajax.php',
        type: 'get',
        data: {
            'status': 2,
            'id': id
        }
    })
    setTimeout(function() { get_Notification(); }, 2000);
}