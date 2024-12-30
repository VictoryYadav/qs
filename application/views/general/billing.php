<?php $this->load->view('layouts/gen/head'); ?>
<style>
    .fixedHeight{
        max-height: 555px;
        overflow-x: hidden;
    }
</style>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">           

            <div class="container mt-4">

                <div class="row">
                
                    <div class="col-xl-12">
                        <div class="card fixedHeight">
                            <div class="card-body">
                                <div id="download-to-pdf" class="container billView">
                                    <div class="text-center">
                                        <p style="font-weight: bold;font-size: 18px !important;margin-bottom: 1px;"><?php
                                                            if($BillName !='-'){
                                                                echo $BillName;
                                                            }else{
                                                                echo $hotelName;
                                                            }
                                         ?></p>
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
                                    
                                    <div style="border-bottom: 1px solid;"></div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p style="margin-bottom: unset;font-size: 15px !important;">Bill No: <b><?= $billno ?></b></p>
                                        </div>
                                        <?php if($this->session->userdata('billPrintTableNo') > 0 && $TableNo < 100 && ($this->session->userdata('EType') == 5)) { ?>
                                        <div class="col-6" style="text-align: right;">
                                            <p style="margin-bottom: unset;font-size: 15px !important;">Table No: <b><?= $MergeNo ?></b></p>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="col-12">
                                            <p style="margin-bottom: unset;font-size: 15px !important;">Date: <?= $dateOfBill ?></p>
                                        </div>
                                    </div>
                                    
                                    <div style="border-bottom: 1px solid;"></div>

                                    <?php

                                    foreach ( $billData as $key => $value ) {
                                        $TaxType = $value['TaxType'];
                                        if( $key != 0 ){
                                            $TaxType = $billData[$key-1]['TaxType'];
                                        }

                                        if( $value['TaxType'] != $TaxType || $key == 0){
                                            // build table with title
                                            $sameTaxType  = '';
                                            $itemTotal = 0;
                                            foreach ($billData as $keyData => $data) {
                                                if($data['TaxType'] == $value['TaxType']){
                                                    $qty = ($splitTyp == 0)?round($data['Qty'], 0):$data['Qty'];

                                                        $ta = '';
                                                         if($data['TA'] == 1){
                                                          $ta = ' [TA]';
                                                        }else if($data['TA'] == 2){
                                                          $ta = ' [Charity]';
                                                        }

                                                        $CustItemDesc = '';
                                                        if(!empty($data['CustItemDesc'])){
                                                            $CustItemDesc = ($data['CustItemDesc'] !='Std')?'-'.$data['CustItemDesc']:'';
                                                        }
                                                        $sameTaxType .= ' <tr> ';
                                                        if($data['Itm_Portion'] > 4 ){
                                                            
                                                            $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].' ('.$data['Portions'].')'.$ta.$CustItemDesc.' </td> ';

                                                        }else{

                                                            $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].$ta.$CustItemDesc.'</td> ';

                                                        }
                                                        
                                                        $sameTaxType .= ' <td style="text-align: right;"> '.$qty.' </td>';
                                                        $sameTaxType .= ' <td style="text-align: right;">'.$data['OrigRate'].'</td> ';
                                                        $sameTaxType .= ' <td style="text-align: right;">'.round($data['ItemAmt'], 2).'</td> ';
                                                        $sameTaxType .= ' </tr> ';
                                                        $itemTotal +=$data['ItemAmt'];
                                                }
                                            }

                                            newTaxType( $value ,$sameTaxType,$value['TaxType'],$taxDataArray,$itemTotal);
                                        }
                                    }

                                    function newTaxType($data,$sameTaxType,$TaxType,$taxDataArray,$itemTotal){
                                        $newTaxType  = ' <div style="margin-bottom: 15px;"> ';
                                        $newTaxType .= ' <table style="width:100%;"> ';
                                        $newTaxType .= ' <tbody> ';
                                        $newTaxType .= ' <tr style="text-align: right;">';
                                        $newTaxType .= ' <th style="float: left;">Item </th> ';
                                        $newTaxType .= ' <th>Qty</th> ';
                                        $newTaxType .= ' <th>Rate</th> ';
                                        $newTaxType .= ' <th>Amt</th> ';
                                        $newTaxType .= ' </tr> ';

                                        $newTaxType .=  $sameTaxType;

                                        $newTaxType .= ' <tr style="border-top: 1px solid;"> ';
                                        $newTaxType .= ' <td></td> <td></td> <td></td> <td></td>';
                                        $newTaxType .= ' </tr> ';
                                        $newTaxType .= ' <tr> ';
                                        $newTaxType .= ' <td style="text-align: left;"><i>Group Total</i></td> ';
                                        $newTaxType .= ' <td></td> <td></td>';
                                        $newTaxType .= ' <td style="float: right;">'.$itemTotal.'</td> ';
                                        $newTaxType .= ' </tr> ';
                                        $sub_total = 0;
                                        foreach ($taxDataArray as $key => $value) {
                                            $total_tax = 0;
                                            foreach ($value as $key1=> $dataTax) {

                                                if($dataTax['TaxType'] == $TaxType && $dataTax['Included'] > 0){

                                                        $newTaxType .= ' <tr> ';
                                                        $newTaxType .= ' <td style="text-align: left;"> <i> '.$dataTax['ShortName'].''.$dataTax['TaxPcent'].'% </i></td> ';
                                                        $newTaxType .= ' <td></td> ';
                                                        $newTaxType .= ' <td></td> ';
                                                        $newTaxType .= ' <td style="text-align: right;">'.$dataTax['SubAmtTax'].'</td> ';
                                                        $newTaxType .= ' </tr> ';
                                                    
                                                }

                                                if( $dataTax['TaxType'] == $TaxType && $dataTax['Included'] >= 5 ){

                                                    $sub_total = $sub_total + $dataTax['SubAmtTax'];

                                                }
                                            }

                                        }
                                        $sub_total = $sub_total  + $itemTotal;

                                        $newTaxType .= ' <tr style="background: #80808052;"> ';
                                        $newTaxType .= ' <td style="text-align: left; font-weight: bold;">Group Sub Total</td> ';
                                        $newTaxType .= ' <td></td> <td></td>';
                                        $newTaxType .= ' <td style="float: right;">'.$sub_total.'</td> ';
                                        $newTaxType .= ' </tr> ';
                                        $newTaxType .= ' </tbody> ';
                                        $newTaxType .= ' </table> ';
                                        $newTaxType .= ' </div> ';

                                        echo $newTaxType;
                                    }

                                    ?>

                                    <div style="margin-top: 10px;">
                                        <table style="width:100%">
                                        
                                            <?php if ($bservecharge > 0) : ?>
                                                <tr>
                                                    <td >Service Charges @ <?= $bservecharge ?></td>
                                                    <td style="text-align: right;"><?= $SerChargeAmt; ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if ($tipamt > 0) : ?>
                                                <tr>
                                                    <td>TIP Amount</td>
                                                    <td style="text-align: right;"><?= round($tipamt, 2); ?></td>
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
                                                        <td style="font-weight: bold;">Total Savings</td>
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
                                            if($billData[0]['discId'] > 0) { ?>
                                            <tr style="border-bottom: 1px solid black;">
                                                <td style="font-weight: bold;"><?= $billData[0]['discountName']; ?> Discount @ <?= $billData[0]['discPcent']; ?>%</td>
                                                <td style="text-align: right;"><?= $billData[0]['autoDiscAmt']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        
                                            <tr>
                                                <td style="font-weight: bold;">GRAND TOTAL <small>(<?= $this->lang->line('roundedoff'); ?>)</small></td>
                                                <td style="text-align: right;font-weight: bold;"><?= $billData[0]['PaidAmt'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="border-bottom: 1px solid;"></div>
                                    <p><?= $thankuline ?></p>
                                    <br>
                                    <?php if(isset($_GET['ShowRatings']) && $_GET['ShowRatings'] == 1){
                                        $ra = $this->db2->query("SELECT AVG(ServRtng) as serv, AVG(AmbRtng) as amb,avg(VFMRtng) as vfm, AVG(rd.ItemRtng) as itm FROM Ratings r, RatingDet rd WHERE r.BillId= $billId and r.EID=".$_GET['EID'])->result_array();
                                        
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
                            </div>
                        </div>
                    </div>                                                              
                </div><!--end row-->
                
            </div> <!-- container-fluid -->

        </div>
        <?php $this->load->view('layouts/gen/footer'); ?>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php $this->load->view('layouts/gen/scripts'); ?>
       
