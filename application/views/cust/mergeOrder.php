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
                                foreach ($orders as $ord) {
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="">
                                </td>
                                <td>
                                    <?= $ord['CellNo']; ?>
                                </td>
                                <td>
                                    <?= $ord['OrdAmt']; ?>
                                </td>
                            </tr>
                        <?php } } ?>
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
   $(document).ready(function() {
        
    });

</script>

</html>