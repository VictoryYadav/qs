<?php $this->load->view('layouts/gen/head'); ?>
<style>
    .fixedHeight{
        max-height: 555px;
        overflow-x: hidden;
    }
</style>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">           

            <div class="container mt-4">

                <div class="row">
                
                    <div class="col-xl-12">
                        <div class="card fixedHeight">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Restaurant List</label>
                                            <select name="EID" id="EID" class="form-control select2 custom-select" onchange="getDetails()">
                                                <option value="">Select</option>
                                                <?php foreach ($rests as $key) { ?>
                                                    <option value="<?= $key['EID']; ?>"><?= $key['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="dtBody">

                                </div>
                            </div>
                        </div>
                    </div>                                                              
                </div><!--end row-->
                
            </div> <!-- container-fluid -->

        </div>
        <?php $this->load->view('layouts/gen/footer'); ?>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php $this->load->view('layouts/gen/scripts'); ?>

        <script>

            $(document).ready(function () {
                $('#EID').select2();
            });

            function getDetails(){
                var EID = $(`#EID`).val();
                if(EID > 0){
                    $.post('<?= base_url('general/get_rest_details') ?>',{EID:EID},function(res){
                        if(res.status == 'success'){
                            var data = res.response;
                            var temp = `<div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-3">${data.Name}</h5>
                                                    <p><b>Person Name</b> : ${data.ContactPerson}</p>
                                                    <p><b>Time</b> : ${data.StTime} - ${data.EndTime}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-3">Contact</h5>   
                                                    <ul class="list-unstyled mb-0">
                                                        <li class=""><i class="mdi mdi-phone mr-2 text-success font-size-18"></i> <b> phone </b> : ${data.CellNo}</li>
                                                        <li class="mt-2"><i class="mdi mdi-email-outline text-success font-size-18 mt-2 mr-2"></i> <b> Email </b> : ${data.Email}</li>
                                                        <li class="mt-2"><i class="mdi mdi-map-marker text-success font-size-18 mt-2 mr-2"></i> <b>Location</b> : ${data.city_name}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <h5 class="card-title">Address</h5>
                                                    </div>
                                                    <address class="ng-scope mb-0">
                                                        <strong>${data.Name}</strong><br>
                                                        <i>${data.HOAddress}, ${data.Area}<br>
                                                        ${data.Suburb}, ${data.city_name} ${data.PIN} (${data.country_name})<br></i>
                                                    </address>                                            
                                                </div>
                                            </div>
                                        </div>`;
                            $(`#dtBody`).html(temp);
                        }else{
                          alert(res.message);
                        }
                    });
                }
            }

        </script>
       
