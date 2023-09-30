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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row" id="app1">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-12 row">
                                                <div class="col-6 form-group">
                                                <select class="form-control form-control-sm col-md-4"  id="kitchen-code" v-on:change="getBill();">
                                                    <?php
                                                    if(count($SettingTableViewAccess) == 1){?>
                                                        <option value="<?= $SettingTableViewAccess[0]['CCd']?>"><?= $SettingTableViewAccess[0]['Name']?></option>
                                                    <?php }else{
                                                        ?>
                                                        <option value="0" style='display:none;'>Select Cashier</option>
                                                        <?php foreach($SettingTableViewAccess as $key => $data):?>
                                                        <option value="<?= $data['CCd']?>"><?= $data['Name']?></option>
                                                    <?php endforeach;} ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 text-right form-group">
                                                    <?php if($EType == 5){?>
                                                    <a href="<?php echo base_url('restaurant/sitting_table'); ?>">
                                                        <button class="btn btn-warning btn-rounded btn-sm" >Back</button>
                                                    </a>
                                                <?php } else{?>
                                                    <a href="<?php echo base_url('restaurant/order_dispense'); ?>">
                                                        <button class="btn btn-warning btn-rounded btn-sm" >Back</button>
                                                    </a>
                                                    <?php } ?>

                                                    <button class="btn btn-primary btn-sm btn-rounded" v-on:click="getBill();">Refresh</button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Bill No</th>
                                                                <th>Bill Date</th>
                                                                <?php if($EType == 5){?>
                                                                    <th>Table No</th>
                                                                <?php } else {?>
                                                                    <th>Cell No</th>
                                                                <?php } ?>

                                                                <!-- <th>Item Amt</th> -->
                                                                <th>Bill Amt</th>
                                                                <th>O.Pymt</th>
                                                                <th>M.Pymt</th>
                                                                <th>Mode</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-if="billData.length > 0" v-for="(data, index) in billData">
                                                                <td>
                                                                    <a v-bind:href="'<?php echo base_url('restaurant/'); ?>bill_rcpt?restaurant=1&billId=' + data.BillNo" target="_blank" >
                                                                        {{ data.BillNo }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ data.BillDate }}</td>

                                                                <?php if($EType == 5){?>
                                                                    <td>{{ data.TableNo }}</td>
                                                                <?php } else {?>
                                                                    <td>{{ data.MobileNo }}</td>
                                                                <?php } ?>

                                                                <td>{{ data.TotAmt }}</td>   <!-- for Bill Amt -->

                                                                <td v-if="data.PaymtMode == 'Cash'">0</td>  <!-- for Online Amt for cash payment -->
                                                                <td v-else>{{ data.PaidAmt }}</td>          <!-- Actual Amt paid online -->

                                                                <td v-if="data.PaymtMode == 'Cash'">    <!-- Amt displayed for cash payment -->
                                                                    <input type="number" name="PaidAmt" id="PaidAmt"  v-model="data.BillValue"  value="data.BillValue" style="width: 125px;">
                                                                </td>
                                                                <td v-else>0</td>       <!-- Amt '0' displayed in cash payment for online payment -->
                                                                <!-- <td >
                                                                    <div class="selectPayDis"></div>
                                                                </td> -->
                                                                <td v-if="data.PaymtMode == 'Cash'">    <!-- Options for payment modes if cash payment -->
                                                                    <select v-bind:id="index" v-model="data.PymtType">
                                                                        <option value="0" style="display: none;">Select Payment</option>
                                                                        <option v-for="pymtMode in pymtModes" :value="pymtMode.PMNo">
                                                                            {{ pymtMode.Name }}
                                                                        </option>
                                                                    </select>
                                                                </td >
                                                                <td v-else>Online</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-success" v-on:click="setPaidAmount(index, data.BillId,data.CNo,data.TableNo,data.CustId, data.BillNo, data.TotAmt, data.PaymtMode);" >
                                                                        <i class="fas fa-check-double"></i> 
                                                                    </button>
                                                                    </button>
                                                                    
                                                                    <!-- v-on:click="PrintBill(data.BillId, index,<?= $EID;?>);" Bill Printing -->
                                                                    <a :href="'vtrend:billid='+data.BillId+'&eid=<?= $EID;?>&kotno=0&s=<?= $_SESSION['DynamicDB'];?>'">
                                                                    <button class='btn btn-warning btn-sm'>
                                                                        <i class="fas fa-print"></i> 
                                                                    </button></a>
                                                                    <!-- Bill Cancellation -->
                                                                    <button class="btn btn-danger btn-sm" v-on:click="rejectBill(data.BillId, index, data.CNo, data.TableNo, data.CustId);">
                                                                        <i class="fas fa-window-close"></i> 
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-1"></div>
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

<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

<script>

var vueApp = new Vue({
    el: "#app1",
    data: {
        billData: [],
        pymtModes: []
    },
    methods: {
        getPaymentOpt() {
            formData = new FormData();
            formData.append('selectpaymentopt', 1);
            axios.post("<?php echo base_url('restaurant/cash_bill_ajax'); ?>", formData)
            .then(response => {
                console.log(response.data);
                if (response.data.status == 1) {
                    this.pymtModes = response.data.pymntModes;
                }else {
                    this.pymtModes = [];
                }
            })
            .catch(error => console.log(error));
        },
        getBill() {
            var STVCd = $('#kitchen-code').val();
            formData = new FormData();
            formData.append('getBill', 1);
            formData.append('STVcd', STVCd);
            axios.post("<?php echo base_url('restaurant/cash_bill_ajax'); ?>", formData)
            .then(response => {
                // console.log(response.data);
                if (response.data.status == 1) {
                    this.billData = response.data.billData;
                    // PymtModes = response.data.PymtModes;
                    // console.log(PymtModes);
                }else {
                    this.billData = [];
                    // this.PymtModes = [];
                }
            })
            .catch(error => console.log(error));
        },
        setPaidAmount(index, id , CNo , TableNo , CustId, billNo, billAmt, pymtMode) {
            // console.log(this.billData[index].PaidAmt);
            // if (parseFloat(this.billData[index].TotAmt) > parseFloat(this.billData[index].PaidAmt)) {
                // alert("Recieved Amount is less than Bill Amount");
            // }else{
                if(this.billData[index].PaymtMode == 'Cash' && $('#'+index).val() == 0){
                    alert('Please Select Payment Mode !');
                }else{
                    formData = new FormData();
                    formData.append('setPaidAmount', 1);
                    formData.append('paidAmount', this.billData[index].BillValue);
                    formData.append('mode', this.billData[index].PymtType);
                    formData.append('id', id);
                    formData.append('CNo', CNo);
                    formData.append('TableNo', TableNo);
                    formData.append('CustId', CustId);
                    formData.append('billNo', billNo);
                    formData.append('billAmt', billAmt);
                    formData.append('pymtMode', pymtMode);
                    // console.log($('#selRt').val());
                    axios.post("<?php echo base_url('restaurant/cash_bill_ajax'); ?>", formData)
                    .then(response => {
                        // console.log(response.data);
                        if(response.data.status == 1) {
                            this.getBill();
                        }else {
                            console.log("error in updating billing");
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }

            // }
        },

        PrintBill(id,index,eid){
            console.log("ashu");
            // window.location.href = 'print/print_rest.php?billId='+id;
            window.location.href = "vtrend:billid="+id+"&eid="+eid+"&s=<?= $_SESSION['DynamicDB'];?>";
        },

        rejectBill(id, index, CNo, TableNo, CustId) {
            if (confirm(`Confirm Reject For Bill No: ${this.billData[index].BillNo}`)) {
                // console.log("reject");
                // console.log("bill id "+ id);
                formData = new FormData();
                formData.append('rejectBill', 1);
                formData.append('id', id);
                formData.append('CNo', CNo);
                formData.append('TableNo', TableNo);
                formData.append('CustId', CustId);
                axios.post("<?php echo base_url('restaurant/cash_bill_ajax'); ?>", formData)
                .then(response => {
                    // console.log(response.data);
                    if (response.data.status ==1) {
                        this.getBill();
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }else {
                console.log("not Rejected");
            }
        }

    },
    created: function() {
        this.getPaymentOpt();
        this.getBill();
    }
});
</script>

<script type="text/javascript">
    // $.ajax({
    //  url:"<?php echo base_url('restaurant/cash_bill_ajax'); ?>",
    //  type:"POST",
    //  data:{
    //      selectpaymentopt : 1
    //  },
    //  success:function(response){
    //      console.log(response);
    //      $(".selectPayDis").html(response);
    //  },
    //  error:function(xhr,status,error){
    //      console.log(xhr);
    //      console.log(status);
    //      console.log(error);
    //  }
    // });
</script>