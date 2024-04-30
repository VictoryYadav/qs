<?php $this->load->view('layouts/admin/head'); ?>
<style>            
    #table-view {
        /*height: 500px;*/
        overflow-y: auto;
        overflow-x: hidden;
    }
    .items-table .table thead th {
        font-size: 12px;
        border: none;
    }
    .items-table .table td {
        font-size: 12px;
    }
    .items-table {
        color: #fff;
    }
    .items-data {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .items-data::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    .items-data::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }

    .items-data::-webkit-scrollbar-thumb {
        background-color: #aaa;
        border-radius: 10px;
    }
    #table-view::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    #table-view::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }

    #table-view::-webkit-scrollbar-thumb {
        background-color: #aaa;
        border-radius: 10px;
    }
    #mydiv {
        position: absolute;
        z-index: 9;
        background-color: #f1f1f1;
        border: 1px solid #d3d3d3;
        text-align: center;
    }
    @media (orientation:portrait) {
        .Po_to_land1 {
            display: none;
        }
    }

    @media (orientation:landscape) {
        .Po_to_land {
            display: none;
        }
    }
</style>
 
        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="Po_to_land" style="width:100%;height:100%;">
                <img src="<?= base_url();?>assets/img/ptl.gif" alt="" width:33px; style="padding-left:29px;">
                <span style="padding: 2px;">Screen Available only Landscape mode on Mobile Devices.</span>
            </div> -->
            
            <div class="main-content">
                <!-- <div class="main-content Po_to_land1"> -->

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row" style="margin-bottom: -16px;">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('counter'); ?></label>
                                                    <select class="form-control form-control-sm" id="kitchen-code" onchange="getTableView1();">  
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                        if(!empty($DispenseAccess)){
                                                        foreach($DispenseAccess as $key => $data) { ?>
                                                        <option value="<?= $data['DCd']?>"><?= $data['Name']?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label><?= $this->lang->line('type'); ?></label>
                                                <select class="form-control form-control-sm" id="dispMode" onchange="getTableView();">  
                                                    <option value="0"><?= $this->lang->line('all'); ?></option> 
                                                    <option value="101"><?= $this->lang->line('thirdParty'); ?></option>
                                                    <option value="105"><?= $this->lang->line('takeAway'); ?></option>
                                                    <option value="110"><?= $this->lang->line('deliver'); ?></option>
                                                </select>

                                            </div>
                                            <div class="col-6">
                                                <div class="text-right" id="showActionBtn">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-7">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div class="items-data" id="order-view-parent">
                                                <table class="display" id="order-view-table" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th><?= $this->lang->line('billNo'); ?></th>
                                                            <th><?= $this->lang->line('packs'); ?></th>
                                                            <th><?= $this->lang->line('mobile'); ?></th>
                                                            <th><?= $this->lang->line('thirdParty'); ?></th>
                                                            <th><?= $this->lang->line('refNo'); ?></th>
                                                            <!-- <th>Order</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-view"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 col-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table" >
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('itemName'); ?></th>
                                                        <th><?= $this->lang->line('quantity'); ?></th>
                                                        <th><?= $this->lang->line('remarks'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="item-view-tbody1"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/admin/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

    <!-- order_deliver_otp Modal -->
    <div class="modal fade" id="deliveryModal" role="dialog">
        <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title order_deliver_header"><?= $this->lang->line('deliveryOTP'); ?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding: 1.3rem;">
            <form class="form-inline" style="margin-bottom: 1px;place-content: center;">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" id="delivery_otp" placeholder="Please Enter OTP">
                </div>
                <input type="hidden" name="del_cno" id="del_cno">
                <input type="hidden" name="del_billid" id="del_billid">
                <input type="hidden" name="del_mobile" id="del_mobile">
                <input type="hidden" name="del_otype" id="del_otype">
                <input type="hidden" name="del_dispcounter" id="del_dispcounter">
                <input type="hidden" name="del_dcd" id="del_dcd">
            </form>
            <small class="text-danger del_msg"></small>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" style="width: 100%;" onclick="checkDelOTP()"><?= $this->lang->line('verifyOTP'); ?></button>
            </div>
        </div>
        </div>
    </div>
      
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#order-view-table').DataTable();
        $('#order_details').DataTable();
    });


            
    getTableView();

    function getTableView1(){
        $('#dispMode').val(0);
        getTableView();        
    }

    function getTableView() {
        $('#item-view-tbody1').empty();
        
        var DispCd = $('#kitchen-code').val();
        var dispMode = $('#dispMode').val();
        var dispText = $('#kitchen-code').find('option:selected').text();
        dispText = "'"+dispText+"'";
        if(DispCd > 0){
            $.ajax({
                url: "<?php echo base_url('restaurant/order_delivery'); ?>",
                type: "post",
                data: {
                    getOrderDetails: 1,
                    DispCd : DispCd,
                    dispMode:dispMode
                },
                dataType: 'json',
                success: response => {
                    var template = ``;
                    console.log(response);
                    // alert(response.status);
                    if (response.status) {                        
                        response
                            .kitchenData
                            .forEach(item => {
                                var CellNo = item.CellNo;
                                if(CellNo ==''){
                                    CellNo = 0;
                                }
                                bcolor = ``;
                                if(item.KStat == 5){
                                    bcolor = `#a7efa7`;
                                }
                                    template += `
                        <tr cno="${item.CNo}" style="background:${bcolor};">
                            <td><input type="radio" name="selectOption" onchange="showAction('${item.CNo}', ${item.CustId},${item.BillId}, ${CellNo}, ${item.OType}, ${dispText}, ${item.DCd})" /> &nbsp;${convertToUnicodeNo(item.BillNo)}</td>
                            <td>${convertToUnicodeNo(item.Qty)}</td>
                            <td>${convertToUnicodeNo(item.CellNo)}</td>
                            <td>${convertToUnicodeNo(item.thirdPartyName)}</td>
                            <td>${item.TPRefNo}</td>
                        </tr>`;
                });
                    }
                    $("#table-view").html(template);
                    $('#mydiv').hide();
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    }

    function showAction(CNo, CustId, BillId, mobile, oType, dispCounter, DCd){
        dispCounter = "'"+dispCounter+"'";
        var btn = '';
        var url = "<?= base_url('restaurant/print/');?>"+BillId;
        btn += '<a onclick="dispenseNotification('+BillId+','+mobile+','+oType+','+dispCounter+')" class="btn btn-sm btn-danger btn-rounded" title="Send Message"><i class="fa fa-bullhorn"></i></a>\
            | <button onclick="deliveryNotification('+CNo+','+BillId+','+mobile+','+oType+','+dispCounter+', '+DCd+')" class="btn btn-sm btn-primary btn-rounded" title="Deliver"><i class="fa fa-thumbs-up" aria-hidden="true"></i></button>\
            | <a href="'+url+'" class="btn btn-sm btn-warning btn-rounded" title="Print"><i class="fa fa-print" aria-hidden="true"></i></a>                    ';

        $('#showActionBtn').html(btn);
        handleDetails(CNo);   
    }

    function dispenseNotification(billId, mobile, oType, dispCounter){
        $.post('<?= base_url('restaurant/dispense_notification') ?>',{billId:billId, mobile:mobile, oType:oType, dispCounter:dispCounter},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            // location.reload();
        });
    }

    function deliveryNotification(CNo, billId, mobile, oType, dispCounter, DCd){
        var del_otp = "<?php echo $this->session->userdata('Dispense_OTP'); ?>";
        if(del_otp > 0){
            $('#del_cno').val(CNo);
            $('#del_billid').val(billId);
            $('#del_mobile').val(mobile);
            $('#del_otype').val(oType);
            $('#del_dispcounter').val(dispCounter);
            $('#del_dcd').val(DCd);
            $('#deliveryModal').modal("show"); 
        }else{
            deliveryAjax(CNo, billId, mobile, oType, dispCounter, DCd);
        }
    }

    function deliveryAjax(CNo, billId, mobile, oType, dispCounter, DCd){
        $.post('<?= base_url('restaurant/delivery_notification') ?>',{CNo:CNo, billId:billId, mobile:mobile, oType:oType, dispCounter:dispCounter, DCd:DCd},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
    }

    function checkDelOTP(){
        var del_otp = $('#delivery_otp').val();
        var CNo = $('#del_cno').val();
        var billId = $('#del_billid').val();
        var mobile = $('#del_mobile').val();
        var oType = $('#del_otype').val();
        var dispCounter = $('#del_dispcounter').val();
        var DCd = $('#del_dcd').val();
        if(del_otp.length >= 4 ){
            $.post('<?= base_url('restaurant/verifyDelOTP') ?>',{otp:del_otp},function(res){
                if(res.status == 'success'){
                  // alert(res.response);
                  $('#deliveryModal').modal("hide"); 
                  deliveryAjax(CNo, billId, mobile, oType, dispCounter, DCd);
                }else{
                  $('.del_msg').html(res.response);
                }
            });
        }else{
            $('.del_msg').html('Enter OTP');
        }
    }         

    function handleDetails(CNo) {
        // alert(uKotNo);
        var DispCd = $('#kitchen-code').val();
        if (CNo !== 0) {
            // console.log(uKotNo);
            // $("#order-detail-modal").modal('show');
            $.ajax({
                url: "<?php echo base_url('restaurant/order_delivery'); ?>",
                type: "post",
                data: {
                    getOrderList: 1,
                    CNo: CNo,
                    DispCd :DispCd
                },
                dataType: 'json',
                success: (response) => {
                    console.log(response);
                    var template = ``;
                    if (response.status) {

                        response
                            .orderList
                            .forEach((item) => {
                                var item_name = '';
                                if(item.Itm_Portion > 4){
                                    item_name = item.ItemNm+'-'+item.ipName;
                                }else{
                                    item_name += item.ItemNm;
                                }

                                bcolor = ``;
                                if(item.KStat == 5){
                                    bcolor = `#a7efa7`;
                                }

                                template += `<tr class="${ (
                                    item.Qty == item.AQty
                                        ? 'h-deliver'
                                        : ''
                                                )}" style="background:${bcolor};">
                                        <td>${item_name}</td>
                                        <td>${convertToUnicodeNo(item.Qty)}</td>
                                        <td>${item.CustRmks}</td>
                                    </tr>`;
                            });
                    }

                    // $("#order-list").html(template);
                    $("#item-view-tbody1").html(template);
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        } else {
            alert("Please Select Order");
        }
    }

</script>

