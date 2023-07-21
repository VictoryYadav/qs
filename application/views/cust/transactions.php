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
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead style="font-size: 12px;">
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Amt</th>
                                <th>Rated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            for ($i = 0; $i < count($custPymt); $i++) {
                                ?>
                                <tr onclick="RedirectPage(<?php echo $custPymt[$i]['BillId'] ?> , <?php echo $custPymt[$i]['EID'] ?>,'<?php echo $custPymt[$i]['DBName'] ?>','<?php echo $custPymt[$i]['DBPasswd'] ?>')">
                                    <td><?php echo date('d-M-Y',strtotime($custPymt[$i]['billdt'])); ?></td>
                                    <td><?php echo $custPymt[$i]['Name'] ?></td>
                                    <td><?php echo $custPymt[$i]['PaidAmt'] ?></td>
                                    <td>-</td>
                                </tr>
                            <?php
                            }
                            ?>

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
    function RedirectPage(id, eid, dbname, dbpass) {
        window.location.href = "/bill_rcpt.php?billId=" + id + "&EID=" + eid + "&dbn=" + dbname + "&dbp=" + dbpass+"&ShowRatings=1";
    }

</script>

</html>