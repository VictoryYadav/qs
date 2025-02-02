
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
            <a class="dropdown-item" href="<?= base_url('customer/current_order'); ?>"><?= $this->lang->line('pendingBill'); ?></a>
            <a class="dropdown-item" href="<?= base_url('customer/loyalty'); ?>"><?= $this->lang->line('loyalty'); ?></a>
            <?php 
                $CustId = $this->session->userdata('CustId');
            if($CustId > 0){
                $check = getAccount($CustId);
                if(!empty($check)){ 
                    if($check['custType'] == 1){ ?>
                        <a class="dropdown-item" href="<?= base_url('customer/onaccount'); ?>"><?= $this->lang->line('Onaccount'); ?></a>
                    <?php }else{ ?>
                    <a class="dropdown-item" href="<?= base_url('customer/prepaid'); ?>"><?= $this->lang->line('Prepaid'); ?></a>
            <?php } } } ?>
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
            <a href="#news" class="dropdown-item" onclick="getOffers()"><?= $this->lang->line('offers'); ?></a>
            <?php } if($this->session->userdata('Ent') > 0){ ?>
            <a href="#" class="dropdown-item" onclick="getEntertainment()"><?= $this->lang->line('entertainment'); ?></a>
            <?php } if($this->session->userdata('ratingHistory') > 0){ ?>
            <a href="#" class="dropdown-item" onclick="ratedDish()"><?= $this->lang->line('ratedDishes'); ?></a>
            <!-- <a href="<?= base_url('customer/rating_history'); ?>" class="dropdown-item" ><?= $this->lang->line('ratingHistory'); ?></a> -->
            <?php } if($this->session->userdata('favoriteItems') > 0){ ?>
            <a href="#" class="dropdown-item" onclick="mostOrderDish()"><?= $this->lang->line('mostOrderDishes'); ?></a>
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
            <?php } 
            if($this->session->userdata('reorder') > 0){ ?>
            <a class="dropdown-item" href="<?= base_url('customer/reorder'); ?>"><?= $this->lang->line('reOrder'); ?></a>
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

                <div class="modal-body" style="border: none;padding: 0px;overflow-y: scroll;height: 425px;" id="offersBody">
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

                <div class="modal-body" style="border: none;padding: 0px;overflow-y: scroll;height: 425px;" id="entBody">
                    
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
    
    <div class="modal fade" id="mostOrderDishes_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #dbbd89;">
                <p class="modal-title offers-txt text-white"><?= $this->lang->line('mostOrderDishes'); ?> <span id="siteVisits"></span></p>
         </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                    <tr>
                        <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?> [<small><?= $this->lang->line('top10'); ?></small>]</th>
                        <th><?= $this->lang->line('noofTimesOrder'); ?></th>
                    </tr>
                </thead>
                <tbody id="orderBody">
                    
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ratedDishes_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #dbbd89;">
                <p class="modal-title offers-txt text-white"><?= $this->lang->line('ratedDishes'); ?></p>
         </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                    <tr>
                        <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?> [<small><?= $this->lang->line('top10'); ?></small>]</th>
                        <th><?= $this->lang->line('rating'); ?></th>
                        <th><?= $this->lang->line('avgRating'); ?></th>
                    </tr>
                </thead>
                <tbody id="ratedBody">
                    
                </tbody>
              </table>
            </div>
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

        function mostOrderDish(){
            var visit = "<?= $this->lang->line('visitNo'); ?>";
            $.post('<?= base_url('customer/get_rated_dishes') ?>',{type:'most'}, function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    var temp = ``;
                    if(data.length > 0){
                        data.forEach((item, index) => {
                            temp += `<tr>
                                        <td>${item.ItemName}</td>
                                        <td>${item.Total}</td>
                                    </tr>`;
                        });
                        $(`#siteVisits`).html(` (${data[0].siteVisit} ${visit})`);
                        $(`#orderBody`).html(temp);
                        $(`#mostOrderDishes_modal`).modal('show');
                    }           
                }else{
                    alert(res.response)
                }
            });
            // 
        }

        function ratedDish(){
            $.post('<?= base_url('customer/get_rated_dishes') ?>',{type:'rated'}, function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    var temp = ``;
                    if(data.length > 0){
                        data.forEach((item, index) => {
                            temp += `<tr>
                                        <td>${item.ItemName}</td>
                                        <td>${item.myrating}</td>
                                        <td>${item.avgGRPRtng}</td>
                                    </tr>`;
                        });
                        $(`#ratedBody`).html(temp);
                        $(`#ratedDishes_modal`).modal('show');
                    }           
                }else{
                alert(res.response)    
                }
            });
        }

        function getOffers(){
            $.post('<?= base_url('customer/get_rated_dishes') ?>',{type:'offers'}, function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    var temp = ``;
                    if(data.length > 0){
                        data.forEach((item, index) => {
                            var name = '';
                            var imgsrc = '';
                            if(item.LngName != ''){
                                name  +=  item.LngName;
                            }
                            if(item.portionName != ''){
                                name  +=  ' ('+item.portionName+')';
                            }
                            if(item.mcName != ''){
                                name  +=  ' - '+item.mcName;
                            }
                            if(item.cuiName != ''){
                                name  +=  ' - '+item.cuiName;
                            }
                            if(item.SchImg != '-'){
                                imgsrc = "<?= base_url(); ?>"+item.SchImg;
                            }else{
                                imgsrc = "<?= base_url(); ?>"+'assets/img/special_offers.png';
                            }

                            temp += `<div class="ad-listing-list mt-20" style="border:1px solid #ede8e8;">
                                        <div class="row p-1">
                                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                <img src="${imgsrc}" alt="${name}" style="height: 100px;width: 200px;background-size: cover;">
                                                <h6 class="mt-1">${item.SchNm} - ${item.SchDesc}</h6>
                                                <p class="pr-5">${name}</p>
                                            </div>
                                        </div>
                                    </div>`;
                        });
                        $(`#offersBody`).html(temp);
                        $(`#offers-modal`).modal('show');
                    }           
                }else{
                alert(res.response)    
                }
            });
        }

        function getEntertainment(){
            $.post('<?= base_url('customer/get_rated_dishes') ?>',{type:'ent'}, function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    var temp = ``;
                    if(data.length > 0){
                        data.forEach((item, index) => {
                            var name = '';
                            var imgsrc = '';
                            if(item.DayName != ''){
                                name  +=  item.DayName;
                            }
                            if(item.Name != ''){
                                name  +=  ' - '+item.Name;
                            }
                            
                            if(item.PerImg != '-'){
                                imgsrc = "<?= base_url(); ?>"+item.PerImg;
                            }else{
                                imgsrc = "<?= base_url(); ?>"+'assets/img/entertainment.jpg';
                            }

                            temp += `<div class="ad-listing-list mt-20">
                                        <div class="row p-1">
                                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                <img src="${imgsrc}" alt="${name}" style="height: 100px;width: 200px;background-size: cover;">
                                                <h6 class="mt-1">${name}</h6>
                                                <p class="pr-5">${item.performBy}</p>
                                            </div>
                                        </div>
                                    </div>`;
                        });
                        $(`#entBody`).html(temp);
                        $(`#Ent_modal`).modal('show');
                    }           
                }else{
                alert(res.response)    
                }
            });
        }

    </script>