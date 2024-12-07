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
                                    <form method="post" id="discountForm" >
                                        <input type="hidden" name="discId" id="discId" >

                                       <div class="row">

                                        <div class="col-md-4 col-6">
                                            <div class="form-group">
                                                <label for=""><?= $this->lang->line('category').' ('.$this->lang->line('english').')'; ?></label>
                                                <input  type="text" class="form-control form-control-sm" name="Name1" id="name1" placeholder="<?= $this->lang->line('category'); ?>">
                                            </div>
                                       </div>
                                        <?php
                                        for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('category').' ('.$languages[$i]['LngName'].')'; ?></label>
                                                    <input  type="text" class="form-control form-control-sm" name="Name<?= $languages[$i]['LCd']; ?>" id="name<?= $languages[$i]['LCd']; ?>" placeholder="Name">
                                                </div>
                                           </div>
                                       <?php } ?>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('percent'); ?></label>
                                                    <input type="number" name="pcent" class="form-control form-control-sm" id="pcent" required="" autocomplete="off" placeholder="<?= $this->lang->line('percent'); ?>">
                                                </div>
                                           </div>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('mode'); ?></label>
                                                    <select name="stat" id="stat" class="form-control form-control-sm" required="">
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
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th>%</th>
                                                    <th><?= $this->lang->line('visitNo'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($discounts)){
                                                        $i = 1;
                                                        foreach ($discounts as $row) {
                                                            $sts = ($row['stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['stat'] == 0)?'success':'danger';
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td><?= $row['pcent']; ?></td>
                                                        <td><?= $row['visitNo']; ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['discId'] ?>, <?= $row['pcent'] ?>,'<?= $row['Name1'] ?>','<?= $row['Name2'] ?>','<?= $row['Name3'] ?>','<?= $row['Name4'] ?>',<?= $row['stat'] ?>)">
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
});

editData = (discId, pcent, name, name2, name3, name4, stat) => {
    
    $('#discId').val(discId);
    $('#pcent').val(pcent);
    $('#name1').val(name);
    $('#name2').val(name2);
    $('#name3').val(name3);
    $('#name4').val(name4);
    $("#stat").val(stat);

    $('#saveBtn').hide();
    $('#updateBtn').show();
}

$('#discountForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/discount_category') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
        }else{
          alert(res.response);
        }
          location.reload();
    });
});

</script>