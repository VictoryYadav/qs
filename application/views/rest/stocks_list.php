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
            
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restaurant/stock_list'); ?>">
                                            <div class="row">
                                                <div class="col-md-2 col-6">
                                                    <label><?= $this->lang->line('transactionNo'); ?></label>
                                                    <input type="number" name="trans_id" id="trans_id" class="form-control form-control-sm" value="<?php if($trans_id){echo $trans_id;}?>" />
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label><?= $this->lang->line('transactionType'); ?></label>
                                                    <select class="form-control form-control-sm" name="trans_type">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($trans_type as $key){?>
                                                            <option value="<?= $key['TagId']; ?>" <?php if($key['TagId'] == $trans_type_id){echo 'checked';}?>><?= $key['TDesc']; ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label><?= $this->lang->line('fromDate'); ?></label>
                                                    <input type="text" name="from_date" class="form-control form-control-sm" value="<?php if($from_date){echo $from_date;}?>" id="fromDt">
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label><?= $this->lang->line('toDate'); ?></label>
                                                    <input type="text" name="to_date" class="form-control form-control-sm" value="<?php if($to_date){echo $to_date;}?>" id="toDt">
                                                </div>
                                                <div class="col-md-1 col-2">
                                                    <label>&nbsp;</label><br>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-3 col-10">
                                                    <div class="d-none d-sm-block">
                                                    <label>&nbsp;</label><br>
                                                    <a href="<?php echo base_url('restaurant/add_stock'); ?>" class="btn btn-primary btn-sm"><?= $this->lang->line('stock').' '.$this->lang->line('transactions'); ?></a>
                                                </div>
                                                    <div class="d-sm-block d-md-none text-right">
                                                        <label>&nbsp;</label><br>
                                                    <a href="<?php echo base_url('restaurant/add_stock'); ?>" class="btn btn-primary btn-sm"><?= $this->lang->line('stock').' '.$this->lang->line('transactions'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div class="table-responsive">
                                            <table id="stock_list_table11" class="table table-striped table-bordered w-100">
                                                <thead>
                                                <tr>
                                                    <th><?= $this->lang->line('transactionNo'); ?></th>
                                                    <th><?= $this->lang->line('transactionDate'); ?></th>
                                                    <th><?= $this->lang->line('transactionType'); ?></th>
                                                    <th><?= $this->lang->line('from'); ?></th>
                                                    <th><?= $this->lang->line('to'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                  <?php 
                                                  if(!empty($stock)){
                                                    foreach($stock as $key){
                                                  ?>
                                                  <tr onclick="edit(<?= $key['TransId']?>)" >
                                                    <td><?= convertToUnicodeNumber($key['TransId']); ?></td>
                                                    <td><?= date('d-M-Y',strtotime($key['TransDt']));?></td>
                                                   <td><?= getTransType($key['TransType']) ?></td>
                                                    <td><?= $key['FName']; ?></td>
                                                    <td><?= $key['TName']; ?></td>
                                                </tr>
                                                  <?php } } ?>
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
        $('#stock_list_table11').DataTable({
            destroy: true, // Allows reinitialization
            order: [[0, "desc"]],
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            dom: 'lBfrtip',
        });

        $("#fromDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });

        $("#toDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });
    });

    function edit(id){
        window.location.href="<?php echo base_url();?>restaurant/edit_stock/"+id;
    }
</script>