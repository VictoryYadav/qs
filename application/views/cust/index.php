<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title; ?> | Quick Service</title>

    <link rel="shortcut icon" href="<?= base_url() ?>assets_admin/images/QSLogo.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css" type="text/css">
    <style>
        .breadcrumb-section {
            display: flex;
            align-items: center;
            padding: 8px 0 8px;
            margin-bottom: -10px;
        }

        .responsive__tabs ul.scrollable-tabs {
          /*background-color: #333;*/
          overflow-x: auto;
          white-space: nowrap;
          display: flex;
          text-transform: uppercase;
          flex-direction: row;
          font-weight: 600;
          font-size: 14px;
        }
        .responsive__tabs ul.scrollable-tabs li {
          list-style-type: none;
        }
        .responsive__tabs ul.scrollable-tabs li a {
          display: inline-block;
          color: #252525;
          text-align: center;
          padding: 14px;
          text-decoration: none;
        }
        .responsive__tabs ul.scrollable-tabs li a:hover, .responsive__tabs ul.scrollable-tabs li a.active {
          /*background-color: #777;*/
          color:#008000;
        }

        /*filter section */
        .sec2-radio-grp {
            /*padding: 15px 30px;*/
            padding: 5px;
        }

        .veg-btn {
            border-radius: 25px 0 0 25px;
        }

        .both-btn {
            border-radius: 0 25px 25px 0;
        }
        .btn-b{
            border:1px solid #008000;
            background: white;
            font-size: 14px !important;
        }
        label.active{
            color:white;
            background: #008000;
        }
        /*end of filter section */

        .featured__controls{
            overflow: auto !important ;
            overflow-y: hidden !important;
            white-space: nowrap;
            margin-bottom: 0px !important;
        }

        #mCategory li.active {
          color:#008000;
        }
        /*grid view */
        .product{
            margin-top: -72px;
            /*padding-top: 10px !important;*/
        }

        .product__item {
            padding: 3px;
            margin-bottom: 10px !important;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            transition: all ease-in-out 0.3s;
        }

        .product__item:hover {
            border-color: #fff;
            box-shadow: 0 0px 25px 0px rgba(0, 0, 0, 0.1);
            transform: translateY(-10px);
        }

        .footer__widget {
            margin-bottom: 3px !important;
            overflow: hidden;
        }

        /* end grid view */

        /*mobile screen only*/
        @media only screen and (max-width: 480px) {
          .product__item__pic{
            height: 150px;
          }
          .mblclass{
            margin-bottom: -30px;
          }
        }

    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header" style="background: #f5f5f5;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-6">
                        <div class="header__top__left">
                            <ul>
                                <li><img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" style="width: 30px;height: 28px;"></li>
                                <li><img src="<?= base_url() ?>assets/img/search.png" alt="" style="width: 30px;height: 28px;"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-6">
                        <div class="header__top__right">
                            
                            <div class="header__top__right__language">
                                <div>English</div>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanis</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div>
                            <div class="header__top__right__auth">
                                <a href="#"><i class="fa fa-user"></i> Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </header>
    <!-- Header Section End -->

    <section class="breadcrumb-section">
        <div class="container" style="background: #f5f5f5;">
            <div class="responsive__tabs">
                <ul class="scrollable-tabs">
                    <?php
                    foreach ($cuisinList as $key) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if($cid == $key['CID']){ echo 'active'; } ?>" data-toggle="tab" href="#home" onclick="getCuisineList(<?php echo $key['CID']; ?>)"><?php echo $key['Name']; ?></a>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </section>

    <section class="breadcrumb-section" id="filterBlock">
        <div class="container text-center" style="background: #f5f5f5;">
            <div class="sec2-radio-grp btn-group btn-group-toggle" data-toggle="buttons" id="filters">
            </div>
        </div>
    </section>

    <section class="breadcrumb-section" id="mcatgBlock">
        <div class="container" style="background: #f5f5f5;">

            <div class="featured__controls">
                <ul id="mCategory" style="padding-bottom: 5px;">
                    
                </ul>
            </div>

        </div>
    </section>

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-12 col-md-12">
                    <div class="row" id="gridData">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="./index.html"><img src="<?= base_url(); ?>assets/img/logo.png" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello@colorlib.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">About Our Shop</a></li>
                            <li><a href="#">Secure Shopping</a></li>
                            <li><a href="#">Delivery infomation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Our Sitemap</a></li>
                        </ul>
                        <ul>
                            <li><a href="#">Who We Are</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Projects</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Testimonials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>Join Our Newsletter Now</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="Enter your mail">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text"><p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p></div>
                        <div class="footer__copyright__payment"><img src="<?= base_url(); ?>assets/img/payment-item.png" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="<?= base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.nice-select.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.slicknav.js"></script>
    <script src="<?= base_url(); ?>assets/js/mixitup.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/main.js"></script>
    


</body>

<script type="text/javascript">
    var cidg = '<?= $cid; ?>';
    var mcatIdg = '';
    var filterg = '';
    getCuisineList(cidg);
    function getCuisineList(cid){
        cidg = cid;
        console.log('cid='+cidg);
        $.post('<?= base_url('customer') ?>',{cid:cid},function(res){
            if(res.status == 'success'){
                
                var mCatList = res.response.list.mcat;
                var filter = res.response.list.filter;
                var MCatgId = mCatList[0].MCatgId;
                mcatIdg = MCatgId;
              // console.log(mCatList[0].MCatgId);
              console.log('ff'+filter.length);
              var mcat = '';
              var fltr = '';
              if(mCatList.length > 0){
                $('#mcatgBlock').show();
                for (var i = 0; i < mCatList.length; i++) {
                    var sts = '';
                    if(MCatgId == mCatList[i].MCatgId){
                        sts = 'active';
                    }
                    // mcat += '<li role="presentation" ><a href="#" aria-controls="home" role="tab" data-toggle="tab">'+mCatList[i].MCatgNm+'</a></li> data-filter="*"';
                    mcat +='<li class="'+sts+'" data-filter="*" onclick="clickMcat('+mCatList[i].MCatgId+')" style="font-size:14px;">'+mCatList[i].MCatgNm+'</li>';
                }
            }else{
                $('#mcatgBlock').hide();
            }

                if(filter.length > 0){
                    $('#filterBlock').show();
                    fltr += '<label class="btn btn-b veg-btn active">\
                        <input id="both-v-nv" type="radio" value="0" name="veg-nonveg" autocomplete="off" checked="" onchange="filterChange(0)">ALL</label>\
                    <label class="btn btn-b nonveg-btn">\
                        <input type="radio" value="'+filter[0].FID+'" name="veg-nonveg" autocomplete="off" onchange="filterChange('+filter[0].FID+')">'+filter[0].Opt+'</label>\
                    <label class="btn btn-b both-btn">\
                        <input type="radio" value="'+filter[0].FIdA+'" name="veg-nonveg" autocomplete="off" onchange="filterChange('+filter[0].FIdA+')">'+filter[0].AltOpt+'</label>';
                    $('#filters').html(fltr);
                }else{
                    $('#filterBlock').hide();
                }
                $('#mCategory').html(mcat);
                // call grid view
                console.log('mcat='+mcatIdg);
                console.log('filter='+filterg);
                getItemDetails(cid, mcatIdg, filterg);
            }else{
              alert(res.response);
              // show error msg pending
            }
        });

    }

    function clickMcat(mcatId){
        mcatIdg = mcatId; 
        console.log('cid='+cidg+',mcat='+mcatIdg+',fl='+filterg);
        getItemDetails(cidg, mcatIdg, filterg);
    }

    function filterChange(filter){
        filterg = filter; 
        console.log('cid='+cidg+',mcat='+mcatIdg+',fl='+filterg);
        getItemDetails(cidg, mcatIdg, filterg);
    }

    function getItemDetails(cid, mcatId, filter){
        // var cid = '10';
        // var mcatId = '1';
        // var filter = '3';
        $.post('<?= base_url('customer/getItemDetailsData') ?>',{cid:cid,mcatId:mcatId,filter:filter},function(res){
            if(res.status == 'success'){
              var data = res.response;
              console.log(data);
              var total = data.length;
              var temp = '';
              if(total > 0){
                  for (var i = 0; i< data.length; i++) {
                      temp += '<div class="col-lg-3 col-md-6 col-sm-6 col-6">\
                                <div class="product__item">\
                                    <div class="product__item__pic set-bg" data-setbg="<?= base_url(); ?>assets/img/product/product-7.jpg">\
                                    <img src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="" style="width:100%;">\
                                        <ul class="product__item__pic__hover">\
                                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>\
                                            <li><a href="#"><i class="fa fa-plus"></i></a></li>\
                                            <li><a href="#"><i class="fa fa-video-camera"></i></a></li>\
                                        </ul>\
                                    </div>\
                                    <div class="footer__widget">\
                                        <div class="row" style="margin-bottom: -33px;">\
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-12 mblclass">\
                                                <p>'+data[i].ItemNm+'</p>\
                                            </div>\
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-12 ">\
                                                <p class="text-right d-none d-sm-block">\
                                                    <i class="fa fa-inr " aria-hidden="true" style="color:blue;"></i> '+data[i].ItmRate+'\
                                                </p>\
                                                <p class="d-block d-sm-none">\
                                                    <i class="fa fa-inr " aria-hidden="true" style="color:blue;"></i> '+data[i].ItmRate+'\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="row" style="margin-bottom: -33px;">\
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-6">\
                                                <p>\
                                                    <i class="fa fa-star ratings text-warning" aria-hidden="true"></i> '+data[i].AvgRtng+'\
                                                </p>\
                                            </div>\
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-6">\
                                                <p class="text-right">\
                                                    <i class="fa fa-heartbeat" style="color:green;"></i>  '+data[i].NV+'\
                                                </p>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>';
                  }
                }else{
                    temp += '<div class="text-center text-danger">Data Not Found! </div>';
                }
              $('#gridData').html(temp);
            }else{
              alert(res.response);
              // show error msg pending
            }
        });
    }

    $('body').on('click', '.scrollable-tabs li', function() {
            $('.scrollable-tabs li a.active').removeClass('active');
            $(this).addClass('active');
        });

    $('body').on('click', '#mCategory li', function() {
            $('#mCategory li.active').removeClass('active');
            $(this).addClass('active');
        });
</script>

</html>