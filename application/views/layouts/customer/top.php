<?php

$EID = authuser()->EID; 
$folder = 'e'.$EID; 
$logo = $this->session->userdata('Logo');

?>

<style>
    .header-section{
        background: <?php echo $this->session->userdata('headerClr'); ?>;
    }
    .selected{
        background: #b1ebac;
    }
</style>

<section class="header-section">
    <div class="container p-2">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-4">
                <ul class="list-inline product-meta">
                    <li class="list-inline-item">
                        <a href="<?= base_url('customer'); ?>">
                            <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="Eat-Out" style="width: 30px;height: 28px;">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a data-toggle="modal" data-target="#item-list-modal">
                            <img src="<?= base_url() ?>assets/img/search.png" alt="Eat Out" style="width: 30px;height: 28px;" >
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-8 col-8 text-right">
                <ul class="list-inline product-meta">
                    <?php if(authuser()->CatgID == 3){ ?>
                    <li class="list-inline-item">
                        <a href="#" onclick="goOutlet()">
                            <i class="fa fa-home" aria-hidden="true" style="color:blue;font-size: 19px;"></i>
                        </a>
                    </li>
                <?php } ?>
                    <?php if($this->session->userdata('CustAssist') == 1 && $this->session->userdata('CustId') > 0){ ?>
                    <li class="list-inline-item">
                        <span id="yellow_bell" onclick="call_help()" style="cursor: pointer;">
                            <img src="<?= base_url() ?>assets/img/yellow_bell.jpg" style="height: 28px;">
                        </span>
                        <span id="red_bell" style="display: none;">
                            <img src="<?= base_url() ?>assets/img/red_bell1.png" style="height: 30px;">
                        </span>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('MultiLingual') > 1){ ?>
                    <li class="list-inline-item">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!">
                            <img src="<?= base_url() ?>assets/img/language1.png" style="height: 22px;">
                        </a>
                        <!-- Dropdown list -->
                            <ul class="dropdown-menu">
                                <?php 
                                    $langId = $this->session->userdata('site_lang');
                                    $langs = langMenuList();
                                    $name = '';
                                    $active = '';
                                    foreach ($langs as $key) {
                                        $sname = strtolower($key['Name1']);
                                        $name = $key['LngName'];
                                        if($langId == 1){
                                            $name = ucwords($key['LngName']);
                                        }

                                        if($langId == $key['LCd']){
                                            $active = ' selected';
                                        }
                                ?>
                                <li><a class="dropdown-item <?= $active; ?>" href="#" onclick="set_lang(<?= $key['LCd']; ?>,'<?= $sname; ?>')"><?= $name; ?></a></li>
                            <?php $active = ''; } ?>
                            </ul>
                    </li>
                    <?php } ?>
                    <li class="list-inline-item">
                        <img src="<?= base_url('uploads/'.$folder.'/'.$logo) ?>" width="auto" height="28px;" alt="<?= $this->session->userdata('restName'); ?>">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
    
    window.onload = get_call_help;
    function set_lang(langId, langName){
        $.post('<?= base_url('customer/switchLang') ?>',{langId:langId, langName:langName},function(res){
            if(res.status == 'success'){
              // alert(res.response);
            }else{
              alert(res.response);
            }
              location.reload();
        });
    }

    goOutlet = () => {
        console.log('outlets');
        $.post('<?= base_url('customer/gotoOutlet') ?>',function(res){
            if(res.status == 'success'){
                window.location = res.response;
                return false;
            }else{
              alert(res.response);
            }
        });
    }

    function call_help(){
        $.post('<?= base_url('customer/call_assist') ?>',{type:'post'}, function(res){
            if(res.status == 'success'){
                if(res.response > 0){
                    $(`#red_bell`).show();
                    $(`#yellow_bell`).hide();
                }
            }else{
              alert(res.response);
            }
        });
    }

    function get_call_help(){
        $.post('<?= base_url('customer/call_assist') ?>',{type:'get'}, function(res){
            if(res.status == 'success'){
                if(res.response > 0){
                    $(`#red_bell`).show();
                    $(`#yellow_bell`).hide();
                }
            }else{
              alert(res.response);
            }
        });
    }

</script>