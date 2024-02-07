<?php $this->load->view('layouts/customer/head');

  $EID = authuser()->EID; 
  $folder = 'e'.$EID; 
 
 ?>
<style>
.product-item{
  transition: all linear 0.3s;
  box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.2);
 }

 .product-item:hover{
  transform: translateY(-10px);
 }

 .card-img-top{
    width: 200px;
    /*height: 200;*/
   }

 /*mobile screen only*/
@media only screen and (max-width: 480px) {
   .card-img-top{
    width: 200px;
    height: 150px;
   }
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
                          <a href="<?= base_url('customer'); ?>">
                              <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="width: 30px;height: 28px;">
                          </a>
                      </li>
                  </ul>
              </div>
              <div class="col-md-8 col-sm-8 col-8 text-right">
                  <ul class="list-inline product-meta">
                      
                      <?php if($this->session->userdata('MultiLingual') > 0){ ?>
                      <li class="list-inline-item">
                          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!">
                              <img src="<?= base_url() ?>assets/img/language1.png" style="height: 22px;">
                          </a>
                          <!-- Dropdown list -->
                              <ul class="dropdown-menu">
                                  <li><a class="dropdown-item" href="#" onclick="set_lang('english')">English</a></li>
                                  <li><a class="dropdown-item" href="#" onclick="set_lang('hindi')">Hindi</a></li>
                                  <!-- <li><a class="dropdown-item" href="#" onclick="set_lang('china')">Chinese</a></li> -->
                              </ul>
                      </li>
                      <?php } ?>
                      <li class="list-inline-item">
                          <span id="red_bell" style="display: none;">
                              <img src="<?= base_url() ?>assets/img/red_bell1.png" style="height: 30px;">
                          </span>
                          <img src="<?= base_url('uploads/'.$folder.'/'.$EID.'_logo.jpg') ?>" width="auto" height="28px;">
                      </li>
                  </ul>
              </div>
          </div>
      </div>
  </section>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
          <div class="row">
              <div class="product-grid-list">
                  <div class="row mt-30">
                    <?php 
                    if(!empty($outlets)){
                      foreach ($outlets as $out ) {
                        $imgSrc = "uploads/$folder/" . $out['EID'] . "_logo.jpg";
                        if (!file_exists($imgSrc)) {
                          $imgSrc = "uploads/$folder/Eat-Out-Icon.png";
                        }
                     ?>
                    <div class="col-lg-3 col-6">
                        <div class="product-item bg-light">
                          <div class="card">
                            <a href="<?= $out['QRLink'] ?>">
                              <div class="thumb-content" style="text-align: center;padding:5px;height: 185px;">
                                  <img class="card-img-top img-fluid" src="<?= base_url($imgSrc) ?>" alt="<?= $out['Name'] ?>" >
                              </div>
                              <div class="card-body">
                                  <h4 class="card-title text-center"><?= $out['Name'] ?></h4>
                              </div>
                            </a>
                          </div>
                        </div>
                    </div>

                    <?php } } ?>
                  </div>
              </div>
          </div>
        </div>
    </section>

    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script>
    function set_lang(language){
        console.log(language);

        $.post('<?= base_url('customer/switchLang') ?>',{language:language},function(res){
            if(res.status == 'success'){
              // alert(res.response);
            }else{
              alert(res.response);
            }
              location.reload();
        });
    }
</script>

</html>