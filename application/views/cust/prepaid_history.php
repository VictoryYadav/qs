<?php $this->load->view('layouts/customer/head'); ?>
<style>

#RecommendationModal,.common-section{
    font-size: 13px;
}

.form-control {
    border-radius: 2px;
    height: 25px !important;
    background-color: transparent;
    color: #666;
    box-shadow: none;
    font-size: 11px !important;
}

#cartView{
       height: 400px;
       overflow: auto; 
    }
/*mobile screen only*/
@media only screen and (max-width: 480px) {
    #cartView{
       height: 480px;
       overflow: auto; 
    }
}

table th,td{
    text-align: center;
    vertical-align: center;
}


</style>

<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
" rel="stylesheet">

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js
"></script>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <!-- Shoping Cart Section Begin -->
    <section class="common-section p-2 dashboard-container">
        <div class="container">
            
            <div class="row" id="cartView">
                <div class="col-lg-12">
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                        <thead>
                          <tr>
                            <th class="text-center">Bill No</th>
                            <th class="tex-center">Bill Date</th>
                            <th width="text-center">Bill Amount</th>
                            <th class="text-center">Paid Amt</th>
                          </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <?php 
                            if(!empty($details)){
                                $Pretotal   = 0;                               
                                $Billtotal  = 0;
                                foreach ($details as $key) {
                                    $BillAmount = 0;
                                    $PreAmount = 0;
                                    if($key['BillId'] > 0){
                                        $BillAmount = $key['Amount'];
                                    }else{
                                        $PreAmount = $key['Amount'];
                                    }

                                    $Pretotal   = $Pretotal + $PreAmount;
                                    $Billtotal  = $Billtotal + $BillAmount;
                                 ?>
                                 <tr>
                                    <td><?= $key['BillId']; ?></td>
                                    <td><?= date('d-M-Y', strtotime($key['Created_at'])); ?></td>
                                    <td><?= $BillAmount; ?></td>
                                    <td><?= $PreAmount; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><b><?= $Billtotal; ?></b></td>
                                <td><b><?= $Pretotal; ?></b></td>
                            </tr>
                            <?php }else{ ?>
                                <tr>
                                    <td>No Records Found!!</td>
                                </tr>
                        <?php } ?>
                        </tbody>
                      </table>
                  </div>
                  
                </div>
            </div>

        </div>
    </section>
   
    <!-- Shoping Cart Section End -->

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    
    <?php $this->load->view('layouts/customer/script'); ?>

</body>

</html>