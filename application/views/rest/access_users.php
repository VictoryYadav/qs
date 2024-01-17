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
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                <div class="card">
                                    <div class="card-body" style="padding: 0.25rem;">
                                        <div id="app1">
                                            <div class="row form-group">
                                                <div class="col-md-5 col-12">
                                                    <select class="form-control form-control-sm" name="RUserId" id="RUserId" onchange="getUser();">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($usersRestData as $key) {
                                                         ?>
                                                        <option value="<?php echo $key['RUserId']; ?>"><?php echo $key['MobileNo'].' ('.$key['FName'].' '.$key['LName'].')'; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                
                                                <div class="col-md-5 col-5">
                                                    <div class="card-header">  
                                                       <div class="row">
                                                           <div class="col-md-12">
                                                            <input type="checkbox" name="allSelected" id="selectAll"> &nbsp;<?= $this->lang->line('selectAllAvailableRoles'); ?></div>
                                                       </div>
                                                    </div>
                                                    <div class="card-body" style="padding: 0.25rem;">
                                                        <ul style="height: 375px;overflow: auto;" id="availableRoles">
                                                            <div class="ck-button"  style="margin-left:-40px;">
                                                               <label>
                                                                  &nbsp;&nbsp;<span>No Roles Found!</span>
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
                                                            <input type="checkbox" id="selectAll_A"> &nbsp;<?= $this->lang->line('selectAllAssignedRoles'); ?></div>
                                                       </div>
                                                    </div>
                                                    <div class="card-body" style="padding: 0.25rem;">   
                                                        <ul style="height: 375px;overflow: auto;" id="assignedRoles">
                                                            <div class="ck-button"style="margin-left:-40px;">
                                                               <label>&nbsp;&nbsp;<span>No Roles Found!</span>
                                                               </label>
                                                            </div>
                                                        </ul>
                                                    </div>
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

var RUserId = 0;
getUser = () =>{
    RUserId = $('#RUserId').val();

    getAvailableRoles(RUserId);
    getAssignedRoles(RUserId);
    $('#RUserId').val(RUserId);
}
// console.log(RUserId);
getAvailableRoles = (RUserId) =>{
    $.post('<?= base_url('restaurant/user_access') ?>',{getAvailableRoles:1, userId:RUserId},function(res){
        if(res.status == 'success'){
          var data = res.response;
          var temp = '';
          if(data.length > 0){
            for (var i = 0; i < data.length; i++) {
                temp += `<div class="ck-button"  style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${data[i].RoleId}" class="selectedAvailableRoles">&nbsp;&nbsp;<span>${data[i].Name}</span>
                           </label>
                        </div>`;
            }
          }else{
            temp = 'No Roles Found!';
          }
          $('#availableRoles').html(temp);
        }else{
          alert(res.response);
        }
    });
}

getAssignedRoles = (RUserId) =>{
    $.post('<?= base_url('restaurant/user_access') ?>',{getAssignedRoles:1, userId:RUserId},function(res){
        if(res.status == 'success'){
          var data = res.response;
          var temp = '';
          if(data.length > 0){
            for (var i = 0; i < data.length; i++) {
                temp += `<div class="ck-button"style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${data[i].URNo}" class="selectedAssignedRoles">&nbsp;&nbsp;<span>${data[i].Name}</span>
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

    if(roleIds.length > 0){
        $.post('<?= base_url('restaurant/user_access') ?>',{setRestRoles:1, userId:RUserId, roles:roleIds},function(res){
            if(res.status == 'success'){
                alert(res.response);
              // location.reload();
            }else{
              alert(res.response);
            }
        });
    }else{
        alert('Please select atleast one available roles');
    }
    getUser();
}

removeRoles = () => {
    var ur_no = [];

    $(".selectedAssignedRoles").each(function(index, el) {
        if ($(this).prop('checked')==true){ 
            ur_no.push($(this).val());    
        }    
    });

    if(ur_no.length > 0){
        $.post('<?= base_url('restaurant/user_access') ?>',{removeRestRoles:1, userId:RUserId, URNo:ur_no},function(res){
            if(res.status == 'success'){
                alert(res.response);
              // location.reload();
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