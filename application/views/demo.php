<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="assets_admin/img/favicon.png" type="image/x-icon" />
	<meta name="robots" content="noindex, nofollow">
    <title>Login | Eat-Out</title>
    <link href="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" rel="shortcut icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" >
    <style>
            /* Coded with love by Mutiullah Samim */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #f6f6f6 !important;
        }
        .user_card {
            /*height: 400px;*/
            /*width: 700px;*/
            /*margin-top: auto;*/
            /*margin-bottom: auto;*/
            background: #fff;
            position: absolute;
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 5px;

        }
        .brand_logo_container {
            position: absolute;
            height: 170px;
            width: 170px;
            top: -75px;
            border-radius: 50%;
            background: #dadddd;
            /*padding: 10px;*/
            text-align: center;
        }
        .brand_logo {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            border: 2px solid white;
            text-align: center;
        }
        .form_container {
            margin-top: 100px;
        }
        .login_btn {
            /*width: 100%;*/
            background: #00c602 !important;
            color: white !important;
            font-weight: 500;
        }
        .login_btn:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }
        .login_btn:hover {
          background-color: #2ce72e !important;
          color: white;
        }

        .login_container {
            padding: 0 2rem;
        }
        .input-group{
            padding: 1px;
            border: 1px solid #ced4da;
        }

        .input-group-text {
            background: #fff !important;
            color: #4f5153 !important;
            border: 0 !important;
            border-radius: 0.25rem 0 0 0.25rem !important;
        }
        .input_user,
        .input_pass:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }
        .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
            background-color: #c0392b !important;
        }

        .form-control{
            border:none;
        }
    </style>
</head>
<body >
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="container user_card fixed-bottom">
                <div class="justify-content-center">
                    <div class="row">
                        <div class="col-md-5 text-right">
                            <button class="btn btn-sm btn-success">Profile</button>
                            <button class="btn btn-sm btn-danger">History</button>
                        </div>
                        <div class="col-md-2 ">
                            <a href="">
                                <div class="brand_logo_container">
                                    <div class="" style="margin-top: 70px;">QR Scan</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-sm btn-success">Search</button>
                            <button class="btn btn-sm btn-danger">Discover</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets_admin/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/js/bootstrap.js"></script>
</body>
</html>