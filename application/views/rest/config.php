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
                                                        <label for=""><?= $this->lang->line('multi').' '.$this->lang->line('kitchen'); ?></label>
                                                        <select name="MultiKitchen" id="" class="form-control form-control-sm" required="">
                                                <?php for ($i=1; $i <=10 ; $i++) { ?> 
                                                    <option value="<?= $i; ?>" <?php if($detail['MultiKitchen'] == $i){ echo 'selected'; } ?> ><?= $i; ?></option>
                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('schemeType'); ?></label>
                                                        <select name="SchType" id="" class="form-control form-control-sm" required="">
                                                            <option value="0" <?php if($detail['SchType'] == 0){ echo 'selected'; } ?>><?= $this->lang->line('no').' '.$this->lang->line('scheme'); ?></option>
                                                            <option value="1" <?php if($detail['SchType'] == 1){ echo 'selected'; } ?>><?= $this->lang->line('bill').' '.$this->lang->line('based'); ?></option>
                                                            <option value="2" <?php if($detail['SchType'] == 2){ echo 'selected'; } ?>><?= $this->lang->line('item').' '.$this->lang->line('based'); ?></option>
                                                            <option value="3" <?php if($detail['SchType'] == 3){ echo 'selected'; } ?>><?= $this->lang->line('both'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('payment').' '.$this->lang->line('type'); ?></label>
                                                        <select name="pymtENV" id="" class="form-control form-control-sm" required="">
                                                            <option value="0" <?php if($detail['pymtENV'] == 0){ echo 'selected'; } ?>><?= $this->lang->line('test'); ?></option>
                                                            <option value="1" <?php if($detail['pymtENV'] == 1){ echo 'selected'; } ?>><?= $this->lang->line('live'); ?></option>
                                                            <option value="2" <?php if($detail['pymtENV'] == 2){ echo 'selected'; } ?>><?= $this->lang->line('offline'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('serviceCharge'); ?></label>
                                                        <input type="number" name="ServChrg" id="" class="form-control form-control-sm" required="" value="<?= $detail['ServChrg']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('deliveryCharge'); ?></label>
                                                        <input type="number" name="DelCharge" id="" class="form-control form-control-sm" required="" value="<?= $detail['DelCharge']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('restaurant').' '.$this->lang->line('billing'); ?></label>
                                                        <select name="restBilling" id="" class="form-control form-control-sm">
                                                        <option value="1" <?php if($detail['restBilling'] == 1){ echo 'selected'; } ?> ><?= $this->lang->line('bill'); ?></option>
                                                        <option value="2" <?php if($detail['restBilling'] == 2){ echo 'selected'; } ?> ><?= $this->lang->line('bill').' & '.$this->lang->line('kot'); ?> </option>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('customer').' '.$this->lang->line('type'); ?></label>
                                                        <select name="custType" id="custType" class="form-control form-control-sm">

                                                        <option value="0" <?php if($detail['custType'] == 0){ echo 'selected'; } ?>><?= $this->lang->line('disabled'); ?></option>
                                                            <option value="1" <?php if($detail['custType'] == 1){ echo 'selected'; } ?>><?= $this->lang->line('Onaccount'); ?></option>
                                                            <option value="2" <?php if($detail['custType'] == 2){ echo 'selected'; } ?>><?= $this->lang->line('Prepaid'); ?></option>
                                                            <option value="5" <?php if($detail['custType'] == 5){ echo 'selected'; } ?>><?= $this->lang->line('both'); ?></option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <?php
                                                $ar = array(0 => $this->lang->line('none'),1 => $this->lang->line('daily'),2 => $this->lang->line('monthly'),3 => $this->lang->line('yearly'));
                                                 ?>
                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('resetKOT'); ?></label>
                                                        <select name="ResetKOT" id="ResetKOT" class="form-control form-control-sm">
                                                            <?php
                                                            foreach ($ar as $key => $value) { ?>
                                                                <option value="<?= $key; ?>" <?php if($detail['ResetKOT'] == $key){ echo 'selected'; } ?> ><?= $value; ?></option>
                                                           <?php  } ?>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('resetBillNo'); ?></label>
                                                        <select name="resetBillNo" id="resetBillNo" class="form-control form-control-sm" onchange="setPrefix()">

                                                            <?php
                                                            foreach ($ar as $key => $value) { ?>
                                                                <option value="<?= $key; ?>" <?php if($detail['resetBillNo'] == $key){ echo 'selected'; } ?> ><?= $value; ?></option>
                                                           <?php  } ?>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('BillPrefix'); ?></label>
                                                        <input type="text" name="BillPrefix" id="BillPrefix" class="form-control form-control-sm" value="<?= $detail['BillPrefix']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('BillSuffix'); ?></label>
                                                        <input type="text" name="BillSuffix" id="BillSuffix" class="form-control form-control-sm" value="<?= $detail['BillSuffix']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('resetBillMonth'); ?></label>
                                                        <select name="resetBillMonth" id="resetBillMonth" class="form-control form-control-sm" >
                                                            <option value="0"><?= $this->lang->line('none'); ?></option>
                                                            <option value="1" <?php if($detail['resetBillMonth'] == 1){ echo 'selected'; } ?>><?= $this->lang->line('january'); ?></option>
                                                            <option value="4" <?php if($detail['resetBillMonth'] == 4){ echo 'selected'; } ?>><?= $this->lang->line('april'); ?></option>
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
                                                            <label class="custom-control-label" for="customCheck01"><?= $this->lang->line('multi').' '.$this->lang->line('language'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck02" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoDeliver" <?php if($detail['AutoDeliver'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoDeliver'];?>">
                                                            <label class="custom-control-label" for="customCheck02"><?= $this->lang->line('autoDeliver'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox" >
                                                            <input type="checkbox" class="custom-control-input" id="customCheck03" data-parsley-multiple="groups" data-parsley-mincheck="2" name="EDT" <?php if($detail['EDT'] == 1){ echo 'checked'; } ?> value="<?= $detail['EDT'];?>" <?= $EType5; ?> >
                                                            <label class="custom-control-label" for="customCheck03"><?= $this->lang->line('edt'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck04" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Move" <?php if($detail['Move'] == 1){ echo 'checked'; } ?> value="<?= $detail['Move'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck04"><?= $this->lang->line('move'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck05" data-parsley-multiple="groups" data-parsley-mincheck="2" name="JoinTable" <?php if($detail['JoinTable'] == 1){ echo 'checked'; } ?> value="<?= $detail['JoinTable'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck05"><?= $this->lang->line('join').' '.$this->lang->line('table'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck06" data-parsley-multiple="groups" data-parsley-mincheck="2" name="SchPop" <?php if($detail['SchPop'] == 1){ echo 'checked'; } ?> value="<?= $detail['SchPop'];?>">
                                                            <label class="custom-control-label" for="customCheck06"><?= $this->lang->line('scheme').' '.$this->lang->line('popup'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck07" data-parsley-multiple="groups" data-parsley-mincheck="2" name="TableReservation" <?php if($detail['TableReservation'] == 1){ echo 'checked'; } ?> value="<?= $detail['TableReservation'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck07"><?= $this->lang->line('reservetable'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck08" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Discount" <?php if($detail['Discount'] == 1){ echo 'checked'; } ?> value="<?= $detail['Discount'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck08"><?= $this->lang->line('discount'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck25" data-parsley-multiple="groups" data-parsley-mincheck="2" name="MultiPayment" <?php if($detail['MultiPayment'] == 1){ echo 'checked'; } ?> value="<?= $detail['MultiPayment'];?>">
                                                            <label class="custom-control-label" for="customCheck25"><?= $this->lang->line('multi').' '.$this->lang->line('payment'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck26" data-parsley-multiple="groups" data-parsley-mincheck="2" name="IMcCdOpt" <?php if($detail['IMcCdOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['IMcCdOpt'];?>">
                                                            <label class="custom-control-label" for="customCheck26"><?= $this->lang->line('IMcCdOption'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck27" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tableSharing" <?php if($detail['tableSharing'] == 1){ echo 'checked'; } ?> value="<?= $detail['tableSharing'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck27"><?= $this->lang->line('table').' '.$this->lang->line('share'); ?></label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck09" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Tips" <?php if($detail['Tips'] == 1){ echo 'checked'; } ?> value="<?= $detail['Tips'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck09"><?= $this->lang->line('tips'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck10" data-parsley-multiple="groups" data-parsley-mincheck="2" name="CustLoyalty" <?php if($detail['CustLoyalty'] == 1){ echo 'checked'; } ?> value="<?= $detail['CustLoyalty'];?>">
                                                            <label class="custom-control-label" for="customCheck10"><?= $this->lang->line('customer').' '.$this->lang->line('loyalty'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck11" data-parsley-multiple="groups" data-parsley-mincheck="2" name="RtngDisc" <?php if($detail['RtngDisc'] == 1){ echo 'checked'; } ?> value="<?= $detail['RtngDisc'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck11"><?= $this->lang->line('rating').' '.$this->lang->line('discount'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck12" data-parsley-multiple="groups" data-parsley-mincheck="2" name="TableAcceptReqd" <?php if($detail['TableAcceptReqd'] == 1){ echo 'checked'; } ?> value="<?= $detail['TableAcceptReqd'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck12"><?= $this->lang->line('table').' '.$this->lang->line('accept').' '.$this->lang->line('required'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck13" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoSettle" <?php if($detail['AutoSettle'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoSettle'];?>">
                                                            <label class="custom-control-label" for="customCheck13"><?= $this->lang->line('autoSettle'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck14" data-parsley-multiple="groups" data-parsley-mincheck="2" name="AutoPrintKOT" <?php if($detail['AutoPrintKOT'] == 1){ echo 'checked'; } ?> value="<?= $detail['AutoPrintKOT'];?>">
                                                            <label class="custom-control-label" for="customCheck14"><?= $this->lang->line('autoPrintKOT'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck15" data-parsley-multiple="groups" data-parsley-mincheck="2" name="CustAssist" <?php if($detail['CustAssist'] == 1){ echo 'checked'; } ?> value="<?= $detail['CustAssist'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck15"><?= $this->lang->line('customer').' '.$this->lang->line('assist'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck16" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Dispense_OTP" <?php if($detail['Dispense_OTP'] == 1){ echo 'checked'; } ?> value="<?= $detail['Dispense_OTP'];?>">
                                                            <label class="custom-control-label" for="customCheck16"><?= $this->lang->line('dispense').' '.$this->lang->line('OTP'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck28" data-parsley-multiple="groups" data-parsley-mincheck="2" name="DeliveryOTP" <?php if($detail['DeliveryOTP'] == 1){ echo 'checked'; } ?> value="<?= $detail['DeliveryOTP'];?>">
                                                            <label class="custom-control-label" for="customCheck28"><?= $this->lang->line('deliver').' '.$this->lang->line('OTP'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck29" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Charity" <?php if($detail['Charity'] == 1){ echo 'checked'; } ?> value="<?= $detail['Charity'];?>">
                                                            <label class="custom-control-label" for="customCheck29"><?= $this->lang->line('charity'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck32" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Seatwise" <?php if($detail['Seatwise'] == 1){ echo 'checked'; } ?> value="<?= $detail['Seatwise'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck32"><?= $this->lang->line('seatWise'); ?></label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-3 col-12">

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck18" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ingredients" <?php if($detail['Ingredients'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ingredients'];?>">
                                                            <label class="custom-control-label" for="customCheck18"><?= $this->lang->line('ingredients'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck19" data-parsley-multiple="groups" data-parsley-mincheck="2" name="NV" <?php if($detail['NV'] == 1){ echo 'checked'; } ?> value="<?= $detail['NV'];?>">
                                                            <label class="custom-control-label" for="customCheck19"><?= $this->lang->line('nutritionValue'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck20" data-parsley-multiple="groups" data-parsley-mincheck="2" name="WelcomeMsg" <?php if($detail['WelcomeMsg'] == 1){ echo 'checked'; } ?> value="<?= $detail['WelcomeMsg'];?>">
                                                            <label class="custom-control-label" for="customCheck20"><?= $this->lang->line('welcome').' '.$this->lang->line('message'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck20" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billPrintTableNo" <?php if($detail['billPrintTableNo'] == 1){ echo 'checked'; } ?> value="<?= $detail['billPrintTableNo'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck20"><?= $this->lang->line('bill').' '.$this->lang->line('print').' '.$this->lang->line('tableNo'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck21" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Bill_KOT_Print" <?php if($detail['Bill_KOT_Print'] == 1){ echo 'checked'; } ?> value="<?= $detail['Bill_KOT_Print'];?>" <?= $EType5; ?> >
                                                            <label class="custom-control-label" for="customCheck21"><?= $this->lang->line('bill').' '.$this->lang->line('kot').' '.$this->lang->line('print'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck22" data-parsley-multiple="groups" data-parsley-mincheck="2" name="sitinKOTPrint" <?php if($detail['sitinKOTPrint'] == 1){ echo 'checked'; } ?> value="<?= $detail['sitinKOTPrint'];?>">
                                                            <label class="custom-control-label" for="customCheck22"><?= $this->lang->line('sitin').' '.$this->lang->line('kot').' '.$this->lang->line('print'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck23" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ing_Cals" <?php if($detail['Ing_Cals'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ing_Cals'];?>">
                                                            <label class="custom-control-label" for="customCheck23"><?= $this->lang->line('ingredients').' '.$this->lang->line('calories'); ?> </label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck24" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Ent" <?php if($detail['Ent'] == 1){ echo 'checked'; } ?> value="<?= $detail['Ent'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck24"><?= $this->lang->line('entertainment'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck30" data-parsley-multiple="groups" data-parsley-mincheck="2" name="GSTInclusiveRates" <?php if($detail['GSTInclusiveRates'] == 1){ echo 'checked'; } ?> value="<?= $detail['GSTInclusiveRates'];?>">
                                                            <label class="custom-control-label" for="customCheck30"><?= $this->lang->line('GSTInclusiveRates'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck31" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BillMergeOpt" <?php if($detail['BillMergeOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['BillMergeOpt'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck31"><?= $this->lang->line('billMerge'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck34" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billSplitOpt" <?php if($detail['billSplitOpt'] == 1){ echo 'checked'; } ?> value="<?= $detail['billSplitOpt'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck34"><?= $this->lang->line('splitbill'); ?></label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-3 col-12">

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck36" data-parsley-multiple="groups" data-parsley-mincheck="2" name="recommend" <?php if($detail['recommend'] == 1){ echo 'checked'; } ?> value="<?= $detail['recommend'];?>">
                                                            <label class="custom-control-label" for="customCheck36"><?= $this->lang->line('recommendation'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck37" data-parsley-multiple="groups" data-parsley-mincheck="2" name="billSplitOpt" <?php if($detail['addItemLock'] == 1){ echo 'checked'; } ?> value="<?= $detail['addItemLock'];?>">
                                                            <label class="custom-control-label" for="customCheck37"><?= $this->lang->line('add').' '.$this->lang->line('item').' '.$this->lang->line('lock'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck38" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BOM" <?php if($detail['BOM'] == 1){ echo 'checked'; } ?> value="<?= $detail['BOM'];?>">
                                                            <label class="custom-control-label" for="customCheck38"><?= $this->lang->line('bom'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck39" data-parsley-multiple="groups" data-parsley-mincheck="2" name="BOMStore" <?php if($detail['BOMStore'] == 1){ echo 'checked'; } ?> value="<?= $detail['BOMStore'];?>">
                                                            <label class="custom-control-label" for="customCheck39"><?= $this->lang->line('bom').' '.$this->lang->line('store'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck40" data-parsley-multiple="groups" data-parsley-mincheck="2" name="custItems" <?php if($detail['custItems'] == 1){ echo 'checked'; } ?> value="<?= $detail['custItems'];?>">
                                                            <label class="custom-control-label" for="customCheck40"><?= $this->lang->line('custom').' '.$this->lang->line('item'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck41" data-parsley-multiple="groups" data-parsley-mincheck="2" name="kds" <?php if($detail['kds'] == 1){ echo 'checked'; } ?> value="<?= $detail['kds'];?>">
                                                            <label class="custom-control-label" for="customCheck41"><?= $this->lang->line('kitchenDisplaySystem'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck42" data-parsley-multiple="groups" data-parsley-mincheck="2" name="reorder" <?php if($detail['reorder'] == 1){ echo 'checked'; } ?> value="<?= $detail['reorder'];?>" <?= $EType1; ?> >
                                                            <label class="custom-control-label" for="customCheck42"><?= $this->lang->line('reOrder'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck43" data-parsley-multiple="groups" data-parsley-mincheck="2" name="ratingHistory" <?php if($detail['ratingHistory'] == 1){ echo 'checked'; } ?> value="<?= $detail['ratingHistory'];?>">
                                                            <label class="custom-control-label" for="customCheck43"><?= $this->lang->line('ratingHistory'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck44" data-parsley-multiple="groups" data-parsley-mincheck="2" name="favoriteItems" <?php if($detail['favoriteItems'] == 1){ echo 'checked'; } ?> value="<?= $detail['favoriteItems'];?>">
                                                            <label class="custom-control-label" for="customCheck44"><?= $this->lang->line('favouriteItems'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck45" data-parsley-multiple="groups" data-parsley-mincheck="2" name="Rating" <?php if($detail['Rating'] == 1){ echo 'checked'; } ?> value="<?= $detail['Rating'];?>">
                                                            <label class="custom-control-label" for="customCheck45"><?= $this->lang->line('rating'); ?></label>
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

    function setPrefix(){
        var resetBillNo = $(`#resetBillNo`).val();
        if(resetBillNo > 0){
            $(`#BillPrefix`).prop('required', true);
            // $(`#BillSuffix`).prop('required', true);
            if(resetBillNo == 3){
                $(`#resetBillMonth`).prop('required', true);    
            }else{
                $(`#resetBillMonth`).prop('required', false);
            }
        }else{
            $(`#BillPrefix`).prop('required', false);
            // $(`#BillSuffix`).prop('required', false);
            $(`#resetBillMonth`).prop('required', false);
            
        }
    }

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