<?php $this->load->view('layouts/customer/head'); ?>
<style type="text/css">

    .order-details-page {
        padding-bottom: 70px;
        height: -webkit-fill-available;
    }

    .payment-btns 
    {
        padding-left: 15px !important;
        padding-right: 15px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .order-list {
        /*padding-left: 15px;
        padding-right: 15px;*/
        height: 400px;
        overflow: auto;
        overflow-x: hidden;
        /*scrollbar-color: #c1c1c1 #ededed;*/
        /*cursor: all-scroll;*/
    }

    .order-list tr {
        font-size: 13px;
    }

    .order-list td {
        font-size: 13px;
        border-bottom: 1px solid;
        padding: 10px 0;
    }

    .order-list th {
        font-size: 14px;
        font-weight: 500;
    }

</style>
<style media="screen">

    .TextRating {

        margin-right: 5px;

        font-size: 13px;

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
        width: 50%;
        background: <?php echo $this->session->userdata('successBtn'); ?>;
        color: <?php echo $this->session->userdata('successBtnClr'); ?>;
        height: 30px;
        margin-left: 0px !important;
        border-radius: 0 1.5rem 1.5rem 0;
    }

    .paybtn:hover
    {
        background: #03bb2c;
        color: #fff;
        margin-left: 0px !important;
        border-radius: 0 1.5rem 1.5rem 0;
    }

    .backbtn 
    {
        width: 50%;
        margin-right: 0px !important;
        border-radius: 1.5rem 0 0 1.5rem;
        background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
        color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
        height: 30px;
    }

    .backbtn:hover
    {
        background-color: #9d9696;
        color:#fff;   
    }

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            
            <div class="order-details-page">
             <?php if(isset($_GET['rat'])){ ?>
                <div style="margin-left: 15px;">
                    <div class="row" id="mobileBlock">
                        <div class="col-md-3">
                            <input type="number" placeholder="Mobile" id="mobile">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-success" onclick="getOTP()"><?= $this->lang->line('send'); ?> <?= $this->lang->line('otp'); ?></button>
                        </div>
                    </div>
                    <div class="row" id="verifyBlock" style="display: none;">
                        <div class="col-md-3">
                            <input type="number" placeholder="OTP" id="otp">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-success" onclick="verifyOTP()"><?= $this->lang->line('verifyOTP'); ?></button>
                        </div>
                    </div>
                    <div id="welcome">
                        <p><?= $this->lang->line('welcome'); ?> <b><span id="name"></span></b></p>
                    </div>
                </div>
            <?php } ?>
                <div class="order-list" id="OrderList">
                    <table class="fixed_headers" style="width:100%; color: <?php echo isset($body_text)?$body_text:"#000"?>;">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('general'); ?></th>
                                <th class="text-center"><?= $this->lang->line('rating'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <tr>
                                <td><?= $this->lang->line('service'); ?></td>
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
                                <td><?= $this->lang->line('ambience'); ?></td>
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
                                <td><?= $this->lang->line('valueForMoney'); ?></td>
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
                                <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></th>
                                <th class="text-center"><?= $this->lang->line('rating'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <?php
                            $count = 0;
                            foreach ($kitchenGetData as $key => $value) {
                                $count++;
                                ?>
                                <tr style="border-bottom: none;">
                                    <td style='width: 35%;'><?= $value['ItemNm']; ?></td>
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
            </div>

            <div class="row container payment-btns fixed-bottom" style=" width: 100%;bottom: 53px !important;">

                    <input type="hidden" id="mobileR" value="0">
                    <input type="hidden" id="custidR" value="0">

                    <?php if(isset($_GET['rat'])){ ?>
                    <button id="SubmitRating" type="button" class="btn btn-sm paybtn" style="width:50%;" onclick="SubmitRating()" disabled><?= $this->lang->line('submit'); ?></button>
                    <?php }else{ ?>
                    <a id="MenuBackButton"  class="btn btn-sm backbtn" width="50%" href="<?= base_url('customer'); ?>"><?= $this->lang->line('menu'); ?></a>
                    <button id="SubmitRating" type="button" class="btn btn-sm paybtn" style="width:50%;" onclick="SubmitRating()"><?= $this->lang->line('submit'); ?></button>
                    <?php } ?>
                    <button id="shareRating" type="button" class="btn btn-sm paybtn" data-toggle="modal" data-target="#exampleModal" style="width:50%;display: none;" onclick=""><?= $this->lang->line('share'); ?></button>
            </div>

        </div>
    </section>

    <!-- Modal  fade-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #e1af75;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family: auto; font-size: 14px;"><?= $this->lang->line('shareLinkWithPeople'); ?></h5>
                </div>

                <div class="modal-body" style="padding: 0.5rem;">
                    <p class="text-right" style="margin-bottom: -1px;">
                        <span style="background: #000;color:#fff;padding: 3px;font-size: 11px;border-radius: 50px;cursor: pointer;" id="copyButton"><?= $this->lang->line('copy'); ?></span>
                    </p>
                    <p id="textToCopy"><?= $link; ?></p>
                    <div id="iconsSub" class="a2a_kit a2a_kit_size_32 a2a_default_style">
                    </div>
                    <script async src="https://static.addtoany.com/menu/page.js"></script>
                </div>
            </div>
        </div>
    </div>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">

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

    <?php $count++; } ?>

    function SubmitRating() {
        $.ajax({

            url: "<?php echo base_url('customer/rating/'.$billId); ?>",
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

            success: function(data) {
                console.log(data);
                if (data == 1) {
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

    function getOTP(){
        var mobile = $('#mobile').val();
        if(mobile.length >= 10){
            $.post('<?= base_url('customer/genOTPRating') ?>',{mobile:mobile},function(res){
                if(res.status == 'success'){
                  
                  $('#verifyBlock').show();
                  $('#mobileBlock').hide();
                  
                }else{
                  alert(res.response);
                  $('#verifyBlock').hide();
                  $('#mobileBlock').show();
                }
            });
        }else{
            alert('Enter Mobile No');
        }
    }

    function verifyOTP(){
        var otp = $('#otp').val();
        var mobile = $('#mobile').val();
        if(mobile.length >= 10 && otp >= 4){
            $.post('<?= base_url('customer/verifyOTPRating') ?>',{otp:otp, mobile:mobile},function(res){
                if(res.status == 'success'){
                  
                  $('#name').html(res.response.data.FName+' '+res.response.data.LName);
                  $('#mobileR').val(mobile);
                  $('#custidR').val(res.response.data.CustId);
                  $('.paybtn').prop("disabled", false); 
                }else{
                  alert(res.response);
                }
            });
        }else{
            alert('Enter Mobile No');
        }
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

</html>