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
                        <div class="text-right mb-2">
                            <?php if($counter == 0){ ?>
                                <a href="<?= base_url('restaurant/sections'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;
                                <a href="<?= base_url('restaurant/cashier'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                                
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="kitchenForm">
                                            <input type="hidden" id="KitCd" name="KitCd" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('kitchen'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="kitchen" placeholder="<?= $this->lang->line('name'); ?>" required="" id="kitchen" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('printer'); ?> <?= $this->lang->line('name'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="PrinterName" placeholder="<?= $this->lang->line('printer'); ?>" required="" id="PrinterName" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('printer'); ?> <?= $this->lang->line('ip'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="PrintIP" placeholder="<?= $this->lang->line('ip'); ?>" required="" id="PrintIP" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                    
                                                    <div class="text-success" id="msgText"></div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="kitchenTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('kitchen'); ?></th>
                                                    <th><?= $this->lang->line('printer'); ?></th>
                                                    <th><?= $this->lang->line('ip'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($kitchens)){
                                                        $i = 1;
                                                        foreach ($kitchens as $row) {
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['KitName']; ?></td>
                                                        <td><?= $row['PrinterName']; ?></td>
                                                        <td><?= $row['PrintIP']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['KitCd'] ?>, '<?= $row['KitName'] ?>','<?= $row['PrinterName'] ?>', '<?= $row['PrintIP'] ?>', <?= $row['Stat'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php  }
                                                    } 
                                                    ?>
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
        $('#kitchenTbl').DataTable();
    });

    $('#kitchenForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/kitchen') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(kitcd,name, printerName, ip, stat){
        
        $('#KitCd').val(kitcd);
        $('#kitchen').val(name);
        $('#PrinterName').val(printerName);
        $('#PrintIP').val(ip);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>