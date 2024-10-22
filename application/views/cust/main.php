<?php $this->load->view('layouts/customer/head'); ?>
<style>
	
	.list-inline-item > .nav-link {
		padding: 0px !important;
	}
	.common-section{
		background: #f5f5f5;
		margin-bottom: 5px;
	}
	.section-sm{
		margin-top: -12px;
		padding: 0px 0px 70px 0px !important
	}
	.responsive__tabs ul.scrollable-tabs {
      /*background-color: #333;*/
      overflow-x: auto;
      white-space: nowrap;
      display: flex;
      text-transform: uppercase;
      flex-direction: row;
      font-weight: 600;
      font-size: 14px;
    }
    .responsive__tabs ul.scrollable-tabs li {
      list-style-type: none;
    }
    .responsive__tabs ul.scrollable-tabs li a {
      display: inline-block;
      /*color: #252525;*/
      color: <?php echo $this->session->userdata('cuisineTxtClr'); ?>;
      text-align: center;
      padding: 14px;
      text-decoration: none;
    }
    .responsive__tabs ul.scrollable-tabs li a:hover, .responsive__tabs ul.scrollable-tabs li a.active {
      /*background-color: #777;*/
      color:#008000;
    }

    .widget {
        padding: 5px 5px 5px !important;
        margin-bottom: 5px !important;
    }
    .widget .widget-header {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        padding-bottom: 4px;
        border-bottom: 1px solid #dedede;
    }

    /*filter section */
        .sec2-radio-grp {
            padding: 5px;
        }

        .veg-btn {
            border-radius: 25px 0 0 25px;
        }

        .both-btn {
            border-radius: 0 25px 25px 0;
        }
        .btn-b{
        	width: 80px;
        	padding: 5px !important;
            border:1px solid #008000;
            background: white;
            font-size: 12px !important;
        }
        #filters label{
            color: <?php echo $this->session->userdata('filterTxtClr'); ?>;
        }

        label.active{
            color:white;
            background: #008000;
        }
        /*end of filter section */

        .featured__controls{
            overflow: auto !important ;
            overflow-y: hidden !important;
            white-space: nowrap;
            margin-bottom: 0px !important;
        }

        #mCategory li{
            color: <?php echo $this->session->userdata('categoryTxtClr'); ?>;
        }
        #mCategory li.active {
          	color:#008000;
          	border:1px solid #008000;
          	padding: 2px;
          	border-radius: 20px;
        }
       
        /*grid view*/
       .product-item{
       	transition: all linear 0.3s;
       	box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.2);
       }

       .product-item:hover{
       	transform: translateY(-10px);
       }

       .col-mbl{
	        padding: 0.5rem !important;
	        margin-bottom: -30px !important;
	   }

       .item_img{
       		width: 100%;
            height:200px;
            margin:0 auto;
            background-size: cover;
            display:block;
        }

        .product-item .card .card-body {
		    padding: 0.5rem !important;
		}
		.product-item .product-meta {
			margin-bottom: 0px !important;
		}

		.strTruncate{
			font-size: 13px;
			white-space: nowrap;
		  	overflow: hidden;
		  	text-overflow: ellipsis;
		  	margin-bottom: 1px;
		}
       /*end grid view*/
        
        /*modal button */
        .modal-footer {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
             flex-wrap: nowrap; 
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: end;
            justify-content: flex-end;
            padding: 0.75rem;
            border-top: 1px solid #dee2e6;
            border-bottom-right-radius: calc(0.3rem - 1px);
            border-bottom-left-radius: calc(0.3rem - 1px);
        }
        .modal-confirm {
            width: 50%;
            background: <?php echo $this->session->userdata('successBtn'); ?>;
            color: <?php echo $this->session->userdata('successBtnClr'); ?>;
            margin-left: 0px !important;
            border-radius: 0 1.5rem 1.5rem 0;
            
        }

        .modal-confirm:hover {
            background: #5ab950;
            color: #fff;
        }

        .modal-back {
            width: 50%;
            margin-right: 0px !important;
            border-radius: 1.5rem 0 0 1.5rem;
            background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
            color: <?php echo $this->session->userdata('menuBtnClr'); ?>;;
        }

        .modal-back:hover {
            background-color: #efd4b3;
        }

        .modal-item-img {
            margin:0 auto;
            height: 180px;
            background-size: cover;
            /*border-radius: 0 0 175px 175px;*/
        }

/*mobile screen only*/
@media only screen and (max-width: 480px) {
   .col-mbl{
        padding: 0.2rem !important;
        margin-bottom: -28px !important;
   }
  .item_img{
  	/*width: 100%;*/
    height: 150px;
  }
  .strTruncate{
		/*border:1px solid red;*/
		width: 168px !important;
		white-space: nowrap;
	  	overflow: hidden;
	  	text-overflow: ellipsis;
	}
	.product-item .product-meta li {
	    margin-right: 9px;
	}

    .forRightIcon {
      position: absolute;
      top: 40px;
      right: -5px;
      display: inline-block;
      /*padding: 4px 8px;*/
      font-size: 9px;
      padding: 1px 5px;
    }
}

.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("<?= base_url('theme/images/Eat-Out-loader.png') ?>") 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

/* read more */
.morecontent span {
    display: none;
}
.morelink {
    display: inline-block;
}
/* read less */

.cuisineColor{
    background: <?php echo $this->session->userdata('cuisineClr'); ?>;
}
.filterColor{
    background: <?php echo $this->session->userdata('filterClr'); ?>;
}
.categoryColor{
    background: <?php echo $this->session->userdata('categoryClr'); ?>;
}

.mainColor{
    background: <?php echo $this->session->userdata('mainSection'); ?>;
}

</style>
</head>

<body class="body-wrapper">

<!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
<!-- Header Section End -->

<section class="common-section cuisineColor">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="responsive__tabs">
	                <ul class="scrollable-tabs">
	                    <?php
	                    foreach ($cuisinList as $key) {
	                    ?>
	                    <li class="nav-item">
	                        <a class="nav-link <?php if($cid == $key['CID']){ echo 'active'; } ?>" data-toggle="tab" href="#home" onclick="getCuisineList(<?php echo $key['CID']; ?>)"><?php echo $key['Name']; ?></a>
	                    </li>
	                <?php } ?>
	                </ul>
	            </div>
			</div>
		</div>
	</div>
</section>

<section class="common-section filterColor" id="filterBlock">
	<div class="container text-center">
		<div class="row">
			<div class="col-md-9 col-8">
				<div class="sec2-radio-grp btn-group btn-group-toggle" data-toggle="buttons" id="filters" style="width: 100%;">
		        </div>
			</div>
			<div class="col-md-3 col-4 text-right">
		        <ul class="list-inline view-switcher">
					<li class="list-inline-item">
						<i class="fa fa-th-large" onclick="showProdct('grid');" style="cursor: pointer;"></i>
					</li>
					<li class="list-inline-item">
						<i class="fa fa-reorder" onclick="showProdct('list');" style="cursor: pointer;"></i>
					</li>
				</ul>
			</div>
		</div>

	</div>
</section>

<section class="common-section categoryColor">
	<div class="container">
		<div class="featured__controls">
            <ul id="mCategory" style="padding-bottom: 5px;cursor: pointer;" class="list-inline">
            </ul>
        </div>
	</div>
</section>

<section class="section-sm mainColor">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-grid-list">
					<div class="row mt-4" id="gridView">
					</div>
				</div>
				<!-- list view -->
				<div class="view" style="display: none;">
				</div>
				<!-- end of list view -->
			</div>
		</div>
	</div>
</section>

<!-- footer -->
<?php $this->load->view('layouts/customer/footer'); ?>
<!-- end of footer -->

<!-- search model -->
    <div class="modal" id="item-list-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" style="display: block; padding: 5px; background-color: #efb955;">
                    <h4 class="modal-title text-white"><?= $this->lang->line('search'); ?> <?= $this->lang->line('item'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="search-item" class="form-control" placeholder="Enter item Name" style="border-radius: 20px;">
                            <div id="item-search-result"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- itemModal Modal -->
    <div class="modal product-modal" id="itemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <img id="product-img" class="modal-item-img" src="<?= base_url(); ?>assets/img/sample-foods.jpg">
                        <input type="hidden" id="sdetcd" value="">
                        <input type="text" id="TaxType" hidden>
                        <input type="hidden" id="itemTyp">
                <div class="modal-body" style="padding-top: 0px;">
                    <p id="item-name-modal" style="margin-bottom: 1px;"><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></p>
                    <span id="item_offer_remark"></span>
                    <ul class="list-inline product-meta">
				    	<li class="list-inline-item">
				    		<i class="fa fa-star ratings text-warning" aria-hidden="true">
                            </i>
                            <span id="item-rating-modal" class="rating-no">0</span>
				    	</li>
                        <?php if($this->session->userdata('NV') == 1){ ?>
				    	<li class="list-inline-item">
				    		<i class="fa fa-heartbeat" style="color:green;"></i>
				    		<span class="" id="nvRating">000</span>
				    	</li>
                    <?php } ?>
				    	<li class="list-inline-item">
				    		<i class="fa fa-inr" style="color:blue;"></i>
                            <input type="hidden" id="product-price" value="0">
				    		<span class="modal-price price" id="product-priceView">000</span>
				    	</li>
				    </ul>

                    <p id="item-desc-modal" style="font-size: 13px;" class="more"></p>

                    <div class="row" style="margin-left: 0px;margin-right: 0px;position: relative;">
                        <div class="form-group" style="width: 156px; margin-bottom: 4px;">
                            <label for="item_portions" style="margin: 0px;font-size: 14px;"><?= $this->lang->line('portion'); ?>:</label>
                            <select class="form-control" id="item_portions" name="item_portions"  style="font-size: 13px; height: 30px; padding: 4px; width: 103px; font-weight: 600;" <?php if ($Itm_Portion == 0) {echo "disabled";} ?>>
                            </select>
                        </div>
                        <div style="position: absolute;right: 0pc;">
                            <label style="margin: 0px;font-size: 14px;">&nbsp;<?= $this->lang->line('quantity'); ?>:</label>
                            <div class="input-group" style="width: 94px;height: 28px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" disabled="">-
                                    </button>
                                </span>
                                <input type="hidden"  id="qty-val" >
                                <input type="text" readonly="" id="qty-valView" class="form-control input-number" min="1" max="10" style="text-align: center;">
                                <span class="input-group-btn">
                                    <button type="button" id="add-qty" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 30px;">+
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if ($EType < 25) { ?>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">

                            <div >
                                <label style="display: grid;margin: 0px;font-size: 14px;"><?= $this->lang->line('takeAway'); ?></label>
                                
                                <select class="form-control" style="font-size: 13px; height: 30px; padding: 4px;width: 103px;" id="take-away">
                                    <option value="0"><?= $this->lang->line('sitIn'); ?></option>
                                    <option value="1"><?= $this->lang->line('takeAway'); ?></option>
                                    <?php if($Charity == 1){ ?>
                                    <option value="2"><?= $this->lang->line('charity'); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if($this->session->userdata('EDT') > 0 ){ ?>
                            <div style="position: absolute;right: 1pc;">
                                <label for="sel1" style="margin: 0px;font-size: 14px;"><?= $this->lang->line('delivery_time'); ?></label>
                                <div id="waiting-btn" class="your-class1 btn-group btn-group-toggle" data-toggle="buttons" style="width: 96px;display: block;">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" id="minus-serve" class="btn btn-default btn-number active" data-type="minus" style="background-color: #0a88ff;color: #fff;border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="true" disabled="">-
                                            </button>
                                        </span>
                                        <input type="hidden" id="serve-val">
                                        <input type="text" readonly="" id="serve-valView" class="form-control input-number" value="5" min="5" max="30" style="text-align: center;">
                                        <span class="input-group-btn">
                                            <button type="button" id="add-serve" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="false" >+
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                        </div>
                    <?php } ?>

                    <div class="row" style="margin: 0px;padding-bottom: 20px;">
                        <div class="form-group offerBlock" style="width: 100%;">
                            <select name="schcd" id="schcd" class="form-control" >
                                <option value=""><?= $this->lang->line('selectOffer'); ?></option>
                            </select>
                        </div>
                        <div class="remark" style="width: 100%">
                            <input id="cust-remarks" type="text" class="form-control Remarks-input" placeholder="<?= $this->lang->line('enterRemarks'); ?>" name="remark-box">
                        </div>

                        <small id="requiredMsg" class="text-danger"></small>

                        <div class="widget category" style="width: 100%;display: none;" id="radioOption">
                            
                        </div>

                        <div class="widget category" style="width: 100%;display: none;" id="checkboxOption">
                            <h5 class="widget-header" id="chkHeader"></h5>
                            <ul class="category-list" id="chkList">
                                
                            </ul>
                        </div>

                        <div class="form-group" style="width: 100%;margin-top: 8px;" id="customizeItemBlock" style="display: none;">
                            <select name="customizeItem" id="customizeItem" class="form-control" >
                                <option value="0"><?= $this->lang->line('select'); ?></option>
                            </select>
                        </div>

                    </div>

                    
                </div>
            </div>
        </div>
        <?php if($this->session->userdata('addItemLock') < 1 ){ ?>
        <div class="modal-footer" style="bottom: 0px;background-color: #ffffff;padding: 3px;padding-left: 15px;right: 0px;bottom: 0px;left: 0px;z-index: 1050;outline: 0px;position: fixed; width: 100%;">
            <button type="button" class="btn col-5 modal-back" data-dismiss="modal" style="padding: 5px 5px 5px; font-size: 12px;"><?php echo  $this->lang->line('back'); ?></button>
            <label class="col-2" style="padding: 3px;height: 25px;text-align: center;">
                <input type="hidden" value="0" id="totalAmount">
                <b id="totalAmountView">0</b>
            </label>
            <button type="button" class="btn col-5 modal-confirm" data-dismiss="modal" id="confirm-order" tax_type="" tbltyp="" pck="" style="padding: 5px 5px 5px; font-size: 12px;"><?php echo  $this->lang->line('addItem'); ?></button>
        </div>
        <?php } ?>
    </div>

    <div class="modal fade" id="ingrediants" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #e1af75; padding: 7px 7px !important;">
            <h6 class="modal-title text-white" id="ingTitle"><?= $this->lang->line('name'); ?></h6>
          </div>
          <div class="modal-body">
            <p id="ingText"></p>
          </div>
            
        </div>
      </div>
    </div>

    <div class="modal fade" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #e1af75; padding: 7px 7px !important;">
            <h6 class="modal-title text-white" id="imgTitle"><?= $this->lang->line('name'); ?></h6>
          </div>
          <div class="modal-body">
            <img src="" id="imgpop" style="height: 300px;width: 100%;">
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #e1af75; padding: 7px 7px !important;">
            <h6 class="modal-title text-white" id="youtubeTitle"><?= $this->lang->line('name'); ?></h6>
          </div>
          <div class="modal-body">
            <iframe width="100%" src="https://www.youtube.com/embed/-y9FOb8CQoM?si=i_Qst60Hf9gMQ6jn" allow="autoplay" frameborder="0" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>

<?php $this->load->view('layouts/customer/script'); ?>

</body>
<script type="text/javascript">

    $(`#customizeItemBlock`).hide();
    
    var deliveryVal = 0;
	var prList = 'grid';
	function showProdct(val=''){
		if(val != ''){
			prList = val;
		}
		if(prList == 'grid'){
			$('.product-grid-list').show();
			$('.view').hide();
		}else{
			$('.product-grid-list').hide();
			$('.view').show();
		}
	}

   $(document).ready(function() {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });

        $('#qty-val').val(1);
        $('#qty-valView').val(convertToUnicodeNo(1));

        $('#add-qty').click(function() {
            let val = 0;
            val = $('#qty-val').val(); 
            val = parseInt(val) + 1;
            $('#qty-val').val(val);
            $('#qty-valView').val(convertToUnicodeNo(val));

            $('#minus-qty').prop('disabled', false);
            if ($('#qty-val').val() == 99) {
                $('#add-qty').prop('disabled', true);
            }
        });
        $('#minus-qty').click(function() {
            let val = 0;
            val = $('#qty-val').val(); 
            val = parseInt(val) - 1;
            $('#qty-val').val(val);
            $('#qty-valView').val(convertToUnicodeNo(val));

            $('#add-qty').prop('disabled', false);
            if ($('#qty-val').val() == 1) {
                $('#minus-qty').prop('disabled', true);
            }
        });

        // deliver buttons
        $('#add-serve').click(function() {
            let val = parseInt($('#serve-val').val()) + 1;

            $('#serve-val').val(val);
            $('#serve-valView').val(convertToUnicodeNo(val));

            $('#minus-serve').prop('disabled', false);
            if ($('#serve-val').val() == 99) {
                $('#add-serve').prop('disabled', true);
            }
        });
        $('#minus-serve').click(function() {
            let val = parseInt($('#serve-val').val()) - 1;

            $('#serve-val').val(val);
            $('#serve-valView').val(convertToUnicodeNo(val));

            $('#add-serve').prop('disabled', false);
            if ($('#serve-val').val() == deliveryVal) {
                $('#minus-serve').prop('disabled', true);
            }
        });

    });

    var cidg = "<?= $cid; ?>";
    var mcatIdg = "<?= $fmcat; ?>";
    var filterg = "<?= $ffid; ?>";

    getCuisineList(cidg);
    function getCuisineList(cid){
        cidg = cid;
        
        $.post('<?= base_url('customer') ?>',{cid:cid},function(res){
            if(res.status == 'success'){
                var mCatList = res.response.list.mcat;
                var filter = res.response.list.filter;
                var MCatgId = mCatList[0].MCatgId;
                mcatIdg = MCatgId;
              
              var mcat = '';
              if(mCatList.length > 0){
                // $('#mcatgBlock').show();
                for (var i = 0; i < mCatList.length; i++) {
                    var sts = '';
                    if(MCatgId == mCatList[i].MCatgId){
                        sts = 'active';
                    }
                    
                    mcat +='<li class="list-inline-item '+sts+'" data-filter="*" onclick="clickMcat('+mCatList[i].MCatgId+')" style="font-size:14px;">'+mCatList[i].LngName+'</li>';
                }
            }else{
                // $('#mcatgBlock').hide();
            }
                $('#mCategory').html(mcat);
                // call grid view
                
                clickMcat(mcatIdg);
                getItemDetails(cid, mcatIdg, filterg);
            }else{
              alert(res.response);
              // show error msg pending
            }
        });
    }

    function clickMcat(mcatId){

        mcatIdg = mcatId; 
        filterg = 0;

        $.post('<?= base_url('customer/getFoodTypeList') ?>',{mcatId:mcatId},function(res){
            if(res.status == 'success'){
                
                var filter = res.response;
                var all = res.all;

                if(filter.length > 0){
                    $('#filterBlock').show();
                    fltr = '<label class="btn btn-b veg-btn active">\
                        <input id="both-v-nv" type="radio" value="0" name="veg-nonveg" autocomplete="off" onchange="filterChange(0)" checked="">'+all+'</label>';
                    for(i=0; i < filter.length; i++){
                        fltr += '<label class="btn btn-b nonveg-btn">\
                        <input type="radio" value="'+filter[i].FID+'" name="veg-nonveg" autocomplete="off" onchange="filterChange('+filter[i].FID+')">'+filter[i].LngName+'</label>';
                    }
                    $('#filters').html(fltr);
                }else{
                    $('#filterBlock').hide();
                }
                // end of fid
            }else{
              alert(res.response);
              // show error msg pending
            }
        });

        getItemDetails(cidg, mcatIdg, filterg);
    }

    function filterChange(filter){
        filterg = filter; 
        getItemDetails(cidg, mcatIdg, filterg);
    }

    function getItemDetails(cid, mcatId, filter){
        
        $("#gridView").html('<div class="loader"></div>');
        $.post('<?= base_url('customer/getItemDetailsData') ?>',{cid:cid,mcatId:mcatId,filter:filter},function(res){
            if(res.status == 'success'){
              var data = res.response;

              var total = data.length;
              var listView = '';
              var grid = '';
              if(total > 0){
                  for (var i = 0; i< data.length; i++) {
                    var openModal = '#itemModal';
                    // var itemName = "'"+data[i].itemName+"'";
                    var itemName = data[i].itemName;
                    var ingrediant = "'"+data[i].Ingeredients+"'";
                    var imgUrl = "'<?= base_url(); ?>"+data[i].imgSrc+"'";

                    var ing_g = '';
                    var ing_l = '';
                    var ytube_g = '';
                    var ytube_l = '';
                    var itemNameStr = "'"+data[i].itemName+"'";
                    if(data[i].Ingeredients != "" && data[i].Ingeredients != "-"){
                        ing_g = '<li class="list-inline-item text-center" style="display:block;margin-bottom:1px;"><a class="fa fa-database" href="#" onclick="ingerediants('+itemNameStr+','+ingrediant+')"></a></li>';

                        ing_l = '<li class="list-inline-item text-center"><a class="fa fa-database" href="#" onclick="ingerediants('+itemNameStr+','+ingrediant+')"></a></li>';
                    }
                    
                    if(data[i].videoLink != '-'){
                        ytube_g = '<li class="list-inline-item text-center" style="display:block;margin-bottom:1px;"><a class="fa fa-play" href="#" onclick="youtubeOpen('+itemNameStr+')"></a></li>';

                        ytube_l = '<li class="list-inline-item text-center"><a class="fa fa-play" href="#" onclick="youtubeOpen('+itemNameStr+')"></a></li>';
                    }

                    if(data[i].ItemTyp > 0 )
                    {
                        openModal = '#itemModal';
                    }

                    var sale = '';
                    var saleView = '';
                    if(data[i].ItemSale == 1){
                        sale = '<div class="priceRight" style="background:#f5b6b6;color:#343a40"><?= $this->lang->line('new'); ?></div>';
                        saleView = '<span style="background:#f5b6b6;color:#000;border-radius:50px;padding:3px;font-size:10px;"><?= $this->lang->line('new'); ?></span>';
                    }else if(data[i].ItemSale == 2){
                        sale = '<div class="priceRight" style="background:#92d6ebcc;color:#343a40"><?= $this->lang->line('mustTry'); ?></div>';
                        saleView = '<span style="background:#92d6ebcc;color:#000;border-radius:50px;padding:3px;font-size:10px;"><?= $this->lang->line('mustTry'); ?></span>';
                    }else if(data[i].ItemSale == 3){
                        sale = '<div class="priceRight" style="background:#ecf10a;color:#343a40"><?= $this->lang->line('bestseller'); ?></div>';
                        saleView = '<span style="background:#ecf10a;color:#000;border-radius:50px;padding:3px;font-size:10px;"><?= $this->lang->line('bestseller'); ?></span>';
                    }

                    var attrib = '';
                    var attribView = '';
                    if(data[i].ItemAttrib == 1){
                        attrib = '<div class="price" style="background:#fd4800;color:#fff;"><?= $this->lang->line('spicy'); ?></div>';
                        attribView = '<span style="background:#fd4800;color:#fff;border-radius:50px;padding:3px;margin-right:2px;font-size:10px;"><?= $this->lang->line('spicy'); ?></span>';
                    }else if(data[i].ItemAttrib == 2){
                        attrib = '<div class="price" style="background:#c51919;color:#fff;"><?= $this->lang->line('verySpicy'); ?></div>';
                        attribView = '<span style="background:#c51919;color:#fff;border-radius:50px;padding:3px;margin-right:2px;font-size:10px;"><?= $this->lang->line('verySpicy'); ?></span>';
                    }else if(data[i].ItemAttrib == 3){
                        attrib = '<div class="price" style="background:#80b927;color:#fff;"><?= $this->lang->line('sweet'); ?></div>';
                        attribView = '<span style="background:#80b927;color:#fff;border-radius:50px;padding:3px;margin-right:2px;font-size:10px;"><?= $this->lang->line('sweet'); ?></span>';
                    }else if(data[i].ItemAttrib == 4){
                        attrib = '<div class="price" style="background:#567d1a;color:#fff;"><?= $this->lang->line('verySweet'); ?></div>';
                        attribView = '<span style="background:#567d1a;color:#fff;border-radius:50px;padding:3px;margin-right:2px;font-size:10px;"><?= $this->lang->line('verySweet'); ?></span>';
                    }
                                    // <div class="priceBottom">$400</div>\
                                    // <div class="priceBottomRight">$300</div>\
                      grid += '<div class="col-lg-3 col-md-4 col-sm-6 col-6 col-mbl">\
							<div class="product-item bg-light">\
								<div class="card">\
									<div class="thumb-content">\
                                        '+sale+'\
										'+attrib+'\
                                        <?php if($this->session->userdata('Ingredients') == 1){ ?>
                                        <div class="forRightIcon">\
                                            <ul class="social-circle-icons list-inline">\
                                              <li class="list-inline-item text-center" style="display:block;margin-bottom:1px;"><a class="fa fa-joomla" href="#" onclick="imgPOPUP('+itemNameStr+','+imgUrl+')"></a></li>\
                                              '+ing_g+'\
                                              '+ytube_g+'\
                                            </ul>\
                                        </div>\
                                    <?php } ?>
										<?php if(!empty($this->session->userdata('CustId'))){ ?>
										<a href="#" data-toggle="modal" data-target="'+openModal+'" onclick="getItemDeatils(this,'+data[i].ItemTyp+');" item-id="'+data[i].ItemId+'" item-nm="'+itemName+'"  item-portion="'+data[i].Portion+'" item-portion-code="'+data[i].Itm_Portion+'" item-value="'+data[i].ItmRate+'" item-avgrtng="'+data[i].AvgRtng+'" item-dedc="'+data[i].itemDescr+'" item-dcd="'+data[i].DCd+'" item-imgsrc="<?= base_url(); ?>'+data[i].imgSrc+'" item-type="'+data[i].ItemTyp+'" item-kitcd="'+data[i].KitCd+'" cid="'+data[i].CID+'" mcatgid="'+data[i].MCatgId+'" item-fid="'+data[i].FID+'" TaxType="'+data[i].TaxType+'" tbltyp="'+data[i].TblTyp+'"  style="cursor: pointer;" item-prepTime="'+data[i].PrepTime+'" item-NV="'+data[i].NV+'" item-pck="'+data[i].PckCharge+'" item-itemsale="'+data[i].ItemSale+'">\
											<img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+itemName+'">\
										</a>\
										<?php } else{ ?>
										<a href="<?= base_url('customer/login') ?>" >\
											<img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+itemName+'">\
										</a>\
									<?php } ?>
									</div>\
									<div class="card-body">\
                                        <p data-toggle="tooltip" data-placement="top" title="'+data[i].itemName+'" class="strTruncate">'+data[i].itemName+'</p>\
                                        <ul class="list-inline product-meta">\
                                            <li class="list-inline-item">\
                                                <i class="fa fa-star ratings text-warning" aria-hidden="true"></i> '+convertToUnicodeNo(data[i].AvgRtng)+'\
                                            </li>\
                                            <?php if($this->session->userdata('NV') == 1){ ?>
                                            <li class="list-inline-item">\
                                                <i class="fa fa-heartbeat" style="color:green;"></i> '+convertToUnicodeNo(data[i].NV)+'\
                                            </li>\
                                            <?php } ?>
                                            <li class="list-inline-item">\
                                                <i class="fa fa-handshake-o " aria-hidden="true" style="color:blue;"></i> '+convertToUnicodeNo(data[i].ItmRate)+'\
                                            </li>\
                                        </ul>\
									</div>\
								</div>\
							</div>\
						</div>';

                      listView += '<div class="ad-listing-list mt-20">\
					    <div class="row p-lg-3 p-sm-5 p-1">\
					        <div class="col-lg-4 col-md-4 col-sm-4 col-4 align-self-center">\
					        <?php if(!empty($this->session->userdata('CustId'))){ ?>
					            <a data-toggle="modal" data-target="'+openModal+'" onclick="getItemDeatils(this,'+data[i].ItemTyp+');" item-id="'+data[i].ItemId+'" item-nm="'+itemName+'"  item-portion="'+data[i].Portion+'" item-portion-code="'+data[i].Itm_Portion+'" item-value="'+data[i].ItmRate+'" item-avgrtng="'+data[i].AvgRtng+'" item-dedc="'+data[i].itemDescr+' " item-dcd="'+data[i].DCd+'" item-imgsrc="<?= base_url(); ?>'+data[i].imgSrc+'" item-type="'+data[i].ItemTyp+'" item-kitcd="'+data[i].KitCd+'" cid="'+data[i].CID+'" mcatgid="'+data[i].MCatgId+'" item-fid="'+data[i].FID+'" TaxType="'+data[i].TaxType+'" tbltyp="'+data[i].TblTyp+'" item-NV="'+data[i].NV+'"  style="cursor: pointer;" item-prepTime="'+data[i].PrepTime+'" item-pck="'+data[i].PckCharge+'" item-itemsale="'+data[i].ItemSale+'">\
					                <img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+itemName+'">\
					            </a>\
					            <?php } else{ ?>
					            	<a href="<?= base_url('customer/login') ?>" >\
					                <img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+itemName+'">\
					            </a>\
					            <?php } ?>
					        </div>\
					        <div class="col-lg-8 col-md-8 col-sm-8 col-8">\
			                    <div class="ad-listing-content">\
			                        <div>\
			                            <a data-toggle="tooltip" data-placement="top" title="'+itemName+'" class="font-weight-bold">'+itemName+'</a>\
			                        </div>\
			                        <ul class="list-inline mt-2">\
			                            <li class="list-inline-item">\
								    		<i class="fa fa-star ratings text-warning" aria-hidden="true"></i> '+convertToUnicodeNo(data[i].AvgRtng)+'\
								    	</li>\
                                        <?php if($this->session->userdata('NV') == 1){ ?>
								    	<li class="list-inline-item">\
								    		<i class="fa fa-heartbeat" style="color:green;"></i> '+convertToUnicodeNo(data[i].NV)+'\
								    	</li>\
                                    <?php } ?>
								    	<li class="list-inline-item">\
								    		<i class="fa fa-handshake-o " aria-hidden="true" style="color:blue;"></i> '+convertToUnicodeNo(data[i].ItmRate)+'\
								    	</li>\
			                        </ul>\
                                    <p>'+data[i].itemDescr+'</p>\
                                    <?php if($this->session->userdata('Ingredients') == 1){ ?>
                                    <ul class="social-circle-icons list-inline">\
                                      <li class="list-inline-item text-center"><a class="fa fa-joomla" href="#" onclick="imgPOPUP('+itemNameStr+','+imgUrl+')"></a></li>\
                                      '+ing_l+'\
                                      '+ytube_l+'\
                                    </ul>\
                                    <?php } ?>
                                    <div class="textStyle">'+attribView+''+saleView+'</div>\
			                    </div>\
					        </div>\
					    </div>\
					</div>';
                  }
                }else{
                    grid += '<div class="text-center text-danger">No Options Available! </div>';
                }

              $('#gridView').html(grid);
              $('.view').html(listView);
            }else{
              alert(res.response);
            }
        });
    }

    $('body').on('click', '.scrollable-tabs li', function() {
            $('.scrollable-tabs li a.active').removeClass('active');
            $(this).addClass('active');
        });

    $('body').on('click', '#mCategory li', function() {
            $('#mCategory li.active').removeClass('active');
            $(this).addClass('active');
        });
    
    var groupNameList = [];
        $("#search-item").keyup(function(event) {
            var itemName = $(this).val();
            if (itemName != '') {
                $.ajax({
                    url: '<?= base_url('customer/searchItemList') ?>',
                    type: "post",
                    data: {
                        searchItemCust: 1,
                        itemName: itemName
                    },
                    dataType: "json",
                    success: (response) => {
                        
                        if (response.status) {
                            var template = `<ul style='list-style-type:none;padding:5px;'>`;
                            response.items.forEach((item) => {
                                var targetModal = "#itemModal";
                                
                                var IMcCd = "<?php echo $this->session->userdata('IMcCdOpt'); ?>";
                                var printItemName = '';
                                if(IMcCd == 0){
                                    printItemName = item.ItemNm;
                                }else {
                                    printItemName = item.ItemNm+'-'+item.portionName;
                                }
                                
                                template += `
                                <li data-toggle="modal" data-target="${targetModal}" onclick="getItemDeatils(this,${item.ItemTyp});" item-id="${item.ItemId}" item-nm="${item.ItemNm}"  item-portion="${item.Itm_Portion}" item-portion-code="${item.Itm_Portion}" item-value="${item.OrigRate}" item-avgrtng="${item.AvgRtng}" item-dedc="${item.ItmDesc}" item-dcd="${item.DCd}" item-imgsrc="${item.imgSrc}" item-type="${item.ItemTyp}" item-kitcd="${item.KitCd}" cid="${item.CID}" mcatgid="${item.MCatgId}" item-fid="${item.FID}" TaxType="${item.TaxType}" item-NV="${item.NV}" item-pck="${item.PckCharge}" style="cursor: pointer;" item-prepTime="${item.PrepTime}" item-itemsale="${item.ItemSale}" tbltyp="${item.TblTyp}">${printItemName}</li>
                            `;
                            });
                            template += `</ul>`;
                        } else {
                            var template = `
                            <ul>
                                <li>No Item Found</li>
                            </ul>
                        `;
                        }
                        $("#item-search-result").html(template);
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            } else {
                $("#item-search-result").html('');
            }
        });

        function getItemDeatils(item, itemTyp) {

            $('#radioOption').hide();
            $('#checkboxOption').hide();

            window.itemMaxQtyValidation = $(item).attr('item-maxqty');
            $('#item-list-modal').modal('hide');

            itemId = $(item).attr('item-id');
            cid = $(item).attr('cid');
            itemTyp = $(item).attr('item-type');
            mCatgId = $(item).attr('mcatgid');
            itemPortion = $(item).attr('item-portion');
            PrepTime = $(item).attr('item-prepTime');
            FID = $(item).attr('item-fid');
            itemsale = $(item).attr('item-itemsale');
            deliveryVal = PrepTime;
            $('#itemTyp').val(itemTyp);

            var PckCharge = $(item).attr('item-pck');

            itemKitCd = $(item).attr('item-kitcd');
            DCd = $(item).attr('item-dcd');

            //get item portion from data base 
            getItemPortion(itemId, itemPortion, cid, itemTyp, mCatgId, FID, itemsale);

            $('#minus-serve').attr("disabled", true);
            $('#serve-val').val($(item).attr('item-preptime'));
            $('#serve-valView').val(convertToUnicodeNo($(item).attr('item-preptime')));
            $('#qty-val').val(1);
            $('#qty-valView').val(convertToUnicodeNo(1));

            $('#confirm-order').attr('tax_type',$(item).attr('taxtype'));
            $('#confirm-order').attr('tbltyp',$(item).attr('tbltyp'));
            $('#confirm-order').attr('pck',$(item).attr('item-pck'));

            $("#item-name-modal").text($(item).attr('item-nm'));
            $("#item-prepare-time").text(PrepTime + ' min to prepare');
            $("#item-prepare-time").attr('time', PrepTime);
            $("#item-rating-modal").text(convertToUnicodeNo( $(item).attr('item-avgrtng')) );
            
            if($(item).attr('item-dedc') != '-'){
                $("#item-desc-modal").text($(item).attr('item-dedc'));
            }

            $("#product-img").attr('src', $(item).attr('item-imgsrc'));

            $("#product-price").val(' ' + $(item).attr('item-value') );

            $("#product-priceView").text(' ' + convertToUnicodeNo($(item).attr('item-value')) );

            $("#nvRating").text(' ' + convertToUnicodeNo($(item).attr('item-NV')) );
            $('#totalAmount').val($(item).attr('item-value') );
            $('#totalAmountView').text(convertToUnicodeNo($(item).attr('item-value')) );

            // end for common

            // read more
            var showChar = 100;  // How many characters are shown by default
            var ellipsestext = "";
            var moretext = "<?= $this->lang->line('readMore'); ?>";
            var lesstext = "<?= $this->lang->line('readLess'); ?>";
        
            $('.more').each(function() {
                var content = $(this).html();
                if(content.length > showChar) {
                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);
                    var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });
     
            $(".morelink").click(function(){
                if($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
            
            // end of read more

            if (itemTyp == 0) {
                groupNameList = [];
                
            } else {
                
                getCustomItemDetails(itemId, itemTyp, $(item).attr('item-portion-code'), $(item).attr('item-fid'));
            }
            // getCustOffer(itemId, $(item).attr('item-nm'), cid, itemTyp, mCatgId);
            var item_Portion = $(item).attr('item-portion-code');
                
            getItemOffers(itemId, $(item).attr('item-nm'), cid, itemTyp, mCatgId, item_Portion, FID, itemsale);        
        }

        function getItemPortion(itemId, itemPortion, cid, itemTyp, mCatgId, FID, itemsale) {
            var data = {
                getItemPortion: 1,
                itemId: itemId,
                cid : cid,
                ItemTyp:itemTyp,
                MCatgId:mCatgId
            };
            function handleData(response) {
                
                if (response.length != 0) {
                    var html = '';
                    for (let index = 0; index < response.length; index++) {
                        var slct = '';
                        if(itemPortion == response[index]['IPCode']){
                            slct = 'selected';
                        }
                        html += `<option value="`+response[index]['IPCode']+`" rate="` + response[index]['ItmRate'] + `" offer_remark="` + response[index]['Remarks'] + `"  sdetcd="` + response[index]['SDetCd'] + `"  schcd="` + response[index]['SchCd'] + `" itemId="`+itemId+`" itemTyp="`+itemTyp+`" FID="`+FID+`" cid="`+cid+`" mCatgId="`+mCatgId+`" itemsale="`+itemsale+`" ${slct} > ` + response[index]['Name'] + ` </option>`;
                    }
                    $('#item_portions').html(html);
                    $('#item_portions_custome').html(html);
                    $("#item_offer_remark").text(response[0]['Remarks']);
                } else {
                    var html = `<option> ` + itemPortion + ` </option>`;
                    $('#item_portions').html(html);
                    $('#item_portions_custome').html(html);
                }
            }

            ajaxCall('<?= base_url('customer/get_item_portion_ajax') ?>', 'post', data, handleData);
        }

        function getItemOffers(itemId, itemNm, cid, itemTyp, mCatgId, itemPortion, FID, itemsale)
        {
            $(`.offerBlock`).hide();
            var tableOffer = "<?= $currentTableOffer; ?>"; 
            var TableNo = "<?= $this->session->userdata('TableNo'); ?>";
            var SchType = "<?= $this->session->userdata('SchType'); ?>";
            if((TableNo == 101 || TableNo == 105 || TableNo == 110 || TableNo == 100) && tableOffer == 1){
                if(SchType == 2 || SchType == 3){
                    $(`.offerBlock`).show();
                    $.ajax({
                        url: '<?= base_url('customer/get_item_offer_ajax') ?>',
                        type: 'post',
                        data: {
                            getOrderData: 1,
                            itemId: itemId,
                            cid:cid,
                            itemTyp:itemTyp,
                            MCatgId:mCatgId,
                            itemPortion:itemPortion,
                            FID : FID,
                            itemsale:itemsale
                        },
                        success: function(res) {
                            if(res.status == 'success'){
                                var data = res.response;
                                var temp = '<option value="0"><?= $this->lang->line('selectOffer'); ?></option>';
                                if(data.length > 0){
                                    for(i=0; i<data.length; i++){
                                        if(data[i].SchTyp == 2){
                                            temp += '<option value="'+data[i].SchCd+'" sdcode="'+data[i].SDetCd+'" Qty="'+data[i].Qty+'" Disc_Qty="'+data[i].Disc_Qty+'" Disc_pcent="'+data[i].Disc_pcent+'" Disc_Amt="'+data[i].Disc_Amt+'" ipcd="'+data[i].IPCd+'" Disc_IPCd="'+data[i].Disc_IPCd+'"  itemid="'+data[i].ItemId+'" disc_itemid="'+data[i].Disc_ItemId+'" itemtype="'+data[i].ItemTyp+'" disc_itemtype="'+data[i].Disc_ItemTyp+'" schcat="'+data[i].SchCatg+'">'+data[i].SchNm+'-'+data[i].SchDesc+'</option>';
                                        }
                                    }
                                    $('#schcd').css("border", "#e30223 solid 1px");
                                }
                                $('#schcd').html(temp);
                            }else{
                              alert(res.response);
                            }
                        }
                    });
                }
            }else{
                if(SchType == 2 || SchType == 3){
                    $(`.offerBlock`).show();
                    $.ajax({
                        url: '<?= base_url('customer/get_item_offer_ajax') ?>',
                        type: 'post',
                        data: {
                            getOrderData: 1,
                            itemId: itemId,
                            cid:cid,
                            itemTyp:itemTyp,
                            MCatgId:mCatgId,
                            itemPortion:itemPortion,
                            FID : FID,
                            itemsale:itemsale
                        },
                        success: function(res) {
                            if(res.status == 'success'){
                                var data = res.response;
                                var temp = '<option value="0"><?= $this->lang->line('selectOffer'); ?></option>';
                                if(data.length > 0){
                                    for(i=0; i<data.length; i++){
                                        if(data[i].SchTyp == 2){
                                            temp += '<option value="'+data[i].SchCd+'" sdcode="'+data[i].SDetCd+'" Qty="'+data[i].Qty+'" Disc_Qty="'+data[i].Disc_Qty+'" Disc_pcent="'+data[i].Disc_pcent+'" Disc_Amt="'+data[i].Disc_Amt+'" ipcd="'+data[i].IPCd+'" Disc_IPCd="'+data[i].Disc_IPCd+'"  itemid="'+data[i].ItemId+'" disc_itemid="'+data[i].Disc_ItemId+'" itemtype="'+data[i].ItemTyp+'" disc_itemtype="'+data[i].Disc_ItemTyp+'" schcat="'+data[i].SchCatg+'">'+data[i].SchNm+'-'+data[i].SchDesc+'</option>';
                                        }
                                    }
                                    $('#schcd').css("border", "#e30223 solid 1px");
                                }
                                $('#schcd').html(temp);
                            }else{
                              alert(res.response);
                            }
                        }
                    });
                }                
            }

        }

        function getCustomItemDetails(itemId, itemTyp, itemPortionCode, FID){
            var schcd = $(`#schcd`).val();
            if(schcd > 0){

            }else{
                $.post('<?= base_url('customer/get_custom_item') ?>',
                    {
                        getCustomItem:1,
                        itemId:itemId,
                        itemTyp:itemTyp,
                        itemPortionCode:itemPortionCode,
                        FID:FID
                    },
                    function(res){

                        if(res.status == 'success'){

                            var customItem = res.response;
                            radioList = customItem;

                            $('#radioOption').html('');
                            $('#checkboxOption').html('');
                            groupNameList = [];

                            for(i=0; i< customItem.length; i++){
                                
                                if(customItem[i].GrpType == 1){
                                    groupNameList.push(customItem[i].ItemGrpName);
                                    
                                    var tempRadio = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'&nbsp;<span class="text-danger">*</span></h5>\
                                            <ul class="category-list">';
                                    var details = customItem[i].Details;
                                    
                                    for(var r=0; r < details.length; r++){
                                        var name = "'"+details[r].Name+"'";
                                        tempRadio += '<li><input type="radio" name="'+customItem[i].ItemGrpName+'" value="'+details[r].ItemOptCd+'" rate="'+details[r].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+i+', '+name+', event)" /> '+details[r].Name+' <span class="float-right">('+details[r].Rate+')</span></li>';
                                    }
                                    tempRadio += '</ul>';
                                    $('#radioOption').append(tempRadio);
                                    $('#radioOption').show();
                                }else if(customItem[i].GrpType == 2){
                                    
                                    var tempCHK = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                            <ul class="category-list">';

                                    var details = customItem[i].Details;
                                    
                                    for(var c=0; c < details.length; c++){
                                        var name = "'"+details[c].Name+"'";
                                        tempCHK += '<li><input type="checkbox" name="'+customItem[i].ItemGrpName+'" value="'+details[c].ItemOptCd+'" rate="'+details[c].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+c+', '+name+', event)" /> '+details[c].Name+' <span class="float-right">('+details[c].Rate+')</span></li>';
                                    }
                                    tempCHK += '</ul>';
                                    $('#checkboxOption').append(tempCHK);
                                    $('#checkboxOption').show();
                                }
                            }

                        }else{
                            alert(res.response);
                        }
                    }
                );
            }
        }

        $('#schcd').change(function(){
            var schcd = $(this).val();
            
            var itemId = $('option:selected', this).attr('itemid');
            var ipcd = $('option:selected', this).attr('ipcd');
            var SDetCd = $('option:selected', this).attr('sdcode');
            var itemtype = $('option:selected', this).attr('itemtype');
            var Disc_IPCd = $('option:selected', this).attr('Disc_IPCd');
            var disc_itemtype = $('option:selected', this).attr('disc_itemtype');
            
            if(ipcd > 0){
                $('#item_portions').val(ipcd);
            }

            if(schcd > 0){
                $('#sdetcd').val($('option:selected', this).attr('sdcode'));

                $('#radioOption').hide();
                $('#checkboxOption').hide();
                if(itemtype > 0){
                    getCustomizeItem(schcd, SDetCd, disc_itemtype, Disc_IPCd);
                }
            }else{
                $('#sdetcd').val(0);
            }

            item_portions_call();
        })

        function item_portions_call(){
            
            var portionCode = $('#item_portions').val();
            
            var rate = $('#item_portions option:selected').attr('rate');

            var itemId = $('#item_portions option:selected').attr('itemid');
            var itemTyp = $('#item_portions option:selected').attr('itemtyp');
            var FID = $('#item_portions option:selected').attr('fid');
            // change custom item price according to itemPortionCode
            if (itemTyp > 0) {
                getCustomItemDetails(itemId, itemTyp, portionCode, FID);
            }
            $("#product-price").val(' ' + rate);
            $("#product-priceView").text(' ' + rate);

            $('#totalAmount').val(rate);
            $('#totalAmountView').text(rate);
            var remarks = $('#item_portions option:selected').attr('offer_remark');
            
            if(remarks !== 'null'){
            }else{
                $("#item_offer_remark").text('');
            }   
        }

        $('#item_portions').change(function(){
            // alert("aaaa");
            $('#schcd').val(0);
            var element = $(this);
            var rate = $('option:selected', this).attr('rate');
            var itemId = $('option:selected', this).attr('itemid');
            var itemTyp = $('option:selected', this).attr('itemtyp');
            var FID = $('option:selected', this).attr('fid');
            var cid = $('option:selected', this).attr('cid');
            var mCatgId = $('option:selected', this).attr('mcatgid');
            var itemsale = $('option:selected', this).attr('itemsale');
            // change custom item price according to itemPortionCode
            var portion = $(this).val();

            getItemOffers(itemId, '', cid, itemTyp, mCatgId, portion, FID, itemsale);
            if (itemTyp != 0) {
                getCustomItemDetails(itemId, itemTyp, element.val(), FID);
            }


            $("#product-price").val(' ' + rate);
            $("#product-priceView").text(' ' + convertToUnicodeNo(rate));

            $('#totalAmount').val(rate);
            $('#totalAmountView').text(convertToUnicodeNo(rate));
            var remarks = $('option:selected', this).attr('offer_remark');
            if(remarks !== 'null'){
            }else{
                $("#item_offer_remark").text('');
            }
        });

        var radioRate= [];
        var raidoGrpCd= [];
        var radioName= [];
        var checkboxVal= [];
        var checkboxRate= [];
        var checkboxItemCd= [];
        var checkboxGrpCd= "";
        var checkboxName= [];
        var total= 0;

        function calculateTotalc(itemGrpCd, index, itemName, event) {

            element = event.currentTarget;
            var rate = element.getAttribute('rate');
            console.log('calc '+index, event.target.type, rate);
            
            if (event.target.type == "radio") {
                this.radioRate[index] = parseInt(rate);
                this.raidoGrpCd[index] = itemGrpCd;
                this.radioName[index] = itemName;
            } else {
                // console.log(event.target.checked);
                if (event.target.checked) {
                    this.checkboxRate[index] = parseInt(rate);
                    this.checkboxName[index] = itemName;
                } else {
                    this.checkboxRate[index] = 0;
                    this.checkboxName[index] = 0;
                    // console.log(index);
                }
            }
            getTotalc();
        }

        function getTotalc() {
            var itemAmount =  $('#product-price').val();
            var radioTotal = 0;
            this.radioRate.forEach(item => {
                radioTotal += parseInt(item);
            });

            var checkTotal = 0;
            this.checkboxRate.forEach(item => {
                checkTotal += parseInt(item);
            });

            this.total = parseInt(itemAmount) + parseInt(radioTotal) + parseInt(checkTotal);
            $('#totalAmount').val(this.total);
            $('#totalAmountView').text(this.total);
        }

        $("#confirm-order").click(function(event) {
            if (window.itemMaxQtyValidation > 0) {
                $.ajax({
                    url: "<?php echo base_url('customer/item_details_ajax'); ?>",
                    type: 'post',
                    data: {
                        checkMaxQty: 1,
                        itemId: itemId,
                        maxQty: window.itemMaxQtyValidation,
                        enterQty: $('#qty-val').val(),
                    },
                    success: function(response) {
                        if (parseInt(response) >= 1) {
                            itemOrderConfirm();
                        } else {
                            alert("Sorry, Selected item has gone out of stock.");
                        }
                    }
                })
            } else {
                itemOrderConfirm();
            }
        });

        function itemOrderConfirm() {
            // mandatory radio options
            var schcd = $('#schcd').val();
            var customizeItemId = $('#customizeItem').val();
            var flag = 1;
            if(schcd == 0){
                if(groupNameList.length > 0){
                    var mandatory = false;
                    //groupNameList
                    var counter = 0;
                    var totalGroup = groupNameList.length;
                    var notSelect = '';
                    for(var g=0; g<groupNameList.length;g++){ 
                        //comment on and check this code mandatory = false;
                        var groupName = document.getElementsByName(groupNameList[g]); 
                          for(var i=0; i<groupName.length;i++){ 
                              if(groupName[i].checked == true){ 
                                  mandatory = true; 
                                  counter++;    
                              }else{
                                notSelect = groupNameList[g];
                              } 
                          } 
                      }

                    if(counter < totalGroup){
                        flag = 0;
                        alert('Please Choose the Required '+notSelect);
                        return false; 
                    }
                }
            }else{
                var Disc_IPCd = $('option:selected', $('#schcd')).attr('Disc_IPCd');
                var disc_itemtype = $('option:selected', $('#schcd')).attr('disc_itemtype');
                if(Disc_IPCd > 0 && disc_itemtype > 0){
                    if(customizeItemId == 0){
                        alert('Please select customize Item');
                        return false;
                    }
                }
            }
            
            var custRemarks = $("#cust-remarks").val();
            var qty = $("#qty-val").val();
            var serveTime = $("#serve-val").val();
            var itmrate = $("#product-price").val();
            var itemPortionText = itemPortion;
            var prepration_time = $('#item-prepare-time').attr('time');
            var tax_type = $('#confirm-order').attr('tax_type');
            var TblTyp = $('#confirm-order').attr('tbltyp');
            var sdetcd = $('#sdetcd').val();
            var itemTyp = $('#itemTyp').val();
            var total = $('#totalAmount').val();

            takeAway = $("#take-away").val();
            var PckCharge = $('#confirm-order').attr('pck');
            
            $.ajax({
                url: "<?php echo base_url('customer/item_details_ajax'); ?>",
                type: "post",
                data: {
                    orderToCart: 1,
                    itemId: itemId,
                    custRemarks: custRemarks,
                    qty: qty,
                    itemPortionText: $('#item_portions').val(),
                    takeAway: takeAway,
                    PckCharge:PckCharge,
                    serveTime: serveTime,
                    itemKitCd: itemKitCd,
                    DCd:DCd,
                    itmrate: itmrate,
                    prepration_time: serveTime,
                    tax_type:tax_type,
                    sdetcd:sdetcd,
                    schcd:schcd,
                    TblTyp:TblTyp,
                    itemTyp:itemTyp,
                    total : total,
                    checkboxName:this.checkboxName,
                    checkboxRate:this.checkboxRate,
                    radioRate:this.radioRate,
                    raidoGrpCd: this.raidoGrpCd,
                    radioName: this.radioName,
                    customizeItemId:customizeItemId
                },
                dataType: "json",
                beforeSend: function() {
                    $('.loder_bg').css('display','flex');
                },
                success: (response) => {
                    if (response == 1) {
                        window.location = "<?= base_url('customer'); ?>";
                        return false;
                    }
                    $("#cust-remarks").val('');
                    $("#qty-val").val(1);
                    $('#minus-qty').prop('disabled', true);
                    $("#take-away").prop('checked', false);
                    $("#serve-val").val(5);
                    
                    if (response.status == 2) {
                        alert('Payment for earlier bill is pending, please clear previous bill first !!');
                        window.location = `${response.redirectTo}`;
                        return false;
                    }
                    
                    if (response.status == 100) {
                        alert(response.msg);
                        window.location.reload();
                    } else if (response.status == 1) {
                        window.location = `${response.redirectTo}`;
                    } else {
                        console.log(response.msg);
                        $('.loder_bg').css('display','none');
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
            
        }

        function ingerediants(itemName, ingerediants){

            $('#ingTitle').html(itemName);
            $('#ingText').html(ingerediants);
            $('#ingrediants').modal('show');
        }

        function imgPOPUP(itemName, imgSrc){
            $('#imgTitle').html(itemName);
            document.getElementById('imgpop').src = imgSrc;
            $('#imgModal').modal('show');
        }

        function youtubeOpen(itemName){
            $('#youtubeTitle').html(itemName);
            $('#youtubeModal').modal('show');
        }

        function getCustomizeItem(SchCd, SDetCd, ItemTyp, IPCd){
            $.post('<?= base_url('customer/get_customize_items') ?>',{SchCd:SchCd,SDetCd:SDetCd,ItemTyp:ItemTyp, IPCd:IPCd},function(res){
                if(res.status == 'success'){
                    if(res.response.length > 0){
                        var tempOpt = `<option value="0"><?= $this->lang->line('select'); ?></option>`;
                        res.response.forEach((item, index) => {
                            tempOpt += `<option value="${item.ItemId}">${item.itemName} (${item.PortionName})</option>`;
                        });
                        $(`#customizeItemBlock`).show();
                        $(`#customizeItem`).html(tempOpt);
                    }else{
                        $(`#customizeItemBlock`).hide();
                    }

                }else{
                  alert(res.response);
                }
            });
        }

    </script>
</html>



