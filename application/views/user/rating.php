<?php $this->load->view('layouts/customer/head'); 
$EID = $this->session->userdata('EID');
$folder = 'e'.$EID; 
?>
<link href="<?= base_url() ?>assets_admin/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">

    .order-details-page {
        padding-bottom: 70px;
        /*background-color: #0a88ff;*/
        /*background-color: <?php echo isset($body_bg)?$body_bg:"#000"?> !important;*/
        /*color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/
        /*padding-top: 65px;*/
        height: -webkit-fill-available;
    }

    .payment-btns {
        padding-left: 10px;
        padding-right: 10px;
    }

    .order-list {
        padding-left: 15px;
        padding-right: 15px;
        overflow: scroll;
        height: 350px;
        /*cursor: all-scroll;*/
    }

    .order-list::-webkit-scrollbar {
        width: 5px;
    }

    .order-list::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }
    .order-list::-webkit-scrollbar-thumb {
        background-color: white;
        /*outline: 1px solid slategrey;*/
    }

    .order-list tr {
        font-size: 12px;
    }

    .order-list td {
        font-size: 12px;
        border-bottom: 1px solid;
        padding: 10px 0;
    }

    .order-list th {
        font-size: 14px;
        font-weight: 500;
    }

    .main-ammount {
        padding: 5px 37px;
        color: #fff;
    }

    .main-ammount th {
        font-weight: 500;
        font-size: 15px;
    }

    .navbar {
        overflow: hidden;
        background-color: #fff;
        position: fixed;
        bottom: 0;
        width: 100%;
        padding: 0px;
        text-align: center;
    }

    .navbar a {
        float: left;
        display: block;
        color: #000;
        text-align: center;
        padding: 8px 13px;
        text-decoration: none;
        font-size: 7px;
        /* width: 20%; */
    }

    .navbar a:hover {
        background: #f1f1f1;
        color: black;
    }

    .navbar a.active {
        background-color: #0a88ff;
        color: #fff;
    }

    .navbar a img {
        display: inherit;
    }

    .dropdown-menu a {
        font-size: 15px !important;
    }

</style>
<style media="screen">

    .dropup .dropdown-toggle::after {

        display: inline-block;

        width: 0;

        height: 0;

        margin-left: .255em;

        vertical-align: .255em;

        content: "";

        border-top: 0;

        border-right: .3em solid transparent;

        border-bottom: none !important;

        border-left: .3em solid transparent;

    }





    input[type="checkbox"] {

        display: none;

    }



    input[type="checkbox"]+label {

        color: #f2f2f2;

    }



    input[type="checkbox"]+label span {

        display: inline-block;

        width: 15px;

        height: 15px;

        vertical-align: middle;

        background: url(<?php echo base_url(); ?>assets/img/star_white.png) center no-repeat;

        background-size: cover;

        cursor: pointer;

    }

    input[type="checkbox"]:checked+label span {

        background: url(<?php echo base_url(); ?>assets/img/star_gold.png) center no-repeat;

        background-size: cover;

    }



    .TextRating {

        margin-right: 5px;

        font-size: 12px;

        margin-top: -5px;

        position: absolute;

        border-radius: 50%;

        width: 20px;

        height: 20px;

        background: white;

        color: black;

        font-weight: 900;

        right: 0px;

        margin-top: 0px;

        text-align: center;

    }



    .icona {

        width: 60px;

        color: black;

        font-size: 13px;

        text-align: center;

        text-decoration: none;

        font-family: serif;

        margin: 5px;

    }

    .paybtn 
    {
        width: 100%;
        background: #30b94f;
        color: #fff;
        /*background: #000 !important;*/
        /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/
        height: 30px;
        margin-left: 0px !important;
        border-radius: 1.5rem;
    }

    .paybtn:hover
    {
        background: #03bb2c;
        color: #fff;
        margin-left: 0px !important;
        border-radius: 1.5rem;
    }

    
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <section class="header-section">
        <div class="container p-2">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-4">
                    <ul class="list-inline product-meta">
                        <li class="list-inline-item">
                            <a href="#">
                                <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="width: 30px;height: 28px;">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 col-sm-8 col-8 text-right">
                    <ul class="list-inline product-meta">

                        <li class="list-inline-item">
                            <img src="<?= base_url('uploads/'.$folder.'/'.$EID.'_logo.jpg') ?>" width="auto" height="28px;">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <?php if($this->session->userdata('ratingShow') == 0 ){ ?> 
        <div class="container" id="mobileSection">
            <form method="post" id="loginForm">
                <div class="row">
                    <div class="col-md-6 mx-auto">

                        <div class="form-group">
                            <select name="countryCd" id="countryCd" class="form-control form-control-sm select2 custom-select" required="">
                                <option value="">Select Country</option>
                                <?php 
                            foreach ($country as $key) { ?>
                                <option value="<?= $key['phone_code']; ?>" ><?= $key['country_name']; ?></option>
                            <?php } ?>  
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile" required="" autocomplete="off" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                            <small id="loginMsg" class="text-danger" style="font-size: 10px;"></small>
                        </div>

                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                    </div>
                    
                </div>
            </form>

            <form method="post" id="otpForm" style="display: none;">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="form-group">
                            <input type="number" name="otp" class="form-control" placeholder="Enter OTP" autocomplete="off" required="" id="otp">
                            <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                        </div>
                        <input type="submit" class="btn btn-sm btn-success" value="Verify OTP">
                        <button class="btn btn-sm btn-warning" type="button" onclick="resendOTP()">Resend OTP</button>
                    </div>
                </div>
            </form>
        </div>
        <?php }else { ?>
        <div class="container" id="ratingSection">
            <div class="order-details-page">            
                <div class="order-list" id="OrderList">
                    <table class="fixed_headers" style="width:100%; color: <?php echo isset($body_text)?$body_text:"#000"?>;">
                        <thead>
                            <tr>
                                <th>General</th>
                                <th class="text-center">Rating</th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <tr>
                                <td>Service</td>
                                <td>
                                    <div class="row">
                                        <div class="col-6">
                                            <p id="ServiceValue" class="TextRating">3</p>
                                        </div>
                                        <div class="col-6">
                                            <input type="range" min="1" max="5" value="3" class="slider" id="ServiceRange" style="width: 100%;padding: 0px;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Ambience</td>
                                <td>
                                    <div class="row">
                                        <div class="col-6">
                                            <p id="AmbienceValue" class="TextRating">3</p>
                                        </div>
                                        <div class="col-6">
                                            <input type="range" min="1" max="5" value="3" class="slider" id="AmbienceRange" style="width: 100%;padding: 0px;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Value For Money</td>
                                <td>
                                    <div class="row">
                                        <div class="col-6">
                                            <p id="vfmValue" class="TextRating">3</p>
                                        </div>
                                        <div class="col-6">
                                            <input type="range" min="1" max="5" value="3" class="slider" id="vfmRange" style="width: 100%;padding: 0px;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- scroll all page -->
                    <table class="fixed_headers" style="width:100%; color: <?php echo isset($body_text)?$body_text:"#000"?>; margin-top: 26px;">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th class="text-center">Rating</th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <?php
                            $count = 0;
                            foreach ($kitchenGetData as $key => $value) {
                                $count++;
                                ?>
                                <tr style="border-bottom: none;">
                                    <td style='width: 35%;'><?= $value['ItemName']; ?></td>
                                    <!-- Rating Slider -->
                                    <td>
                                        <div class="row">
                                            <div class="col-6">
                                                <p id="<?= 'value_' . $value['ItemId']; ?>" class="TextRating">3</p>
                                            </div>
                                            <div class="col-6">
                                                <input type="range" min="1" max="5" name="<?= $count; ?>" value="3" class="slider" id="<?= $value['ItemId']; ?>" style="width: 100%;padding: 0px;">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /Rating Slider -->
                                </tr>
                            <?php   } ?>
                        </tbody>
                    </table>
                </div>

                <div class="row remove-margin payment-btns">
                        <!-- hidden field -->
                        <input type="hidden" id="mobileR" value="0">
                        <input type="hidden" id="custidR" value="0">
                        <!-- hidden field -->
                        <button id="SubmitRating" type="button" class="btn btn-sm paybtn" onclick="SubmitRating()">Submit</button>
                        
                </div>
                <!-- <div class="sharethis-inline-share-buttons"></div> -->
            </div>

        </div>
        <?php } ?>
    </section>

    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>
<script src="<?= base_url() ?>assets_admin/libs/select2/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#countryCd').select2();
    });

    var ServiceRange = document.getElementById("ServiceRange");
    var ServiceValue = document.getElementById("ServiceValue");
    ServiceValue.innerHTML = ServiceRange.value;
    ServiceRange.oninput = function() {
        ServiceValue.innerHTML = this.value;
    }

    var AmbienceRange = document.getElementById("AmbienceRange");
    var AmbienceValue = document.getElementById("AmbienceValue");
    AmbienceValue.innerHTML = ServiceRange.value;
    AmbienceRange.oninput = function() {
        AmbienceValue.innerHTML = this.value;
    }

    var vfmRange = document.getElementById("vfmRange");
    var vfmValue = document.getElementById("vfmValue");
    vfmValue.innerHTML = ServiceRange.value;
    vfmRange.oninput = function() {
        vfmValue.innerHTML = this.value;
    }

    var ItemRatingData = new Array();
    var ItemidData = new Array();

    <?php
    $count = 0;
    foreach ($kitchenGetData as $key => $value) {
        ?>
        ItemRatingData['<?= $count; ?>'] = 3;
        ItemidData['<?= $count ?>'] = '<?= $value['ItemId']; ?>';
        var <?= 'id' . $value['ItemId']; ?> = document.getElementById("<?= $value['ItemId']; ?>");
        var <?= 'value_' . $value['ItemId']; ?> = document.getElementById("<?= 'value_' . $value['ItemId']; ?>");
        <?= 'value_' . $value['ItemId']; ?>.innerHTML = ServiceRange.value;
        <?= 'id' . $value['ItemId']; ?>.oninput = function() {
            <?= 'value_' . $value['ItemId']; ?>.innerHTML = this.value;
            ItemRatingData['<?= $count; ?>'] = this.value;
        }

    <?php $count++;
    } ?>

    function SubmitRating() {

        $.ajax({
            url: "<?php echo base_url('users/rating/'.$billId); ?>",
            type: 'post',
            data: {
                ratingData: ItemidData,
                rating: ItemRatingData,
                Service: $('#ServiceRange').val(),
                Ambience: $('#AmbienceRange').val(),
                vfm: $('#vfmRange').val(),
                mobileR: $('#mobileR').val(),
                custidR: $('#custidR').val(),
                billid: <?= $billId; ?>
            },

            success: function(res) {
                // console.log(data);
                if (res.status == 1) {
                    window.location = "<?php echo base_url('users/thanku'); ?>" ;
                    return false;
                    $('#shareRating').css('display', 'block');
                    $('#SubmitRating').css('display', 'none');
                    $("input").attr('disabled', 'disabled');
                }
            },

            error: function(xhr, status, error) {
                console.log('xhr : ' + xhr);
                console.log('status : ' + status);
                console.log('error : ' + error);
            }
        });

    }

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

        var html = `<a class="icona" href="tg://msg?text=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/telegram.png" width="30px;" height="30px;">Telegram</a>`;

        html += `<a class="icona" href="whatsapp://send?text=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/whatsapp.png" width="30px;" height="30px;">Whats app</a>`;

        html += `<a class="icona" href="mailto:?body=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/email.png" width="30px;" height="30px;">Email</a>`;

        html += `<a  href="sms:?body=<?php echo $link; ?>" class="icona"><img src="<?= base_url('assets'); ?>/images/icons/msg.png"width="30px;" height="30px;">SMS</a>`;

        $('#iconsSub').html(html);

    } else {

        var html = `<a class="icona" href="https://telegram.me/share/url?url=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/telegram.png" width="30px;" height="30px;">Telegram</a>`;

        html += `<a class="icona" href="https://api.whatsapp.com/send?phone=whatsappphonenumber&text=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/whatsapp.png" width="30px;" height="30px;">Whats app</a>`;

        html += `<a class="icona" href="mailto:?Subject=Loma&amp;Body=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/email.png" width="30px;" height="30px;">Email</a>`;

        html += `<a  href="sms:?body=<?php echo $link; ?>" class="icona"><img src="<?= base_url('assets'); ?>/images/icons/msg.png" width="30px;" height="30px;"><br>SMS</a>`;

        $('#iconsSub').html(html);

    }

// copy text 
    const copyButton = document.getElementById('copyButton');

    copyButton.addEventListener('click', () => {
        const textToCopy = $('#textToCopy').text();
    copyTextToClipboard(textToCopy);
    });

    function copyTextToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            $('#copyButton').html('Copied');
        }).catch(error => {
            console.error('Unable to copy text:', error);
        });
    }

    // end copy text 

    function copied(){
        $('#copyButton').html('Copy');
    }
    setInterval(copied, 3000);


</script>


<script type="text/javascript">
  var mobile = ''; 
    $('#loginForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        console.log(data[0].value);
        
        mobile = data[1].value;

        $.post('<?= base_url('users/send_otp') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpForm').show();
              $('#loginForm').hide();
            }else{
              $('#loginMsg').html(res.response);
              $('#loginForm').show();
              $('#otpForm').hide();
            }
        });
    });

   $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('users/verifyOTP') ?>',data,function(res){
            if(res.status == 'success'){
                $('#mobileSection').hide();
                $('#ratingSection').show();
                location.reload();
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });

   function resendOTP(){
    $('#otp').val('');
    var page = 'Resend Rating';
      $.post('<?= base_url('users/resendOTP') ?>',{mobile:mobile,page:page},function(res){
            if(res.status == 'success'){
                $('#errorMsg').html(res.response);
            }else{
              $('#errorMsg').html(res.response);
            }
      });
   }
</script>

</html>