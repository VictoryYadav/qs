<?php $this->load->view('layouts/admin/head');

$CheckOTP = $this->session->userdata('DeliveryOTP');
$EID = authuser()->EID;
$EType = $this->session->userdata('EType');
$RestName = authuser()->RestName;
 ?>
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
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" action="<?php echo base_url('restaurant/edit_stock/'.$TransId); ?>">

                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="trans_id" value="<?= $TransId; ?>">
                                            <div class="row">
                                                <div class="col-2">
                                                    <label><?= $this->lang->line('transactionNo'); ?></label><br>
                                                    <b><?= $TransId?></b>
                                                </div>
                                                <div class="col-2">
                                                    <label><?= $this->lang->line('transactionType'); ?></label><br>
                                                    <b><?= getTransType($stock['TransType']) ?></b>
                                                    <input type="hidden" id="trans_type" value="<?= $stock['TransType']?>">
                                                    
                                                </div>
                                                <div class="col-2">
                                                    <label><span id="tr_date_label"></span><?= $this->lang->line('date'); ?></label><br>
                                                    <b><?= date('d-M-Y', strtotime($stock['TransDt']))?></b>
                                                </div>

                                                <div class="col-2">
                                                    <label><?= $this->lang->line('from'); ?> - <?= $this->lang->line('to'); ?></label><br>
                                                    <b><?= getNameFromMast($stock['FrmID']).' - '.getNameFromMast($stock['ToID']); ?></b>
                                                </div>
                                                
                                                <div class="col-2">
                                                    <button class="btn btn-sm btn-danger btn-rounded" onclick="delete_trans(<?= $TransId?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                            <div class="container pt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?= $this->lang->line('item'); ?></th>
                                                                <th><?= $this->lang->line('uom'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                                <th><?= $this->lang->line('quantity'); ?></th>
                                                                <th><?= $this->lang->line('remarks'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="stock_list" id="stock_list">
                                                            <?php $n=1;foreach($stock_details as $sd){?>
                                                                <input type="hidden" name="RMDetId[]" value="<?= $sd['RMDetId']?>">
                                                                <tr>
                                                                    <td>
                                                                        <select name="ItemId[]" class="items form-control form-control-sm" id="items<?= $n?>" onchange="getUOM(this, <?= $n?>)">
                                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                                            <?php foreach($items as $key){?>
                                                                                <option value="<?= $key['RMCd']?>" <?php if($key['RMCd'] == $sd['RMCd']){echo 'selected';}?>><?= $key['RMName']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" id="uomcd<?= $n?>" value="<?= $sd['UOMCd']?>">
                                                                        <select name="UOM[]" class="uom form-control form-control-sm" id="uom<?= $n?>">
                                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" name="Rate[]" value="<?= $sd['Rate']?>" class="rate form-control form-control-sm"></td>
                                                                    <td><input type="number" name="Qty[]" value="<?= $sd['Qty']?>" class="form-control form-control-sm"></td>
                                                                    <td><input type="text" name="Remarks[]" class="form-control form-control-sm" value="<?= $sd['Rmks']?>"></td>
                                                                </tr>
                                                            <?php $n++;}?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <?php if($this->session->flashdata('error')): ?>
                                                <div class="alert alert-danger" role="alert" id="alertBlock"><?= $this->session->flashdata('error') ?></div>
                                            <?php endif; ?>
                                            <div class="text-center p-2"><button class="btn btn-primary btn-sm" type="submit"><?= $this->lang->line('update'); ?></button></div>
                                        
                                    </div>
                                </div>
                                </form>
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
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">
var cntr = <?= sizeof($stock_details)?>;    

var temp =1;
for(k = 1; k<=cntr; k++){
    var item_id = $('#items'+temp).val();
    var uom = $('#uomcd'+temp).val();

    set_uom( item_id, uom, temp);
    temp++;
}

function set_uom( item_id, uom, temp){
    $.ajax({
        url: "<?php echo base_url('restaurant/rm_ajax'); ?>",
        type: "post",
        data: {'getUOM':1, 'RMCd':item_id},
        success: response => {
            var data = JSON.parse(response);
            var b = '<option value=""><?= $this->lang->line('select'); ?></option>';
            if (data != '') {
                for(i = 0;i<data.length;i++){
                    var c = '';
                    if(data[i].UOMCd == uom){
                        c='selected';
                    }
                    b+='<option value="'+data[i].UOMCd+'" '+c+' >'+data[i].Name+'</option>';
                }
                $('#uom'+temp).html(b);
            } else {
                alert(response);
            }
        },
        error: (xhr, status, error) => {
            
        }
    });      
}

function getUOM(el, n){
    var item_id = el.value;
    $.ajax({
        url: "<?php echo base_url('restaurant/rm_ajax'); ?>",
        type: "post",
        data: {'getUOM':1, 'RMCd':item_id},
        success: response => {
            var data = JSON.parse(response);
            var b = '<option value=""><?= $this->lang->line('select'); ?></option>';
            if (data != '') {
                for(i = 0;i<data.length;i++){
                    b+='<option value="'+data[i].UOMCd+'">'+data[i].Name+'</option>';
                }
                $('#uom'+n).html(b);
            } else {
                alert(response);
            }
        },
        error: (xhr, status, error) => {
            
        }
    });
}

function delete_details(id){
    if(confirm("Are you sure want to continue?")){
        $.ajax({
            url: "<?php echo base_url('restaurant/add_stock'); ?>",
            type: "post",
            data: {'delete_details':1, 'RMDetId':id},
            success: response => {
                // console.log(response);
                location.reload();
            },
            error: (xhr, status, error) => {
                
            }
        });
    }
}
function delete_trans(id){
    if(confirm("Are you sure want to continue?")){
        $.ajax({
            url: "<?php echo base_url('restaurant/add_stock'); ?>",
            type: "post",
            data: {'delete_trans':1, 'TransId':id},
            success: response => {
                
            window.location.href = '<?php echo base_url('restaurant/stock_list'); ?>';
            return false;
            },
            error: (xhr, status, error) => {
                
            }
        });
    }
}

setTimeout(function() {
  $('#alertBlock').hide()
}, 4000);


var trans_type = "<?= $stock['TransType'] ?>";
if(trans_type != 10){
    $('.rate').attr('readonly', "")
}

</script>