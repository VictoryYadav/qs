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
                                        <form method="post" id="rmForm">
                                            <input type="hidden" id="RCd" name="RCd" value="0">
                                            <div class="row">

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>RMCd</label>
                                                        <select class="form-control form-control-sm" name="RMCd" required="" id="RMCd">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach ($items as $key) { ?>
                                                                    <option value="<?= $key['RMCd']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>UOMCd</label>
                                                        <select class="form-control form-control-sm" name="UOMCd" required="" id="UOMCd">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach ($uomList as $key) { ?>
                                                                    <option value="<?= $key['UOMCd']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
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

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                    </div>
                                                    
                                                </div>
                                                    <div class="text-success" id="msgText"></div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="cashierTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th>RM Items</th>
                                                    <th>RMUOM</th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($rmUOMs)){
                                                        $i=1;
                                                        foreach ($rmUOMs as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['RMItems']; ?></td>
                                                        <td><?= $row['UOMName']; ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RCd']; ?>, <?= $row['RMCd']; ?>, <?= $row['UOMCd']; ?>, <?= $row['Stat']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php    }
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
        $('#cashierTbl').DataTable();
    });

    $('#rmForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/rm_items_uom') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(RCd,RMCd, UOMCd, stat){
        
        $('#RCd').val(RCd);
        $('#RMCd').val(RMCd);
        $('#UOMCd').val(UOMCd);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>