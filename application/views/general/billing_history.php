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
                                <form method="post" id="formFilter">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Country</label>
                                                <select name="country" id="country" class="form-control" onchange="getCity()">
                                                    <option value="">Select</option>
                                                    <?php 
                                                    foreach ($country as $key) { ?>
                                                        <option value="<?= $key['phone_code']; ?>" <?php if($countryCd == $key['phone_code']){ echo 'selected'; } ?> ><?= $key['country_name']; ?></option>
                                                    <?php } ?> 
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <select name="city" id="city" class="form-control">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label><br>
                                                <input type="submit" class="btn btn-success" value="Search">
                                            </div>
                                        </div>

                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="usersTBL" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?= $this->lang->line('date'); ?></th>
                                            <th><?= $this->lang->line('name'); ?></th>
                                            <th><?= $this->lang->line('amount'); ?></th>
                                            <th><?= $this->lang->line('rating'); ?></th>
                                        </tr>
                                        </thead>
    
                                        <tbody>
                                            <?php
                                                if(!empty($custPymt)){
                                                    $i = 1;
                                                    foreach ($custPymt as $key ) {
                                                    ?>
                                                    <tr onclick="RedirectPage(<?php echo $key['BillId'] ?> , <?php echo $key['EID'] ?>,'<?php echo $key['DBName'] ?>','<?php echo $key['DBPasswd'] ?>')" style="cursor: pointer;">
                                                        <td><?= $i++; ?></td>
                                                        <td><?php echo date('d-M-y',strtotime($key['billdt'])); ?></td>
                                                        <td><?php echo $key['Name'] ?></td>
                                                        <td><?php echo $key['PaidAmt'] ?></td>
                                                        <td><?php 
                                                        echo ($key['avgBillRtng'] > 0)? $key['avgBillRtng']: '-';
                                                         ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                 }else{ ?>
                                                    <tr>
                                                        <td class="text-center text-danger" colspan="5">No Bill Found!!</td>
                                                    </tr>
                                                 <?php } ?>
                                        </tbody>
                                    </table>
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


        <script type="text/javascript">
            $(document).ready(function () {
                $('#usersTBL').DataTable();
            });

            getCity();

            function getCity(){
                var city = "<?= $city; ?>";

                var country = $('#country').val();
                $.post('<?= base_url('general/getCityList') ?>',{phone_code:country},function(res){
                    if(res.status == 'success'){
                      var cities = res.response;
                      $('#city').empty();
                      var temp = `<option value=""><?= $this->lang->line('select');?></option>`;
                      var select = '';
                      cities.forEach((ct) => {
                        if(city == ct.city_id){
                            var select = 'selected';
                        }
                          temp += `<option value="${ct.city_id}" ${select}>${ct.city_name}</option>`;
                      });
                      $('#city').html(temp);     
                    }else{
                      alert(res.response);
                    }
                });
            }

            function RedirectPage(id, eid, dbname, dbpass) {
                window.location.href = "<?= base_url('general/bill/')?>" + id + "?EID=" + eid + "&dbn=" + dbname + "&dbp=" + dbpass+"&ShowRatings=0";
            }
        </script>
       
