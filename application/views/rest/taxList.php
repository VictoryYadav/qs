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
                                        <form method="post" id="taxForm">
                                            <input type="hidden" id="TNo" name="TNo" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('tax'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="Name1" placeholder="<?= $this->lang->line('name'); ?>" required="" id="Name1" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('percent'); ?> </label>
                                                        <input type="text" class="form-control form-control-sm" name="TaxPcent" placeholder="<?= $this->lang->line('percent'); ?>" required="" id="TaxPcent" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('included'); ?></label>
                                                        <select class="form-control form-control-sm" name="Included" required="" id="Included">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1"><?= $this->lang->line('included').' '.$this->lang->line('tax'); ?></option>
                                                            <option value="5"><?= $this->lang->line('chargedSeparately'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('tax'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select class="form-control form-control-sm" name="TaxType" required="" id="TaxType">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1"><?= $this->lang->line('bar'); ?></option>
                                                            <option value="2"><?= $this->lang->line('food'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromDate'); ?> </label>
                                                        <input type="date" class="form-control form-control-sm" name="FrmDt" required="" id="FrmDt" value="<?= date('Y-m-d'); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toDate'); ?> </label>
                                                        <input type="date" class="form-control form-control-sm" name="EndDt" required="" id="EndDt" value="<?= date('Y-m-d'); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('country'); ?></label>
                                                        <select class="form-control form-control-sm" name="CountryCd" required="" id="CountryCd">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                                <?php 
                                                                foreach ($country as $key) { ?>
                                                                    <option value="<?= $key['phone_code']; ?>"><?= $key['country_name']; ?></option>
                                                                <?php } ?> 
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('rank'); ?> </label>
                                                        <input type="text" class="form-control form-control-sm" name="Rank" placeholder="<?= $this->lang->line('rank'); ?>" required="" id="Rank" autocomplete="off">
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
                                            <table id="cashierTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th><?= $this->lang->line('tax'); ?></th>
                                                    <th><?= $this->lang->line('percent'); ?></th>
                                                    <th><?= $this->lang->line('included').' '.$this->lang->line('tax'); ?></th>
                                                    <th><?= $this->lang->line('tax'); ?> <?= $this->lang->line('type'); ?></th>
                                                    <th><?= $this->lang->line('date'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($taxList)){
                                                        foreach ($taxList as $row) {
                                                            $taxTyp = ($row['TaxType'] == 1)?$this->lang->line('bar'):$this->lang->line('food');

                                                            $Included = ($row['Included'] == 1)?$this->lang->line('included'):$this->lang->line('chargedSeparately');

                                                            $stat = ($row['Stat'] == 1)?$this->lang->line('inactive'):$this->lang->line('active');
                                                         ?>
                                                    <tr>
                                                        <td><?= $row['taxName']; ?></td>
                                                        <td><?= $row['TaxPcent']; ?></td>
                                                        <td><?= $Included; ?></td>
                                                        <td><?= $taxTyp; ?></td>
                                                        <td><?= date('d-M-Y', strtotime($row['FrmDt'])).' to '.date('d-M-Y', strtotime($row['EndDt'])); ?></td>
                                                        <td><?= $stat; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['TNo']; ?>, <?= $row['CountryCd']; ?>, '<?= $row['Name1']; ?>', '<?= $row['TaxPcent']; ?>', <?= $row['Included']; ?>, <?= $row['TaxType']; ?>, <?= $row['Rank']; ?>, '<?= $row['FrmDt']; ?>', '<?= $row['EndDt']; ?>', <?= $row['Stat']; ?>)">
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

    $('#taxForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/tax') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(TNo, cd, taxName, tPcent, taxInclude, taxTyp, rank, fdate, edate, stat){
        
        $('#TNo').val(TNo);
        $('#CountryCd').val(cd);
        $('#Name1').val(taxName);
        $('#TaxPcent').val(tPcent);
        $('#Included').val(taxInclude);
        $('#TaxType').val(taxTyp);
        $('#Rank').val(rank);
        $('#FrmDt').val(fdate);
        $('#EndDt').val(edate);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>