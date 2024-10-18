<?php $this->load->view('layouts/customer/head'); ?>
<style>
/*select2*/
    .select2-container--default .select2-selection--single {
      background-color: #00000000;
      border: 1px solid #ced4da;
      border-radius: 2px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #717070;
        line-height: 28px;
        font-size: 11px;
    }
    .select2-container .select2-selection--single {
      height: 29px;
    }
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="margin-bottom: 40px;">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-4 col-5">
                                <div class="form-group">
                                    <label for=""><?= $this->lang->line('country');?></label>
                                    <select name="country" id="country" class="form-control select2 custom-select" onchange="getCity()">
                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                            <?php 
                                            foreach ($country as $key) { ?>
                                                <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                            <?php } ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-5">
                                <div class="form-group">
                                    <label for=""><?= $this->lang->line('city');?></label>
                                    <select name="city" id="city" class="form-control select2 custom-select">
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
                      <table class="table table-striped table-sm" id="tblData">
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
                             } ?>

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
        $('#country').select2();
    });
    
    function RedirectPage(id, eid, dbname, dbpass) {
        window.location.href = "<?= base_url('customer/bill/')?>" + id + "?EID=" + eid + "&dbn=" + dbname + "&dbp=" + dbpass+"&ShowRatings=0";
    }

    getCity();

    function getCity(){
        var city = "<?= $city; ?>";

        var country = $('#country').val();
        $.post('<?= base_url('customer/getCityList') ?>',{phone_code:country},function(res){
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
              $('#city').select2();       
            }else{
              alert(res.response);
            }
        });
    }

</script>

</html>