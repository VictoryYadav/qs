
<style>
/*modal center*/
.modal-dialog {
  margin-top: 0;
  margin-bottom: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.footerColor{
    background: <?php echo $this->session->userdata('footerClr'); ?>;
}

h6{
 color: <?php echo $this->session->userdata('footerTxtClr'); ?>;
 font-size: 12px;
}


./*modal.fade .modal-dialog {
  transform: translate(0, -100%);
}

.modal.in .modal-dialog {
  transform: translate(0, 0);
}*/
/* end modal center*/
</style>
<div class="navbar menu-footer fixed-bottom footerColor">
    <input type="hidden" value="<?php echo $this->session->userdata('site_lang'); ?>" id="site_lang">
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/menu.svg" width="33" height="20">
            <h6><?= $this->lang->line('account'); ?></h6>           
        </a>
        <div class="dropdown-menu">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="<?= base_url('customer/profile'); ?>"><?= $this->lang->line('profile'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/current_order'); ?>"><?= $this->lang->line('currentorder'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/loyalty'); ?>"><?= $this->lang->line('loyalty'); ?></a>

            <a class="dropdown-item" href="<?= base_url('customer/transactions'); ?>"><?= $this->lang->line('history'); ?></a>
            <a class="dropdown-item" href="#"><?= $this->lang->line('username'); ?>(<?= $_SESSION['signup']['MobileNo']; ?>)</a>
            <a class="dropdown-item" href="<?= base_url('customer/logout'); ?>"><?= $this->lang->line('logout'); ?></a>
        <?php } else { ?>
            <a class="dropdown-item" href="<?= base_url('customer/login'); ?>"><?= $this->lang->line('login'); ?></a>
        <?php } ?>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/feedback.svg" width="33" height="20">
            <h6>
                <?= $this->lang->line('aboutus'); ?>
            </h6>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="<?= base_url('customer/terms_conditions'); ?>" target="_blank"><?= $this->lang->line('termsConditions'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/cookie_policy'); ?>" target="_blank"><?= $this->lang->line('cookiePolicy'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/privacy_policy'); ?>" target="_blank"><?= $this->lang->line('privacyPolicy'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/contact'); ?>"><?= $this->lang->line('contact'); ?></a>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/home.svg" width="33" height="20">
            <h6>
                <?= $this->lang->line('outlet'); ?>
            </h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <?php if($this->session->userdata('SchPop') > 0){ ?>
            <a href="#news" class="dropdown-item" data-toggle="modal" data-target="#offers-modal"><?= $this->lang->line('offers'); ?></a>
        <?php } if($this->session->userdata('Ent') > 0){ ?>
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#Ent_modal" ><?= $this->lang->line('entertainment'); ?></a>
            <?php } ?>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/inbox.svg" width="33" height="20">
            <h6>
                <?= $this->lang->line('order'); ?>
            </h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <a class="dropdown-item" href="<?= base_url('customer'); ?>"><?= $this->lang->line('menu'); ?></a>
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
                <a class="dropdown-item" href="<?= base_url('customer/cart'); ?>"><?= $this->lang->line('cart'); ?></a>
            <?php }
                $CustId = $this->session->userdata('CustId');
                $CNo = $this->session->userdata('CNo');
                $val = checkCheckout($CustId, $CNo, 2);
             if(!empty($val)){
             ?>
             <a class="dropdown-item" href="#" onclick="goCheckout()"><?= $this->lang->line('checkout'); ?></a>
            <?php }else{ ?>
            <a class="dropdown-item" href="<?= base_url('customer/checkout'); ?>"><?= $this->lang->line('checkout'); ?></a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- offers modal -->
    <div class="modal" id="offers-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;">
                    <p class="modal-title offers-txt text-white"><?= $this->lang->line('offers'); ?></p>
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
                            $imgsrc = '';
                            if(!empty($key['LngName'])){
                                $name  .=  $key['LngName'];
                            }
                            if(!empty($key['portionName'])){
                                $name  .=  ' ('.$key['portionName'].')';
                            }
                            if(!empty($key['mcName'])){
                                $name  .=  ' - '.$key['mcName'];
                            }
                            if(!empty($key['cuiName'])){
                                $name  .=  ' - '.$key['cuiName'];
                            }
                            if($key['SchImg'] != '-'){
                                $imgsrc = $key['SchImg'];
                            }else{
                                $imgsrc = 'assets/img/special_offers.png';
                            }
                            ?>

                            <div class="ad-listing-list mt-20" style="border:1px solid #ede8e8;">
                                <div class="row p-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                        <img src="<?= base_url($imgsrc); ?>" alt="<?= $name; ?>" style="height: 100px;width: 200px;background-size: cover;">
                                        <h6 class="mt-1"><?= $key['SchNm'].' - '.$key['SchDesc'];?></h6>
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
                <div class="modal-header" style="background: #dbbd89;">
                    <p class="modal-title offers-txt text-white"><?= $this->lang->line('entertainment'); ?></p>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body" style="border: none;padding: 0px;overflow-y: scroll;height: 425px;">
                    <?php 
                    $Ent = allEntertainments();
                    if(!empty($Ent)){?>
                        <?php foreach($Ent as $key){
                            $name = '';
                            $imgsrc = '';
                            if(!empty($key['DayName'])){
                                $name  .=  $key['DayName'];
                            }
                            if(!empty($key['Name'])){
                                $name  .=  ' - '.$key['Name'];
                            }
                            
                            if($key['PerImg'] != '-'){
                                $imgsrc = $key['PerImg'];
                            }else{
                                $imgsrc = 'assets/img/entertainment.jpg';
                            }

                            ?>

                            <div class="ad-listing-list mt-20">
                                <div class="row p-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                        <img src="<?= base_url($imgsrc); ?>" alt="<?= $name; ?>" style="height: 100px;width: 200px;background-size: cover;">
                                        <h6 class="mt-1"><?= $name; ?></h6>
                                        <p class="pr-5"><?php if($key['performBy'] != '-'){
                                                echo $key['performBy'];
                                            } ?></p>
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
            <h5 class="modal-title" id="exampleModalLabel"><?= $this->lang->line('order'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="checkoutForm">
                <div class="form-group">
                    <label for=""><?= $this->lang->line('sendYourPendingOrdersToKitchen'); ?></label>
                    <select name="orderOption" id="orderOption" class="form-control">
                        <option value="yes"><?= $this->lang->line('yes'); ?></option>
                        <option value="no"><?= $this->lang->line('reject'); ?></option>
                        <option value="cancel"><?= $this->lang->line('viewCart'); ?></option>
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