<?php $this->load->view('layouts/customer/head'); ?>
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

    .remove-margin {
        margin-left: 0px;
        margin-right: 0px;
    }

    h3 {
        color: #fff;
        font-weight: bold;
    }

    .billView{
        /*margin-top: 25px;*/
        /*overflow-y: scroll;
        height: 78vh;*/
        height: 400px;
        overflow: auto; 
    }
    /*mobile screen only*/
    @media only screen and (max-width: 480px) {
        #billView{
           height: 480px;
           overflow: auto; 
        }
    }

.payment-btns 
{
    padding-left: 10px;
    padding-right: 10px;
}

.paybtn 
{
    width: 50%;
    background: <?php echo $this->session->userdata('successBtn'); ?>;
    color: <?php echo $this->session->userdata('successBtnClr'); ?>;
    height: 30px;
    margin-left: 0px !important;
    border-radius: 0 1.5rem 1.5rem 0;
}

.paybtn:hover
{
    background: #03bb2c;
    color: #fff;
    margin-left: 0px !important;
    border-radius: 0 1.5rem 1.5rem 0;
}

.backbtn 
{
    width: 50%;
    margin-right: 0px !important;
    border-radius: 1.5rem 0 0 1.5rem;
    background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
    color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
    height: 30px;
}

.backbtn:hover
{
    background-color: #efd4b3;
    color:#fff;   
}

#innerBlock table{
    width: 100%;
}

/*#innerBlock th, td {
  padding: 5px;
}*/
    </style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">

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
                    
                    <?php if($CustNo == 0){?>

                        <div class="row">
                            <div class="col-6"> 
                            <?php if(!empty($Fullname)){
                                echo $Fullname;
                            }
                             ?>
                            </div>
                            <div class="col-6" style=" text-align: right;"> 
                                Cell No: <?= $CellNo; ?>
                            </div>

                        </div>

                    <?php } ?>

                    <div style="border-bottom: 1px solid;"></div>

                    <div class="row">
                        <div class="col-6">
                            <p style="margin-bottom: unset;font-size: 15px !important;">Bill No: <b><?= $billno ?></b></p>
                        </div>
                        <?php if ($CustNo != "0") : ?>
                            <div class="col-6">
                                <p style="margin-bottom: unset;">Ord: <b><?= $CustNo ?></b></p>
                            </div>
                        <?php endif; ?>
                        <?php if($this->session->userdata('billPrintTableNo') > 0) { ?>
                        <div class="col-6" style="text-align: right;">
                            <p style="margin-bottom: unset;font-size: 15px !important;">Table No: <b><?= $MergeNo ?></b></p>
                        </div>
                        <?php } ?>
                        <div class="col-12">
                            <p style="margin-bottom: unset;font-size: 15px !important;">Date: <?= $dateOfBill ?></p>
                        </div>
                    </div>
                    
                    <div style="border-bottom: 1px solid;"></div>
                    <!-- bill body here -->

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
                                      $ta = '[TA]';
                                    }else if($data['TA'] == 2){
                                      $ta = '[Charity]';
                                    }
                                    
                                    $CustItemDesc = '';
                                    if(!empty($data['CustItemDesc'])){
                                        $CustItemDesc = ($data['CustItemDesc'] !='Std')?'-'.$data['CustItemDesc']:'';
                                    }
                                        $sameTaxType .= ' <tr> ';
                                        if($data['Itm_Portion'] > 4 ){
                                            $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].' ('.$data['Portions'].')'.$ta.$CustItemDesc.' </td> ';
                                        }else{
                                            $sameTaxType .= '<td style="float: left;">'.$data['ItemNm'].$ta.$CustItemDesc.'</td> ';
                                        }
                                        
                                        $sameTaxType .= ' <td style="text-align: right;"> '.$qty.' </td>';
                                        $sameTaxType .= ' <td style="text-align: right;">'.$data['OrigRate'].'</td> ';
                                        $sameTaxType .= ' <td style="text-align: right;">'.round($data['ItemAmt'],2).'</td> ';
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
                                        <td>Total Discounts</td>
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
                                <td><?= $billData[0]['discountName']; ?> Discount @ <?= $billData[0]['discPcent']; ?>%</td>
                                <td style="text-align: right;"><?= $billData[0]['autoDiscAmt']; ?></td>
                            </tr>
                            <?php } ?>
                        
                            <tr>
                                <td><span style="font-weight: bold;">GRAND TOTAL</span>(<small>rounded off</small>)</td>
                                <td style="text-align: right;font-weight: bold;"><?= round($billData[0]['PaidAmt']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-bottom: 1px solid;"></div>
                    <p><?= $thankuline ?></p>
                    <?php if($splitTyp == 2){ ?>
                    <p style="color: red;">Note : Split Bills</p>
                    <?php } ?>
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
            </div>

            <div id="editor"></div>
                
            <div class="row remove-margin payment-btns fixed-bottom" style=" width: 100%; margin-left: 1px;bottom: 60px !important;">
                <?php if(isset($_GET['EID'])) { ?>
                <a class="btn btn-sm backbtn" href="<?= base_url('customer'); ?>" style="border-radius: 50px;width: 100%;"><?= $this->lang->line('menu'); ?></a>
            <?php }else{ ?>
                <a class="btn btn-sm backbtn" href="<?= base_url('customer'); ?>" style="width: 50%;"><?= $this->lang->line('menu'); ?></a>

                <a href="<?= base_url('customer/rating/'.$billId);?>" class="btn btn-sm paybtn" style="width: 50%;"><?= $this->lang->line('rating'); ?></a>
            <?php } ?>

            </div>
        </div>
    </section>

<!-- loyalty modal -->
    <div class="modal fade" id="loyalityModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header" style="background: #e1af75;">
            <h4 class="modal-title text-white">Loyalty Points</h4>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-danger">Note : Eat-Out loyalty is valid acorss all outlets using Eat-Out.<br>Restaurant loyalty may be valid across the chain.</p>
                    <form method="post" id="loyaltyForm">
                        <div id="listBlock"></div>
                        <div id="innerBlock" class="mt-2">
                        </div>
                        <div class="mt-2">
                            <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->
<script src="<?= base_url(); ?>assets/js/sendalert_notification.js"></script>

</body>

<script>
                    

$(document).ready(function() {
    $("div.desc").hide();
    

    // var doc = new jsPDF();
    // var specialElementHandlers = {
    //     '#editor': function(element, renderer) {
    //         return true;
    //     }
    // };

    // $('#download-pdf').click(function() {
    //     doc.fromHTML($('#download-to-pdf').html(), 15, 15, {
    //         'width': 170,
    //         'elementHandlers': specialElementHandlers
    //     });
    //     doc.save('bill.pdf');
    // });
});

    var billId = "<?= $billId; ?>";

// $('#download-to-pdf').on('click', function() {
//     alert('Thank you for using Quick Service.');
// });

    function bill_page(billid){
        window.location.href = "<?= base_url('customer/rating');?>"+billid;
    }

    var CustLoyalty = "<?php echo $this->session->userdata('CustLoyalty'); ?>";
    var checkLoyalty = "<?= $checkLoyalty; ?>";
    if(CustLoyalty > 0 && (checkLoyalty == 0) ){
        getLoyality();
    }

    function getLoyality(){
        var billAmount = "<?= $billData[0]['PaidAmt'] ?>";

        $.post('<?= base_url('customer/get_loyality') ?>',{billId:billId},function(res){
            if(res.status == 'success'){
              var data = res.response;
              if(data.length > 0){
                var rdoOption = '';
                var inner = ``;
                var tblTemp = ``;
                data.forEach((item, index) => {
                    rdoOption += `<div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="LNo" onchange="showPoints(${item.LNo})" value="${item.LNo}" required>${item.Name}
                          </label>
                          <input type="hidden" name="totalPoints[${item.LNo}]" value="${item.totalPoints}" />

                          <input type="hidden" name="billAmount" value="${billAmount}" />
                          <input type="hidden" name="billId" value="${billId}" />
                        </div>`;
                        inner = `<div id="loyalty_${item.LNo}" class="desc">
                                    <table style="border: 1px solid #bdb4b4;border-collapse: collapse;font-size:13px;">
                                        <tr>
                                            <th>Mode</th>
                                            <th>Points</th>
                                        </tr>`;
                        item.points.forEach((loy, ind) =>{
                            inner += `<tr>
                                            <td>${loy.Name}</td>
                                            <td>${loy.PointsValue}</td>
                                      </tr>`;
                        });
                         inner += `</table>
                                    </div>`;
                        $(`#innerBlock`).append(inner);
                });
                $(`#listBlock`).html(rdoOption);
                $("div.desc").hide();
                $(`#loyalityModal`).modal('show');
              }
            }else{
              alert(res.response);
            }
        });
    }

    function showPoints(LNo){
        $("div.desc").hide();
        $(`#loyalty_${LNo}`).show();
    }

    $('#loyaltyForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/update_loyalty_point') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });
    })

</script>

</html>