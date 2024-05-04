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
    background: <?php echo $this->session->userdata('successBtn'); ?>;
    color: <?php echo $this->session->userdata('successBtnClr'); ?>;
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
    background: <?php echo $this->session->userdata('orderBtn'); ?>;
    color: <?php echo $this->session->userdata('orderBtnClr'); ?>;
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
    background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
    color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
    height: 35px;
}

.backbtn:hover
{
    background-color: #efd4b3;
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

table th,td{
    text-align: center;
    vertical-align: center;
}


</style>

<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
" rel="stylesheet">

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js
"></script>
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
                            <table class="table table-hover table-sm">
                            <thead>
                              <tr>
                                <th class="text-center"><?= $this->lang->line('order'); ?></th>
                                <th width="95px"><?= $this->lang->line('quantity'); ?></th>
                                <th class="text-center"><?= $this->lang->line('rate'); ?></th>
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
                <button type="button" class="btn btn-sm backbtn" data-dismiss="modal" onclick="goBack()" style="width: <?= $width; ?>"><?php echo  $this->lang->line('menu'); ?></button>
                <?php 
                if($EType == 5){
                ?>
                <button type="button" class="btn btn-sm orderbtn" data-dismiss="modal" onclick="kotGenerate()" style="width: <?= $width; ?>"><?php echo  $this->lang->line('order'); ?></button>
                <?php } ?>
                <button type="button" class="btn btn-sm paybtn" data-dismiss="modal" onclick="goBill()" style="width: <?= $width; ?>"><?php echo  $this->lang->line('checkout'); ?></button>

            </div>
            <!-- end of btn -->
        </div>
    </section>

    <div class="modal" id="RecommendationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;">
                    <h6 class="modal-title text-white"><?= $this->lang->line('recommendation'); ?></h6>
                    <p id="item_name"></p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" id="recomForm">
                                <div class="table-responsive" >
                                  <table class="table table-hover table-sm" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th><?= $this->lang->line('item'); ?></th>
                                            <th><?= $this->lang->line('quantity'); ?></th>
                                            <th><?= $this->lang->line('rate'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="recom-body">
                                    </tbody>
                                  </table>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-sm btn-success"><?= $this->lang->line('add'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="billBasedOffer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;">
                    <h6 class="modal-title text-white"><?= $this->lang->line('offers'); ?></h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                              <table class="table table-hover table-sm" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th><?= $this->lang->line('bill'); ?> <?= $this->lang->line('amount'); ?></th>
                                        <th><?= $this->lang->line('discount'); ?></th>
                                        <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="billOffer-body">
                                </tbody>
                              </table>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-success" onclick="billBaseOffer()">Yes</button>
                                <button class="btn btn-sm btn-danger" onclick="gotoCheckout()">No</button>
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

function gotoCheckout(){
    window.location = "<?= base_url('customer/checkout'); ?>";
}

function billBaseOffer(){
    $.post('<?= base_url('customer/billBasedOfferUpdate') ?>',function(res){
        if(res.status == 'success'){
          window.location = "<?= base_url('customer/checkout'); ?>";
        }else{
          alert(res.response);
        }
    });
}
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
                            var rate = parseInt(item.Value);
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

                        template += ` <input type="hidden" name="OrdNo[]" value="${item.OrdNo}" /><td >
                            <div class="input-group" style="width: 94px;height: 23px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 25px;"  onclick="decQty(${item.OrdNo})">-
                                    </button>
                                </span>
                                <input type="hidden" id="qty-val${item.OrdNo}" value="${item.Qty}" name="qty[]">
                                <input type="text" readonly="" id="qty-valView${item.OrdNo}" class="form-control input-number" value="${convertToUnicodeNo(item.Qty)}" min="1" max="10" style="text-align: center; height:20px;">
                                <span class="input-group-btn">
                                    <button type="button" id="add-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 25px;" onclick="incQty(${item.OrdNo})">+
                                    </button>
                                </span>
                            </div></td> `;
                        template += ` <td class="text-center">${convertToUnicodeNo(rate)}</td> `;
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
        let val = parseInt($('#qty-val'+ord).val()) + 1;
        $('#qty-val'+ord).val(val);
        $('#qty-valView'+ord).val(convertToUnicodeNo(val));

        $('#minus-qty'+ord).prop('disabled', false);
        if ($('#qty-val'+ord).val() == 99) {
            $('#add-qty'+ord).prop('disabled', true);
        }
    }

    function decQty(ord){
        var val = $('#qty-val'+ord).val();
        if(val < 2){
            $('#minus-qty'+ord).prop('disabled', true);
        }else{
            val = parseInt(val) - 1;
        }
        
        $('#qty-val'+ord).val(val);
        $('#qty-valView'+ord).val(convertToUnicodeNo(val));
        $('#add-qty'+ord).prop('disabled', false);
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
                                <input type="text" readonly="" id="qty-val'+data[i].ItemId+'" class="form-control input-number" value="'+convertToUnicodeNo(0)+'" min="1" max="10" style="text-align: center;" name="qty['+data[i].ItemId+'][]">\
                                <span class="input-group-btn">\
                                    <button type="button" id="add-qty'+data[i].ItemId+'" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 25px;" onclick="incQty('+data[i].ItemId+')">+\
                                    </button>\
                                </span></div></td>\
                            <td><input type="hidden" name="rate['+data[i].ItemId+'][]" value="'+data[i].ItmRate+'">'+convertToUnicodeNo(data[i].ItmRate)+'</td>\
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
                    Swal.fire({
                      text: '<?= $this->lang->line('kitchen_msg'); ?>',
                      confirmButtonText: 'OK',
                      confirmButtonColor: "green",
                    });
                    window.location = "<?= base_url('customer/checkout'); ?>";
                }

                if (response.status == 3) {
                    var list = response.resp;
                    var temp = '';
                    for (var i = 0; i < list.length; i++) {
                        temp +='<tr><td>'+list[i].MinBillAmt+'</td><td>'+list[i].Bill_Disc_pcent+'</td><td>'+list[i].itemName+'</td></tr>';
                    }
                    $('#billOffer-body').html(temp);
                    $('#billBasedOffer').modal();
                }

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
                // alert(res.response);
                Swal.fire({
                      text: res.response,
                      confirmButtonText: 'OK',
                      confirmButtonColor: "green",
                    });
               window.location = "<?php echo base_url('customer'); ?>";
            }else{
              alert(res.response);
            }
        });

    }
    
</script>

</html>