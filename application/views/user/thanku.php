<?php $this->load->view('layouts/customer/head');
$folder = 'e'.$this->session->userdata('EID'); 
 ?>
<style>
body{
    font-size: 13px;
}

</style>

</head>

<body>

    <!-- Header Section Begin -->
    <section class="header-section">
        <div class="container p-2">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-4">
                    <ul class="list-inline product-meta">
                        <li class="list-inline-item">
                            <a href="#">
                                <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="width: 30px;height: 28px;">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 col-sm-8 col-8 text-right">
                    <ul class="list-inline product-meta">

                        <li class="list-inline-item">
                            <img src="<?= base_url('uploads/'.$folder.'/logo.jpg') ?>" width="auto" height="28px;">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto text-center" style="height: 85vh;display: flex;flex-direction: column;justify-content: center;">
                    <h1>Thank You.</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

</html>