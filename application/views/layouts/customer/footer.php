<div class="navbar menu-footer fixed-bottom" style="background: #dee2e6;">
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/menu.svg" width="33" height="20">
            <h6 style="font-size: 12px;">Account</h6>           
        </a>
        <div class="dropdown-menu">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="<?= base_url('customer/profile'); ?>">Profile</a>
            <a class="dropdown-item" href="<?= base_url('customer/transactions'); ?>">History</a>
            <a class="dropdown-item" href="<?= base_url('customer/reserve_table'); ?>">Book Table</a>
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
            <a class="dropdown-item" href="<?= base_url('customer/terms_conditions'); ?>" target="_blank">T &amp; C</a>
            <a class="dropdown-item" href="<?= base_url('customer/cookie_policy'); ?>" target="_blank">Cookie Policy</a>
            <a class="dropdown-item" href="<?= base_url('customer/privacy_policy'); ?>" target="_blank">Privacy Policy</a>
            <a class="dropdown-item" href="<?= base_url('customer/contact'); ?>">Contact Us</a>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/home.svg" width="33" height="20">
        <h6 style="font-size: 12px;">Outlet</h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <?php if($this->session->userdata('SchPop') > 0){ ?>
            <a href="#news" class="dropdown-item" data-toggle="modal" data-target="#offers-modal">Offers</a>
        <?php } if($this->session->userdata('Ent') > 0){ ?>
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#Ent_modal" >Entertainment</a>
            <?php } ?>
        </div>
    </div>


    <!-- <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="modal" data-target="#offers-modal">
            <img src="<?php echo base_url(); ?>assets/img/home.svg" width="33" height="20">
        <h6 style="font-size: 12px;"><?= $language['offers']?></h6>
        </a>
    </div> -->
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/inbox.svg" width="33" height="20">
        <h6 style="font-size: 12px;">Order</h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <a class="dropdown-item" href="<?= base_url('customer'); ?>">Menu</a>
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
                <a class="dropdown-item" href="<?= base_url('customer/cart'); ?>">Cart</a>
            <?php }
                $CustId = $this->session->userdata('CustId');
                $CNo = $this->session->userdata('CNo');
                $val = checkCheckout($CustId, $CNo);
             if(!empty($val)){
             ?>
            <a class="dropdown-item" href="#" onclick="goCheckout()">Checkout</a>
            <!-- <a class="dropdown-item" href="<?= base_url('customer/checkout'); ?>">Checkout</a> -->
            <?php } ?>
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

    <!-- entertainment modal -->
    <div class="modal" id="Ent_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title offers-txt">Entertainment</p>
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

    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Orders</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="checkoutForm">
                <div class="form-group">
                    <label for="">Send your pending orders to kitchen?</label>
                    <select name="orderOption" id="orderOption" class="form-control">
                        <option value="yes">Yes</option>
                        <option value="no">Reject</option>
                        <option value="cancel">View Cart</option>
                    </select>
                </div>
                <div>
                    <input type="button" class="btn btn-sm btn-success" value="Submit" onclick="checkoutForm()">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
        function goCheckout(){
            $('#checkoutModal').modal('show');

        }

        function checkoutForm(){
            var orderOption = $('#orderOption').val();
            $.post('<?= base_url('customer/go_checkout') ?>',{orderOption:orderOption},function(res){
                if(res.status == 2){
                   window.location = "<?php echo base_url('customer/checkout'); ?>"; 
                }else{
                    window.location = "<?php echo base_url('customer/cart'); ?>"; 
                }
            });
        }

        

        
    </script>