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
                                        <form method="post" id="tableForm">
                                            <input type="hidden" id="RoleId" name="RoleId" value="0">
                                            <div class="row">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('menu'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="menu" required="" id="menu" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('url'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="pageUrl" required="" id="pageUrl" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('role'); ?></label>
                                                        <select name="roleGroup" id="roleGroup" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="1"><?= $this->lang->line('master'); ?></option>
                                                            <option value="2"><?= $this->lang->line('operation'); ?></option>
                                                            <option value="3"><?= $this->lang->line('report'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('rank'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" name="Rank" required="" id="Rank" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="0"><?= $this->lang->line('user'); ?></option>
                                                            <option value="0"><?= $this->lang->line('support'); ?></option>
                                                            <option value="5"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                </div>
                                                <div class="text-success" id="msgText"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="menutable" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('menu'); ?></th>
                                                    <th><?= $this->lang->line('url'); ?></th>
                                                    <th><?= $this->lang->line('rank'); ?></th>
                                                    <th><?= $this->lang->line('role'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($menus)){
                                                        $i = 1;
                                                        foreach ($menus as $row) {
                                                            $sts = $this->lang->line('user');
                                                            $clr = 'success';
                                                            if(in_array($row['Stat'], array(1,2,3))){
                                                                $sts = $this->lang->line('support');
                                                                $clr = 'info';
                                                            }else if($row['Stat'] == 5){
                                                                $sts = $this->lang->line('inactive');
                                                                $clr = 'danger';
                                                            }

                                                            $role = 'Not Defined';
                                                            if($row['roleGroup'] == 1){
                                                                $role = 'Master';
                                                            }else if($row['roleGroup'] == 2){
                                                                $role = 'Operation';
                                                            }else if($row['roleGroup'] == 3){
                                                                $role = 'Report';
                                                            }

                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['LngName']; ?></td>
                                                        <td><?= $row['pageUrl']; ?></td>
                                                        <td><?= $row['Rank']; ?></td>
                                                        <td><?= $role; ?></td>
                                                        <td>
                                                            <span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RoleId'] ?>,'<?= $row['LngName'] ?>','<?= $row['pageUrl'] ?>', <?= $row['Rank'] ?>,<?= $row['Stat'] ?>)">
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
        $('#menutable').DataTable();
    });

    $('#tableForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/menu_list') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
              location.reload();
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(RoleId, menu, url, rank, stat){
        
        $('#RoleId').val(RoleId);
        $('#menu').val(menu);
        $('#pageUrl').val(url);
        $('#Rank').val(rank);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>