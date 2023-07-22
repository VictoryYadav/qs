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
            <form method="post" id="bookForm">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" name="GuestName" required="" id="name">
                    </div>
                    <div class="form-group col-6">
                        <label for="phone_no">Phone</label>
                        <input class="form-control" type="text" name="MobileNo" required="" id="phone_no" autocomplete="off" minlength="10" maxlength="10" pattern="[6-9]{1}[0-9]{9}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                    </div>
                    <div class="form-group col-6">
                        <label for="date">Date</label>
                        <input class="form-control" type="date" name="RDate" required="" id="date">
                    </div>
                    <div class="form-group col-6">
                        <label for="guest_nos">Total Guests</label>
                        <input class="form-control" type="number" name="GuestNos" required="" id="guest_nos" onkeyup="set_guest()">
                    </div>
                    <div class="form-group col-6">
                        <label for="from_time">From Time</label>
                        <input class="form-control" type="time" name="FrmTime" required="" id="from_time">
                    </div>
                    <div class="form-group col-6">
                        <label for="upto_time">Upto Time</label>
                        <input class="form-control" type="time" name="ToTime" required="" id="upto_time">
                    </div>
                </div>
                <div id="guest_div" style="display: none;">
                    <div class="text-center"><span>Add Guest Details</span></div>
                    <div class="row" id="guest_list">
                    </div>
                </div>
                <div class="" style="margin-bottom: 50px;">
                    <button type="button" style="border-radius: 40px;" class="btn btn-primary btn-sm" id="add_guestt" onclick="add_guest()">Add Guest Details</button>
                    <button type="submit" style="border-radius: 40px;" class="btn btn-outline-success btn-sm" >Submit</button>
                </div>

            </form>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">
    var n =0;
    function add_guest(){
        n++;
        var b = '';
        
        b+='<div class="form-group col-6"><label for="">Name</label><input class="form-control" type="text" name="CustName[]" required=""></div><div class="form-group col-6"><label for="">Phone Number</label><input class="form-control" type="text" name="CustMobileNo[]" required="" autocomplete="off" minlength="10" maxlength="10" pattern="[6-9]{1}[0-9]{9}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"></div>';
        $('#guest_div').show();
        $('#guest_list').append(b);
    }

    $('#bookForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/reserve_table') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              // location.reload();
            }else{
              alert(res.response);
            }
        });

    });
    
</script>

</html>