<?php $this->load->view('layouts/customer/head'); ?>
<style>
p{
font-size: 12px !important;
}
@font-face {
    font-family: Montserrat Bold;
    src: url(fonts/Montserrat-Bold.otf);
}

@font-face {
    font-family: Montserrat Regular;
    src: url(fonts/Montserrat-Regular.otf);
}

.backbtn 
{
    width: 50%;
    margin-right: 0px !important;
    border-radius: 1.5rem 0 0 1.5rem;
    background-color: #bfbcbc;
    color:#fff;
    height: 30px;
}

.backbtn:hover
{
    background-color: #9d9696;
    color:#fff;   
}

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            <div class="text-center text-danger">
                <p class="text-danger">
                    Something Went Wrong !
                </p>
                <p class="text-danger">
                    Please Try Again Later!
                </p>
                <p class="text-danger">
                    <a href="<?= base_url('customer'); ?>" class="btn btn-sm backbtn" style="border-radius: 50px;">Menu</a>
                </p>
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