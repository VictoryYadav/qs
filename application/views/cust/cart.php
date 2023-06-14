<?php $this->load->view('layouts/customer/head'); ?>
<style>
.payment-btns {

        padding-left: 10px;

        padding-right: 10px;

    }

        .paybtn {

        width: 50%;

        background: #30b94f;

        color: #fff;
        /*background: #000 !important;*/
        /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/

        height: 54px;

        margin-left: 0px !important;

        border-radius: 0 0.5rem 0.5rem 0;

    }



    .backbtn {

        width: 50%;

        margin-right: 0px !important;

        border-radius: 0.5rem 0 0 0.5rem;

        background-color: #d3c8c8;
        color:#fff;

        /*background-color:#000 !important;*/
        /*color: <?php echo isset($body_btn1text)?$body_btn1text:"#000"?> !important;*/

    }


</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order Details</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="order-details-table-body">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- btn -->

            <div class="row remove-margin payment-btns fixed-bottom" style=" width: 100%; margin-left: 1px;bottom: 60px !important;">

                <button type="button" class="btn btn-sm backbtn" data-dismiss="modal" width="50%" onclick="goBack()"><img src="<?= base_url(); ?>assets/img/back.svg" width="40" style="margin-right: 10px;">Menu</button>

                <button type="button" class="btn btn-sm paybtn" data-dismiss="modal" style="width:50%;" onclick="sendToKitchen()">Continue</button>

            </div>
            
        </div>
    </section>
    <!-- Shoping Cart Section End -->

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>


<script>

// send_to_kitchen_ajax
    function getSendToKitchenList() {
        $.ajax({
            url: "<?php echo base_url('customer/cart'); ?>",
            type: "post",
            data: {
                getSendToKitchenList: 1
            },
            dataType: "json",
            success: (response) => {
                console.log(response);
                var template = ``;
                if (response.status == 100) {
                    alert(response.msg);
                    window.location.reload();
                } else if (response.status == 1) {
                    response.kitcheData.forEach((item) => {
                        if (item.TA != 0) {
                            var rate = parseInt(item.Value) + parseInt(item.PckCharge);
                            var itemName = item.ItemNm + ` (TA)`;
                        } else {
                            var rate = item.Value;
                            var itemName = item.ItemNm;
                        }

                        template += ` <tr> `;
                        if(item.Itm_Portion > 4){
                            template += ` <td>${itemName}  ( ${item.Portions} )</td> `;
                        }else{
                            template += ` <td>${itemName}</td> `;
                        }

                        template += ` <td class="text-center">${item.Qty}</td> `;
                        template += ` <td class="text-center">${rate}</td> `;
                        template += ` <td class="text-center"> `;
                        template += ` <span class="icon_close" onclick="cancelOrder(${item.OrdNo});" style="cursor:pointer;"> `;
                        template += ` </span> `;
                        template += ` </td> `;
                        template += ` </tr> `;
                    });
                } else {
                    $(".paybtn").prop('disabled', true);
                }
                $("#order-details-table-body").html(template);
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }

        });
    }

    function goBack() {
        <?php
        if ($cId != '') {
            $backUrl = "item_details.php?cId=$cId&mCatgId=$mCatgId&cType=$cType";
        } else {
            $backUrl = "customer_landing_page.php";
        }
        ?>
        window.location = '<?= "$backUrl" ?>';
    }

    function goBill() {
        window.location = "bill.php";
    }

    function sendToKitchen() {
        $.ajax({
            url: "<?php echo base_url('customer/cart'); ?>",
            type: "post",
            data: {
                sendToKitchen: 1
            },
            dataType: "json",
            success: response => {
                console.log(response);
                if (response.status == 11) {
                    window.location = "cust_registration.php";
                    // window.location.assign("cust_registration.php");
                } else if (response.status == 1) {
                    window.location = "order_details.php";
                }
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    function cancelOrder(orderNo) {
        console.log(orderNo);
        $.ajax({
            url: "<?php echo base_url('customer/cart'); ?>",
            type: "post",
            data: {
                cancelOrder: 1,
                orderNo: orderNo
            },
            dataType: "json",
            success: response => {
                console.log(response);
                if (response.status) {
                    getSendToKitchenList();
                }
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    $(document).ready(() => {
        getSendToKitchenList();
    });

</script>

</html>