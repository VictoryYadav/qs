<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
  
  <title>Print | Eat-Out</title>
  <link href="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" rel="shortcut icon">
  <style>
@media print {
    .page-break { display: block; page-break-before: always; }
}

#invoice-POS {
  /*padding: 2mm;*/
  margin: 0 auto;
  /*width: 44mm;*/
  width: 80mm;
  background: #FFF;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
}
#invoice-POS h2 {
  font-size: .9em;
  margin-bottom: -12px;
  /*margin vijay*/
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: 1em;
  color: #202122;
  line-height: 1.2em;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}
#invoice-POS #top {
  /*min-height: 100px;*/
}
#invoice-POS #mid {
  min-height: 80px;
}
#invoice-POS #bot {
  min-height: 50px;
}
#invoice-POS #top .logo {
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
  background-size: 60px 60px;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: 1em;
  background: #EEE;
}
#invoice-POS .service {
  font-size: 18px;
  border-bottom: 1px solid #EEE;
}
#invoice-POS .item {
  width: 42mm;
}
#invoice-POS .itemtext {
  font-size: 1em;
  color: #202122;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}
    </style>

  <script>
  window.console = window.console || function(t) {};
</script>



  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


</head>

<!-- <body translate="no"> -->
  <body translate="no" onload="window.print()">

  <div id="invoice-POS">
    <center id="top">
        <h1 style="margin-bottom: 5px;"><?= $hotelName ?></h1>
    </center>

    <div id="mid">
      <div class="info" style="text-align: center;">
        <!-- <h2>Contact Info</h2> -->
            <?= $address ?>, <?= $city ?>-<?= $pincode ?>,
            Tel: <?= $phone ?><br>
            <?php if ($gstno != '-') { ?>
                GST NO: <?= $gstno ?><br>
            <?php } ?>
            <?php if ($fssaino != '-') { ?>
                FSSAI NO: <?= $fssaino ?><br>
            <?php } ?>
            <?php if ($cinno != '-') { ?>
                CIN NO: <?= $cinno ?>
            <?php } ?>
      </div>
    </div><!--End Invoice Mid-->

    <div>
      <table style="font-size: 18px;">
        <?php if($CustNo == 0){?>
        <tr style="border-bottom: 1px solid black;">
          <td>
            <?php if(!empty($Fullname)){ echo $Fullname.',   '; } ?> Cell : <?php if(!empty($CellNo)) { echo $CellNo; } ?>
          </td>
        </tr>
        <?php } ?>
        <tr style="border-bottom: 1px solid black;">
          <td style="text-align: left;">Bill No: <?= $billno ?> <?php if($this->session->userdata('billPrintTableNo') > 0) { echo " , Table No: ".$TableNo; } ?><br>
            Date: <?= $dateOfBill ?>
          </td>
          <?php if ($CustNo != "0") : ?>
          <td>Ord: <b><?= $CustNo ?></b></td>
          <?php endif; ?>
        </tr>
      </table>
    </div>

    <div id="bot">

        <div id="table">
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
                            $portions = '';
                            $std = '';
                            foreach ($billData as $keyData => $data) {
                              if($data['Portions'] != 'Std'){
                                  $portions = ' ('.$data['Portions'].')';
                                }
                                if($data['CustItemDesc'] != 'Std'){
                                  $std = ' - '.$data['CustItemDesc'];
                                }

                                if($data['TaxType'] == $value['TaxType']){
                                        $sameTaxType .= ' <tr class="service"> ';
                                        if($data['Itm_Portion'] > 4 ){
                                            
                                            $sameTaxType .= ' <td class="tableitem">'.$data['ItemNm'].$std.$portions.'</td> ';

                                        }else{

                                            $sameTaxType .= ' <td>'.$data['ItemNm'].$std.$portions.'</td> ';

                                        }
                                        
                                        $sameTaxType .= ' <td>'.$data['Qty'].' </td>';
                                        $sameTaxType .= ' <td>'.$data['ItmRate'].'</td> ';
                                        $sameTaxType .= ' <td>'.$data['ItemAmt'].'</td> ';
                                        $sameTaxType .= ' </tr> ';
                                        $itemTotal +=$data['ItemAmt'];
                                }
                            }

                            newTaxType( $value ,$sameTaxType,$value['TaxType'],$taxDataArray,$itemTotal);
                        }
                    }

                    function newTaxType($data,$sameTaxType,$TaxType,$taxDataArray,$itemTotal){
                        $newTaxType  = ' <div style="margin-bottom: 10px;"> ';
                        $newTaxType .= ' <table style="width:100%;"> ';
                        $newTaxType .= ' <tbody> ';
                        $newTaxType .= ' <tr class="tabletitle"> ';
                        $newTaxType .= ' <th class="item" style="text-align: left;">Menu Item </th> ';
                        $newTaxType .= ' <th style="text-align: left;">Qty</th> ';
                        $newTaxType .= ' <th style="text-align: left;">Rate</th> ';
                        $newTaxType .= ' <th style="text-align: left;">Amt</th> ';
                        $newTaxType .= ' </tr> ';

                        $newTaxType .=  $sameTaxType;

                        $newTaxType .= ' <tr style="border-top: 1px solid;" class="service"> ';
                        $newTaxType .= ' <td></td> <td></td> <td></td> <td></td>';
                        $newTaxType .= ' </tr> ';
                        $newTaxType .= ' <tr> ';
                        $newTaxType .= ' <td style="text-align: left;" class="tableitem">Group Total</td> ';
                        $newTaxType .= ' <td></td> <td></td>';
                        $newTaxType .= ' <td class="tableitem">'.$itemTotal.'</td> ';
                        $newTaxType .= ' </tr> ';
                        $sub_total = 0;
                        foreach ($taxDataArray as $key => $value) {
                            $total_tax = 0;
                            foreach ($value as $key1=> $dataTax) {

                                if($dataTax['TaxType'] == $TaxType && $dataTax['Included'] > 0){

                                    // $total_tax = calculatTotalTax($total_tax,number_format($dataTax['SubAmtTax'],2));

                                        $newTaxType .= ' <tr class="service"> ';
                                        $newTaxType .= ' <td class="tableitem">'.$dataTax['ShortName'].''.$dataTax['TaxPcent'].'% </td> ';
                                        $newTaxType .= ' <td></td> ';
                                        $newTaxType .= ' <td></td> ';
                                        $newTaxType .= ' <td class="tableitem">'.$dataTax['SubAmtTax'].'</td>';
                                        $newTaxType .= ' </tr> ';
                                    
                                }

                                if( $dataTax['TaxType'] == $TaxType && $dataTax['Included'] >= 5 ){

                                    $sub_total = $sub_total + $dataTax['SubAmtTax'];

                                }
                            }

                        }
                        $sub_total = $sub_total  + $itemTotal;

                        $newTaxType .= ' <tr style="background: #80808052;border-bottom:2px dashed black; "> ';
                        $newTaxType .= ' <td style="text-align: left; font-weight: bold;">Group Sub Total</td> ';
                        $newTaxType .= ' <td></td> <td></td>';
                        $newTaxType .= ' <td>'.$sub_total.'</td> ';
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
                                    <td><?= $SerChargeAmt; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($tipamt > 0) : ?>
                                <tr>
                                    <th>TIP Amount</th>
                                    <td><?= $tipamt ?></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php
                                if($tipamt || $total_discount_amount != 0 || $total_packing_charge_amount || $total_delivery_charge_amount){?>
                                <tr style="border-bottom: 2px solid;">
                                    <th></th>
                                    <td></td>
                                </tr>
                            <?php   } ?>

                            <!-- Total Discount section -->
                            <?php
                                if ($total_discount_amount != 0) {?>
                                    <tr>
                                        <td>Discounts Availed</td>
                                        <td><?= $total_discount_amount; ?></td>
                                    </tr>
                            <?php   }?>
                            
                            <!-- Packing Charges section -->
                            <?php
                                if ($total_packing_charge_amount > 0) { ?>
                                    <tr>
                                        <td>Packing Charges</td>
                                        <td><?= $total_packing_charge_amount; ?></td>
                                    </tr>
                            <?php   }?>

                            <!-- Delivery Charges section -->
                            <?php
                                if ($total_delivery_charge_amount > 0) { ?>
                                    <tr>
                                        <td>Delivery Charge</td>
                                        <td><?= $total_delivery_charge_amount; ?></td>
                                    </tr>
                            <?php   }?>

                            <?php
                                if($total_discount_amount != 0 || $total_packing_charge_amount || $total_delivery_charge_amount){?>
                                <tr style=" border-bottom: 2px solid;">
                                    <th></th>
                                    <td></td>
                                </tr>
                            <?php   } ?>
                        
                            <tr>
                                <td style="font-weight: bold;">GRAND TOTAL</td>
                                <td style="font-weight: bold;"><?= $billData[0]['PaidAmt'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-bottom: 2px solid;"></div>
                    <?= $thankuline ?>
                    <br>
                    <?php if(isset($_GET['ShowRatings']) && $_GET['ShowRatings'] == 1){
                        
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
        </div><!--End Table-->

    </div><!--End InvoiceBot-->
  </div><!--End Invoice-->






</body>

</html>