<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="">
                        <div class="table-responsive">
                          <table class="table table-striped">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CellNo</th>
                                    <th>Order Amt</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                if(!empty($orders)){
                                    $total = 0;
                                    foreach ($orders as $ord) {
                                        $total = $total + $ord['OrdAmt'];
                                ?>
                                <tr onclick="getOrderDetails(<?= $ord['CNo']; ?>)">
                                    <td>
                                        <input type="checkbox" name="" checked="" onchange="calcValue(<?= $ord['ItemId']; ?>,<?= $ord['OrdAmt']; ?>)" id="item_<?= $ord['ItemId']; ?>">
                                    </td>
                                    <td>
                                        <?= $ord['CellNo']; ?>
                                    </td>
                                    <td>
                                        <?= $ord['OrdAmt']; ?>
                                    </td>
                                </tr>
                            <?php } } ?>
                            <tr>
                               <td></td> <td>Grand Total</td><td id="grandTotal" style="font-weight: bold;"><?= $total; ?></td>
                            </tr>
                            </tbody>

                          </table>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-sm btn-success" value="Merge">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- offers modal -->
    <div class="modal" id="orderDetails">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title offers-txt">Offers</p>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody id="orderBody">
                            </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">
   $(document).ready(function() {
        
    });

   function getOrderDetails(CNo){
    console.log('ff '+CNo);
        $.post('<?= base_url('customer/get_merge_order') ?>',{CNo:CNo},function(res){
            if(res.status == 'success'){
              // alert(res.response);
              var data = res.response;
              console.log(data);
              var temp = '';
              for (var i = 0; i < data.length; i++) {
                  temp += '<tr><td>'+data[i].ItemNm+'</td><td>'+data[i].Qty+'</td><td>'+data[i].OrdAmt+'</td></tr>';
              }
              $('#orderBody').html(temp);
              // $('#orderDetails').modal('show');
            }else{
            }
        });
   }

   function calcValue(itemId, val){
    var grandTotal = 0;
    var total = $('#grandTotal').text();
    console.log('kk '+val+' tt '+total);

    if ($('item_'+itemId).is(':checked')) {
        grandTotal = parseInt(total) + parseInt(val);
    }else{
        grandTotal = parseInt(total) - parseInt(val);
    }

    // if(total > 0){
    //     grandTotal = parseInt(total) - parseInt(val);
    // }else{
    //     grandTotal = parseInt(total) + parseInt(val);
    // }
    $('#grandTotal').html(grandTotal);
   }

</script>

</html>