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
                                    <form method="post" id="menuCatForm" >
                                       <div class="row">
                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('restaurant'); ?></label>
                                                    <input  type="text" value="<?php echo authuser()->RestName; ?>" class="form-control form-control-sm" readonly>
                                                    <input  type="hidden" name="EID" id="EID" value="<?= authuser()->EID; ?>">

                                                    <input  type="hidden" name="MCatgId" id="MCatgId" >

                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('cuisine'); ?></label>
                                                    <select name="CID" id="CID" class="form-control form-control-sm select2 custom-select" required="">
                                                        <option value="">
                                                            <?= $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php
                                                            foreach ($cuisines as $key) {
                                                         ?>
                                                         <option value="<?= $key['CID']; ?>">
                                                            <?= $key['Name']; ?>
                                                        </option>
                                                     <?php } ?>
                                                    </select>   
                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('menuCategory'); ?></label>
                                                    <input type="text" name="menuName" class="form-control form-control-sm" id="menuName" required="">
                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('kitchen'); ?></label>
                                                    <select name="KitCd" id="KitCd" class="form-control form-control-sm" required="">
                                                        <option value="">
                                                            <?= $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php
                                                            foreach ($kitchens as $key) {
                                                         ?>
                                                         <option value="<?= $key['KitCd']; ?>">
                                                            <?= $key['KitName']; ?>
                                                        </option>
                                                     <?php } ?>
                                                    </select>   
                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('rank'); ?></label>
                                                    <input type="number" name="Rank" class="form-control form-control-sm" id="Rank" required="">
                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
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

                                       <div class="text-center">
                                            <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                            <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                        </div>
                                    </form> 
                                        
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tblData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('menuCategory'); ?></th>
                                                    <th><?= $this->lang->line('cuisine'); ?></th>
                                                    <th><?= $this->lang->line('kitchen'); ?></th>
                                                    <th><?= $this->lang->line('rank'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($menuCatList)){
                                                        $i = 1;
                                                        foreach ($menuCatList as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['MCatgNm']; ?></td>
                                                        <td><?= $row['cuisine']; ?></td>
                                                        <td><?= $row['kitchen']; ?></td>
                                                        <td><?= $row['Rank']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['EID'] ?>,<?= $row['CID'] ?>,<?= $row['KitCd'] ?>, <?= $row['Rank'] ?>,<?= $row['MCatgId'] ?>,'<?= $row['MCatgNm'] ?>',<?= $row['Stat'] ?>)">
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
    $('#tblData').DataTable();
    $('#CID').select2();
});

editData = (eid, cid, kitcd, rank, MCatgId, name, Stat) => {
    
    $('#menuName').val(name);
    $('#Rank').val(rank);
    $('#KitCd').val(kitcd);

    $("#EID").val(eid);
    $("#MCatgId").val(MCatgId);
    $("#CID").val(cid).trigger('change');
    $("#Stat").val(Stat);

    $('#saveBtn').hide();
    $('#updateBtn').show();
}

$('#menuCatForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/menu_category') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
        }else{
          alert(res.response);
        }
          // location.reload();
    });
});

</script>