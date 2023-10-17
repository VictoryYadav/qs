<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container" style="margin-bottom: 30px;">
        <div class="container">
            <div class="row">
                <div class="col-md-7 mx-auto">
                    <p><i>Eat-Out - Tech by <b>VTREND</b> Services Pvt Ltd.</i></p>
                    <h3>Contact Us</h3>
                    <form action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="fullname" placeholder="FullName">
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" name="phone" placeholder="Phone No">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <select name="" id="" class="form-control">
                                <option value="">Choose</option>
                                <option value="feedback">Feedback</option>
                                <option value="compliment">Compliment</option>
                                <option value="complaint">Complaint</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea name="description" class="form-control" placeholder="Descriptions....."></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success form-control" value="Submit">
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <h3>VTREND Services Pvt Ltd.</h3>
                        <ul>
                            <li class="my-4"><strong>Address</strong> : 301, Guru Ashish Limited, North Avenue, Santacruz(W), Mumbai 400054</li>
                            <li class="my-4"><strong>Email </strong>: sales@vtrend.org, support@vtrend.org</li>
                        </ul>
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