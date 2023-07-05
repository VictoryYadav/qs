<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Razorpay Verify</title>
</head>

<body>

    <?php

    require_once APPPATH.'third_party/razorpay-php/Razorpay.php';

    use Razorpay\Api\Api;
    use Razorpay\Api\Errors\SignatureVerificationError;

    $keyId = 'rzp_test_Z50p6ZM95VvlSy';
        $keySecret = 'gtQvvO7aRLLoMefAWCV7Pcwp';
        $displayCurrency = 'INR';

    $success = true;

    $error = "Payment Failed";

    if (empty($_POST['razorpay_payment_id']) == false) {
        $api = new Api($keyId, $keySecret);

        try {
            // Please note that the razorpay order ID must
            // come from a trusted source (session here, but
            // could be database or something else)
            // print_r($_POST);
            $attributes = array(
                // 'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                'razorpay_order_id' => $_POST['razorpay_order_id'],
                'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                'razorpay_signature' => $_POST['razorpay_signature']
            );

            $api->utility->verifyPaymentSignature($attributes);
        } catch (SignatureVerificationError $e) {
            $success = false;
            $error = 'Razorpay Error : ' . $e->getMessage();
        }
    }

    if ($success === true) {
        ?>
        <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
        <script>
            var form = $(document.createElement('form'));
            $(form).attr("action", "<?php echo base_url('razorpay/handle_payment'); ?>");
            $(form).attr("method", "POST");
            $(form).css("display", "none");

            var input_1 = $("<input>")
                .attr("type", "text")
                .attr("name", "orderId")
                .val("<?= $_SESSION['razorpay_order_id']; ?>");
            $(form).append($(input_1));


            var input_2 = $("<input>")
                .attr("type", "text")
                .attr("name", "orderAmount")
                .val("<?= $_SESSION['rez_totalAmount']; ?>");
            $(form).append($(input_2));

            var input_3 = $("<input>")
                .attr("type", "text")
                .attr("name", "referenceId")
                .val("");
            $(form).append($(input_3));

            var input_4 = $("<input>")
                .attr("type", "text")
                .attr("name", "txStatus")
                .val("SUCCESS");
            $(form).append($(input_4));

            var input_5 = $("<input>")
                .attr("type", "text")
                .attr("name", "paymentMode")
                .val("Online");
            $(form).append($(input_5));

            var input_6 = $("<input>")
                .attr("type", "text")
                .attr("name", "txMsg")
                .val("");
            $(form).append($(input_6));

            var input_7 = $("<input>")
                .attr("type", "text")
                .attr("name", "txTime")
                .val("");
            $(form).append($(input_7));

            var input_8 = $("<input>")
                .attr("type", "text")
                .attr("name", "signature")
                .val("<?= $_POST['razorpay_signature']; ?>");
            $(form).append($(input_8));

            form.appendTo(document.body);
            $(form).submit();
        </script>
    <?php
        // $html = "<p>Your payment was successful</p>
        //          <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
    } else {?>
<script type="text/javascript">
    // alert("<?php echo $error; ?>");
    alert("Invalid Signature, Try Again..!!");
    window.location.href='<?= $_SERVER['HTTP_REFERER']?>';
</script>
        <?php 
        $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
    }

    // echo $html;
    ?>

</body>

</html>