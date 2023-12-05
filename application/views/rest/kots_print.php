<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
  
  <title>KOT Print | Eat-Out</title>
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
  <?php if(!empty($kotList)){ ?>
    <center id="top">
        <h2 style="margin-bottom: 5px;font-size: 16px;"><?= authuser()->RestName.' ('.$kotList[0]['KitName'].')';  ?></h2>
    </center>

    <div>
      <table style="border-bottom: 2px solid black;border-top: 2px solid black;font-size: 18px;">
        <tr>
          <td>KOT No: <?php 
                        echo $kotList[0]['KitCd'].'-'.$kotList[0]['FKOTNo'];
                      ?><br>
            
          </td>
          <td>Table: <?= $kotList[0]['TableNo']; ?></td>
        </tr>
        <tr>
          <td>Date: <?= date('d-M-Y H:i', strtotime($kotList[0]['LstModDt'])); ?></td>
          <td><?php 
          if($kotList[0]['OType']== 101){
            echo "3rd Party";
          }else if($kotList[0]['OType']== 105){
            echo "Take Away";
          }else if($kotList[0]['OType']== 110){
            echo "Deliver";
          }else if($kotList[0]['OType']== 1){
            echo "QSR";
          }else if($kotList[0]['OType']== 25){
            echo "Drive-In";
          }else if($kotList[0]['OType']== 30){
            echo "Charity";
          }else if($kotList[0]['OType']== 35){
            echo "RoomService";
          }else if($kotList[0]['OType']== 40){
            echo "Suite Service";
          }else{
            echo "Sit In";
          } 
          ?></td>
        </tr>
      </table>
    </div>

    <div id="bot">

        <div id="table" style="border-bottom: 2px solid black;font-size: 18px;">
            <table>
                <tr class="tabletitle">
                    <th class="item" style="text-align: left;">Menu Item</th>
                    <th class="Hours" style="text-align: left;">Qty</th>
                </tr>
                <?php
                foreach($kotList as $key){
                $portions = '';
                $std = '';
                $ta = '';
                  if($key['Portions'] != 'Std'){
                    $portions = ' ('.$key['Portions'].')';
                  }
                  if($key['CustItemDesc'] != 'Std'){
                    $std = ' - '.$key['CustItemDesc'];
                  }
                  
                  if(in_array($kotList[0]['OType'], array(1,7,8))){
                    if($key['TA'] > 0){
                      $ta = '(TA)';
                    }
                  }
                  $edt = '';
                  if($this->session->userdata('EDT') > 0 && $this->session->userdata('EType')== 5 && ($key['OType'] == 7 || $key['OType'] ==8)){
                      if(!empty($key['EDT'])){
                        $ta = $ta.'  Del: '.date('H:i',strtotime($key['EDT']));
                        if(empty($ta)){
                          $ta = '<br>Del: '.date('H:i',strtotime($key['EDT']));
                        }
                      }
                  }
                 ?>
                <tr class="service">
                    <td class="tableitem">
                      <?= $key['ItemNm'].$std.$portions; ?><br><?= $key['CustRmks']; ?><?= $ta ?>
                    </td>
                    <td class="tableitem"><?= $key['Qty']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div><!--End Table-->

    </div><!--End InvoiceBot-->
  <?php }else{ ?>
    <h1>Something went wrong, please pick to support!</h1>
  <?php } ?>
  </div><!--End Invoice-->






</body>

</html>