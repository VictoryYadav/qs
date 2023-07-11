<?php $this->load->view('layouts/customer/head'); ?>
<style type="text/css">

    .paybtn {

        width: 50%;

        /*background: #30b94f;

        color: #fff;*/
        /*background: <?php echo isset($body_btn2color)?$body_btn2color:"#000"?> !important;*/
        /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/

        height: 54px;

        margin-left: 0px !important;

        border-radius: 0 0.5rem 0.5rem 0;

    }



    .backbtn {

        width: 50%;

        margin-right: 0px !important;

        border-radius: 0.5rem 0 0 0.5rem;

        /*background-color: #fff;*/
        /*background-color: <?php echo isset($body_btn1color)?$body_btn1color:"#000"?> !important;*/
        /*color: <?php echo isset($body_btn1text)?$body_btn1text:"#000"?> !important;*/

    }



    .order-details-page {

        padding-bottom: 70px;

        /*background-color: #0a88ff;*/
        /*background-color: <?php echo isset($body_bg)?$body_bg:"#000"?> !important;*/
        /*color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/

        padding-top: 65px;

        height: -webkit-fill-available;

    }



    .payment-btns {

        padding-left: 10px;

        padding-right: 10px;

    }



    .order-list {

        padding-left: 15px;

        padding-right: 15px;

        color: #fff;

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

        font-size: 17px;

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

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            
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

                                <!-- <td class="text-center">

                                    <input type="checkbox" id="c1" name="1" /><label for="c1"><span></span></label>

                                    <input type="checkbox" id="c2" name="2" /><label for="c2"><span></span></label>

                                    <input type="checkbox" id="c3" name="3" /><label for="c3"><span></span></label>

                                    <input type="checkbox" id="c4" name="4" /><label for="c4"><span></span></label>

                                    <input type="checkbox" id="c5" name="5" /><label for="c5"><span></span></label>

                                </td> -->

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



                    <!-- ************************************************ -->

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

                            <?php   }

                            ?>

                        </tbody>

                    </table>



                </div>

                <div class="row remove-margin payment-btns" style=" bottom: 12%;position: absolute;width: 100%;     margin-left: auto;">



                    <a id="MenuBackButton"  class="btn backbtn" width="50%" href="<?= base_url('customer'); ?>">Menu</a>



                    <button id="SubmitRating" type="button" class="btn paybtn" style="width:50%;" onclick="SubmitRating()">Submit</button>



                    <button id="shareRating" type="button" class="btn paybtn" data-toggle="modal" data-target="#exampleModal" style="width:50%;display: none;" onclick="">Share</button>

                </div>

                <!-- <div class="sharethis-inline-share-buttons"></div> -->

            </div>

        </div>
    </section>




    <!-- Modal  fade-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 99999999;">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel" style="font-family: auto; font-size: 21px;">Share with people.</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <!-- AddToAny BEGIN -->



                <div id="iconsSub" class="a2a_kit a2a_kit_size_32 a2a_default_style">



                    <!-- <a class="a2a_button_facebook" style="width: 40px;"></a>

                <a class="a2a_button_email"></a>

                <a class="a2a_button_whatsapp"></a>

                <a class="a2a_button_telegram"></a>

                <a class="a2a_button_facebook_messenger"></a> -->

                </div>

                <script async src="https://static.addtoany.com/menu/page.js"></script>

                <!-- AddToAny END -->

            </div>

            <!-- <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" class="btn btn-primary">Save changes</button>

              </div> -->

        </div>

    </div>

</div>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
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

    <?php $count++;
    } ?>



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
                billid: <?= $billId; ?>
            },

            success: function(data) {
                console.log(data);
                if (data == 1) {
                    $('#shareRating').css('display', 'block');
                    $('#SubmitRating').css('display', 'none');
                    $("input").attr('disabled', 'disabled');
                    // $('#MenuBackButton').css('display', 'none');
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





        var html = `<a class="icona" href="tg://msg?text=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/telegram.png" width="50px;">Telegram</a>`;

        html += `<a class="icona" href="whatsapp://send?text=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/whatsapp.png" width="50px;">Whats app</a>`;



        html += `<a class="icona" href="mailto:?body=<?php echo $link; ?>"><img src="<?= base_url('assets'); ?>/images/icons/email.png" width="50px;">Email</a>`;



        html += `<a  href="sms:?body=<?php echo $link; ?>" class="icona"><img src="<?= base_url('assets'); ?>/images/icons/msg.png" width="50px;">SMS</a>`;

        $('#iconsSub').html(html);

    } else {

        var html = `<a class="icona" href="https://telegram.me/share/url?url=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/telegram.png" width="50px;">Telegram</a>`;

        html += `<a class="icona" href="https://api.whatsapp.com/send?phone=whatsappphonenumber&text=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/whatsapp.png" width="50px;">Whats app</a>`;

        html += `<a class="icona" href="mailto:?Subject=Loma&amp;Body=<?php echo $link; ?>" target="_blank"><img src="<?= base_url('assets'); ?>/images/icons/email.png" width="50px;">Email</a>`;

        html += `<a  href="sms:?body=<?php echo $link; ?>" class="icona"><img src="<?= base_url('assets'); ?>/images/icons/msg.png" width="50px;">SMS</a>`;

        $('#iconsSub').html(html);

    }

</script>

</html>