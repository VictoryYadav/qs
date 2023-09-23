<?php $this->load->view('layouts/customer/head'); ?>
<style>

    #RecommendationModal,.common-section{
        font-size: 12px;
    }
.payment-btns 
{
    padding-left: 10px;
    padding-right: 10px;
}

.paybtn 
{
    background: #30b94f;
    color: #fff;
    /*background: #000 !important;*/
    /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/
    height: 35px;
    margin-left: -3px !important;
    border-radius: 0 1.5rem 1.5rem 0;
}

.paybtn:hover
{
    background: #03bb2c;
    color: #fff;
    border-radius: 0 1.5rem 1.5rem 0;
}

.orderbtn 
{
    background: #f76004;
    color: #fff;
    /*background: #000 !important;*/
    /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/
    height: 35px;
    margin-left: 0px !important;
    /*border-radius: 0 1.5rem 1.5rem 0;*/
}

.orderbtn:hover
{
    background: #e97832;
    color: #fff;
    height: 35px;
    margin-left: 0px !important;
}

.backbtn 
{
    margin-right: -2px !important;
    border-radius: 1.5rem 0 0 1.5rem;
    background-color: #bfbcbc;
    color:#fff;
    height: 35px;
    /*background-color:#000 !important;*/
    /*color: <?php echo isset($body_btn1text)?$body_btn1text:"#000"?> !important;*/
}

.backbtn:hover
{
    background-color: #9d9696;
    color:#fff;   
}

.form-control {
    border-radius: 2px;
    height: 25px !important;
    background-color: transparent;
    color: #666;
    box-shadow: none;
    font-size: 11px !important;
}

#cartView{
       height: 400px;
       overflow: auto; 
    }
/*mobile screen only*/
@media only screen and (max-width: 480px) {
    #cartView{
       height: 480px;
       overflow: auto; 
    }
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
            <div class="row" id="cartView">
                <div class="col-lg-12">
                    <form method="post" id="cartForm">
                        <input type="hidden" name="goBill" value="1" />
                        <div class="table-responsive">
                            <table class="table table-hover">
                            <thead>
                              <tr>
                                <th class="text-center">Order</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center"></th>
                              </tr>
                            </thead>
                            <tbody id="order-details-table-body">
                            </tbody>
                          </table>
                      </div>
                  </form>
                </div>
            </div>

            <!-- btn -->
            <div class="row remove-margin payment-btns fixed-bottom" style=" width: 100%; margin-left: 1px;bottom: 54px !important;">
                <?php 
                $width = '50%';
                if($EType == 5){
                    $width = '33.33%';
                }
                ?>
                <button type="button" class="btn btn-sm backbtn" data-dismiss="modal" onclick="goBack()" style="width: <?= $width; ?>">Menu</button>
                <?php 
                if($EType == 5){
                ?>
                <button type="button" class="btn btn-sm orderbtn" data-dismiss="modal" onclick="kotGenerate()" style="width: <?= $width; ?>">Order</button>
                <?php } ?>
                <button type="button" class="btn btn-sm paybtn" data-dismiss="modal" onclick="goBill()" style="width: <?= $width; ?>">Checkout</button>

            </div>
            <!-- end of btn -->
        </div>
    </section>

    <div class="modal" id="RecommendationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div style="margin-left: 14px;margin-bottom: -15px;">
                    <h6>Recommendation</h6>
                    <p id="item_name"></p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" id="recomForm">
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
                                <div class="text-right">
                                    <button class="btn btn-sm btn-success">Add</button>
                                </div>
                            </form>
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

                        var recmnd = '';
                        if(item.recom > 0){
                            recmnd = `<a onclick="recommendation(${item.ItemId}, '${item.ItemNm}')" style="cursor:pointer;color:green;">`;
                        }

                        // for italic
                        var italicItem = `${itemName}`;
                        if(item.Stat == 0){
                            
                            italicItem = `<i>${itemName}</i>`;
                        }

                        template += ` <tr> `;
                        if(item.Itm_Portion > 4){
                            template += ` <td>${recmnd}
                                ${italicItem}  ( ${item.Portions} )
                            </a></td> `;
                        }else{
                            template += ` <td>${italicItem}</td> `;
                        }

                        template += ` <input type="hidden" name="OrdNo[]" value="${item.OrdNo}" /><td class="text-center">
                            <div class="input-group" style="width: 94px;height: 23px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 25px;"  onclick="decQty(${item.OrdNo})">-
                                    </button>
                                </span>
                                <input type="text" readonly="" id="qty-val${item.OrdNo}" class="form-control input-number" value="${item.Qty}" min="1" max="10" style="text-align: center; height:20px;" name="qty[]">
                                <span class="input-group-btn">
                                    <button type="button" id="add-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 25px;" onclick="incQty(${item.OrdNo})">+
                                    </button>
                                </span>
                            </div></td> `;
                        template += ` <td class="text-center">${rate}</td> `;
                        template += ` <td class="text-center">
                                        <button type="button" onclick="cancelOrder(${item.OrdNo});" style="border-radius:50px;background:red;color:#fff;border:1px solid red;">
                                        <i class="fa fa-trash" style="font-size:12px;"></i>
                                        </button>
                                     </td> `;
                        template += ` </tr> `;
                    });
                } else {
                    window.location = "<?php echo base_url('customer'); ?>";
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
        if ($('#qty-val'+ord).val() < 1) {
            $('#minus-qty'+ord).prop('disabled', true);
        }
    }
    // end quantity increase and decrease

    function recommendation(itemId, itemName){

        $.post('<?= base_url('customer/recommendation') ?>',{itemId:itemId},function(res){
            if(res.status == 'success'){
              var data = res.response;
              var temp = '';
              for(i=0; i<data.length; i++){
                temp += '<tr><input type="hidden" name="TblTyp['+data[i].ItemId+'][]" value="'+data[i].TblTyp+'"><input type="hidden" name="itemKitCd['+data[i].ItemId+'][]" value="'+data[i].KitCd+'"><input type="hidden" name="tax_type['+data[i].ItemId+'][]" value="'+data[i].TaxType+'"><input type="hidden" name="prepration_time['+data[i].ItemId+'][]" value="'+data[i].PrepTime+'"><input type="hidden" name="Itm_Portions['+data[i].ItemId+'][]" value="'+data[i].Itm_Portions+'">\
                            <td><input type="hidden" name="itemArray[]" value="'+data[i].ItemId+'">'+data[i].ItemNm+'</td>\
                            <td><div class="input-group" style="width: 94px;height: 28px;margin-left: 5px;"><span class="input-group-btn">\
                                    <button type="button" id="minus-qty'+data[i].ItemId+'" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 25px;" disabled="" onclick="decQty('+data[i].ItemId+')">-\
                                    </button>\
                                </span>\
                                <input type="text" readonly="" id="qty-val'+data[i].ItemId+'" class="form-control input-number" value="0" min="1" max="10" style="text-align: center;" name="qty['+data[i].ItemId+'][]">\
                                <span class="input-group-btn">\
                                    <button type="button" id="add-qty'+data[i].ItemId+'" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 25px;" onclick="incQty('+data[i].ItemId+')">+\
                                    </button>\
                                </span></div></td>\
                            <td><input type="hidden" name="rate['+data[i].ItemId+'][]" value="'+data[i].ItmRate+'">'+data[i].ItmRate+'</td>\
                        </tr>';
              }
              $('#item_name').html(itemName);
              $('#recom-body').html(temp);
            }else{
              alert(res.response);
            }
        });

        $('#RecommendationModal').modal();
    }

    $('#recomForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serializeArray();

        $.post('<?= base_url('customer/recomAddCart') ?>', data ,function(res){
            if(res.status == 'success'){
               location.reload();
            }else{
              alert(res.response);
            }
        });

    });

    // generate billing page

    function goBill() {
        var data = $('#cartForm').serializeArray();

        $.ajax({
            url: "<?php echo base_url('customer/order_details_ajax'); ?>",
            type: "post",
            data: data,
            success: response => {
                
                console.log(response);
                if (response.status == 2) {
                    alert('Order Sent To Kitchen Successfully');
                }
                window.location = "<?= base_url('customer/checkout'); ?>";
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });

    }

    function kotGenerate(){
        var data = $('#cartForm').serializeArray();
        $.post('<?= base_url('customer/kotGenerate') ?>', data ,function(res){
            if(res.status == 'success'){
                alert(res.response);
               window.location = "<?php echo base_url('customer'); ?>";
            }else{
              alert(res.response);
            }
        });

    }
    
</script>

</html>