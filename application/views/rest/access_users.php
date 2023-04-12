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
                            <div class="col-md-10 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="app1">
                                            <div class="row form-group">
                                                <div class="col-md-5 col-6">
                                                    <input type="number" class="form-control" placeholder="Enter User Mobile No" v-model="mobileNumber" v-on:focusout="getUser();">
                                                </div>

                                                <div class="col-md-2  d-none d-sm-block"></div>

                                                <div class="col-md-5 col-6">
                                                    <input type="text" class="form-control" readonly="" v-model="userName">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                
                                                <div class="col-md-5 roles-div items-data col-5" style="border: 1px solid #dddbdb;padding-top: 3px;">
                                                    <h5 class="card-title text-center">Available Roles</h5><hr>
                                                    <ul v-if="availableRoles.length > 0">
                                                        <div class="ck-button" v-for="role in availableRoles" style="margin-left:-40px;">
                                                           <label>
                                                              <input type="checkbox" v-bind:value="role.RoleId" v-model="selectedAvailableRoles">&nbsp;&nbsp;<span>{{ role.Name }}</span>
                                                           </label>
                                                        </div>
                                                    </ul>
                                                </div>
                                                <div class="col-md-2 text-center col-2" style="padding-top: 180px;">
                                                    <div class="form-group">
                                                        <button class="btn btn-success btn-sm btn-rounded" v-on:click="setRoles();"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-danger btn-sm btn-rounded" v-on:click="removeRoles();"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 roles-div items-data col-5" style="border: 1px solid #dddbdb;padding-top: 3px;">
                                                    <h5 class="card-title text-center">Assigned Roles</h5>
                                                    <hr>
                                                    <ul v-if="assignRoles.length > 0">
                                                        <div class="ck-button" v-for="role in assignRoles"style="margin-left:-40px;">
                                                           <label>
                                                              <input type="checkbox" v-bind:value="role.URNo" v-model="selectedAssignedRoles">&nbsp;&nbsp;<span>{{ role.Name }}</span>
                                                           </label>
                                                        </div>
                                                    </ul>
                                                </div>
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
            userName: "",
            userId: "",
            assignRoles: [],
            availableRoles: [],
            mobileNumber: "",
            selectedAvailableRoles: [],
            selectedAssignedRoles: []
        },
        methods: {
            getUser() {
                // console.log(this.mobileNumber.length);
                if (this.mobileNumber.length == 10) {
                    formData = new FormData();
                    formData.append('mobileNumber', this.mobileNumber);
                    formData.append('getUser', 1);
                    axios.post("<?php echo base_url('restaurant/user_access'); ?>", formData)
                    .then(response => {
                        // console.log(response.data);
                        if (response.data.status == 1) {
                            this.userName = response.data.userName;
                            this.userId = response.data.userId;
                            this.getAvailableRoles();
                            this.getAssignedRoles();
                        }else {
                            this.assignRoles = [];
                            this.availableRoles = [];
                            this.userName = "";
                            this.userId = "";
                            this.selectedAvailableRoles = [];
                            this.selectedAssignedRoles = [];
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }else{
                    this.assignRoles = [];
                    this.availableRoles = [];
                    this.userName = "";
                    this.userId = "";
                    this.selectedAvailableRoles = [];
                    this.selectedAssignedRoles = [];
                }
            },
            getAvailableRoles() {
                console.log("getAvailableRoles");
                this.selectedAvailableRoles = [];
                formData = new FormData();
                formData.append('getAvailableRoles', 1);
                formData.append('userId', this.userId);
                axios.post("<?php echo base_url('restaurant/user_access'); ?>", formData)
                .then(response => {
                    console.log(response.data);
                    if (response.data.status == 1) {
                        this.availableRoles = response.data.availableRoles;
                    }
                }).
                catch(error => {
                    console.log(error);
                });
            },
            setRoles() {
                if (this.selectedAvailableRoles.length > 0) {
                    // console.log(this.selectedAvailableRoles);
                    formData = new FormData();
                    formData.append('setRoles', 1);
                    formData.append('roles', this.selectedAvailableRoles);
                    formData.append('userId', this.userId);
                    axios.post("<?php echo base_url('restaurant/user_access'); ?>", formData)
                    .then(response => {
                        console.log(response.data);
                        if (response.data.status == 1) {
                            this.getAvailableRoles();
                            this.getAssignedRoles();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
                }else{
                    alert("Please Select Roles Before Assign");
                }
            },
            getAssignedRoles() {
                console.log("getAssignedRoles");
                this.selectedAssignedRoles = [];
                formData = new FormData();
                formData.append('getAssignedRoles', 1);
                formData.append('userId', this.userId);
                axios.post("<?php echo base_url('restaurant/user_access'); ?>", formData)
                .then(response => {
                    console.log(response.data);
                    if (response.data.status == 1) {
                        this.assignRoles = response.data.getAssignedRoles;
                    }
                })
                .catch(error => {
                    console.log(error);
                })
            },
            removeRoles() {
                if (this.selectedAssignedRoles.length > 0) {
                    // console.log(this.selectedAvailableRoles);
                    formData = new FormData();
                    formData.append('removeRoles', 1);
                    formData.append('roles', this.selectedAssignedRoles);
                    formData.append('userId', this.userId);
                    axios.post("<?php echo base_url('restaurant/user_access'); ?>", formData)
                    .then(response => {
                        console.log(response.data);
                        if (response.data.status == 1) {
                            this.getAvailableRoles();
                            this.getAssignedRoles();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
                }else{
                    alert("Please Select Roles Before Remove");
                }
            }
        }
    })
</script>