<div class="navbar menu-footer fixed-bottom" >
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/menu.svg" width="33" height="20">
            <h6 style="font-size: 12px;">Account</h6>           
        </a>
        <div class="dropdown-menu">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="/cust_registration.php">Edit Profile</a>
            <a class="dropdown-item" href="<?= base_url('customer/transactions'); ?>">Transaction</a>
            <a class="dropdown-item" href="reserve_table.php">Book Table</a>
            <a class="dropdown-item" href="/cust_registration.php">Refer Outlet</a>
            <a class="dropdown-item" href="#">Username(<?= $_SESSION['signup']['MobileNo']; ?>)</a>
            <a class="dropdown-item" href="<?= base_url('customer/logout'); ?>">Logout</a>
        <?php } else { ?>
            <a class="dropdown-item" href="<?= base_url('customer/login'); ?>">Login</a>
        <?php } ?>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/feedback.svg" width="33" height="20">
            <h6 style="font-size: 12px;"><?= $language['about_us']?></h6>          
            </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">T &amp; C</a>
            <a class="dropdown-item" href="#">Testimonials</a>
            <a class="dropdown-item" href="#">Contact Us</a>
        </div>
    </div>
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="modal" data-target="#offers-modal">
            <!-- <a data-toggle="modal" data-target="#offers-modal"> -->
            <img src="<?php echo base_url(); ?>assets/img/home.svg" width="33" height="20">
        <h6 style="font-size: 12px;"><?= $language['offers']?></h6>
        </a>
    </div>
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/inbox.svg" width="33" height="20">
        <h6 style="font-size: 12px;">Order List</h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="<?= base_url('customer/cart'); ?>">Order List</a>
        <?php } ?>
            <a class="dropdown-item" href="send_to_kitchen.php">Current Order</a>
        </div>
    </div>
</div>

<!-- offers modal -->
    <div class="modal" id="offers-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title offers-txt">Offers</p>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body" style="border: none;padding: 0px;overflow-y: scroll;height: 425px;">
                    <?php 
                    $offers = allOffers();
                    if(!empty($offers)){?>
                        <?php foreach($offers as $key){
                            $name = '';
                            if(!empty($key['ItemNm'])){
                                $name  .=  $key['ItemNm'];
                            }
                            if(!empty($key['portionName'])){
                                $name  .=  ' ('.$key['portionName'].')';
                            }
                            if(!empty($key['MCatgNm'])){
                                $name  .=  ' - '.$key['MCatgNm'];
                            }
                            if(!empty($key['Name'])){
                                $name  .=  ' - '.$key['Name'];
                            }
                            ?>

                            <div class="ad-listing-list mt-20">
                                <div class="row p-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 align-self-center">
                                        <img src="<?= base_url($key['SchImg']); ?>" alt="" style="height: 80px;width: 100px;background-size: cover;">
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                        <h6><?= $key['SchNm'].' - '.$key['SchDesc'];?></h6>
                                        <p class="pr-5"><?= $name; ?></p>
                                    </div>
                                </div>
                            </div>
                            
                        <?php }?>
                            
                    <?php }?>
                </div>
            </div>
        </div>
    </div>