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
                                        <form method="post" id="paymentForm">
                                            <input type="hidden" id="PymtMode" name="PymtMode" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('name'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="name" placeholder="<?= $this->lang->line('name'); ?>" required="" id="name" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('company'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="Company" placeholder="<?= $this->lang->line('company'); ?>" required="" id="Company" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('test'); ?> <?= $this->lang->line('key'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="TKeyCd" placeholder="<?= $this->lang->line('key'); ?>" required="" id="TKeyCd" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('test'); ?> <?= $this->lang->line('secret'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="TSecretCd" placeholder="<?= $this->lang->line('secret'); ?>" required="" id="TSecretCd" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('live'); ?> <?= $this->lang->line('key'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="PKeyCd" placeholder="<?= $this->lang->line('key'); ?>" required="" id="PKeyCd" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('live'); ?> <?= $this->lang->line('secret'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="PSecretCd" placeholder="<?= $this->lang->line('secret'); ?>" required="" id="PSecretCd" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('url'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="CodePage1" placeholder="<?= $this->lang->line('url'); ?>" required="" id="CodePage1" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="1"><?= $this->lang->line('active'); ?></option>
                                                            <option value="0"><?= $this->lang->line('inactive'); ?></option>
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
                                            <table id="TableData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('company'); ?></th>
                                                    <th><?= $this->lang->line('test'); ?></th>
                                                    <th><?= $this->lang->line('live'); ?></th>
                                                    <th><?= $this->lang->line('url'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($lists)){
                                                        $i = 1;
                                                        foreach ($lists as $row) {
                                                            $sts = ($row['Stat'] == 1)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 1)?'success':'danger';
                                                     ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td>
                                                            <?= $row['Company']; ?><br>
                                                            <span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                            </td>
                                                        <td>
                                                            <?= $row['TKeyCd']; ?><br>
                                                            <small><?= $row['TSecretCd']; ?></small>
                                                        </td>
                                                        <td>
                                                            <?= $row['PKeyCd']; ?><br>
                                                            <small><?= $row['PSecretCd']; ?></small>
                                                        </td>
                                                        <td>
                                                            <?= $row['CodePage1']; ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['PymtMode'] ?>, '<?= $row['Name'] ?>',
                                                            '<?= $row['Company'] ?>', '<?= $row['TKeyCd'] ?>', '<?= $row['TSecretCd'] ?>', '<?= $row['PKeyCd'] ?>', '<?= $row['PSecretCd'] ?>', '<?= $row['CodePage1'] ?>', <?= $row['Stat'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php  }  } ?>
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
        $('#TableData').DataTable();
    });

    $('#paymentForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/config_payment') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(PymtMode,name, Company, TKeyCd, TSecretCd, PKeyCd, PSecretCd, CodePage1, stat){
        
        $('#PymtMode').val(PymtMode);
        $('#name').val(name);
        $('#Company').val(Company);
        $('#TKeyCd').val(TKeyCd);
        $('#TSecretCd').val(TSecretCd);
        $('#PKeyCd').val(PKeyCd);
        $('#PSecretCd').val(PSecretCd);
        $('#CodePage1').val(CodePage1);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>