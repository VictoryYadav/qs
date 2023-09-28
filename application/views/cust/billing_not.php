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

        .single-category h3 {
            font-family: Montserrat Bold;
        }

        .offers-tab label {
            font-family: Montserrat Regular;
        }

        .remove-margin {
            margin-left: 0px;
            margin-right: 0px;
        }

        .img-category {
            height: 95px;
            width: 125px;
        }

        .single-category {
            height: 125px;
            display: flex;
            align-items: center;
            background-color: #0a88ff;
        }

        .single-category:nth-last-child(1) {
            margin-bottom: 60px;
        }

        #body-row {
            top: 44px;
            height: 100%;
            position: relative;
        }

        .header {
            color: blue;
        }

        .main-header {
            margin-left: 0px;
            margin-right: 0px;
            /*padding: 10px 20px;*/
            position: fixed;
            background: #fff;
            z-index: 900;
            width: 100%;
        }

        .top-header {
            padding: 3px 20px;
        }

        .header-left,
        .header-right {
            display: inline-block;
            width: 49%;

        }

        .header-right {
            text-align: right;
        }

        .header-left p,
        .header-right p {
            margin: 0px;
        }

        .header-right,
        .header-left {
            font-weight: bold;
        }

        .header-left {
            font: 20px;
        }

        h3 {
            color: #fff;
            font-weight: bold;
        }

        .navbar {
            text-align: center;
            overflow: hidden;
            background-color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 0px;
        }

        .navbar a {
            float: left;
            display: block;
            color: #000;
            text-align: center;
            padding: 14px 13px;
            text-decoration: none;
            font-size: 7px;
            /* width: 20%; */
        }

        .navbar a:hover {
            background: #f1f1f1;
            color: black;
        }

        .navbar a.active {
            background-color: #0a88ff;
            color: #fff;
        }

        .main {
            padding: 16px;
            margin-bottom: 30px;
        }

        .navbar a img {
            display: inherit;
        }

        .offers-tab {
            background-color: #0a88ff;
            top: 59px;
            position: relative;
            width: 100%;
            padding: 10px 15px;
            text-align: center;
        }

        .menu-radio {
            border-radius: 25px 0 0 25px;
        }

        .offers-rdaio {
            border-radius: 0 25px 25px 0;
        }

        #sidebar {
            z-index: 1000;
            width: 50%;
        }

        #sidebar .sidebar-header {
            height: 150px;
            background: url(assets/img/a.png);
            background-size: cover;
            background-repeat: no-repeat;
            padding: 0px;
        }

        #sidebar ul li a {
            border-radius: 50px;
            border: 1px solid #fff;
        }

        #sidebar ul.components {
            padding: 20px 15px 20px 20px;
        }

        #sidebar ul li a {
            padding: 5px 10px;
            font-size: 1rem;
        }

        #sidebar ul li {
            margin-bottom: 10px;
        }

        .name-overlay {
            background: rgba(0, 94, 222, 0.82);
            overflow: hidden;
            height: 100%;
            z-index: 2;
            width: 100%;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .menu-footer {
            margin-bottom: 0px;
        }

        .close {
            font-size: 15px !important;
            font-weight: 300 !important;
            padding-top: 21px !important;
            padding-bottom: 0px !important;
            border: none !important;
            color: #fff;
        }

        .modal-header {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .offers-txt {
            color: #fff;
        }

        .dropup .dropdown-toggle::after {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: 0;
            border-right: .3em solid transparent;
            border-bottom: none !important;
            border-left: .3em solid transparent;
        }

        #catg-list a {
            color: white;
            text-align: center;
        }

        .dropdown-menu a {
            font-size: 15px !important;
        }

        .billView{
            /*margin-top: 25px;*/
            /*overflow-y: scroll;
            height: 78vh;*/
            height: 400px;
            overflow: auto; 
        }
        /*mobile screen only*/
        @media only screen and (max-width: 480px) {
            #billView{
               height: 480px;
               overflow: auto; 
            }
        }

.payment-btns 
{
    padding-left: 10px;
    padding-right: 10px;
}

.paybtn 
{
    width: 50%;
    background: #30b94f;
    color: #fff;
    /*background: #000 !important;*/
    /*color: <?php echo isset($body_btn2text)?$body_btn2text:"#000"?> !important;*/
    height: 30px;
    margin-left: 0px !important;
    border-radius: 0 1.5rem 1.5rem 0;
}

.paybtn:hover
{
    background: #03bb2c;
    color: #fff;
    margin-left: 0px !important;
    border-radius: 0 1.5rem 1.5rem 0;
}

.backbtn 
{
    width: 50%;
    margin-right: 0px !important;
    border-radius: 1.5rem 0 0 1.5rem;
    background-color: #bfbcbc;
    color:#fff;
    height: 30px;
    /*background-color:#000 !important;*/
    /*color: <?php echo isset($body_btn1text)?$body_btn1text:"#000"?> !important;*/
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
<script src="<?= base_url(); ?>assets/js/sendalert_notification.js"></script>
</body>

</html>