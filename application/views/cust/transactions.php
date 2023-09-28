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
                                    <select name="country" id="country" class="form-control" onchange="getCity()">
                                        <option value="">Country</option>
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
                                    <select name="city" id="city" class="form-control">
                                        <option value="">City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-2">
                                <input type="Submit" class="btn btn-sm btn-success" value="Search">
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead style="font-size: 12px;">
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Amt</th>
                                <th>Rated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($custPymt)){
                            for ($i = 0; $i < count($custPymt); $i++) {
                                ?>
                                <tr onclick="RedirectPage(<?php echo $custPymt[$i]['BillId'] ?> , <?php echo $custPymt[$i]['EID'] ?>,'<?php echo $custPymt[$i]['DBName'] ?>','<?php echo $custPymt[$i]['DBPasswd'] ?>')">
                                    <td><?php echo date('M-y',strtotime($custPymt[$i]['billdt'])); ?></td>
                                    <td><?php echo $custPymt[$i]['Name'] ?></td>
                                    <td><?php echo $custPymt[$i]['PaidAmt'] ?></td>
                                    <td>-</td>
                                </tr>
                            <?php
                            }
                             }else{
                            ?>
                            <tr>
                                <td colspan="3">No Data Found!</td>
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

<script>
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