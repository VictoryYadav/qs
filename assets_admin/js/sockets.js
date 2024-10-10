  const socket = new WebSocket('ws://localhost:8080');

  socket.onopen = function(event) {
      console.log("Connected to WebSocket server");
      socket.send("Your message"); // You can send a message to the server if needed
  };

  socket.onmessage = function(event) {
      var data = event.data;
      // var myJSON = JSON.parse(data);
      // console.log("Data received from server: ", myJSON.EID);
      dd(51, 1);
      // dd(myJSON.EID, myJSON.BillId);
  };

  socket.onclose = function(event) {
      console.log("WebSocket connection closed");
  };

  socket.onerror = function(event) {
      console.error("WebSocket error observed: ", event);
  };

  function dd(EID, BillId){
      var url = "<?php echo base_url('restaurant/bill_print/') ?>"+BillId+"/"+EID;
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