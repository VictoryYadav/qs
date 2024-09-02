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
                                        <form method="post" id="configForm">
                                            
                                            <div class="row">

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Multi Kitchen</label>
                                                        <select name="MultiKitchen" id="" class="form-control form-control-sm" required="">
                                                <?php for ($i=1; $i <=10 ; $i++) { ?> 
                                                    <option value="<?= $i; ?>" <?php if($detail['MultiKitchen'] == $i){ echo 'selected'; } ?> ><?= $i; ?></option>
                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Scheme Type</label>
                                                        <select name="SchType" id="" class="form-control form-control-sm" required="">
                                                            <option value="0" <?php if($detail['SchType'] == 0){ echo 'selected'; } ?>>No Scheme</option>
                                                            <option value="1" <?php if($detail['SchType'] == 1){ echo 'selected'; } ?>>Bill Based</option>
                                                            <option value="2" <?php if($detail['SchType'] == 2){ echo 'selected'; } ?>>Item Based</option>
                                                            <option value="3" <?php if($detail['SchType'] == 3){ echo 'selected'; } ?>>Both</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Online Payment</label>
                                                        <select name="pymtENV" id="" class="form-control form-control-sm" required="">
                                                            <option value="0" <?php if($detail['pymtENV'] == 0){ echo 'selected'; } ?>>Test</option>
                                                            <option value="1" <?php if($detail['pymtENV'] == 1){ echo 'selected'; } ?>>Live</option>
                                                            <option value="2" <?php if($detail['pymtENV'] == 2){ echo 'selected'; } ?>>Offline</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Service Charge</label>
                                                        <input type="number" name="ServChrg" id="" class="form-control form-control-sm" required="" value="<?= $detail['ServChrg']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Delivery Charge</label>
                                                        <input type="number" name="DelCharge" id="" class="form-control form-control-sm" required="" value="<?= $detail['DelCharge']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for="">Rest Billing</label>
                                                        <select name="restBilling" id="" class="form-control form-control-sm">
                                                        <option value="1" <?php if($detail['restBilling'] == 1){ echo 'selected'; } ?> >BIll</option>
                                                        <option value="2" <?php if($detail['restBilling'] == 2){ echo 'selected'; } ?> >BIll & KOT</option>
                                                    </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3 col-12">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck01" data-parsley-multiple="groups" data-parsley-mincheck="2" name="MultiLingual" <?php if($detail['MultiLingual'] == 1){ echo 'checked'; } ?>  value="<?= $detail['MultiLingual'];?>">
                                                            <label class="custom-control-label" for="customCheck01">Multi Language</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck02" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoDeliver" <?php if($detail['AutoDeliver'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoDeliver'];?>">
                                                            <label class="custom-control-label" for="customCheck02">Auto Deliver</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox" >
                                                            <input type="checkbox" class="custom-control-input" id="customCheck03" data-parsley-multiple="groups" data-parsley-mincheck="2" name="EDT" <?php if($detail['EDT'] == 1){ echo 'checked'; } ?> value="<?= $detail['EDT'];?>">
                                                            <label class="custom-control-label" for="customCheck03">EDT</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck04" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Move" <?php if($detail['Move'] == 1){ echo 'checked'; } ?> value="<?= $detail['Move'];?>">
                                                            <label class="custom-control-label" for="customCheck04">Move</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck05" data-parsley-multiple="groups" data-parsley-mincheck="2" name="JoinTable" <?php if($detail['JoinTable'] == 1){ echo 'checked'; } ?> value="<?= $detail['JoinTable'];?>">
                                                            <label class="custom-control-label" for="customCheck05">Join Table</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck06" data-parsley-multiple="groups" data-parsley-mincheck="2" name="SchPop" <?php if($detail['SchPop'] == 1){ echo 'checked'; } ?> value="<?= $detail['SchPop'];?>">
                                                            <label class="custom-control-label" for="customCheck06">Scheme Pop Up</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck07" data-parsley-multiple="groups" data-parsley-mincheck="2" name="TableReservation" <?php if($detail['TableReservation'] == 1){ echo 'checked'; } ?> value="<?= $detail['TableReservation'];?>">
                                                            <label class="custom-control-label" for="customCheck07">Table Reservation</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck08" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Discount" <?php if($detail['Discount'] == 1){ echo 'checked'; } ?> value="<?= $detail['Discount'];?>">
                                                            <label class="custom-control-label" for="customCheck08">Discount</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck25" data-parsley-multiple="groups" data-parsley-mincheck="2" name="MultiPayment" <?php if($detail['MultiPayment'] == 1){ echo 'checked'; } ?> value="<?= $detail['MultiPayment'];?>">
                                                            <label class="custom-control-label" for="customCheck25">MultiPayment</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck26" data-parsley-multiple="groups" data-parsley-mincheck="2" name="IMcCdOpt" <?php if($detail['IMcCdOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['IMcCdOpt'];?>">
                                                            <label class="custom-control-label" for="customCheck26">IMcCd Option</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck27" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tableSharing" <?php if($detail['tableSharing'] == 1){ echo 'checked'; } ?> value="<?= $detail['tableSharing'];?>">
                                                            <label class="custom-control-label" for="customCheck27">Table Sharing</label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck09" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Tips" <?php if($detail['Tips'] == 1){ echo 'checked'; } ?> value="<?= $detail['Tips'];?>">
                                                            <label class="custom-control-label" for="customCheck09">Tips</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck10" data-parsley-multiple="groups" data-parsley-mincheck="2" name="CustLoyalty" <?php if($detail['CustLoyalty'] == 1){ echo 'checked'; } ?> value="<?= $detail['CustLoyalty'];?>">
                                                            <label class="custom-control-label" for="customCheck10">Customer Loyalty</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck11" data-parsley-multiple="groups" data-parsley-mincheck="2" name="RtngDisc" <?php if($detail['RtngDisc'] == 1){ echo 'checked'; } ?> value="<?= $detail['RtngDisc'];?>">
                                                            <label class="custom-control-label" for="customCheck11">Rating Discount</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck12" data-parsley-multiple="groups" data-parsley-mincheck="2" name="TableAcceptReqd" <?php if($detail['TableAcceptReqd'] == 1){ echo 'checked'; } ?> value="<?= $detail['TableAcceptReqd'];?>">
                                                            <label class="custom-control-label" for="customCheck12">Table Accept Req</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck13" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoSettle" <?php if($detail['AutoSettle'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoSettle'];?>">
                                                            <label class="custom-control-label" for="customCheck13">Auto Settle</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck14" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoPrintKOT" <?php if($detail['AutoPrintKOT'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoPrintKOT'];?>">
                                                            <label class="custom-control-label" for="customCheck14">Auto Print KOT</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck15" data-parsley-multiple="groups" data-parsley-mincheck="2" name="CustAssist" <?php if($detail['CustAssist'] == 1){ echo 'checked'; } ?> value="<?= $detail['CustAssist'];?>">
                                                            <label class="custom-control-label" for="customCheck15">Customer Assist</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck16" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Dispense_OTP" <?php if($detail['Dispense_OTP'] == 1){ echo 'checked'; } ?> value="<?= $detail['Dispense_OTP'];?>">
                                                            <label class="custom-control-label" for="customCheck16">Dispense OTP</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck28" data-parsley-multiple="groups" data-parsley-mincheck="2" name="DeliveryOTP" <?php if($detail['DeliveryOTP'] == 1){ echo 'checked'; } ?> value="<?= $detail['DeliveryOTP'];?>">
                                                            <label class="custom-control-label" for="customCheck28">Delivery OTP</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck29" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Charity" <?php if($detail['Charity'] == 1){ echo 'checked'; } ?> value="<?= $detail['Charity'];?>">
                                                            <label class="custom-control-label" for="customCheck29">Charity</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck32" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Seatwise" <?php if($detail['Seatwise'] == 1){ echo 'checked'; } ?> value="<?= $detail['Seatwise'];?>">
                                                            <label class="custom-control-label" for="customCheck32">Seat Wise</label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-3 col-12">

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck18" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ingredients" <?php if($detail['Ingredients'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ingredients'];?>">
                                                            <label class="custom-control-label" for="customCheck18">Ingredients</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck19" data-parsley-multiple="groups" data-parsley-mincheck="2" name="NV" <?php if($detail['NV'] == 1){ echo 'checked'; } ?> value="<?= $detail['NV'];?>">
                                                            <label class="custom-control-label" for="customCheck19">NV</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck20" data-parsley-multiple="groups" data-parsley-mincheck="2" name="WelcomeMsg" <?php if($detail['WelcomeMsg'] == 1){ echo 'checked'; } ?> value="<?= $detail['WelcomeMsg'];?>">
                                                            <label class="custom-control-label" for="customCheck20">Welcome Message</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck20" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billPrintTableNo" <?php if($detail['billPrintTableNo'] == 1){ echo 'checked'; } ?> value="<?= $detail['billPrintTableNo'];?>">
                                                            <label class="custom-control-label" for="customCheck20">Bill Print Table No</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck21" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Bill_KOT_Print" <?php if($detail['Bill_KOT_Print'] == 1){ echo 'checked'; } ?> value="<?= $detail['Bill_KOT_Print'];?>">
                                                            <label class="custom-control-label" for="customCheck21">Bill KOT Print</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck22" data-parsley-multiple="groups" data-parsley-mincheck="2" name="sitinKOTPrint" <?php if($detail['sitinKOTPrint'] == 1){ echo 'checked'; } ?> value="<?= $detail['sitinKOTPrint'];?>">
                                                            <label class="custom-control-label" for="customCheck22">Sit In KOT Print</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck23" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ing_Cals" <?php if($detail['Ing_Cals'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ing_Cals'];?>">
                                                            <label class="custom-control-label" for="customCheck23">Ingredients Calories</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck24" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ent" <?php if($detail['Ent'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ent'];?>">
                                                            <label class="custom-control-label" for="customCheck24">Entertainment</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck30" data-parsley-multiple="groups" data-parsley-mincheck="2" name="GSTInclusiveRates" <?php if($detail['GSTInclusiveRates'] == 1){ echo 'checked'; } ?> value="<?= $detail['GSTInclusiveRates'];?>">
                                                            <label class="custom-control-label" for="customCheck30">GST Inclusive Rates</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck31" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BillMergeOpt" <?php if($detail['BillMergeOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['BillMergeOpt'];?>">
                                                            <label class="custom-control-label" for="customCheck31">Bill Merge Option</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck34" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billSplitOpt" <?php if($detail['billSplitOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['billSplitOpt'];?>">
                                                            <label class="custom-control-label" for="customCheck34">Bill Split Option</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-3 col-12">

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck36" data-parsley-multiple="groups" data-parsley-mincheck="2" name="recommend" <?php if($detail['recommend'] == 1){ echo 'checked'; } ?> value="<?= $detail['recommend'];?>">
                                                            <label class="custom-control-label" for="customCheck36">Recommendation</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck37" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billSplitOpt" <?php if($detail['addItemLock'] == 1){ echo 'checked'; } ?> value="<?= $detail['addItemLock'];?>">
                                                            <label class="custom-control-label" for="customCheck37">Add Item Lock</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck38" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BOM" <?php if($detail['BOM'] == 1){ echo 'checked'; } ?> value="<?= $detail['BOM'];?>">
                                                            <label class="custom-control-label" for="customCheck38">BOM</label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck39" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BOMStore" <?php if($detail['BOMStore'] == 1){ echo 'checked'; } ?> value="<?= $detail['BOMStore'];?>">
                                                            <label class="custom-control-label" for="customCheck39">BOM Store</label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div >
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">

                                                    <div class="text-success" id="msgText"></div>
                                                </div>
                                            </div>
                                        </form>
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
        
    });

    $('#configForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/config') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

</script>