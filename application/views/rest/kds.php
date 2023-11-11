<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .select_option{
        background: white;
        padding: 10px;
        display: none;
        max-height: 254px;
        overflow-y: scroll;
        position: absolute;
        width: 95%;
        z-index: 2;
        box-shadow: 2px 3px 6px #00000070;
    }

    .select_option a {
        color: black;
        width: 100%;
        display: block;
        text-decoration: none;
        margin-bottom: 5px;
        border-bottom: 1px solid #8080806b;
    }
</style>
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
                        <?php if($this->session->userdata('EDT') == 0){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <div class="form-group row">
                                                        <label class="col-md-5">Due In</label>
                                                        <input type="number" class="form-control form-control-sm col-md-7" placeholder="Minutes" name="minutes" value="<?= $minutes; ?>">

                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-4 col-4">
                                                    <div class="form-group row">
                                                        <select name="kitchen" id="kitchen" class="form-control form-control-sm">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($kitchen as $key) {
                                                            ?>
                                                            <option value="<?= $key['KitCd']; ?>" <?php if($kitcd ==$key['KitCd']){ echo 'selected'; } ?>><?= $key['KitName']; ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-2 col-2">
                                                    <input type="submit" class="btn btn-sm btn-success" value="Go">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <?php
                            $group_arr = [];
                            if(!empty($kds)){
                                foreach ($kds as $key ) {
                                    $fkot = $key['FKOTNo'];
                                    if(!isset($group_arr[$fkot])){
                                        $group_arr[$fkot] = [];
                                    }
                                    array_push($group_arr[$fkot], $key);
                                }
                            } 

                            if(!empty($group_arr)){
                                foreach ($group_arr as $fkot) {
                                    // echo "<pre>";
                                    // print_r($kot);die;
                            ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <table width="100%;" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                                            <tr>
                                              <td>KOT No: <?= $fkot[0]['KitCd'].'-'.$fkot[0]['FKOTNo']; ?>
                                              </td>
                                              <td>Table: <?= $fkot[0]['MergeNo']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Date: <?= date('d-M-Y',strtotime($fkot[0]['LstModDt']));?></td>
                                                <?php 
                                                if($this->session->userdata('EDT') > 0){
                                                ?>
                                                <td>EDT: <?= date('H ; i',strtotime($fkot[0]['EDT']));?></td>
                                            <?php } ?>
                                            </tr>
                                        </table>
                                        <table style="border-bottom: 1px solid black;" width="100%;">
                                            <tr class="tabletitle">
                                                <th class="item" style="text-align: left;">Menu Item</th>
                                                <th class="Hours" style="text-align: left;">Qty</th>
                                            </tr>
                                            <?php 
                                            $portions = '';
                                            $std = '';
                                            $ordNoArr = [];
                                            foreach ($fkot as $item) {
                                                $fkotno = $item['FKOTNo'];
                                                $ordNoArr[] = $item['OrdNo'];
                                    $etype = $this->session->userdata('EType');
                                            if($item['Portions'] != 'Std'){
                                                    $portions = ' ('.$item['Portions'].')';
                                                  }
                                                  if($item['CustItemDesc'] != 'Std'){
                                                    $std = ' - '.$item['CustItemDesc'];
                                                  }

                                                $clr = '';
                                                if($item['TA'] > 0 && $etype == 5 && ($item['OType'] == 7 || $item['OType'] == 8)){
                                                    $clr = 'blue';
                                                }
                                            ?>
                                            <tr class="service">
                                                <td class="tableitem" style="color:<?= $clr; ?>"><?= $item['ItemNm'].$std.$portions; ?><br><i><?= $item['CustRmks']; ?></i></td>
                                                <td class="tableitem"><?= $item['Qty']; ?></td>
                                            </tr>
                                            <?php }
                                            $ordNoArr = implode(',',$ordNoArr);
                                             ?>
                                            
                                        </table>
                                        <div class="text-center mt-2">
                                            <button class="btn btn-sm btn-success" onclick="changeKOT(<?= $fkotno?>,'<?= $ordNoArr?>')">Close KOT</button>
                                            <!-- <select name="kotStat" id="kotStat<?= $fkotno?>" class="form-control form-control-sm" onchange="">
                                                <option value="1">WIP</option>
                                                <option value="5">Done</option>
                                            </select> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } } ?>
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
function changeKOT(fkot,ordNo) {

    if(confirm('Assign Items shown in KOTNo: '+fkot)) {
         $.post('<?= base_url('restaurant/updateKotStat') ?>',{ordNo:ordNo},function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });
    }
}
</script>