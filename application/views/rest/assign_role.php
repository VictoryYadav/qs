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
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="app1">

                                            <div class="row">

                                                <div class="col-md-12 text-center">

                                                    <h3 id="show-updated" style="display: none;">Role Updated</h3>

                                                </div>



                                                <div class="col-md-12 items-data">

                                                    <table class="table table-bordered">

                                                        <thead>

                                                            <tr>

                                                                <th>User Name</th>

                                                                <th>Role</th>

                                                                <th>Assigned Role</th>

                                                            </tr>

                                                        </thead>

                                                        <tbody>

                                                            <tr v-if="userData.length > 0" v-for="(user, index) in userData">

                                                                <td>{{ user.FName+" "+user.LName }}</td>

                                                                <td>

                                                                    <select class="form-control" v-model="user.RoleType">

                                                                        <option value="0">Select Role</option>

                                                                        <option value="1">Chef</option>

                                                                        <option value="2">Dispense</option>

                                                                        <option value="3">Cashier</option>

                                                                    </select>

                                                                </td>

                                                                <td>

                                                                    <div class="" v-if="user.RoleType == 1">

                                                                        <label class="font-weight-bold" style="margin-right: 5px;">Kitchen</label>

                                                                        <?php foreach($kitData as $data):?>

                                                                            <label style="margin-left: 10px;">

                                                                                <input type="checkbox" name="kitchen" value="<?= $data['KitCd'];?>" v-model="user.KitCd" v-on:change="setKitchen(index, user.DNo);">

                                                                                <?= $data['KitName']?>

                                                                            </label>

                                                                        <?php endforeach;?>

                                                                    </div>



                                                                    <div class="" v-if="user.RoleType == 2">

                                                                        <label class="font-weight-bold" style="margin-right: 5px;">Dispense</label>

                                                                        <?php foreach($disData as $data):?>

                                                                            <label style="margin-left: 10px;">

                                                                                <input type="checkbox" name="dispency" value="<?= $data['DCd'];?>" v-model="user.DCd" v-on:change="setDispency(index, user.DNo);">

                                                                                <?= $data['Name']?>

                                                                            </label>

                                                                        <?php endforeach;?>

                                                                    </div>



                                                                    <div class="" v-if="user.RoleType == 3">

                                                                        <label class="font-weight-bold" style="margin-right: 5px">Cashier</label>

                                                                        <?php foreach($casherData as $data):?>

                                                                            <label style="margin-left: 10px;">

                                                                                <input type="checkbox" name="casher" value="<?= $data['CCd'];?>" v-model="user.CCd" v-on:change="setCasher(index, user.DNo);">

                                                                                <?= $data['Name']?>

                                                                            </label>

                                                                        <?php endforeach;?>

                                                                    </div>

                                                                </td>

                                                            </tr>

                                                        </tbody>

                                                    </table>

                                                </div>

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

        userId: 0,

        kitchen: [],

        chef: [],

        casher: [],

        dispency: 0,

        role: 0,

        userData: []

    },

    created() {

        console.log("Bismillah");

        this.getUsers();

    },

    methods: {

        getUsers() {

            var formData = new FormData();

            formData.append("getUsers", 1);

            axios.post("<?php echo base_url('restorent/rest_manager'); ?>", formData)

            .then(response => {

                console.log(response.data);

                if(response.data.status == 1) {

                    this.userData = response.data.userData;

                }else {

                    this.userData = [];

                }

            })

            .catch(error => {

                console.log(error);

            });

        },

        setKitchen: function(index, id) {

            console.log("set kitCd "+this.userData[index].KitCd);

            formData = new FormData();

            formData.append('setKitchen', 1);

            formData.append('userId', this.userData[index].RUserId);

            formData.append('role', this.userData[index].RoleType);

            formData.append('kitCd', this.userData[index].KitCd);

            formData.append('id', id);



            axios.post("<?php echo base_url('restorent/rest_manager'); ?>", formData)

            .then(response => {

                console.log(response.data);

                if (response.data.status == 1) {

                    this.getUsers();

                }

            })

            .catch(error => {

                console.log(error);

            });

        },

        setDispency: function(index, id) {

            console.log("set DCd "+this.userData[index].DCd);

            formData = new FormData();

            formData.append('setDispency', 1);

            formData.append('userId', this.userData[index].RUserId);

            formData.append('role', this.userData[index].RoleType);

            formData.append('DCd', this.userData[index].DCd);

            formData.append('id', id);



            axios.post("<?php echo base_url('restorent/rest_manager'); ?>", formData)

            .then(response => {

                console.log(response.data);

                if (response.data.status == 1) {

                    this.getUsers();

                }

            })

            .catch(error => {

                console.log(error);

            });

        },

        setCasher: function(index, id) {

            console.log("set CCd "+this.userData[index].CCd);

            formData = new FormData();

            formData.append('setCasher', 1);

            formData.append('userId', this.userData[index].RUserId);

            formData.append('role', this.userData[index].RoleType);

            formData.append('CCd', this.userData[index].CCd);

            formData.append('id', id);



            axios.post("<?php echo base_url('restorent/rest_manager'); ?>", formData)

            .then(response => {

                console.log(response.data);

                if (response.data.status == 1) {

                    this.getUsers();

                }

            })

            .catch(error => {

                console.log(error);

            });

        }

    },

    watch: {

        

    }

});

</script>



<script>

function showUpdated() {

    $("#show-updated").show();

    setTimeout(function() {

        $("#show-updated").hide();

    }, 2000);

}

</script>