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
                
                
                <div class="col-md-2">
                    <button class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table order-list" id="splitTable">
                            <thead>
                                <tr>
                                    <th>Mobile</th>
                                    <th>Mode</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="addBody">
                                <?php
                                if(!empty($shareDetails)){
                                    $count = 0;
                                    foreach ($shareDetails as $key) {
                                        $count++;
                                 ?>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" name="mobile" id="mobile" readonly="" value="<?= $key['CellNo']; ?>">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode" class="form-control" required="">
                                            <option value="">Choose Mode</option>
                                            <option value="w">WhatsApp</option>
                                            <option value="t">Telegram</option>
                                            <option value="e">Email</option>
                                            <option value="s">SMS</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button>
                                    </td>
                                </tr>
                            <?php  }  } else{ ?>
                                <tr>
                                    <td>
                                        <input type="number" placeholder="Mobile" class="form-control" required name="mobile" id="mobile">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode" class="form-control" required="">
                                            <option value="">Choose Mode</option>
                                            <option value="w">WhatsApp</option>
                                            <option value="t">Telegram</option>
                                            <option value="e">Email</option>
                                            <option value="s">SMS</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button>
                                    </td>
                                </tr>
                            <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">

    $(document).ready(function () {
        goToBill();

    var counter = 1;

    $("#addrow").on("click", function () {
        counter++;

        var newRow = '<tr>\
                        <td>\
                            <input type="number" placeholder="Mobile" class="form-control" required name="mobile" id="mobile">\
                        </td>\
                        <td>\
                            <select name="mode" id="mode" class="form-control" required="">\
                                <option value="">Choose Mode</option>\
                                <option value="w">WhatsApp</option>\
                                <option value="t">Telegram</option>\
                                <option value="e">Email</option>\
                                <option value="s">SMS</option>\
                            </select>\
                        </td>\
                        <td>\
                            <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button>\
                        </td>\
                    </tr>';

        $("table.order-list").append(newRow);
    });

   
});

// goto bill page
function goToBill(){
    console.log('hi');
    var BillId = '<?= $BillId; ?>';
    var payable = $('#payable').text();
    var total = $('#sum').val();

    if(payable == total){
     window.location = '<?= base_url();?>customer/bill/'+BillId;   
    }

    // setInterval(function(){ goToBill(); }, 3000);
}

</script>

</html>