<?php $this->load->view('layouts/admin/head'); ?>
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
                                <div class="card">
                                    <div class="card-body">
                                        <form id="reportForm" method="post">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('cuisine'); ?></label>
                                                        <select name="cuisine" id="cuisine" class="form-control form-control-sm" onchange="getMenuCat()">
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <?php 
                                                            foreach ($cuisine as $key) {
                                                            ?>
                                                            <option value="<?= $key['CID']; ?>" <?php if($key['CID'] == $cuisine){ echo 'selected'; } ?> ><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('menuCategory'); ?></label>
                                                        <select name="menucat" id="menucat" class="form-control form-control-sm select2 custom-select" >
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('order').' By'; ?></label>
                                                        <select name="orderBy" id="orderBy" class="form-control form-control-sm" >
                                                            <option value=""><?= $this->lang->line('item').' '.$this->lang->line('name'); ?></option>
                                                            <option value="qty" <?php if($orderBy == 'qty'){ echo 'selected'; } ?>><?= $this->lang->line('qty'); ?></option>
                                                            <option value="value" <?php if($orderBy == 'value'){ echo 'selected'; } ?>><?= $this->lang->line('value'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('mode'); ?></label>
                                                        <select name="modes" id="modes" class="form-control form-control-sm" >
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <option value="full_menu" <?php if($modes == 'full_menu'){ echo 'selected'; } ?>>Full Menu</option>
                                                            <option value="traded_goods" <?php if($modes == 'traded_goods'){ echo 'selected'; } ?>> Traded Goods</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('orderWise'); ?></label>
                                                        <select name="OType" id="OType" class="form-control form-control-sm" >
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($otypes as $ot ) {
                                                            ?>
                                                            <option value="<?= $ot['OType']; ?>" <?php if($ot['OType'] == $OType){ echo 'selected'; } ?> ><?= $ot['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="text" name="fromDate" id="fromDate" class="form-control form-control-sm" required="" value="<?= $fromDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" name="toDate" id="toDate" class="form-control form-control-sm"  value="<?= $toDate; ?>" />
                                                    </div>
                                                </div>

                                            </div>
                                            <input type="submit" class="btn btn-sm btn-success" value="Search">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="abcTBL" class="table table-bordered " style="width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th><?= $this->lang->line('date'); ?></th>
                                                    <th><?= $this->lang->line('cuisine'); ?></th>
                                                    <th><?= $this->lang->line('menuCategory'); ?></th>
                                                    <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('quantity'); ?></th>
                                                    <th><?= $this->lang->line('quantity'); ?> %</th>
                                                    <th><?= $this->lang->line('amount'); ?></th>
                                                    <th><?= $this->lang->line('amount'); ?> %</th>
                                                </tr>
                                                </thead>
            
                                                <tbody id="abcBody">
                                                    <?php 
                                                    if(!empty($report)){
                                                        $qty_per = 0;
                                                        $value_per = 0;
                                                        $tQty = 0;
                                                        $tVal = 0;
                                                        foreach ($report as $key) { 
                                    $qty_per = $key['Qty'] / $key['totalQty'] * 100;
                                    $value_per = $key['itemValue'] / $key['totalItemValue'] * 100;
                                    $tQty = $tQty + $key['totalQty'];
                                    $tVal = $tVal + $key['itemValue'];
                                                            ?>
                                                            <tr>
                                                                <td><?= $key['billTime']; ?></td>
                                                                <td><?= $key['CuisineName']; ?></td>
                                                                <td><?= $key['menuCatName']; ?></td>
                                                                <td><?= $key['ItemName']; ?></td>
                                                                <td><?= $key['totalQty']; ?></td>
                                                                <td><?= round($qty_per, 2); ?></td>
                                                                <td><?= $key['itemValue']; ?></td>
                                                                <td><?= round($value_per, 2); ?></td>
                                                             </tr>
                                                        <?php } ?>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b><?= $tQty; ?></b></td>
                                                                <td></td>
                                                                <td><b><?= $tVal; ?></b></td>
                                                                <td></td>
                                                             </tr>
                                                <?php    } ?>
                                                </tbody>
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
        
        <?php $this->load->view('layouts/admin/script'); ?>

<script type="text/javascript">
$(document).ready(function () {

    $('#abcTBL').DataTable({
            destroy: true, // Allows reinitialization
            order: [[0, "desc"]],
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            dom: 'lBfrtip',
        });

    $('#menucat').select2();
    $('#itemId').select2();

    $("#fromDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

    $("#toDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

}); 
    getMenuCat();
    getMenuCat = () =>{
        var CID = $('#cuisine').val();
        var menucat = "<?php echo $menucat; ?>";
        $.ajax({
            url: "<?php echo base_url('restaurant/item_list_get_category'); ?>",
            type: "post",
            data:{'CID': CID},
            success: function(data){
                data = JSON.parse(data);
                var b = '<option value = "">ALL</option>';
                for(i = 0;i<data.length;i++){
                    var sl = '';
                    if(data[i].MCatgId == menucat){
                        sl = 'selected';
                    }
                    b = b+'<option value="'+data[i].MCatgId+'" '+sl+'>'+data[i].MCatgNm+'</option>';
                }
                $('#menucat').html(b);
            }
        });
    }

</script>