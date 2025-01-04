<?php $this->load->view('layouts/admin/head'); ?>
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

            <!-- =======================================EntId======================= -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="entForm">
                                            <input type="hidden" id="EntId" name="EntId" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('name'); ?></label>
                                                        <select name="EntId" id="EntId" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            if(!empty($lists)){
                                                                foreach ($lists as $key) { ?>
                                                                    <option value="<?= $key['EntId']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('day'); ?></label>
                                                        <select name="Dayno" id="Dayno" class="form-control form-control-sm" required="" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($weekDay as $key) { ?>
                                                            <option value="<?= $key['DayNo']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label>Performer</label>
                                                        <input type="text" name="performBy" id="performBy" class="form-control form-control-sm" required="" placeholder="<?= $this->lang->line('name'); ?>" />
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('image'); ?></label>
                                                        <input type="file" name="item_file" id="PerImg" class="form-control form-control-sm" required="" />
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="text" name="fromDt" id="fromDt" class="form-control form-control-sm" required="" value="<?= date('d-M-Y'); ?>" />
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" name="toDt" id="toDt" class="form-control form-control-sm" required="" value="<?= date('d-M-Y'); ?>" />
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-3">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label>
                                                        <br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="text-success" id="msgText"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="TableData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('day'); ?></th>
                                                    <th><?= $this->lang->line('date'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    
                                                    if(!empty($entertainments)){
                                                        $i = 1;
                                                        foreach ($entertainments as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';
                                                     ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td><?= $row['weekDay']; ?></td>
                                                        <td><?= date('d-M-Y', strtotime($row['fromDt'])).' to '.date('d-M-Y', strtotime($row['toDt'])) ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php  }  } ?>
                                                </tbody>
                                            </table>
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

    $(document).ready(function () {
        $('#TableData').DataTable();

        $("#fromDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });
        $("#toDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });
        
    });

    $('#entForm').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(document.getElementById("entForm"));
        
        $.ajax({
               url : '<?= base_url('restaurant/eat_ent') ?>',
               type : 'POST',
               data : formData,
               processData: false,  
               contentType: false,  
               success : function(data) {
                   alert(data.response);
                   // location.reload();
               }
        });

    });

    function editData(EntId,name, stat){
        
        $('#EntId').val(EntId);
        $('#name').val(name);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>