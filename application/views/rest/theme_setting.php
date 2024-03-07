<?php $this->load->view('layouts/admin/head'); ?>
<style>
#displayItem {
    background: #f8f5f5;
    position: absolute;
    width: 90%;
    z-index: 9;
    box-shadow: 5px 4px 11px #a09999;
    color: black;
    padding: 0.5rem;
    display: none;
}

#displayItem p {
    box-shadow: 0px 1px 0px #d8cfcf;
    padding: 0px;
    cursor: pointer;
}

.inline_label {
    display: inline;
    position: relative;
}

.inline_input {
    display: inline;
    position: relative;
    float: right;
}

.form-group {
    display: flow-root;
}
hr{
    border-top: 2px solid #000;
}
/*.form-control{
    height: 40px;
}*/

.headerSection, .cuisineSection, .filterSection, .categorySection,  .mainSection, .footerSection{
    padding: 5px;
}

    </style>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="themeForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Edit</label>
                                                <select name="ThemeId" id="ThemeId" class="form-control form-control-sm" onchange="getTheme()">
                                                    <option value="0"><?= $this->lang->line('new'); ?></option>
                                                    <?php
                                                    foreach ($themeNames as $key) {
                                                     ?>
                                                    <option value="<?= $key['ThemeId']; ?>" <?php if($key['ThemeId'] == 1){ echo 'selected'; } ?> ><?= $key['themeName']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Theme Name <small class="text-danger" id="editMsg"></small></label>
                                                <input type="text" name="themeName" id="themeName" class="form-control form-control-sm" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Status</label>
                                                <select name="Stat" id="Stat" class="form-control form-control-sm">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="form-group col-3">
                                                    <label for="schnm">Header BGColor</label>
                                                    <input type="color" id="headerClr" name="headerClr" class="form-control" placeholder="Enter Color" required="" value="#fff" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Section BGColor</label>
                                                    <input type="color" id="mainSection" name="mainSection" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Cuisine BGColor</label>
                                                    <input type="color" id="cuisineClr" name="cuisineClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Cuisine Text</label>
                                                    <input type="color" id="cuisineTxtClr" name="cuisineTxtClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Filter BGColor</label>
                                                    <input type="color" id="filterClr" name="filterClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Filter Text </label>
                                                    <input type="color" id="filterTxtClr" name="filterTxtClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Category BGColor</label>
                                                    <input type="color" id="categoryClr" name="categoryClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Category Text</label>
                                                    <input type="color" id="categoryTxtClr" name="categoryTxtClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Footer BGColor</label>
                                                    <input type="color" id="footerClr" name="footerClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Footer Text</label>
                                                    <input type="color" id="footerTxtClr" name="footerTxtClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                                <div class="form-group col-3">
                                                    <label for="schnm">Menu Btn</label>
                                                    <input type="color" id="menuBtn" name="menuBtn" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Menu Btn Text</label>
                                                    <input type="color" id="menuBtnClr" name="menuBtnClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Order Btn</label>
                                                    <input type="color" id="orderBtn" name="orderBtn" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                            
                                                <div class="form-group col-3">
                                                    <label for="schnm">Order Btn Text</label>
                                                    <input type="color" id="orderBtnClr" name="orderBtnClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="schnm">Success Btn</label>
                                                    <input type="color" id="successBtn" name="successBtn" class="form-control" placeholder="Enter Color" required="" />
                                                </div>
                                            
                                                <div class="form-group col-3">
                                                    <label for="schnm">Success Btn Text</label>
                                                    <input type="color" id="successBtnClr" name="successBtnClr" class="form-control" placeholder="Enter Color" required="" />
                                                </div>

                                            </div>
                                            
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-sm btn-rounded" id="btnSubmit"><?= $this->lang->line('add'); ?></button>

                                                <button type="submit" class="btn btn-primary btn-sm btn-rounded" id="btnUpdate"><?= $this->lang->line('update'); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="headerSection">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="Eat-Out" style="width: 30px;height: 28px;">

                                                    <img src="<?= base_url() ?>assets/img/search.png" alt="Eat Out" style="width: 30px;height: 28px;" >
                                                </div>

                                                <div class="col-md-6 text-right">
                                                    <img src="<?= base_url() ?>assets_admin/images/logo-sm.png" alt="Eat Out" style="width: 30px;height: 28px;" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cuisineSection">
                                            <ul class="nav nav-tabs" role="tablist" id="cuisine">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Chinese</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Indian</a>
                                                </li>                                       
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Desserts</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="filterSection">
                                            <ul class="nav nav-pills nav-justified" role="tablist">
                                                <li class="nav-item waves-effect waves-light">
                                                    <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">All</a>
                                                </li>
                                                <li class="nav-item waves-effect waves-light">
                                                    <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">Veg</a>
                                                </li>                                       
                                                <li class="nav-item waves-effect waves-light">
                                                    <a class="nav-link" data-toggle="tab" href="#settings-1" role="tab">Non-Veg</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="categorySection">
                                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="category">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#home1" role="tab">Soups</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#profile1" role="tab">Starters</a>
                                                </li>                                        
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#settings1" role="tab">Main Course</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#settings1" role="tab">Wings</a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="mainSection">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                    <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="height: 150px;">
                                                    <p class="font-size-13 text-muted">Item - 1</p>
                                                    <ul class="list-unstyled text-center text-muted mb-0 mt-2">
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-primary mr-2"></i>3.5</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-success mr-2"></i>125</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-secondary mr-2"></i>680</li>
                                                    </ul>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                    <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="height: 150px;">
                                                    <p class="font-size-13 text-muted">Item - 2</p>
                                                    <ul class="list-unstyled text-center text-muted mb-0 mt-2">
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-primary mr-2"></i>3.5</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-success mr-2"></i>125</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-secondary mr-2"></i>680</li>
                                                    </ul>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                    <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="height: 150px;">
                                                    <p class="font-size-13 text-muted">Item - 3</p>
                                                    <ul class="list-unstyled text-center text-muted mb-0 mt-2">
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-primary mr-2"></i>3.5</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-success mr-2"></i>125</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-secondary mr-2"></i>680</li>
                                                    </ul>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                    <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="height: 150px;">
                                                    <p class="font-size-13 text-muted">Item - 4</p>
                                                    <ul class="list-unstyled text-center text-muted mb-0 mt-2">
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-primary mr-2"></i>3.5</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-success mr-2"></i>125</li>
                                                        <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-secondary mr-2"></i>680</li>
                                                    </ul>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="footerSection">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    Account
                                                </div>
                                                <div class="col-md-3">
                                                    About
                                                </div>
                                                <div class="col-md-3">
                                                    Outlet
                                                </div>
                                                <div class="col-md-3">
                                                    Order
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/admin/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/admin/script'); ?>
        

<script type="text/javascript">
    getTheme();

    function getTheme(){
        var ThemeId = $('#ThemeId').val();
        if(ThemeId > 0){
            $('#btnUpdate').show();
            $('#btnSubmit').hide();
            $('#themeName').attr('readonly', true);
            $('#editMsg').html("Cannot be edited.");
            if(ThemeId == 1 ){
                $('#btnUpdate').hide();
                $('#editMsg').html('');
            }
        }else{
            $('#btnSubmit').show();
            $('#btnUpdate').hide();
            $('#themeName').attr('readonly', false);
            $('#editMsg').html('');
        }

        $.post('<?= base_url('restaurant/get_theme_data') ?>',{ThemeId: ThemeId},function(res){
            if(res.status == 'success'){
                var data = res.response;
                    
                    $('#themeName').val(data.themeName);
                    $('#Stat').val(data.Stat);
                    $('#headerClr').val(data.headerClr);
                    $('#footerClr').val(data.footerClr);
                    $('#footerTxtClr').val(data.footerTxtClr);
                    $('#cuisineClr').val(data.cuisineClr);
                    $('#cuisineTxtClr').val(data.cuisineTxtClr);
                    $('#filterClr').val(data.filterClr);
                    $('#filterTxtClr').val(data.filterTxtClr);
                    $('#categoryClr').val(data.categoryClr);
                    $('#categoryTxtClr').val(data.categoryTxtClr);
                    $('#mainSection').val(data.mainSection);
                    $('#menuBtn').val(data.menuBtn);
                    $('#successBtn').val(data.successBtn);
                    $('#orderBtn').val(data.orderBtn);
                    $('#menuBtnClr').val(data.menuBtnClr);
                    $('#successBtnClr').val(data.successBtnClr);
                    $('#orderBtnClr').val(data.orderBtnClr);

                    // preview
                    $(".headerSection").css('background-color', data.headerClr);
                    $(".mainSection").css('background-color', data.mainSection);

                    $(".cuisineSection").css('background-color', data.cuisineClr);
                    $("#cuisine>li>a").css('color', data.cuisineTxtClr);

                    $(".filterSection").css('background-color', data.filterClr);
                    $(".nav-pills .nav-link").css('color', data.filterTxtClr);

                    $(".categorySection").css('background-color', data.categoryClr);
                    $("#category>li>a").css('color', data.categoryTxtClr);

                    $(".footerSection").css('background-color', data.footerClr);
                    $(".footerSection").css('color', data.footerTxtClr);
                
            }else{
              alert(res.response);
                $('#ThemeId').val(0);
                $('#themeName').val('');
                $('#Stat').val(1);
                $('#headerClr').val('#fff');
                $('#footerClr').val('#dee2e6');
                $('#footerTxtClr').val('#000');
                $('#cuisineClr').val('#d2d1d1');
                $('#cuisineTxtClr').val('#000');
                $('#filterClr').val('#f5f5f5');
                $('#filterTxtClr').val('#000');
                $('#categoryClr').val('#ceffc9');
                $('#categoryTxtClr').val('#000');
                $('#mainSection').val('#fff');
                $('#menuBtn').val('#efc492');
                $('#successBtn').val('#2bbb1b');
                $('#orderBtn').val('#f76004');
                $('#menuBtnClr').val('#000');
                $('#successBtnClr').val('#000');
                $('#orderBtnClr').val('#000');
            }
        });
    }

    $('#themeForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/set_theme') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });
    });
</script>