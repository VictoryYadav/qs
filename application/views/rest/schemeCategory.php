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
                                            <input type="hidden" id="SchCatg" name="SchCatg" value="0">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('category'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="category" required="" id="category" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
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
                                            <table id="categoryTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('category'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($sch_cat)){
                                                        $i = 1;
                                                        foreach ($sch_cat as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';

                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td>
                                                            <span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['SchCatg'] ?>,'<?= $row['Name'] ?>',<?= $row['Stat'] ?>)">
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
        $('#categoryTbl').DataTable();
    });

    $('#tableForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/scheme_category') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(SchCatg, category, stat){
        
        $('#SchCatg').val(SchCatg);
        $('#category').val(category);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>