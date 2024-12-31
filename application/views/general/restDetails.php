<?php $this->load->view('layouts/gen/head'); ?>
<style>
    .fixedHeight{
        max-height: 480px;
        overflow-x: hidden;
    }
</style>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">           

            <?php $this->load->view('layouts/gen/top'); ?>
            <div class="container" style="margin-top: 80px !important;">

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
                            var data    = res.response.details;
                            var configs = res.response.configs;
                            var list = ``;

                            if(configs.EDT > 0){
                                list += `<li class="list-inline-item"><span>EDT</span></li>`;
                            }
                            if(configs.JoinTable > 0){
                                list += `<li class="list-inline-item"><span>Join Table</span></li>`;
                            }
                            if(configs.SchPop > 0){
                                list += `<li class="list-inline-item"><span>Scheme Pop Up</span></li>`;
                            }
                            if(configs.TableReservation > 0){
                                list += `<li class="list-inline-item"><span>Reserve Table</span></li>`;
                            }
                            if(configs.tableSharing > 0){
                                list += `<li class="list-inline-item"><span>Table Share</span></li>`;
                            }
                            if(configs.CustLoyalty > 0){
                                list += `<li class="list-inline-item"><span>Customer Loyalty</span></li>`;
                            }
                            if(configs.CustAssist > 0){
                                list += `<li class="list-inline-item"><span>Customer Assist</span></li>`;
                            }
                            if(configs.Charity > 0){
                                list += `<li class="list-inline-item"><span>Charity</span></li>`;
                            }
                            if(configs.NV > 0){
                                list += `<li class="list-inline-item"><span>NV</span></li>`;
                            }
                            if(configs.Ent > 0){
                                list += `<li class="list-inline-item"><span>Entertainment</span></li>`;
                            }
                            if(configs.recommend > 0){
                                list += `<li class="list-inline-item"><span>Recommendation</span></li>`;
                            }
                            if(configs.addItemLock > 0){
                                list += `<li class="list-inline-item"><span>Add Item Lock</span></li>`;
                            }
                            if(configs.custItems > 0){
                                list += `<li class="list-inline-item"><span>Custom Item</span></li>`;
                            }
                            if(configs.reorder > 0){
                                list += `<li class="list-inline-item"><span>Reorder</span></li>`;
                            }
                            if(configs.Rating > 0){
                                list += `<li class="list-inline-item"><span>Rating</span></li>`;
                            }
                            if(configs.ServChrg > 0){
                                list += `<li class="list-inline-item"><span>Service Charge</span></li>`;
                            }
                            if(configs.DelCharge > 0){
                                list += `<li class="list-inline-item"><span>Delivery Charge</span></li>`;
                            }

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
                                        </div>

                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="mt-0">Features</h5>
                                                    <ul class="list-unstyled list-inline language-skill mb-0">
                                                        ${list}
                                                    </ul>                                         
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
       
