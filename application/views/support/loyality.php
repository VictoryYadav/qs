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
                                        <div id="btnBlock" style="display: none;">
                                            <div>
                                                <p class="text-danger">
                                                    Please login through the link and upload your restaurant data.
                                                </p>
                                                <div class="">
                                                    <a href="" id="loginUrl">Login</a>
                                                </div>
                                            </div>
                                        </div>
                                    <form method="post" id="loyalityForm">
                                        <div class="row">

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('name'); ?></label>
                                                    <input type="text" name="Name" id="Name" class="form-control form-control-sm" required="" placeholder="Loyality Name">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Min Bill Amt</label>
                                                    <input type="number" name="MinPaidValue" id="MinPaidValue" class="form-control form-control-sm"  required="" placeholder="Minimum Paid" value="0">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Max Points Redeemable</label>
                                                    <input type="number" name="MaxPointsUsage" id="MaxPointsUsage" class="form-control form-control-sm" required="" placeholder="Maximum Points">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Bill Usage %</label>
                                                    <input type="number" name="billUsagePerc" id="billUsagePerc" class="form-control form-control-sm" required="" placeholder="Bill Usage %">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Across Outlets</label>
                                                    <select name="AcrossOutlets" id="AcrossOutlets" class="form-control form-control-sm" required="" onchange="changeOutlet()">
                                                        <option value="1"><?= $this->lang->line('yes'); ?></option>
                                                        <option value="0"><?= $this->lang->line('no'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Outlets</label>
                                                    <select name="EatOutLoyalty" id="EatOutLoyalty" class="form-control form-control-sm" required="">
                                                        <option value="0"><?= $this->lang->line('all'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Validity</label>
                                                    <input type="number" name="Validity" id="Validity" class="form-control form-control-sm" placeholder="in Days">
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table order-list" id="loyalityTBL">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 150px;">Point Value</th>
                                                                <th>Pymt.Mode</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="addBody">
                                                            <tr>
                                                                <td>
                                                                    <input type="number" placeholder="PointsValue" class="form-control form-control-sm" required name="PointsValue[]" id="PointsValue">
                                                                </td>
                                                                <td>
                                                                    <select name="PymtMode[]" id="PymtMode" class="form-control form-control-sm" required>
                                                                        <option value="0">All Payment Mode</option>
                                                                        <?php
                                                                            foreach ($modes as $key) {
                                                                            ?>
                                                                            <option value="<?= $key['PymtMode'];?>" ><?= $key['Name'];?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn btn-sm btn-danger deleteRow" id="delBtn'+counter+'"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
                                        </div>
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
                                            <table id="listTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Loyality</th>
                                                    <th>Min Bill Amt</th>
                                                    <th>Max Points</th>
                                                    <th>Across Outlets</th>
                                                    <th>Validity</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($lists)){
                                                        $i=1;
                                                        foreach ($lists as $key) {

                                                            $sts = ($key['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($key['Stat'] == 0)?'success':'danger';
                                                            $outlet = ($key['AcrossOutlets'] == 0)? $this->lang->line('no'):$this->lang->line('yes');
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?= $key['Name']; ?></td>
                                                    <td>
                                                        <?= $key['MinPaidValue']; ?></td>
                                                    <td><?= $key['MaxPointsUsage']; ?></td>
                                                    <td><?= $outlet; ?></td>
                                                    <td><?= $key['Validity'].' Days'; ?></td>
                                                    <td>
                                                        <span class="badge badge-boxed  badge-<?= $clr; ?>" onclick="changeStatus(<?= $key['LNo']; ?>, <?= $key['Stat']; ?>)" style="cursor: pointer;"><?= $sts; ?></span>
                                                    </td>
                                                </tr>
                                                <?php }
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
<!-- loader -->
<div class="container text-center" id="loadBlock" style="display: none;">
    <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('#listTBL').DataTable();
        
    });

    function changeOutlet(){

        var outlet = $('#AcrossOutlets').val();
        var temp = ``;
        if(outlet == 0){
            $.post('<?= base_url('support/get_rest_list') ?>',function(res){
                if(res.status == 'success'){
                    temp += `<option value="">Select</option>`;
                  res.response.forEach((item, index) =>{
                     temp += `<option value="${item.EID}">${item.Name}</option>`;
                  })
                  $('#EatOutLoyalty').html(temp);
                }else{
                  alert(res.response);
                }
            });    
        }else{
            temp += `<option value="0">All</option>`;
            $('#EatOutLoyalty').html(temp);
        }
    }

    $("#addrow").on("click", function () {
        var newRow = `<tr>
                        <td>
                            <input type="number" placeholder="PointsValue" class="form-control form-control-sm" required name="PointsValue[]" id="PointsValue">
                        </td>
                        <td>
                            <select name="PymtMode[]" id="PymtMode" class="form-control form-control-sm" required>
                                <option value="0">All Payment Mode</option>
                                <?php
                                    foreach ($modes as $key) {
                                    ?>
                                    <option value="<?= $key['PymtMode'];?>" ><?= $key['Name'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn btn-sm btn-danger deleteRow" id="delBtn'+counter+'"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;

        $("table.order-list").append(newRow);
    });

    $("table.order-list").on("click", "button.deleteRow", function (event) {
        $(this).closest("tr").remove();
    });

    $('#loyalityForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('support/loyality') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
              
            }else{
              alert(res.response);
            }
        });
    });

    function changeStatus(LNo, Stat){
        $.post('<?= base_url('support/updateLoyalityStats') ?>',{LNo:LNo, Stat:Stat},function(res){
            if(res.status == 'success'){
              // alert(res.response);
              location.reload();
              
            }else{
              alert(res.response);
            }
        });
    }



    
</script>