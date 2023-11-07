<?php $this->load->view('layouts/customer/head'); 
        $EID = authuser()->EID;
        $flag = 'rest';
        $custId = $this->session->userdata('CustId');

        $dbname = $this->session->userdata('my_db');
        $res = getBillData($dbname, $EID, $billId, $custId, $flag);
        // echo "<pre>";
        // print_r($res);
        // die;

        $billData = $res['billData'];
        $taxDataArray = $res['taxDataArray'];

        $hotelName = $billData[0]['Name'];
        $phone = $billData[0]['PhoneNos'];
        $gstno = $billData[0]['GSTno'];
        $fssaino = $billData[0]['FSSAINo'];
        $cinno = $billData[0]['CINNo'];
        $billno = $billData[0]['BillNo'];
        $orderdate = $billData[0]['BillDt'];
        $date = new DateTime($orderdate);
        $dateOfBill = $date->format('d-m-Y @ H:i');
        $address = $billData[0]['Addr'];
        $pincode = $billData[0]['Pincode'];
        $city = $billData[0]['City'];
        $servicecharge = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
        $bservecharge = $billData[0]['bservecharge'];
        $SerChargeAmt = $billData[0]['SerChargeAmt'];

        $tipamt = $billData[0]['Tip'];
        $Stat = $billData[0]['Stat'];
        $total = 0;
        $sgstamt=0;
        $cgstamt=0;
        $grandTotal = $sgstamt + $cgstamt + $bservecharge + $tipamt;
        $thankuline = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

        $total_discount_amount = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
        $total_packing_charge_amount = $billData[0]['TotPckCharge'];
        $total_delivery_charge_amount = $billData[0]['DelCharge'];


?>
<style>
    p{
        font-size: 12px !important;
    }
        @font-face {
            font-family: Montserrat Bold;
            src: url(fonts/Montserrat-Bold.otf);
        }

        @font-face {
            font-family: Montserrat Regular;
            src: url(fonts/Montserrat-Regular.otf);
        }

        .single-category h3 {
            font-family: Montserrat Bold;
        }

        .offers-tab label {
            font-family: Montserrat Regular;
        }

        .remove-margin {
            margin-left: 0px;
            margin-right: 0px;
        }

        .img-category {
            height: 95px;
            width: 125px;
        }

        .single-category {
            height: 125px;
            display: flex;
            align-items: center;
            background-color: #0a88ff;
        }

        .single-category:nth-last-child(1) {
            margin-bottom: 60px;
        }

        #body-row {
            top: 44px;
            height: 100%;
            position: relative;
        }

        .header {
            color: blue;
        }

        .main-header {
            margin-left: 0px;
            margin-right: 0px;
            /*padding: 10px 20px;*/
            position: fixed;
            background: #fff;
            z-index: 900;
            width: 100%;
        }

        .top-header {
            padding: 3px 20px;
        }

        .header-left,
        .header-right {
            display: inline-block;
            width: 49%;

        }

        .header-right {
            text-align: right;
        }

        .header-left p,
        .header-right p {
            margin: 0px;
        }

        .header-right,
        .header-left {
            font-weight: bold;
        }

        .header-left {
            font: 20px;
        }

        h3 {
            color: #fff;
            font-weight: bold;
        }

        .navbar {
            text-align: center;
            overflow: hidden;
            background-color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 0px;
        }

        .navbar a {
            float: left;
            display: block;
            color: #000;
            text-align: center;
            padding: 14px 13px;
            text-decoration: none;
            font-size: 7px;
            /* width: 20%; */
        }

        .navbar a:hover {
            background: #f1f1f1;
            color: black;
        }

        .navbar a.active {
            background-color: #0a88ff;
            color: #fff;
        }

        .main {
            padding: 16px;
            margin-bottom: 30px;
        }

        .navbar a img {
            display: inherit;
        }

        .offers-tab {
            background-color: #0a88ff;
            top: 59px;
            position: relative;
            width: 100%;
            padding: 10px 15px;
            text-align: center;
        }

        .menu-radio {
            border-radius: 25px 0 0 25px;
        }

        .offers-rdaio {
            border-radius: 0 25px 25px 0;
        }

        #sidebar {
            z-index: 1000;
            width: 50%;
        }

        #sidebar .sidebar-header {
            height: 150px;
            background: url(assets/img/a.png);
            background-size: cover;
            background-repeat: no-repeat;
            padding: 0px;
        }

        #sidebar ul li a {
            border-radius: 50px;
            border: 1px solid #fff;
        }

        #sidebar ul.components {
            padding: 20px 15px 20px 20px;
        }

        #sidebar ul li a {
            padding: 5px 10px;
            font-size: 1rem;
        }

        #sidebar ul li {
            margin-bottom: 10px;
        }

        .name-overlay {
            background: rgba(0, 94, 222, 0.82);
            overflow: hidden;
            height: 100%;
            z-index: 2;
            width: 100%;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .menu-footer {
            margin-bottom: 0px;
        }

        .close {
            font-size: 15px !important;
            font-weight: 300 !important;
            padding-top: 21px !important;
            padding-bottom: 0px !important;
            border: none !important;
            color: #fff;
        }

        .modal-header {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .offers-txt {
            color: #fff;
        }

        .dropup .dropdown-toggle::after {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: 0;
            border-right: .3em solid transparent;
            border-bottom: none !important;
            border-left: .3em solid transparent;
        }

        #catg-list a {
            color: white;
            text-align: center;
        }

        .dropdown-menu a {
            font-size: 15px !important;
        }

        .billView{
            /*height: 400px;
            overflow: auto; */
        }
        /*mobile screen only*/
        @media only screen and (max-width: 480px) {
            #billView{
               /*height: 480px;
               overflow: auto;*/ 
            }
        }


    </style>
</head>

<body>



    <section class="common-section p-2">
        <div class="container">
            <a href="<?= base_url('restaurant/print/'.$billId); ?>" class="'btn btn-sm btn-primary">Print</a>
            <div id="download-to-pdf" class="container billView">
                    <div class="text-center">
                        <p style="font-weight: bold;font-size: 18px !important;margin-bottom: 1px;"><?= $hotelName ?></p>
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
                            <p style="margin-bottom: unset;font-weight: bold;font-size: 15px !important;">Bill No: <?= $billno ?></p>
                        </div>
                        
                        <div class="col-12">
                            <p style="margin-bottom: unset;font-size: 15px !important;">Date: <?= $dateOfBill ?></p>
                            <!-- <p><?= $dateOfBill ?></p> -->
                        </div>
                    </div>
                    
                    <div style="border-bottom: 1px solid;"></div>
                    <!-- bill body here -->

                    <?php
                        // get repository  : billing/bill_print_body.repo.php
                        // include('repository/billing/bill_print_body.repo.php');

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
                                        $sameTaxType .= ' <tr> ';
                                        if($data['Itm_Portion'] > 4 ){
                                            
                                            $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].' ( '.$data['Portions'].' ) </td> ';

                                        }else{

                                            $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].'</td> ';

                                        }
                                        
                                        $sameTaxType .= ' <td style="text-align: right;"> '.$data['Qty'].' </td>';
                                        $sameTaxType .= ' <td style="text-align: right;">'.$data['ItmRate'].'</td> ';
                                        $sameTaxType .= ' <td style="text-align: right;">'.$data['ItemAmt'].'</td> ';
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
                        $newTaxType .= ' <tr style="text-align: right;"> ';
                        $newTaxType .= ' <th style="float: left;">Menu Item </th> ';
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

                                    // $total_tax = calculatTotalTax($total_tax,number_format($dataTax['SubAmtTax'],2));

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
                                    <td style="font-weight: bold;">Service Charges @ <?= $bservecharge ?></td>
                                    <td style="text-align: right;"><?= $SerChargeAmt; ?></td>
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
                                <td style="text-align: right;font-weight: bold;"><?= $billData[0]['PaidAmt'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-bottom: 1px solid;"></div>
                    <p><?= $thankuline ?></p>
                    <br>
                    <?php if(isset($_GET['ShowRatings']) && $_GET['ShowRatings'] == 1){
                        $ra = $this->db2->query("SELECT AVG(ServRtng) as serv, AVG(AmbRtng) as amb,avg(VFMRtng) as vfm, AVG(rd.ItemRtng) as itm FROM Ratings r, RatingDet rd WHERE r.BillId= $billId and r.EID=".$_GET['EID'])->result_array();
                        
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
                
        </div>
    </section>

  


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->
<script src="<?= base_url(); ?>assets/js/sendalert_notification.js"></script>
</body>

<script>
                    

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

                    function bill_page(billid){
                        window.location.href = "<?= base_url('customer/rating');?>"+billid;
                    }
                </script>

</html>