<?php $this->load->view('layouts/customer/head'); ?>
<style>
/*select2*/
    .select2-container--default .select2-selection--single {
      background-color: #00000000;
      border: 1px solid #ced4da;
      border-radius: 2px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #717070;
        line-height: 28px;
        font-size: 11px;
    }
    .select2-container .select2-selection--single {
      height: 29px;
    }
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
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
                    <?php endif; ?>
                    <p><i>Eat-Out - Tech by <b>VTREND</b> Services Pvt Ltd.</i></p>
                    <h3>Contact Us</h3>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="fullname" placeholder="FullName" required="">
                        </div>
                        <div class="form-group">
                            <select name="CountryCd" class="form-control CountryCd select2 custom-select" required="" >
                                <option value=""><?= $this->lang->line('select'); ?></option>
                                <?php 
                                foreach ($country as $key) { ?>
                                    <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                <?php } ?> 
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="tel" class="form-control" name="phone" placeholder="Phone No" required="">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <select name="type" class="form-control" required="">
                                <option value="">Choose</option>
                                <option value="feedback">Feedback</option>
                                <option value="compliment">Compliment</option>
                                <option value="complaint">Complaint</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                        </div>
                        <div class="form-group">
                            <textarea name="description" class="form-control" placeholder="Descriptions....." required=""></textarea>
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
     $('.CountryCd').select2();
   });

</script>

</html>