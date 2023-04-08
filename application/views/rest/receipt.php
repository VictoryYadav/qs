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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <h1>Remain to display bill No <?= $billId; ?></h1> -->
                                        <div id="download-to-pdf" class="container" style="margin-top: 25px;overflow-y: scroll;height: 78vh;">
                                            <div class="text-center">
                                                <p style="font-weight: bold;"><?= $hotelName ?></p>
                                                <p style="margin-bottom: unset;"><?= $address ?>, <?= $city ?>-<?= $pincode ?></p>
                                                <p style="margin-bottom: unset;">Phone: <?= $phone ?></p>
                                                <?php if ($gstno != '-') { ?>
                                                    <p style="margin-bottom: unset;">GST NO: <?= $gstno ?></p>
                                                <?php } ?>
                                                <?php if ($fssaino != '-') { ?>
                                                    <p style="margin-bottom: unset;">FSSAI NO: <?= $fssaino ?></p>
                                                <?php } ?>
                                                <?php if ($cinno != '-') { ?>
                                                    <p style="margin-bottom: unset;">CIN NO: <?= $cinno ?></p>
                                                <?php } ?>
                                            </div>

                                            <div style="border-bottom: 1px solid;"></div>
                                            
                                            <?php if($CustNo == 0){?>

                                                <div class="row">

                                                    <div class="col-6"> 
                                                    <!-- Name : Ashitosh salvi -->
                                                    </div>

                                                    <div class="col-6" style=" text-align: right;"> 
                                                        Cell No: <?= $CellNo; ?>
                                                    </div>

                                                </div>

                                            <?php } ?>

                                            <div style="border-bottom: 1px solid;"></div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p style="margin-bottom: unset;font-weight: bold;">Bill No: <?= $billno ?></p>
                                                </div>
                                                <?php if ($CustNo != "0") : ?>
                                                    <div class="col-6">
                                                        <p style="margin-bottom: unset;">Ord: <b><?= $CustNo ?></b></p>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-12">
                                                    <p style="margin-bottom: unset;">DATE: <?= $dateOfBill ?></p>
                                                    <!-- <p><?= $dateOfBill ?></p> -->
                                                </div>
                                            </div>
                                            
                                            <div style="border-bottom: 1px solid;"></div>
                                            <!-- bill body here -->

                                            <?php
                                                // get repository  : billing/bill_print_body.repo.php
                                                // include('repository/billing/bill_print_body.repo.php');
                                            echo $billBody;
                                            ?>

                                            <div style="margin-top: 10px;">
                                                <table style="width:100%">
                                                
                                                    <?php if ($bservecharge > 0) : ?>
                                                        <tr>
                                                            <td style="font-weight: bold;">Service Charges @ <?= $bservecharge ?></td>
                                                            <td style="text-align: right;"><?= $bservecharge*$itemTotal/100; ?>
                                                                
                                                                <!-- <?= $bservecharge*$itemTotal/100; ?> -->
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php if ($tipamt > 0) : ?>
                                                        <tr>
                                                            <th>TIP Amount</th>
                                                            <td style="text-align: right;"><?= $tipamt ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    
                                                    <?php
                                                        if($tipamt || $total_discount_amount != 0 || $total_packing_charge_amount || $total_delivery_charge_amount){?>
                                                        <tr style="border-bottom: 1px solid;">
                                                            <th></th>
                                                            <td></td>
                                                        </tr>
                                                    <?php   } ?>

                                                    <!-- Total Discount section -->
                                                    <?php
                                                        if ($total_discount_amount != 0) {?>
                                                            <tr>
                                                                <td>Discounts Availed</td>
                                                                <td style="text-align: right;"><?= $total_discount_amount; ?></td>
                                                            </tr>
                                                    <?php   }?>
                                                    
                                                    <!-- Packing Charges section -->
                                                    <?php
                                                        if ($total_packing_charge_amount > 0) { ?>
                                                            <tr>
                                                                <td>Packing Charges</td>
                                                                <td style="text-align: right;"><?= $total_packing_charge_amount; ?></td>
                                                            </tr>
                                                    <?php   }?>

                                                    <!-- Delivery Charges section -->
                                                    <?php
                                                        if ($total_delivery_charge_amount > 0) { ?>
                                                            <tr>
                                                                <td>Delivery Charge</td>
                                                                <td style="text-align: right;"><?= $total_delivery_charge_amount; ?></td>
                                                            </tr>
                                                    <?php   }?>

                                                    <?php
                                                        if($total_discount_amount != 0 || $total_packing_charge_amount || $total_delivery_charge_amount){?>
                                                        <tr style=" border-bottom: 1px solid;">
                                                            <th></th>
                                                            <td></td>
                                                        </tr>
                                                    <?php   } ?>
                                                
                                                    <tr>
                                                        <td style="font-weight: bold;">GRAND TOTAL</td>
                                                        <td style="text-align: right;font-weight: bold;"><?= $totamt; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div style="border-bottom: 1px solid;"></div>
                                            <p><?= $thankuline ?></p>
                                            <br>
                                            <?php if(isset($_GET['ShowRatings']) && $_GET['ShowRatings'] == 1){
                                                $q = "SELECT AVG(ServRtng) as serv, AVG(AmbRtng) as amb,avg(VFMRtng) as vfm, AVG(rd.ItemRtng) as itm FROM Ratings r, RatingDet rd WHERE r.BillId= $billId and r.EID=".$_GET['EID'];
                                                // print_r($q);exit();
                                                $ra = $this->db2->query($q)->result_array();
                                                // print_r($ra);exit();
                                                if(!empty($ra)){?>
                                                    <p><b>Group Rating : </b></p>
                                                    <table class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>Ambience</th>
                                                                <th>Service</th>
                                                                <th>Food</th>
                                                                <th>VFM<sup>*</sup></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><?php echo !empty($ra[0]['amb'])?round($ra[0]['amb']): 0;?></td>
                                                                <td><?php echo !empty($ra[0]['serv'])?round($ra[0]['serv']): 0;?></td>
                                                                <td><?php echo !empty($ra[0]['itm'])?round($ra[0]['itm']): 0;?></td>
                                                                <td><?php echo !empty($ra[0]['vfm'])?round($ra[0]['vfm']): 0;?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <sub>* Value For Money</sub>
                                                <?php }
                                                ?>


                                                
                                            <?php }?>
                                        </div>

                                        <div id="editor"></div>

                                        <?php if(!isset($_GET['restaurant']) && !$_GET['restaurant'] == 1):?>
                                            <div class="navbar menu-footer" style="overflow: initial !important;    padding-left: 10px;
                                                                                        padding-right: 10px;">
                                                
                                                    <!-- <button id="download-pdf" class="btn btn-primary">Download</button> style="width: 47%;" -->
                                                    <?php if(isset($_GET['ShowRatings']) && $_GET['ShowRatings'] == 1){?>
                                                        <div class="col-12 row text-center" style="padding:0px;padding-left: 35%;">
                                                            <button type="button" class="btn" onclick="goBack()" style="width: 50%;">Menu</button>
                                                        </div>
                                                    <?php }else{?>
                                                        <div class="col-12 row text-center" style="padding:0px;">
                                                            <div class="col-6 text-center"><button type="button" class="btn" onclick="goBack()" style="width: 100%;">Menu</button></div>
                                                            <div class="col-6 text-center"><button type="button" class="btn btn-primary" onclick="bill_page(<?= $billId?>)" style="width: 100%;">Rating</button></div>
                                                        </div>
                                                    <?php }?>
                                            <!-- </div> -->

                                            <?php if (isset($_SESSION['CustId'])) { ?>
                                                
                                                    <!-- <a href="logout.php" class="active">
                                                                                                <img src="assets/img/logout.png" width="15" height="15" style="margin-left:10px; margin-bottom: 3px;"><span>SignOut</span> </a> -->
                            
                                                    <div class="btn-group dropup">
                                                        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
                            
                                                            <img src="assets/img/menu.svg" width="33" height="20">Account</a>
                                                        <div class="dropdown-menu">
                                                            <!-- Dropdown menu links -->
                                                            <?php if (Session::get('CellNo') != '1111111111') { ?>
                                                                <a class="dropdown-item" href="edit_user.php">Edit Profile</a>
                                                                <a class="dropdown-item" href="cust_history.php">Transaction</a>
                                                                <a class="dropdown-item" href="#">Refer Outlet</a>
                                                            <?php  } ?>
                                                            <a class="dropdown-item" href="logout.php">SignOut</a>
                                                        </div>
                                                    </div>
                            
                                                    <div class="btn-group dropup">
                                                        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
                                                            <img src="assets/img/feedback.svg" width="33" height="20">AboutUs</a>
                                                        <div class="dropdown-menu">
                                                            <?php if (Session::get('CellNo') != '1111111111') { ?>
                                                                <a class="dropdown-item" href="#">Review Us</a>
                                                            <?php } ?>
                                                            <a class="dropdown-item" href="#">T & C</a>
                                                            <a class="dropdown-item" href="#">Testimonials</a>
                                                            <a class="dropdown-item" href="#">Contact Us</a>
                                                        </div>
                                                    </div>
                            
                                                    <div class="btn-group dropup">
                                                        <a href="#contact" width="33" height="20">
                                                            <img src="assets/img/home.svg" width="33" height="20">Offers</a>
                                                    </div>
                                                    <!-- <div class="">
                                                                                            <a href="#contact">
                                                                                                <img src="assets/img/bill_split.svg" width="33" height="20">RateOrder</a>
                                                                                        </div> -->
                            
                                                    <?php if ($EType != 5) : ?>
                                                        <div class="btn-group dropup">
                                                            <a href="order_details.php">
                                                                <img src="assets/img/inbox.svg" width="33" height="20">OrderList</a>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="btn-group dropup">
                                                            <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
                                                                <img src="assets/img/inbox.svg" width="33" height="20">OrderList</a>
                                                            <div class="dropdown-menu" style="right: 0; left: auto;">
                                                                <!-- Dropdown menu links -->
                                                                <a class="dropdown-item" href="order_details.php">OrderList</a>
                                                                <a class="dropdown-item" href="send_to_kitchen.php">Current Order</a>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php } ?>
                                        <?php endif;?>
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

<!-- <script src="assets/js/sendalert_notification.js"></script> -->
<script>
function goBack() {
    <?php 
        $this->session->set_userdata('KOTNo', 0);
        $this->session->set_userdata('CNo', 0);
    ?>
    window.location = '<?php echo $link; ?>'
}

$(document).ready(function() {
    var doc = new jsPDF();
    var specialElementHandlers = {
        '#editor': function(element, renderer) {
            return true;
        }
    };

    $('#download-pdf').click(function() {
        doc.fromHTML($('#download-to-pdf').html(), 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });
        doc.save('bill.pdf');
    });
});


$('#download-to-pdf').on('click', function() {
    alert('Thank you for using Quick Service.');
});

function bill_page(billid){
    window.location.href = 'rating.php?billid='+billid;
}
</script>

<?php
$this->session->set_userdata('KOTNo', 0);
$this->session->set_userdata('CNo', 0);
?>
