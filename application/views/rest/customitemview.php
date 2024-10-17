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

            <!-- =======================================EntId======================= -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="TableData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('item').' '.$this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    
                                                    if(!empty($menuItem)){
                                                        $i = 1;
                                                        foreach ($menuItem as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td><button class="btn btn-sm btn-success" onclick="customizeDetail('<?= $row['Name']; ?>',<?= $row['ItemId']; ?>, <?= $row['FID']; ?>, <?= $row['ItemTyp']; ?>)">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </button></td>
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

    <!-- customOfferModal -->
    <div class="modal fade bs-example-modal-center customOfferModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel"><?= $this->lang->line('customization'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="customOfferForm">
                        
                        <div class="widget category" style="width: 100%;display: none;" id="radioOption">
                        </div>

                        <div class="widget category" style="width: 100%;display: none;" id="checkboxOption">
                            <h5 class="widget-header" id="chkHeader"></h5>
                            <ul class="category-list" id="chkList"></ul>
                        </div>

                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function () {
        $('#TableData').DataTable();
    });

    function customizeDetail(Itemname, ItemId, FID, ItemTyp){
        $.post('<?= base_url('restaurant/get_custom_items') ?>',{ItemId:ItemId, FID:FID, ItemTyp:ItemTyp, Itm_Portion:1},function(res){
            
            if(res.status == 'success'){

                  var customItem = res.response;
                        radioList = customItem;
                        $('#radioOption').html('');
                        $('#checkboxOption').html('');
                        
                        for(i=0; i< customItem.length; i++){
                            // alert(customItem[i].ItemGrpName);
                            if(customItem[i].GrpType == 1){
                                
                                var tempRadio = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                        <ul class="category-list" style="list-style:none;">';
                                var details = customItem[i].Details;
                                
                                for(var r=0; r < details.length; r++){
                                    var name = "'"+details[r].Name+"'";
                                    tempRadio += '<li><input type="radio" name="'+customItem[i].ItemGrpName+'" value="'+details[r].ItemOptCd+'" rate="'+details[r].Rate+'"  /> '+details[r].Name+'</li>';
                                }
                                tempRadio += '</ul>';

                                $('#radioOption').append(tempRadio);
                                $('#radioOption').show();
                            }else if(customItem[i].GrpType == 2){
                                
                                var tempCHK = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                        <ul class="category-list" style="list-style:none;">';

                                var details = customItem[i].Details;
                                
                                for(var c=0; c < details.length; c++){
                                    var name = "'"+details[c].Name+"'";
                                    tempCHK += '<li><input type="checkbox" name="'+customItem[i].ItemGrpName+'" value="'+details[c].ItemOptCd+'" rate="'+details[c].Rate+'" /> '+details[c].Name+'</li>';
                                }
                                tempCHK += '</ul>';
                                $('#checkboxOption').append(tempCHK);
                                $('#checkboxOption').show();
                            }
                        }
                        
                
                    $("#custom_itemId").val(ItemId);
                    $(`#exampleModalLabel`).html(Itemname);
                    $('.customOfferModal').modal('show');
                  
                }else{
                  alert(res.response);
                }

        });
    }

</script>