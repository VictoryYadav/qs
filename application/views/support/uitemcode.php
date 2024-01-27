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
                                        
                                        <div class="row">

                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('restaurant'); ?></label>
                                                    <select name="EID" id="EID" class="form-control form-control-sm select2 custom-select" onchange="getMenuList()">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                    <?php 
                                                    foreach ($restaurant as $rest) {
                                                     ?>
                                                     <option value="<?= $rest['EID']; ?>"><?= $rest['Name']; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tableData" class="table table-bordered">
                                                <thead>
                                                    <tr >
                                                        <th>#</th>
                                                        <th><?= $this->lang->line('name'); ?></th>
                                                        <th>Eat-Out Item Name</th>
                                                        <th></th>
                                                        <th><?= $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
            
                                                <tbody id="tblBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center text-danger">No Data Found!</td>
                                                    </tr>
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
        
        $('.UItmCd').select2();
        $('#EID').select2();
    });

    getMenuList = () =>{
    
        var eid = $('#EID').val();
        // console.log(eid);
        
        if(eid > 0){
            $.post('<?= base_url('support/uitemcd') ?>',{type:'get', EID:eid},function(res){
                if(res.status == 'success'){
                  var data = res.response.list;
                  var uitemList = res.response.UitemList;
                  var opt = ``;
                  for(var i = 0; i < uitemList.length; i++){
                        opt += `<option>${uitemList[i].ItemName}</option>`
                 }
                 opt += ``; 
                  if(data.length > 0){
                    var temp = '';
                    var count = 0;
                    data.forEach((item, index) => {
                        count++;
                        temp += `<tr>
                                    <td>${count}</td>
                                    <td>${item.ItemNm1}</td>
                                    <td>${item.ItemName}</td>
                                    <td>${createOption(uitemList, count, item.UItmCd)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-rounded btn-warning" onclick="updateData(${item.EID}, ${item.ItemId}, ${count})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>`; 
                    });
                    $('#tblBody').html(temp);
                    $('#updateBtn').show();
                    if ( $.fn.dataTable.isDataTable( '#tableData' ) ) {
                        table = $('#tableData').DataTable();
                    }
                    else {
                        table = $('#tableData').DataTable(    {"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                                    } );
                    }
                  }else{
                    $('#updateBtn').hide();
                    $('#tblBody').html('No Data Found!');
                  }
                }else{
                  $('#msgText').html(res.stats);
                }
                
            });    
        }
    }

    createOption = (data, count, UItmCd) =>{
        var temp = `<select class="form-control form-control-sm" id="text_${count}">`;
        data.forEach((item, index) => {
            var select = '';
            if(item.UItmCd == UItmCd){
                select = 'selected';
            }
            temp += `<option value="${item.UItmCd}" ${select}>${item.ItemName}</option>`; 
        });
        temp += `</select>`;
        return temp;
    }

    updateData = (EID, ItemId, count) => {
        var UItmCd = $('#text_'+count).val();
        if(UItmCd > 0){
            $.post('<?= base_url('support/uitemcd') ?>',{type:'update', EID:EID, ItemId:ItemId, UItmCd:UItmCd},function(res){
                if(res.status == 'success'){
                  alert(res.response);

                  getMenuList();
                }else{
                  alert(res.response);
                }
                
                // location.reload();
            });
        }else{
            alert('Please Enter UItem Code');
        }      
    };


    
</script>