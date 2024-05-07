<?php $this->load->view('layouts/support/head'); ?>
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
        <?php $this->load->view('layouts/support/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/support/sidebar'); ?>
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
                                    <div class="card-body" >
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('name'); ?></label>
                                                    <select class="form-control form-control-sm" name="userId" id="userId" onchange="getUser();">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($users as $key) {
                                                     ?>
                                                    <option value="<?= $key['userId']; ?>"><?= $key['mobileNo'].' - '.$key['fullname']; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('country'); ?></label>
                                                    <select class="form-control form-control-sm" name="countryCd" id="countryCd" onchange="getCity();">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($country as $key) { ?>
                                                    <option value="<?= $key['phone_code']; ?>"><?= $key['country_name']; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('city'); ?></label>
                                                    <select class="form-control form-control-sm" name="city" id="city" onchange="getUser();">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row form-group">
                                            
                                            <div class="col-md-5 col-5">
                                                <div class="card-header">  
                                                   <div class="row">
                                                       <div class="col-md-12">
                                                        <input type="checkbox" name="allSelected" id="selectAll"> &nbsp;Unassigned Restaurant</div>
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
                                                    <p>Total : <span id="availableCount">0</span></p>
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
                                                        <input type="checkbox" id="selectAll_A"> &nbsp;Assigned Restaurant
                                                        <span></span>
                                                        </div>
                                                   </div>
                                                </div>
                                                <div class="card-body" style="padding: 0.25rem;">   
                                                    <ul style="height: 375px;overflow: auto;" id="assignedRoles">
                                                        <div class="ck-button"style="margin-left:-40px;">
                                                           <label>&nbsp;&nbsp;<span>No Roles Found!</span>
                                                           </label>
                                                        </div>
                                                    </ul>
                                                    <p>Total : <span id="assignedCount">0</span></p>
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

                <?php $this->load->view('layouts/support/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/support/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/support/script'); ?>


<script>

$(document).ready(function(){

    $('#userId').select2();
    $('#countryCd').select2();
    $('#city').select2();

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

function getCity(){
    var phone_code = $('#countryCd').val();

    $.post('<?= base_url('support/get_city_by_country') ?>',{phone_code:phone_code},function(res){
        if(res.status == 'success'){
          var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
          res.response.forEach((item,index) => {
            temp += `<option value="${item.city_id}">${item.city_name}</option>`;
          });
          $('#city').html(temp);
        }else{
          alert(res.response);
        }
    });
}

var userId = 0;
var country = 0
var city = 0;
getUser = () =>{
    userId = $('#userId').val();
    country = $('#countryCd').val();
    city = $('#city').val();

    getAvailableRestaurant(userId, country, city);
    getAssignedRestaurant(userId, country, city);

    $('#userId').val(userId);
    $('#countryCd').val(country);
    $('#city').val(city);
}
// console.log(RUserId);
getAvailableRestaurant = (userId, country, city) =>{
    $.post('<?= base_url('support/user_access') ?>',{type:'available', userId:userId, countryCd:country, city:city},function(res){
        if(res.status == 'success'){
          var temp = '';
          if(res.response.length > 0){
            res.response.forEach((item, index) =>{
                temp += `<div class="ck-button"  style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${item.EID}" class="selectedAvailableRoles">&nbsp;&nbsp;<span>${item.Name}</span>
                           </label>
                        </div>`;
            });
          }else{
            temp = 'No Roles Found!';
          }
          $('#availableCount').html(res.response.length);
          $('#availableRoles').html(temp);
        }else{
          alert(res.response);
        }
    });
}

getAssignedRestaurant = (userId, country, city) =>{
    $.post('<?= base_url('support/user_access') ?>',{type:'assigned', userId:userId, countryCd:country, city:city},function(res){
        if(res.status == 'success'){
          
          var temp = '';
          if(res.response.length > 0){
            res.response.forEach((item, index) =>{
                temp += `<div class="ck-button"style="margin-left:-40px;">
                           <label>
                              <input type="checkbox" value="${item.EID}" class="selectedAssignedRoles">&nbsp;&nbsp;<span>${item.Name}</span>
                           </label>
                        </div>`;
            });
          }else{
            temp = 'No Roles Found!';
          }
          $('#assignedCount').html(res.response.length);
          $('#assignedRoles').html(temp);
        }else{
          alert(res.response);
        }
    });
}

setRoles = () => {
    var eids = [];

    $(".selectedAvailableRoles").each(function(index, el) {
        if ($(this).prop('checked')==true){ 
            eids.push($(this).val());    
        }    
    });

    if(eids.length > 0){
        $.post('<?= base_url('support/user_access') ?>',{type:'setRest', userId:userId, EID:eids},function(res){
            if(res.status == 'success'){
                alert(res.response);
                getUser();
              // location.reload();
            }else{
              alert(res.response);
            }
        });
    }else{
        alert('Please select atleast one available restaurant');
    }
    getUser();
}

removeRoles = () => {
    var eids = [];

    $(".selectedAssignedRoles").each(function(index, el) {
        if ($(this).prop('checked')==true){ 
            eids.push($(this).val());    
        }    
    });

    if(eids.length > 0){
        $.post('<?= base_url('support/user_access') ?>',{type:'removeRest', userId:userId, EID:eids},function(res){
            if(res.status == 'success'){
                alert(res.response);
              // location.reload();
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