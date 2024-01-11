<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-4 col-5">
                                <div class="form-group">
                                    <label for=""><?= $this->lang->line('country');?></label>
                                    <select name="country" id="country" class="form-control" onchange="getCity()">
                                        <option value=""><?= $this->lang->line('select');?></option>
                                        <?php  
                                        foreach ($countryList as $key) {
                                        ?>
                                        <option value="<?= $key['phone_code']; ?>" <?php if($key['phone_code'] == $country) { echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-5">
                                <div class="form-group">
                                    <label for=""><?= $this->lang->line('city');?></label>
                                    <select name="city" id="city" class="form-control">
                                        <option value=""><?= $this->lang->line('select');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                <input type="Submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search');?>">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                      <table class="table table-striped" id="tblData">
                        <thead style="font-size: 12px;">
                            <tr>
                                <th><?= $this->lang->line('date');?></th>
                                <th><?= $this->lang->line('name');?></th>
                                <th><?= $this->lang->line('amount');?></th>
                                <th><?= $this->lang->line('rating');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($custPymt)){
                                foreach ($custPymt as $key ) {
                                ?>
                                <tr onclick="RedirectPage(<?php echo $key['BillId'] ?> , <?php echo $key['EID'] ?>,'<?php echo $key['DBName'] ?>','<?php echo $key['DBPasswd'] ?>')">
                                    <td><?php echo date('d-M-y',strtotime($key['billdt'])); ?></td>
                                    <td><?php echo $key['Name'] ?></td>
                                    <td><?php echo $key['PaidAmt'] ?></td>
                                    <td><?php 
                                    echo ($key['avgBillRtng'] > 0)? $key['avgBillRtng']: '-';
                                     ?></td>
                                </tr>
                            <?php
                            }
                             }else{
                            ?>
                            <tr>
                                <td colspan="3"><?= $this->lang->line('noDataFound');?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>

    $(document).ready(function () {
        $('#tblData').DataTable();
    });
    function RedirectPage(id, eid, dbname, dbpass) {
        window.location.href = "<?= base_url('customer/bill/')?>" + id + "?EID=" + eid + "&dbn=" + dbname + "&dbp=" + dbpass+"&ShowRatings=0";

        // if ShowRatings= 1 then group rating will show in bill page "
    }

    getCity();

    function getCity(){
        var city = "<?= $city; ?>";

        var country = $('#country').val();
        $.post('<?= base_url('customer/getCityList') ?>',{phone_code:country},function(res){
            if(res.status == 'success'){
              var cities = res.response;
              $('#city').empty();
              var temp = `<option value="">City</option>`;
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

</script>

</html>