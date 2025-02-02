<?php $this->load->view('layouts/customer/head'); ?>
<style type="text/css">

    .order-details-page {
        padding-bottom: 70px;
        height: -webkit-fill-available;
    }

    .payment-btns {
        padding-left: 10px;
        padding-right: 10px;
    }

    .order-list {
        padding-left: 15px;
        padding-right: 15px;
        overflow: scroll;
        height: 350px;
    }

    .order-list::-webkit-scrollbar {
        width: 5px;
    }

    .order-list::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }
    .order-list::-webkit-scrollbar-thumb {
        background-color: white;
    }

    .order-list tr {
        font-size: 12px;
    }

    .order-list td {
        font-size: 12px;
        border-bottom: 1px solid;
        padding: 10px 0;
    }

    .order-list th {
        font-size: 14px;
        font-weight: 500;
    }

</style>
<style media="screen">

    .TextRating {

        margin-right: 5px;

        font-size: 12px;

        margin-top: -5px;

        position: absolute;

        border-radius: 50%;

        width: 20px;

        height: 20px;

        background: white;

        color: black;

        font-weight: 900;

        right: 0px;

        margin-top: 0px;

        text-align: center;

    }
    .icona {

        width: 60px;

        color: black;

        font-size: 13px;

        text-align: center;

        text-decoration: none;

        font-family: serif;

        margin: 5px;

    }

    .paybtn 
    {
        width: 50%;
        background: <?php echo $this->session->userdata('successBtn'); ?>;
        color: <?php echo $this->session->userdata('successBtnClr'); ?>;
        height: 30px;
        margin-left: 0px !important;
        border-radius: 0 1.5rem 1.5rem 0;
    }

    .paybtn:hover
    {
        background: #03bb2c;
        color: #fff;
        margin-left: 0px !important;
        border-radius: 0 1.5rem 1.5rem 0;
    }

    .backbtn 
    {
        width: 50%;
        margin-right: 0px !important;
        border-radius: 1.5rem 0 0 1.5rem;
        background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
        color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
        height: 30px;
    }

    .backbtn:hover
    {
        background-color: #9d9696;
        color:#fff;   
    }

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            
                <div class="order-list" id="OrderList">
                    <table class="fixed_headers" style="width:100%;">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('restaurant');?></th>
                                <th><?= $this->lang->line('earned');?> <?= $this->lang->line('points');?></th>
                                <th><?= $this->lang->line('used');?> <?= $this->lang->line('points');?></th>
                                <th><?= $this->lang->line('available');?> <?= $this->lang->line('points');?></th>
                            </tr>
                        </thead>
                        <tbody id="loyaltyBody">
                            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>


    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
   
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">

    getLoyaltyPoints();
    function getLoyaltyPoints(){
        $.post('<?= base_url('customer/loyalty') ?>',function(res){
            if(res.status == 'success'){
              var temp = ``;
              res.response.forEach((item, index) =>{
                var restName =  item.Name;
                if(item.LNo == 1){
                    restName =  'Eat-Out';
                }
                temp += `<tr>
                            <td>${restName}</td>
                            <td>${item.EarnedPoints}</td>
                            <td>${item.UsedPoints}</td>
                            <td>${item.EarnedPoints - item.UsedPoints}</td>
                        </tr>`;
              });
              $(`#loyaltyBody`).html(temp);
            }else{
              alert(res.response);
            }
        });
    }

</script>

</html>