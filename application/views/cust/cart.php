<?php $this->load->view('layouts/customer/head'); ?>
<style>

#RecommendationModal,.common-section{
    font-size: 13px;
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
                                <th class="text-right"></th>
                                <th class="text-left"></th>
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
                <button type="button" class="btn btn-sm paybtn" data-dismiss="modal" onclick="goCheckout()" style="width: <?= $width; ?>"><?php echo  $this->lang->line('checkout'); ?></button>

            </div>
            <!-- end of btn -->
        </div>
    </section>

    <div class="modal" id="RecommendationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;padding: 8px 5px 2px 12px;">
                    <h6 class="modal-title text-white" id="item_name"><?= $this->lang->line('recommendation'); ?></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
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
                            <form method="post" id="billBasedForm">
                                <input type="hidden" id="billBasedFlag" value="" name="flag">
                                <input type="hidden" id="billBasedMCNo" value="0" name="MCNo">
                                <input type="hidden" id="billBasedMergeNo" value="0" name="MergeNo">
                                <input type="hidden" id="billBasedCustId" value="0" name="CustId">
                                <input type="hidden" id="billBasedFilter" value="0" name="tableFilter">
                                <input type="hidden" id="sdetcd" value="0" name="sdetcd">
                        
                                <div class="row" >
                                    <div class="col-md-12 mt-2">
                                        <select name="SchCd" id="SchCd" class="form-control form-control-sm" onchange="changeOffer()" required="">
                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mt-2 itemidBlock" style="display: none;">
                                        <select name="ItemId" id="ItemId" class="form-control select2 custom-select form-control-sm">
                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('yes'); ?>" />
                                        <button class="btn btn-sm btn-danger" onclick="goBill()"><?= $this->lang->line('no'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="customOfferModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;">
                    <h6 class="modal-title text-white"><?= $this->lang->line('customization'); ?></h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" id="customizationForm">
                                <div class="widget category" style="width: 100%;display: none;" id="radioOption">
                            
                                </div>

                                <div class="widget category" style="width: 100%;display: none;" id="checkboxOption">
                                    <h5 class="widget-header" id="chkHeader"></h5>
                                    <ul class="category-list" id="chkList">
                                        
                                    </ul>
                                </div>
                                <div class="text-center">
                                    <input type="hidden" id="itemTotal" value="0">
                                    <input type="hidden" id="origTotal" value="0">
                                    <input type="hidden" id="origTotalView" value="0">

                                    <input type="hidden" id="order_no" value="0">
                                    <input type="hidden" id="order_no_itemid" value="0">
                                    <b><span id="itmTotalView">0</span></b>
                                </div>
                                <div class="text-right">
                                    <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit'); ?>">
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
    
    <?php $this->load->view('layouts/customer/script'); ?>

</body>

<script>

    $(`#billBasedForm`).on('submit', function(e){
        e.preventDefault();

        var ipcd = $('option:selected', $('#ItemId')).attr('ipcd');
        var data = $(this).serializeArray();
        var form = new FormData(document.getElementById("billBasedForm"));
        form.append('ipcd', ipcd);

        $.ajax({
               url : '<?= base_url('customer/billBasedOfferUpdate') ?>',
               type : 'POST',
               data : form,
               processData: false,  
               contentType: false,  
               success : function(res) {
                    if(res.status == 'success'){
                        goBill();
                    }else{
                      alert(res.response);
                    }        
               }
        });
    })

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
                        var btndisable = ``;
                        if (item.SchCd > 0) {
                            btndisable = `disabled`;
                        }

                        var customOfferBtn = ``;
                        if(item.ItemTyp > 0){
                            customOfferBtn = `<button type="button" onclick="getCustomItems(${item.ItemId}, ${item.ItemTyp}, ${item.Itm_Portion}, ${item.FID}, ${item.OrdNo})" style="border-radius:50px;background:#50e13c;color:#fff;border:1px solid #50e13c;" title="Customization">
                                        <i class="fa fa-gift" style="font-size:12px;"></i>
                                        </button>`;
                        }

                        if (item.TA == 1) {
                            var rate = parseInt(item.Value);
                            var itemName = item.ItemNm + ` (TA)`;
                        }else if (item.TA == 2) {
                            var rate = parseInt(item.Value);
                            var itemName = item.ItemNm + ` (Charity)`;
                        } else {
                            var rate = item.Value;
                            var itemName = item.ItemNm;
                        }
                        
                        var recommend = "<?= $this->session->userdata('recommend'); ?>";
                        var recmnd = '';
                        if(recommend == 1){
                            if(item.recom > 0){
                                recmnd = `<a onclick="recommendation(${item.ItemId}, '${item.ItemNm}')" style="cursor:pointer;color:green;">`;
                            }
                        }

                        // for italic
                        var italicItem = `${itemName}`;
                        if(item.Stat == 0){
                            italicItem = `<i>${itemName}</i>`;
                        }

                        if(item.CustItemDesc != 'Std' && item.CustItemDesc != ''){
                            italicItem = italicItem+' - '+item.CustItemDesc;
                        }

                        template += ` <tr> `;
                        if(item.Itm_Portion > 4){
                            template += ` <td>${recmnd}
                                ${italicItem}  ( ${item.Portions} )
                            </a></td> `;
                        }else{
                            template += ` <td>${italicItem}</td> `;
                        }

                        template += ` <input type="hidden" name="OrdNo[]" value="${item.OrdNo}" /><input type="hidden" id="tmp_itmrate_${item.OrdNo}" value="${item.tmpItmRate}" /><input type="hidden" id="tmp_origrate_${item.OrdNo}" value="${item.tmpOrigRate}" /><td >
                            <div class="input-group" style="width: 94px;height: 23px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 25px;"  onclick="decQty(${item.OrdNo})" ${btndisable}>-
                                    </button>
                                </span>
                                <input type="hidden" id="qty-val${item.OrdNo}" value="${item.Qty}" name="qty[]">
                                <input type="text" readonly="" id="qty-valView${item.OrdNo}" class="form-control input-number" value="${convertToUnicodeNo(item.Qty)}" min="1" max="10" style="text-align: center; height:20px;">
                                <span class="input-group-btn">
                                    <button type="button" id="add-qty${item.OrdNo}" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 25px;" onclick="incQty(${item.OrdNo})" ${btndisable}>+
                                    </button>
                                </span>
                            </div></td> `;
                        template += ` <td class="text-center">${convertToUnicodeNo(rate)}</td> `;
                        template += `<td class="text-right">${customOfferBtn}</td> 
                                    <td class="text-left">
                                        <button type="button" onclick="cancelOrder(${item.OrdNo});" style="border-radius:50px;background:red;color:#fff;border:1px solid red;">
                                        <i class="fa fa-trash" style="font-size:12px;"></i>
                                        </button>
                                     </td> `;
                        template += ` </tr> `;
                    });
                } else {
                    alert('No Order Pending!! ');
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
            $backUrl = base_url('customer');
        } else {
            $backUrl = base_url('customer');
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
                    window.location = "<?php echo base_url('customer/signup'); ?>";
                } else if (response.status == 1) {
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

    function goCheckout() {
        var tableOffer = "<?= $currentTableOffer; ?>"; 
        var TableNo = "<?= $this->session->userdata('TableNo'); ?>";
        var SchType = "<?php echo $this->session->userdata('SchType'); ?>";
        if(SchType == 1){
            if((TableNo == 101 || TableNo == 105 || TableNo == 110 || TableNo == 100) && tableOffer == 1){
                checkBillOffer(); 
            }else{
                checkBillOffer();  
            }       
        }else{
            goBill();
        }
    }

    function goBill(){
        var data = $('#cartForm').serializeArray();
        $.ajax({
            url: "<?php echo base_url('customer/order_details_ajax'); ?>",
            type: "post",
            data: data,
            success: response => {
                console.log(response);
                Swal.fire({
                  text: '<?= $this->lang->line('kitchen_msg'); ?>',
                  confirmButtonText: 'OK',
                  confirmButtonColor: "green",
                });
                window.location = "<?= base_url('customer/checkout'); ?>";
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    function checkBillOffer(){
        $.post('<?= base_url('customer/order_details_ajax') ?>', {billOffer:1} ,function(res){
            if(res.status == 'success'){
                
                if(res.resp.length > 0){
                    var temp = "<option value='' ><?php echo $this->lang->line('select'); ?></option>";
                    res.resp.forEach((item) => {
                        var discpcent = 0;
                        if(item.Disc_pcent > 0){
                            discpcent = item.Disc_pcent;
                        }else{
                            discpcent = item.DiscItemPcent;
                        }
                        temp +=`<option value="${item.SchCd}" sdetcd="${item.SDetCd}" cid="${item.CID}" disccid="${item.Disc_CID}" mcatgid="${item.MCatgId}" discmcatgid="${item.Disc_MCatgId}" itemtyp="${item.ItemTyp}" discitemtyp="${item.Disc_ItemTyp}" itemid="${item.ItemId}" discitemid="${item.Disc_ItemId}" ipcd="${item.IPCd}" discipcd="${item.Disc_IPCd}" minbillamt="${item.MinBillAmt}" offertype="${item.offerType}" >${item.MinBillAmt}-${item.SchNm}</option>`;
                    });

                    $('#SchCd').html(temp);
                    $('#billBasedOffer').modal();
                }else{
                    goBill();
                }
            }else{
              alert(res.response);
            }
        });
    }

    function kotGenerate(){
        var data = $('#cartForm').serializeArray();
        $.post('<?= base_url('customer/kotGenerate') ?>', data ,function(res){
            if(res.status == 'success'){
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

    var groupNameList = [];
    var radioRate= [];
    var checkboxRate= [];
    var raidoGrpCd= [];
    var radioName= [];
    var checkboxName= [];
    var checkboxVal= [];
    var checkboxItemCd= [];
    var checkboxGrpCd= "";
    var total= 0;
    var custOfferName = [];

    function getCustomItems(itemId, itemTyp, itemPortionCode, FID, OrdNo){
        custOfferName = [];
        checkboxName = [];
        radioName = [];

        $.post('<?= base_url('customer/get_custom_item') ?>',
            {
                getCustomItem:1,
                itemId:itemId,
                itemTyp:itemTyp,
                itemPortionCode:itemPortionCode,
                FID:FID
            },
            function(res){

                if(res.status == 'success'){

                    var customItem = res.response;
                    radioList = customItem;

                    $('#radioOption').html('');
                    $('#checkboxOption').html('');
                    groupNameList = [];
                    radioRate= [];
                    checkboxRate= [];
                    for(i=0; i< customItem.length; i++){
                        
                        if(customItem[i].GrpType == 1){
                            groupNameList.push(customItem[i].ItemGrpName);
                            
                            var tempRadio = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'&nbsp;<span class="text-danger">*</span></h5>\
                                    <ul class="category-list">';
                            var details = customItem[i].Details;
                            
                            for(var r=0; r < details.length; r++){
                                var name = "'"+details[r].Name+"'";
                                tempRadio += '<li><input type="radio" name="'+customItem[i].ItemGrpName+'" value="'+details[r].ItemOptCd+'" rate="'+details[r].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+i+', '+name+', event)" /> '+details[r].Name+' <span class="float-right">('+details[r].Rate+')</span></li>';
                            }
                            tempRadio += '</ul>';
                            $('#radioOption').append(tempRadio);
                            $('#radioOption').show();
                        }else if(customItem[i].GrpType == 2){
                            
                            var tempCHK = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                    <ul class="category-list">';

                            var details = customItem[i].Details;
                            
                            for(var c=0; c < details.length; c++){
                                var name = "'"+details[c].Name+"'";
                                tempCHK += '<li><input type="checkbox" name="'+customItem[i].ItemGrpName+'" value="'+details[c].ItemOptCd+'" rate="'+details[c].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+c+', '+name+', event)" /> '+details[c].Name+' <span class="float-right">('+details[c].Rate+')</span></li>';
                            }
                            tempCHK += '</ul>';
                            $('#checkboxOption').append(tempCHK);
                            $('#checkboxOption').show();
                        }
                    }

                    $(`#order_no`).val(OrdNo);
                    $(`#order_no_itemid`).val(itemId);
                    $(`#itemTotal`).val($(`#tmp_itmrate_${OrdNo}`).val());
                    $(`#origTotal`).val($(`#tmp_origrate_${OrdNo}`).val());

                    $(`#itmTotalView`).text($(`#tmp_itmrate_${OrdNo}`).val());
                    $(`#customOfferModal`).modal('show')
                }else{
                    alert(res.response);
                }
            }
        );
    }

    function calculateTotalc(itemGrpCd, index, itemName, event) {

        element = event.currentTarget;
        var rate = element.getAttribute('rate');
        console.log('calc '+index, event.target.type, rate);
        
        if (event.target.type == "radio") {
            this.radioRate[index] = parseInt(rate);
            this.raidoGrpCd[index] = itemGrpCd;
            this.radioName[index] = itemName;
        } else {
            // console.log(event.target.checked);
            if (event.target.checked) {
                this.checkboxRate[index] = parseInt(rate);
                this.checkboxName[index] = itemName;
            } else {
                this.checkboxRate[index] = 0;
                this.checkboxName[index] = 0;
                // console.log(index);
            }
        }
        getTotalc();
    }

    function getTotalc() {
        var tmpItemRate =  $('#itemTotal').val();
        var tmpOrigRate =  $('#origTotal').val();
        var tmpOrigRateTotal = 0;

        var radioTotal = 0;
        this.radioRate.forEach(item => {
            radioTotal += parseInt(item);
        });

        var checkTotal = 0;
        this.checkboxRate.forEach(item => {
            checkTotal += parseInt(item);
        });

        this.total = parseInt(tmpItemRate) + parseInt(radioTotal) + parseInt(checkTotal);
        tmpOrigRateTotal = parseInt(tmpOrigRate) + parseInt(radioTotal) + parseInt(checkTotal);

        $('#origTotalView').val(tmpOrigRateTotal);
        $('#itmTotalView').text(this.total);
    }

    $(`#customizationForm`).on('submit', function(e){
        e.preventDefault();
        // mandatory radio options
            if(groupNameList.length > 0){
                var mandatory = false;
                //groupNameList
                var counter = 0;
                var totalGroup = groupNameList.length;
                for(var g=0; g<groupNameList.length;g++){ 
                    //comment on and check this code mandatory = false;
                    var groupName = document.getElementsByName(groupNameList[g]); 
                      for(var i=0; i<groupName.length;i++){ 
                          if(groupName[i].checked == true){ 
                              mandatory = true;
                              counter++;     
                          } 
                      } 
                  }
                   
                  // if(!mandatory){ 
                  //     alert("Please Choose the Required Field!!"); 
                  //     return false; 
                  // } 
                if(totalGroup != counter){
                    alert("Please Choose the Required Field!!"); 
                    return false; 
                }
            }

        var ordNo   = $(`#order_no`).val();
        var ItemId  = $(`#order_no_itemid`).val();
        var kitItemRate = $('#itmTotalView').text();
        var kitOrigRate  = $(`#origTotalView`).val();

        if(radioName.length > 0){
            radioName.forEach(item =>{
                custOfferName.push(item);
            });
        }

        if(checkboxName.length > 0){
            checkboxName.forEach(item =>{
                custOfferName.push(item);
            });
        }

        $.post('<?= base_url('customer/update_customItem_onKitchen') ?>',{ItemId:ItemId, OrdNo:ordNo, CustItemDesc:custOfferName.join(", "), ItemRates:kitItemRate, OrigRates:kitOrigRate},function(res){
            if(res.status == 'success'){
                $('#customOfferModal').modal('hide');
                getSendToKitchenList();
            }else{

            }
        });
    });
    
    function changeOffer(){
        var SchCd = $(`#SchCd`).val(); 
        var sdetcd = $('option:selected', $('#SchCd')).attr('sdetcd');
        var cid = $('option:selected', $('#SchCd')).attr('cid');
        var mcatgid = $('option:selected', $('#SchCd')).attr('mcatgid');
        var itemid = $('option:selected', $('#SchCd')).attr('itemid');
        var itemtyp = $('option:selected', $('#SchCd')).attr('itemtyp');
        var ipcd = $('option:selected', $('#SchCd')).attr('ipcd');

        var disccid = $('option:selected', $('#SchCd')).attr('disccid');
        var discmcatgid = $('option:selected', $('#SchCd')).attr('discmcatgid');
        var discitemid = $('option:selected', $('#SchCd')).attr('discitemid');
        var discitemtyp = $('option:selected', $('#SchCd')).attr('discitemtyp');
        var discipcd = $('option:selected', $('#SchCd')).attr('discipcd');

        var MergeNo = $('option:selected', $('#SchCd')).attr('mergeno');
        var minbillamt = $('option:selected', $('#SchCd')).attr('minbillamt');
        var offerType = $('option:selected', $('#SchCd')).attr('offertype');

        $(`#sdetcd`).val(sdetcd);
        $(`#ItemId`).prop('required', false);
        $(`.btnyes`).prop('disabled', false);
        $(`.itemidBlock`).hide();
        $.post('<?= base_url('customer/get_selection_offer') ?>', {SchCd:SchCd, sdetcd:sdetcd, cid:cid, mcatgid:mcatgid, itemid:itemid, itemtyp:itemtyp, ipcd:ipcd,disccid:disccid, discmcatgid:discmcatgid, discitemid:discitemid, discitemtyp:discitemtyp, discipcd:discipcd, MergeNo:MergeNo, minbillamt:minbillamt,offerType:offerType}, function(res){
            if(res.status == 'success'){
                var data = res.response;
                if(data.length > 0){
                    var temp = `<option value="" ><?php echo $this->lang->line('select'); ?></option>`;
                    data.forEach((item) => {
                        temp +=`<option value="${item.ItemId}" ipcd="${item.IPCd}">${item.itemName}-${item.portionName}</option>`;
                    });
                    $(`.itemidBlock`).show();
                    $('#ItemId').select2();
                    $(`#ItemId`).prop('required', true);
                    $(`#ItemId`).html(temp);
                }
                    $(`#ItemId`).prop('required', false);
            }else{
                $(`.btnyes`).prop('disabled', true);
                $(`.itemidBlock`).hide();
              alert(res.response);
            }
        });  
    }
</script>

</html>