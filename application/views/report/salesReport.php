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
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="text" name="fromDate" id="fromDate" class="form-control form-control-sm" required="" value="<?= $fromDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" name="toDate" id="toDate" class="form-control form-control-sm" required="" value="<?= $toDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('mode'); ?></label>
                                                        <select name="modes" id="modes" class="form-control form-control-sm" >
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <option value="full_menu" <?php if($modes == 'full_menu'){ echo 'selected'; } ?>><?= $this->lang->line('rawMaterial'); ?></option>
                                                            <option value="traded_goods" <?php if($modes == 'traded_goods'){ echo 'selected'; } ?>><?= $this->lang->line('finish'); ?> <?= $this->lang->line('goods'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('category'); ?></label>
                                                        <select name="rmcat" id="rmcat" class="form-control form-control-sm" >
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <?php
                                                            if(!empty($catList)){
                                                                foreach ($catList as $key) {
                                                             ?>
                                                            <option value="<?= $key['RMCatgCd'] ?>" <?php if($rmcat == $key['RMCatgCd']){ echo 'selected'; } ?> ><?= $key['RMCatgName'] ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search'); ?>">
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
                                            <table id="abcTBL" class="table table-bordered ">
                                                <thead>
                                                <tr>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('quantity'); ?></th>
                                                    <th><?= $this->lang->line('amount'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody id="abcBody">
                                                    <?php 
                                                    if(!empty($report)){
                                                        foreach ($report as $key) {?>
                                                        <tr>
                                                            <td><?= $key['menuItem']; ?></td>
                                                            <td><?= $key['Qty']; ?></td>
                                                            <td><?= $key['itemValue']; ?></td>
                                                         </tr>

                                                 <?php  } } ?>
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

        $("#fromDate").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });

        $("#toDate").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });

    }); 

</script>