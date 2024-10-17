<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="chat-box"></div>
    <input type="text" id="message" placeholder="Type a message">
    <button id="sendButton">Send</button>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    const socket = new WebSocket('ws://localhost:8080');

socket.onopen = function(event) {
    console.log("Connected to WebSocket server");
    socket.send("Your message"); // You can send a message to the server if needed
};

socket.onmessage = function(event) {
    var data = event.data;
    const result = data.split('_');

    var MCNo = result[0]; 
    var mergeNo = result[1]; 
    var FKOTNo = result[2];
    var KOTNo = result[3];

    kot_print(MCNo, mergeNo, FKOTNo, KOTNo);
};

socket.onclose = function(event) {
    console.log("WebSocket connection closed");
};

socket.onerror = function(event) {
    console.error("WebSocket error observed: ", event);
};

function kot_print(MCNo, mergeNo, FKOTNo, KOTNo){
    var url = "<?php echo base_url('restaurant/kot_print/') ?>"+MCNo+"/"+mergeNo+"/"+FKOTNo+"/"+KOTNo;
    window.open(url, "_blank");
    return false;
}

$('#sendButton').click(function() {
    
    const message = 'how r u';
    const senderId = 1;
    const receiverId = 2;



    $.post('<?= base_url('ChatController/sendMessage') ?>', {
        message: message,
        sender_id: senderId,
        receiver_id: receiverId
    }, function(res) {

        if(res.status == 'success'){
          // alert(res.response);
          socket.send(res.response);
        }else{
          alert(res.response);
        }
    });

});

</script>

</html>