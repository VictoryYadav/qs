<?php $this->load->view('layouts/admin/head'); ?>
<style>
    body{
        overflow: hidden;
        font-size: 12px;
    }
    label{
        font-size: 12px !important;
        font-weight: normal !important;
    }
</style>
        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
         
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                <div class="card">
                                    <div class="card-body" style="padding: 0.25rem;">
                                        <div id="app1">
                                            
                                            <div class="row form-group">
                                                
                                                <div class="col-md-5 col-5">
                                                    <div class="card-header">  
                                                       <div class="row">
                                                           <div class="col-md-12">
                                                            <input type="checkbox" name="allSelected" id="selectAll"> &nbsp;Available Languages</div>
                                                       </div>
                                                    </div>
                                                    <div class="card-body" style="padding: 0.25rem;">
                                                        <ul style="height: 375px;overflow: auto;" id="availableRoles">
                                                            <div class="ck-button"  style="margin-left:-40px;">
                                                               <label>
                                                                  &nbsp;&nbsp;<span>No Languages Available!</span>
                                                               </label>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-center col-2" style="padding-top: 180px;">
                                                    <div class="form-group">
                                                        <button class="btn btn-success btn-sm btn-rounded" onclick="setRoles();"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-danger btn-sm btn-rounded" onclick="removeRoles();"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-5">
                                                    <div class="card-header">  
                                                       <div class="row">
                                                           <div class="col-md-12">
                                                            <input type="checkbox" id="selectAll_A"> &nbsp;Assigned Languages</div>
                                                       </div>
                                                    </div>
                                                    <div class="card-body" style="padding: 0.25rem;">   
                                                        <ul style="height: 375px;overflow: auto;" id="assignedRoles">
                                                            <div class="ck-button"style="margin-left:-40px;">
                                                               <label>&nbsp;&nbsp;<span>No Language Assigned!</span>
                                                               </label>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if($counter != 0){ ?>
                                    <div>
                                        <a href="<?= base_url('restaurant/table_list'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;
                                        <a href="<?= base_url('restaurant/data_upload'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                                    </div>
                                <?php } ?>
    
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


<script>

$(document).ready(function(){
    $('#selectAll').on('click',function(){
        if(this.checked){
            $('.selectedAvailableRoles').each(function(){
                this.checked = true;
            });
        }else{
             $('.selectedAvailableRoles').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.selectedAvailableRoles').on('click',function(){
        if($('.selectedAvailableRoles:checked').length == $('.selectedAvailableRoles').length){
            $('#selectAll').prop('checked',true);
        }else{
            $('#selectAll').prop('checked',false);
            
        }
    });

    // select all assigned roles
    
    $('#selectAll_A').on('click',function(){
        if(this.checked){
            $('.selectedAssignedRoles').each(function(){
                this.checked = true;
            });
        }else{
             $('.selectedAssignedRoles').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.selectedAssignedRoles').on('click',function(){
        if($('.selectedAssignedRoles:checked').length == $('.selectedAssignedRoles').length){
            $('#selectAll_A').prop('checked',true);
        }else{
            $('#selectAll_A').prop('checked',false);
            
        }
    });
});

var MultiLingual = "<?= $this->session->userdata('MultiLingual'); ?>";
var availableLang = 0;
var assignedLang = 0;

getUser();

function getUser(){
    getAvailableRoles();
    getAssignedRoles();
}

function getAvailableRoles(){
    $.post('<?= base_url('restaurant/language_access') ?>',{getAvailableRoles:1},function(res){
        if(res.status == 'success'){
          var data = res.response;
          var temp = '';
          if(data.length > 0){
            for (var i = 0; i < data.length; i++) {
                temp += `<div class="ck-button"  style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${data[i].id}" class="selectedAvailableRoles">&nbsp;&nbsp;<span>${data[i].LangName}</span>
                           </label>
                        </div>`;
            }
          }else{
            temp = 'No Cuisine Found!';
          }
          $('#availableRoles').html(temp);
        }else{
          alert(res.response);
        }
    });
}

function getAssignedRoles(){
    $.post('<?= base_url('restaurant/language_access') ?>',{getAssignedRoles:1},function(res){
        if(res.status == 'success'){
          var data = res.response;
          var temp = '';

          assignedLang = data.length;

          if(data.length > 0){
            for (var i = 0; i < data.length; i++) {
                temp += `<div class="ck-button"style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${data[i].LCd}" class="selectedAssignedRoles">&nbsp;&nbsp;<span>${data[i].Name}</span>
                           </label>
                        </div>`;
            }
          }else{
            temp = 'No Roles Found!';
          }

          $('#assignedRoles').html(temp);
        }else{
          alert(res.response);
        }
    });
}

setRoles = () => {
    var roleIds = [];

    $(".selectedAvailableRoles").each(function(index, el) {
        if ($(this).prop('checked')==true){ 
            roleIds.push($(this).val());    
        }    
    });
    // console.log('as = '+assignedLang+', av ' + roleIds.length+' m '+MultiLingual );

    if(MultiLingual > 1){
        var lng = MultiLingual - 1;

        var total = parseInt(assignedLang) + parseInt(roleIds.length);
        if(lng >= total){
            if(roleIds.length > 0){
                $.post('<?= base_url('restaurant/language_access') ?>',{setRestRoles:1, roles:roleIds},function(res){
                    if(res.status == 'success'){
                        alert(res.response);
                        getUser();
                    }else{
                      alert(res.response);
                    }
                });
            }else{
                alert('Please select atleast one available roles');
            }
        }else{
            alert(`You can assign ${lng} language(s)`);
        }
    }

    getUser();
}

removeRoles = () => {
    var lcd = [];

    $(".selectedAssignedRoles").each(function(index, el) {
        if ($(this).prop('checked')==true){ 
            lcd.push($(this).val());    
        }    
    });

    if(lcd.length > 0){
        $.post('<?= base_url('restaurant/language_access') ?>',{removeRestRoles:1, LCd:lcd},function(res){
            if(res.status == 'success'){
                alert(res.response);
              getUser();
            }else{
              alert(res.response);
            }
        });
    }else{
        alert('Please select atleast one assigned roles');
    }

    getUser();
}

</script>