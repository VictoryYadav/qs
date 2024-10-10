<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->
<?php

$displayCurrency = 'INR';

 ?>
 <!-- <form action="<?= base_url('razorpay/verify'); ?>" method="POST"> -->
<form action="verify" method="POST">
  <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?php echo $data['key'] ?>" data-amount="<?php echo $data['amount'] ?>" data-currency="INR" data-name="<?php echo $data['name'] ?>" data-image="<?php echo $data['image'] ?>" data-description="<?php echo $data['description'] ?>" data-prefill.name="<?php echo $data['prefill']['name'] ?>" data-prefill.email="<?php echo $data['prefill']['email'] ?>" data-prefill.contact="<?php echo $data['prefill']['contact'] ?>" data-notes.shopping_order_id="3456" data-order_id="<?php echo $data['order_id'] ?>" <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount'] ?>" <?php } ?> <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency'] ?>" <?php } ?>>
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="shopping_order_id" value="3456">
  <input type="hidden" name="MCNo" value="<?php echo $data['MCNo'] ?>">
  <input type="hidden" name="billId" value="<?php echo $data['billId'] ?>">
  <input type="hidden" name="billRef" value="<?php echo $data['billRef'] ?>">
  
</form>

<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script>
  $('.razorpay-payment-button').css('display', 'none');
  $('.razorpay-payment-button').click();

  $(document).ready(function() {
    if ($('.razorpay-container').is(":hidden") == false) {
      setInterval(function() {
        if ($('.razorpay-container').is(":hidden")) {
          window.history.back();
        }
      }, 1000);
    }
  });
</script>