<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
  
  <title><?= $title; ?> | Eat-Out</title>
  <link href="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" rel="shortcut icon">
  <style>

@media print {
  .cutPrint {
    /*break-inside: avoid;*/
    page-break-before: always;
  }
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
  width: 65mm;
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
  <?php if(!empty($kotList)){ 
    foreach ($kotList as $kot) {
    ?>
    <div class="cutPrint">
    <center id="top">
        <h2 style="margin-bottom: 5px;font-size: 16px;"><?= authuser()->RestName.' ('.$kot[0]['KitName'].')';  ?></h2>
    </center>

    <div>
      <table style="border-bottom: 2px solid black;border-top: 2px solid black;font-size: 18px;">
        <tr>
          <td><?= $this->lang->line('kotNo'); ?>: <?php 
                      if ($this->session->userdata('MultiKitchen') > 0) {
                        echo convertToUnicodeNumber($kot[0]['KOTNo']).'-'.convertToUnicodeNumber($kot[0]['FKOTNo']);
                      }else{
                        echo convertToUnicodeNumber($kot[0]['KOTNo']);
                      }
                      ?><br>
            
          </td>
          <?php 
          if($kot[0]['OType'] < 100){
          ?>
          <td><?= $this->lang->line('tableNo'); ?>: <?= convertToUnicodeNumber($kot[0]['MergeNo']); ?></td> <?php } ?>
        </tr>
        <tr>
          <td><?= $this->lang->line('date'); ?>: <?= date('d-M-Y H:i', strtotime($kot[0]['LstModDt'])); ?></td>
          <td><?php 
          if($kot[0]['OType']== 101){
            echo "3rd Party";
          }else if($kot[0]['OType']== 105){
            echo "Take Away";
          }else if($kot[0]['OType']== 110){
            echo "Deliver";
          }else if($kot[0]['OType']== 1){
            echo "QSR";
          }else if($kot[0]['OType']== 25){
            echo "Drive-In";
          }else if($kot[0]['OType']== 30){
            echo "Charity";
          }else if($kot[0]['OType']== 35){
            echo "RoomService";
          }else if($kot[0]['OType']== 40){
            echo "Suite Service";
          }else{
            echo $this->lang->line('sitIn');
          } 
          ?></td>
        </tr>
      </table>
    </div>

    <div id="bot">

        <div id="table" style="border-bottom: 2px solid black;font-size: 18px;">
            <table>
                <tr class="tabletitle">
                    <th class="item" style="text-align: left;"><?= $this->lang->line('menuItem'); ?></th>
                    <th class="Hours" style="text-align: left;"><?= $this->lang->line('quantity'); ?></th>
                </tr>
                <?php
                $delvery = $this->lang->line('del');
                foreach($kot as $key){
                $portions = '';
                $std = '';
                $ta = '';
                  if($key['Portions'] != 'Std'){
                    $portions = ' ('.$key['Portions'].')';
                  }
                  if($key['CustItemDesc'] != 'Std'){
                    $std = ' - '.$key['CustItemDesc'];
                  }
                  
                  if(in_array($kot[0]['OType'], array(1,7,8))){
                    if($key['TA'] == 1){
                      $ta = ' (TA)';
                    }else if($key['TA'] == 2){
                      $ta = ' (Charity)';
                    }
                  }
                  $edt = '';
                  if($this->session->userdata('EDT') > 0 && $this->session->userdata('EType')== 5 && ($key['OType'] == 7 || $key['OType'] ==8)){
                      if(!empty($key['EDT'])){
                        $ta = $ta."  $delvery: ".convertToUnicodeNumber(date('H:i',strtotime($key['EDT'])));
                        if(empty($ta)){
                          $ta = "<br>$delvery: ".convertToUnicodeNumber(date('H:i',strtotime($key['EDT'])));
                        }
                      }
                  }
                 ?>
                <tr class="service">
                    <td class="tableitem">
                      <?= $key['ItemNm'].$portions.$std; ?><?= $ta ?> <?= $key['CustRmks']; ?>
                    </td>
                    <td class="tableitem"><?= convertToUnicodeNumber($key['Qty']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div><!--End Table-->

      </div>  <!--cutprint-->
    </div><!--End InvoiceBot-->
  <?php } } else{ ?>
    <h1>Something went wrong, please speak to support!</h1>
  <?php } ?>
  </div><!--End Invoice-->






</body>

</html>