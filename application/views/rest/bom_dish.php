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
                                        <div class="col-md-12 text-right">
                                            <a href="<?= base_url('restaurant/add_bom_dish'); ?>" class="btn btn-sm btn-success btn-rounded mb-1" ><i class="fa fa-plus"></i> <?= $this->lang->line('new'); ?></a>
                                        </div>

                                            <div class="table-responsive">          
                                                <table class="table table-bordered" id="bomDish">
                                                    <thead>
                                                      <tr>
                                                        <th>#</th>
                                                        <th><?= $this->lang->line('item'); ?></th>
                                                        <th><?= $this->lang->line('quantity'); ?></th>
                                                        <th><?= $this->lang->line('costing'); ?></th>
                                                        <th><?= $this->lang->line('type'); ?></th>
                                                        <th><?= $this->lang->line('aciton'); ?></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        if(!empty($boms)){
                                                            foreach ($boms as $key) { 
                                                                $type = ($key['BOMDishTyp'] == 1)?'Finish Goods':'Intermediate';
                                                                ?>
                                                                <tr>
                                                                    <td><?= $key['BOMNo']; ?></td>
                                                                    <td><?= $key['Name']; ?></td>
                                                                    <td><?= $key['Qty']; ?></td>
                                                                    <td><?= $key['Costing']; ?></td>
                                                                    <td><?= $type; ?></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url('restaurant/edit_bom/'.$key['BOMNo']);?>" class="btn btn-sm btn-primary">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>

                                                                        <button class="btn btn-sm btn-danger" onclick="deleteBom(<?= $key['BOMNo']?>)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </a>
                                                                    </td>
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
        $('#bomDish').DataTable();
    });

    function deleteBom(BOMNo){
        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/delete_bom/') ?>', {BOMNo:BOMNo},function(res){
            if(res.status == 'success'){
              alert(res.response);
            location.reload();
            }else{
              $('#msgText').html(res.response);
            }
        });
    }
    
</script>