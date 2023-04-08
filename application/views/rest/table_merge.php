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
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#join" role="tab" aria-selected="true">Join</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#unjoin" role="tab" aria-selected="false">Unjoin</a>
                                            </li>
                                        </ul>
        
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane p-3 active" id="join" role="tabpanel">
                                                <form method="post">
                                                    <div class="merge-table-main text-center">
                                                        <div class="row text-center" style="margin-left: 0px;margin-right: 0px;height: 100vh;">


                                                            <div class="col-md-10 text-center" style="padding-top: 20px;padding-left: 30px;padding-right: 45px;">

                                                                

                                                            <div class="row row-margin text-center" id="unmerge-table">

                                                                

                                                                <div class="col-md-12 merge-table-data">

                                                                    <div id="unmerge_tables" class="row" style="padding: 15px;">
                                                                        
                                                                    </div>

                                                                    <div class="text-center">

                                                                        <button id="merge-table" type="button" class="btn btn-primary" style="box-shadow: inset 0 0 0 2000px rgb(31 35 60 / 80%);">Join Tables</button>

                                                                    </div>

                                                                </div>

                                                                <div class="col-md-2"></div>

                                                            </div>

                                                            <div class="col-md-12 text-center" id="no-tables" style="display: none;">

                                                                <h1 style="margin-top: 30px;">No Tables are Free</h1>

                                                            </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane p-3" id="unjoin" role="tabpanel">
                                                <form method="post">
                                                    <div class="merge-table-main text-center">
                                                        <div class="row text-center" style="margin-left: 0px;margin-right: 0px;height: 100vh;">

                                                            <div class="col-md-10 text-center" style="padding-top: 20px;padding-left: 30px;padding-right: 45px;">
                                                                <select class="form-control" id="merged_tables" onchange="get_each_table()">
                                                                    <option value="">Select Tables</option>
                                                                </select>
                                                                

                                                            <div class="row row-margin text-center" id="mergeed-table">

                                                                

                                                                <div class="col-md-4 merge-table-data">

                                                                    <table class="table text-center">

                                                                        <thead>

                                                                            <tr>

                                                                                <!-- <th>Free Tables</th> -->

                                                                                <!-- <th>Capacity</th> -->

                                                                                <th>Action</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody id="merged-table-body"></tbody>

                                                                    </table>

                                                                    <div class="text-center">
                                                                        <input type="hidden" id="selected_merge_no">
                                                                        <button type="button" id="unmerge-table-btn" class="btn btn-primary" style="box-shadow: inset 0 0 0 2000px rgb(31 35 60 / 80%);">Unjoin Tables</button>

                                                                    </div>

                                                                </div>

                                                                <div class="col-md-2"></div>

                                                            </div>

                                                            <div class="col-md-12 text-center" id="no-tables" style="display: none;">

                                                                <h1 style="margin-top: 30px;">No Tables are Free</h1>

                                                            </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                </form>
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


<script type="text/javascript">

function getUnmergeTables(){

    $.ajax({

        url: "<?php echo base_url('restorent/merge_table'); ?>",

        type: 'POST',

        data:{

            getUnmergeTables: 1

        },

        dataType: 'json',

        success: function(response) {

            console.log(response);

            if (response.status) {

                $("#unmerge-table").show();



                availableTables = ``;

                response.tables.forEach(function(table) {
                    var b = `<div class="col-md-4">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`"><label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label>`;
                    availableTables += `<div class="col-md-4">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`"><label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label></div>`;

                });



                $("#unmerge_tables").html(availableTables);

            }else {

                $("#merge-table-body").html('');

                $("#unmerge-table").hide();

                $("#no-tables").show();

            }

        },

        error: function(xhr, status, error) {

            console.log(xhr);

            console.log(status);

            console.log(error);

        }

    });

}
function getMmergedTables(){

    $.ajax({

        url: "<?php echo base_url('restorent/merge_table'); ?>",

        type: 'POST',

        data:{

            getMergedTables: 1

        },

        dataType: 'json',

        success: function(response) {

            console.log(response);

            if (response.status) {

                $("#merged-table").show();



                availableTables = ``;
                opt = '';

                response.tables.forEach(function(table) {

                    availableTables += `

                        <tr>

                            <td>

                                <input type="checkbox" class="form-check-input" id="`+table.MergeNo+`">

                                <label class="form-check-label" for="`+table.MergeNo+`">TableNo `+table.MergeNo+`</label>

                            </td>

                        </tr>

                    `;
                    opt+='<option value="'+table.MergeNo+'">'+table.MergeNo+'</option>';

                });


                $('#merged_tables').append(opt);

            }else {

                $("#merged-table-body").html('');

                $("#merged-table").hide();

                $("#no-tables").show();

            }

        },

        error: function(xhr, status, error) {

            console.log(xhr);

            console.log(status);

            console.log(error);

        }

    });

}



$(document).ready(function() {

    getUnmergeTables();
    getMmergedTables();



    $("#merge-table").click(function(event) {

        var selectedTables = [];



        $(".form-check-input").each(function(index, el) {

            if($(this).is(':checked')) {

                selectedTables.push($(this).attr('id'));

            }

        });



        if (selectedTables.length > 1) {

            $.ajax({

                url: "<?php echo base_url('restorent/merge_table'); ?>",

                type: "post",

                data: {

                    mergeTables: 1,

                    selectedTables: JSON.stringify(selectedTables)

                },

                dataType: 'json',

                success: function(response) {

                    console.log(response);

                    if (response.status == 1) {

                        getUnmergeTables();

                    }else if (response.status == 0) {

                        alert(response.msg);

                    }else {

                        console.log(response.msg);

                    }

                },

                error: function(xhr, status, error) {

                    console.log(xhr);

                    console.log(status);

                    console.log(error);

                }

            });

        }else {


        }

    });
    

});
$("#unmerge-table-btn").click(function(event) {

        var selectedTables = [];
        var deselectedTables = [];
// alert("sss");


        $(".form-check-inputt").each(function(index, el) {

            if($(this).is(':checked')) {

                selectedTables.push($(this).attr('id'));

            }else{
                deselectedTables.push($(this).attr('id'));
            }

        });

        // alert(selectedTables);
        var check = true;
        if(selectedTables.length < 2){
            if(confirm("All the tables will get unmerged. Are you sure want to continue?")){
                check = true;
            }else{
                check = false;
            }
        }
        // alert(check);
        if (deselectedTables.length > 0 && check) {

            $.ajax({

                url: "<?php echo base_url('restorent/merge_table'); ?>",

                type: "post",

                data: {

                    unmergeTables: 1,

                    selectedTables: JSON.stringify(selectedTables),
                    deselectedTables: JSON.stringify(deselectedTables),
                    MergeNo: $('#selected_merge_no').val()

                },

                dataType: 'json',

                success: function(response) {

                    // console.log(response);

                    // if (response.status == 1) {

                    //  getUnmergeTables();

                    // }else if (response.status == 0) {

                    //  alert(response.msg);

                    // }else {

                    //  console.log(response.msg);

                    // }
                    get_each_table();
                },

                error: function(xhr, status, error) {

                    console.log(xhr);

                    console.log(status);

                    console.log(error);

                }

            });

        }else {

            // alert("You can select Min 2 and Max 4 Tables");

        }

    });
function get_each_table(){
    var v = $('#merged_tables').val();
    $('#selected_merge_no').val(v);
    if(v != ''){
        $.ajax({

            url: "<?php echo base_url('restorent/merge_table'); ?>",

            type: 'POST',

            data:{

                getEachTable: 1,
                MergeNo: v

            },

            dataType: 'json',

            success: function(response) {

                console.log(response);

                if (response.status) {

                    $("#merged-table").show();



                    availableTables = ``;

                    response.tables.forEach(function(table) {

                        availableTables += `

                            <tr>

                                <td>

                                    <input type="checkbox" class="form-check-inputt" id="`+table.TableNo+`" merge_no="`+v+`" value"`+table.TableNo+`" checked onchange="">

                                    <label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label>

                                </td>

                            </tr>

                        `;

                    });



                    $("#merged-table-body").html(availableTables);

                }else {

                    $("#merged-table-body").html('');

                    $("#merged-table").hide();

                    $("#no-tables").show();

                }

            },

            error: function(xhr, status, error) {

                console.log(xhr);

                console.log(status);

                console.log(error);

            }

        });
    }else{
        $("#merged-table-body").html('');
    }
}
function unmerge_table(el){
    // alert(el.value);
    // var v = $('#el.')
    $.ajax({

        url: "<?php echo base_url('restorent/merge_table'); ?>",

        type: 'POST',

        data:{

            UnmergeTable: 1,
            TableNo: el.id,
            MergeNo:$('#'+el.id).attr('merge_no')

        },

        dataType: 'json',

        success: function(response) {
        }
    });
}
</script>