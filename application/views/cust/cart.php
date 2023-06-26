<?php $this->load->view('layouts/customer/head'); ?>
<style>
.payment-btns 
{
    padding-left: 10px;
    padding-right: 10px;
}

.paybtn 
{
    width: 50%;
    background: #30b94f;
    color: #fff;
    /*background: #000 !important;*/
    /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/
    height: 54px;
    margin-left: 0px !important;
    border-radius: 0 0.5rem 0.5rem 0;
}

.backbtn 
{
    width: 50%;
    margin-right: 0px !important;
    border-radius: 0.5rem 0 0 0.5rem;
    background-color: #d3c8c8;
    color:#fff;
    height: 54px;
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
    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive" style="height: 380px;">
                        <table class="table">
                        <thead>
                          <tr>
                            <th>Order Details</th>
                            <th>Quantity</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center"></th>
                          </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                        </tbody>
                      </table>
                  </div>
                </div>
            </div>

            <!-- btn -->
            <div class="row remove-margin payment-btns fixed-bottom" style=" width: 100%; margin-left: 1px;bottom: 54px !important;">

                <button type="button" class="btn btn-sm backbtn" data-dismiss="modal" width="50%" onclick="goBack()">Menu</button>

                <button type="button" class="btn btn-sm paybtn" data-dismiss="modal" style="width:50%;" onclick="goBill()">Bill</button>

            </div>
            <!-- end of btn -->
        </div>
    </section>

    <div class="modal" id="RecommendationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div style="margin-left: 14px;">
                    <h5>Recommendation</h5>
                    <p>Glen Fiddich</p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                              <table class="table">
                                <thead>
                                <tr>
                                    <td>Item</td>
                                    <td>Qty</td>
                                    <td>Rate</td>
                                </tr>
                            </thead>
                            <tbody id="recom-body">
                            </tbody>
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
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
                            template += ` <td><a href="#" onclick="recommendation(${item.ItemId})">
                                ${itemName}  ( ${item.Portions} )
                            </a></td> `;
                        }else{
                            template += ` <td>${itemName}</td> `;
                        }

                        template += ` <td class="text-center">
                            <div class="input-group" style="width: 94px;height: 28px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" disabled="" onclick="decQty(${item.OrdNo})">-
                                    </button>
                                </span>
                                <input type="text" readonly="" id="qty-val${item.OrdNo}" class="form-control input-number" value="${item.Qty}" min="1" max="10" style="text-align: center;">
                                <span class="input-group-btn">
                                    <button type="button" id="add-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 30px;" onclick="incQty(${item.OrdNo})">+
                                    </button>
                                </span>
                            </div></td> `;
                        template += ` <td class="text-center">${rate}</td> `;
                        template += ` <td class="text-center">
                                        <button onclick="cancelOrder(${item.OrdNo});" style="border-radius:50px;background:red;color:#fff;border:1px solid red;">
                                        <i class="fa fa-trash"></i>
                                        </button>
                                     </td> `;
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
            // $backUrl = "item_details.php?cId=$cId&mCatgId=$mCatgId&cType=$cType";
            $backUrl = base_url('customer');
        } else {
            $backUrl = base_url('customer');
            // $backUrl = base_url('customer/landing_page');
        }
        ?>
        window.location = '<?= "$backUrl" ?>';
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
                    // window.location = "cust_registration.php";
                    window.location = "<?php echo base_url('customer/signup'); ?>";
                } else if (response.status == 1) {
                    // window.location = "order_details.php";
                    window.location = "<?php echo base_url('customer/order_details'); ?>";
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

    // quantity increase and decrease
    function incQty(ord){
        $('#qty-val'+ord).val(parseInt($('#qty-val'+ord).val()) + 1);
        $('#minus-qty'+ord).prop('disabled', false);
        if ($('#qty-val'+ord).val() == 99) {
            $('#add-qty'+ord).prop('disabled', true);
        }
    }

    function decQty(ord){
        $('#qty-val'+ord).val(parseInt($('#qty-val'+ord).val()) - 1);
        $('#add-qty'+ord).prop('disabled', false);
        if ($('#qty-val'+ord).val() == 1) {
            $('#minus-qty'+ord).prop('disabled', true);
        }
    }
    // end quantity increase and decrease

    function recommendation(itemId){

        $.post('<?= base_url('customer/recommendation') ?>',{itemId:itemId},function(res){

                if(res.status == 'success'){
                  var data = res.response;
                  var temp = '';
                  for(i=0; i<data.length; i++){
                    temp += '<tr>\
                                <td>'+data[i].ItemNm+'</td>\
                                <td>1</td>\
                                <td>'+data[i].ItmRate+'</td>\
                            </tr>';
                  }
                  $('#recom-body').html(temp);
                }else{
                  alert(res.response);
                }
            });

        $('#RecommendationModal').modal();
    }

    // generate billing page

    function goBill() {
        // url: "ajax/order_details_ajax.php",
        $.ajax({
            url: "<?php echo base_url('customer/checkout'); ?>",
            type: "post",
            data: {
                goBill: 1
            },
            success: response => {
                console.log(response);
                if (response == 1) {
                    // debugger;
                    window.location = "bill.php";
                } else {
                    // debugger;
                    window.location.assign("cust_registration.php");
                }
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });

    }
    
</script>

</html>