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
.form-control{
    height: 40px;
}

    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" enctype="multipart/form-data11">
                                            <div class="text-right p-2">
                                                <button class="btn btn-primary" onclick="return validate()" type = "Submit" name="apply_default" value="1">Set Default</button>
                                            </div>
                                            <div class="text-center"><h3>Item Details - Sec 1</h3></div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label for="schnm">Background</label>
                                                    <input type="color" id="Sec1Background" name="Sec1Background" class="form-control" placeholder="Enter Color" required="" value="<?php if(!empty($data11)){echo $data11['Sec1Background'];}?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Text Color</label>
                                                    <input type="color" id="Sec1TextColor" name="Sec1TextColor" class="form-control" placeholder="Enter Color" required="" value="<?php if(!empty($data11)){echo $data11['Sec1TextColor'];}?>" />
                                                </div>
                                            </div>
                                            <div class="text-center"><h3>Item Details - Sec 2</h3></div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label for="schnm">Background</label>
                                                    <input type="color" id="Sec2Background" name="Sec2Background" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec2Background']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Text Color</label>
                                                    <input type="color" id="Sec2TextColor" name="Sec2TextColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec2TextColor']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button Color</label>
                                                    <input type="color" id="Sec2BtnColor" name="Sec2BtnColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec2BtnColor']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button Text Color</label>
                                                    <input type="color" id="Sec2BtnText" name="Sec2BtnText" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec2BtnText']:'';?>" />
                                                </div>
                                            </div>
                                            <div class="text-center"><h3>Item Details - Sec 3</h3></div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label for="schnm">Background</label>
                                                    <input type="color" id="Sec3Background" name="Sec3Background" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec3Background']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Text Color</label>
                                                    <input type="color" id="Sec3TextColor" name="Sec3TextColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Sec3TextColor']:'';?>" />
                                                </div>
                                            </div>
                                            <div class="text-center"><h3>Other Page</h3></div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label for="schnm">Body Background</label>
                                                    <input type="color" id="BodyBackground" name="BodyBackground" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['BodyBackground']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Text Color</label>
                                                    <input type="color" id="BodyTextColor" name="BodyTextColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['BodyTextColor']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button 1 Background</label>
                                                    <input type="color" id="Button1Color" name="Button1Color" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Button1Color']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button 1 Text</label>
                                                    <input type="color" id="Button1TextColor" name="Button1TextColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Button1TextColor']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button 2 Background</label>
                                                    <input type="color" id="Button2Color" name="Button2Color" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Button2Color']:'';?>" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="schnm">Button 2 Text</label>
                                                    <input type="color" id="Button2TextColor" name="Button2TextColor" class="form-control" placeholder="Enter Color" required="" value="<?php echo !empty($data11)?$data11['Button2TextColor']:'';?>" />
                                                </div>
                                            </div>
                                            
                                            <div class="text-center">Apply Theme <input type="checkbox" name="apply" value="1"> &nbsp;&nbsp;<button type="submit" class="btn btn-primary" >Submit</button></div>
                                        </form>
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
    function validate(){
        if (confirm("Are you sure want to set the default setting?")) {
            return true;
        }else{
            return false;
        }
    }
</script>