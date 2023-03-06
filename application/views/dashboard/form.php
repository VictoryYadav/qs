<?php
    $_SESSION["page"] = "user";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Media | Common</title>
    <?php $this->load->view('layouts/admin/head'); ?>
    <link href="<?= base_url()?>assets_admin/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets_admin/css/datepicker.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php $this->load->view('layouts/admin/navbar'); ?>
        <div id="page-wrapper" class="gray-bg">
            <?php $this->load->view('layouts/admin/topbar'); ?>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-2">
                    </div>
                    <div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Add New Media</h5>
                                <a href="<?= base_url()?>admin/home" class="btn btn-primary btn-xs pull-right" style="margin-top: -3px;"><i class="fa fa-list"></i> All Media</a>
                            </div>
                            <div class="ibox-content">
                                <form method="post" class="form-horizontal" action="<?= base_url()?>admin/save-media" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" style="margin-bottom: 7px; text-align: left;">Title</label>
                                        <div class="col-sm-12" style="margin-bottom: 5px;">
                                            <input type="text" class="form-control" id="" name="title" placeholder="Title" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label" style="margin-bottom: 7px; text-align: left;">News Date</label>
                                            <input type="text" class="form-control datepicker" id="" name="news_date" placeholder="DD-MM-YYYY" style="cursor: pointer; background: #fff;" required readonly />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label" style="margin-bottom: 7px; text-align: left;">News Year</label>
                                            <select name="year" class="form-control">
                                                <option value="">Choose Year</option>
                                                <?php 
                                                    $year = date('Y');
                                                 for ($i=2015; $i <= $year; $i++) { ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" style="text-align: left; margin-bottom: 7px;">Upload Image</label>
                                        <div class="col-sm-12" style="margin-bottom:5px;">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i> 
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">Select file</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="images[]" id="file" required accept="image/jpg" >
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <button type="submit" name="" class="btn btn-w-m btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('layouts/admin/footer'); ?>
        </div>
    </div>
    <?php $this->load->view('layouts/admin/scripts'); ?>
    <script src="<?= base_url()?>assets_admin/js/plugins/jasny/jasny-bootstrap.min.js"></script>
    <script src="<?= base_url()?>assets_admin/js/datepicker.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".datepicker").datepicker({
                dateFormat : 'dd-mm-yy',
                changeMonth : true,
                changeYear : true,
                yearRange: '-50y:c+nn'
                // maxDate: '-1d'
            });
        });
    </script>
</body>
</html>