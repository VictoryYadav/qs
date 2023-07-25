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
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="widget rate">
                                <!-- Heading -->
                                <h5 class="widget-header text-center">What would you rate
                                    <br>
                                    this product</h5>
                                <!-- Rate -->
                                    <i class="fa fa-mobile"></i>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Contact Us</h3>
                    <form action="">
                        <div class="form-group">
                            <label for="">Fullname</label>
                            <input type="text" class="form-control" name="fullname">
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
                        </div>
                    </form>
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