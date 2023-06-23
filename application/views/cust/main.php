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
      color: #252525;
      text-align: center;
      padding: 14px;
      text-decoration: none;
    }
    .responsive__tabs ul.scrollable-tabs li a:hover, .responsive__tabs ul.scrollable-tabs li a.active {
      /*background-color: #777;*/
      color:#008000;
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
            background: #ffc245;
            color: #fff;
            margin-left: 0px !important;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .modal-back {
            width: 50%;
            margin-right: 0px !important;
            border-radius: 0.5rem 0 0 0.5rem;
            background-color: #dedee2;
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
	    margin-right: 10px;
	}
}

.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("<?= base_url('assets/img/QSLogoName.png') ?>") 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

</style>
</head>

<body class="body-wrapper">

<!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
<!-- Header Section End -->

<section class="common-section">
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

<section class="common-section" id="filterBlock">
	<div class="container text-center">
		<div class="row">
			<div class="col-md-6 col-6">
				<div class="sec2-radio-grp btn-group btn-group-toggle" data-toggle="buttons" id="filters">
		        </div>
			</div>
			<div class="col-md-6 col-6 text-right">
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

<section class="common-section">
	<div class="container">
		<div class="featured__controls">
            <ul id="mCategory" style="padding-bottom: 5px;" class="list-inline">
            </ul>
        </div>
	</div>
</section>

<section class="section-sm">
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
                <div class="modal-header text-center" style="display: block; padding: 5px; background-color: darkblue;">
                    <h4 class="modal-title text-white">Search Item</h4>
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
                        <!-- <input type="hidden" id="schcd" value=""> -->
                        <input type="text" id="TaxType" hidden>
                        <!-- <p id="item-prepare-time" time="" style="font-size: 12px;color: blue;margin-bottom: 2px;">45 min to prepare</p> -->
                <div class="modal-body" style="padding-top: 0px;">
                    <p id="item-name-modal" style="margin-bottom: 1px;">Item name </p>
                    <span id="item_offer_remark"></span>
                    <ul class="list-inline product-meta">
				    	<li class="list-inline-item">
				    		<i class="fa fa-star ratings text-warning" aria-hidden="true">
                            </i>
                            <span id="item-rating-modal" class="rating-no" style="color: #000;">0</span>
				    	</li>
				    	<li class="list-inline-item">
				    		<i class="fa fa-heartbeat" style="color:green;"></i>
				    		<span class="" id="nvRating">000</span>
				    	</li>
				    	<li class="list-inline-item">
				    		<i class="fa fa-inr" style="color:blue;"></i>
				    		<span class="modal-price price" id="product-price">000</span>
				    	</li>
				    </ul>

                    <p id="item-desc-modal" style="font-size: 12px;"></p>

                    <div class="row" style="margin-left: 0px;margin-right: 0px;position: relative;">
                        <div class="form-group" style="width: 156px; margin-bottom: 4px;">
                            <label for="item_portions" style="margin: 0px;font-size: 14px;"><?= $language['portion']?>:</label>
                            <select class="form-control" id="item_portions" name="item_portions"  style="font-size: 13px; height: 30px; padding: 4px; width: 103px; font-weight: 600;" <?php if ($Itm_Portion == 0) {echo "disabled";} ?>>
                            </select>
                        </div>
                        <div style="position: absolute;right: 0pc;">
                            <label style="margin: 0px;font-size: 14px;">&nbsp;<?= $language['quantity']?>:</label>
                            <div class="input-group" style="width: 94px;height: 28px;margin-left: 5px;">
                                <span class="input-group-btn">
                                    <button type="button" id="minus-qty" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" disabled="">-
                                    </button>
                                </span>
                                <input type="text" readonly="" id="qty-val" class="form-control input-number" value="1" min="1" max="10" style="text-align: center;">
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
                                <label style="display: grid;margin: 0px;font-size: 14px;"><?= $language['take_away']?></label>
                                <!-- <label class="switch">
                                    <input type="checkbox" id="take-away">
                                    <span class="slider round"></span>
                                </label> -->
                                <select class="form-control" style="font-size: 13px; height: 30px; padding: 4px;width: 103px;" id="take-away">
                                    <option value="0">Sit In</option>
                                    <option value="1">Take Away</option>
                                    <?php if($Charity == 1){ ?>
                                    <option value="2">Charity</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div style="position: absolute;right: 1pc;">
                                <label for="sel1" style="margin: 0px;font-size: 14px;"><?= $language['delivery_time']?></label>
                                <div id="waiting-btn" class="your-class1 btn-group btn-group-toggle" data-toggle="buttons" style="width: 96px;display: block;">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" id="minus-serve" class="btn btn-default btn-number active" data-type="minus" style="background-color: #0a88ff;color: #fff;border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="true" disabled="">-
                                            </button>
                                        </span>
                                        <input type="text" readonly="" id="serve-val" class="form-control input-number" value="5" min="5" max="30" style="text-align: center;">
                                        <span class="input-group-btn">
                                            <button type="button" id="add-serve" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="false" >+
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    <?php } ?>

                    <div class="row" style="margin: 0px;">
                        <div class="form-group" style="width: 100%;">
                            <select name="schcd" id="schcd" class="form-control">
                                <option value="">Select Offer</option>
                            </select>
                        </div>

                        <div class="remark" style="width: 100%">
                            <input id="cust-remarks" type="text" class="form-control Remarks-input" placeholder="Enter Remarks" name="remark-box">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer" style="border-top: none;">
            <button type="button" class="btn modal-back" data-dismiss="modal" width="50%">
                <?= $language['back']?>
            </button>
            
            <button type="button" class="btn modal-confirm" data-dismiss="modal" width="50%" id="confirm-order" tax_type="" tbltyp=""><?= $language['add_item']?></button>
        </div>
    </div>

    <!-- customizeModal Modal -->
    <div class="modal" id="customizeModal">
        <div class="modal-dialog" style="position: fixed;right: 0;bottom: 40px;left: 0;z-index: 1050;outline: 0;position: fixed;">
            <div>
                <div class="modal-content" style="height: 400px;z-index: -1;overflow: auto;bottom: 0px;">
                    
                    <div>
                        <img :src="itemImg" class="modal-item-img" style=" width: 100%; background-size: cover;">
                    </div>

                    <div class="modal-header" style="border-bottom: none;padding-bottom: 0px;">
                        <p id="item-name-modal_custome"><b>{{itemName}}</b></p>
                        <span id="item_offer_remark"></span>
                        <!-- <p id="item-prepare-time_custome" time="" style="font-size: 12px;color: blue;margin-bottom: 2px;">{{item_prepTime}} min to prepare</p> -->
                        
                        <ul class="list-inline product-meta">
					    	<li class="list-inline-item">
					    		<i class="fa fa-star ratings text-warning" aria-hidden="true">
	                            </i>
	                            <span id="item-rating-modal" class="rating-no" style="color: #000;">4.5</span>
					    	</li>
					    	<li class="list-inline-item">
					    		<i class="fa fa-heartbeat" style="color:green;"></i>
					    		<span class="" id="nvRating">000</span>
					    	</li>
					    	<li class="list-inline-item">
					    		<i class="fa fa-inr" style="color:blue;"></i>
					    		<span class="modal-price price" id="product-price">{{defaultPrice}}</span>
					    	</li>
					    </ul>
                        
                    </div>

                    <div class="modal-body" style="padding-top: 0px;">
                        <p>{{itemDescription}}</p>

                        <div class="row" style="margin-left: 0px;margin-right: 0px;position: relative;">
                            <div class="form-group" style="width: 156px; margin-bottom: 4px;">
                                <label for="item_portions_custome" style="margin: 0px;font-size: 14px;"><?= $language['portion']?>:</label>
                            <select class="form-control" id="item_portions_custome" name="item_portions"   @change="selectPortion()" style="font-size: 13px; height: 30px; padding: 4px; width: 103px; font-weight: 600;" <?php if ($Itm_Portion == 0) {                                    echo "disabled";
                               } ?>>
                                </select>
                            </div>
                            <div style="position: absolute;right: 2pc;">
                                <label style="margin: 0px;font-size: 14px;"><?= $language['quantity']?>:</label>
                                <div class="input-group" style="width: 94px;height: 28px;margin-left: 5px;">
                                    <span class="input-group-btn">
                                        <button type="button" @click="(qty > 1 ? qty-- : qty = 1)" class="btn btn-default btn-number" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;">-
                                        </button>
                                    </span>
                                    <input type="text" readonly="" type="number" v-model="qty" min="1" class="form-control input-number" value="1" max="10" style="height :28px;">
                                    <span class="input-group-btn">
                                        <button type="button" @click="qty++" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px;    padding: 1px 7px;height: 30px;">+
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if ($EType < 25) { ?>
                            <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">

                            	<div>
                                    <label style="display: grid;margin: 0px;font-size: 14px;"><?= $language['take_away']?></label>
                                    <!-- <label class="switch">
                                        <input type="checkbox" v-model="takeAway">
                                        <span class="slider round"></span>
                                    </label> -->
                                    <select v-model="takeAway" class="form-control" style="font-size: 13px; height: 30px; padding: 4px;width: 103px;">
                                        <option value="0">Sit In</option>
                                        <option value="1">Take Away</option>
                                        <?php if($Charity == 1){ ?>
                                        <option value="2">Charity</option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div style="position: absolute;right: 2pc;width: 109px;">
                                    <label for="sel1" style="margin: 0px;font-size: 14px;"><?= $language['delivery_time']?></label>
                                    <div id="waiting-btn" class="your-class1 btn-group btn-group-toggle" data-toggle="buttons" style="width: 96px;display: block;">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" @click="(del > 1 ? del-- : del = 1)" class="btn btn-default btn-number active" data-type="minus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="true">-
                                                </button>
                                            </span>
                                            <input type="text" readonly="" v-model="del" class="form-control input-number" value="5" min="5" max="30" style="height: 28px;">
                                            <span class="input-group-btn">
                                                <button type="button" @click="(del < 5 ? del++ : del = 5)" class="btn btn-default btn-number" data-type="plus" style="background-color: #0a88ff;color: #fff;    border-radius: 0px; padding: 1px 7px;height: 30px;" aria-pressed="false">+
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>


                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Enter Remarks" v-model="custRemarks" style="background: #ced4da; padding: 3px;">
                        </div>

                        <div>
                            <!-- <p>{{itemDescription}}</p> -->
                        </div>

                        <div v-for="(form, index) in customForm" :key="index">
                            <div v-if="form.GrpType == 1">
                                <h4>{{form.ItemGrpName}}</h4>
                                <div v-for="detail in form.Details">
                                    <label>
                                        <input type="radio" :value="detail.ItemOptCd" :name="form.ItemGrpName" :rate="detail.Rate" v-model="radioVal[index]" @click="calculateTotal(form.ItemGrpCd, index, detail.Name, $event)">
                                        {{detail.Name}}
                                    </label>
                                    <label class="float-right">{{detail.Rate}}</label>
                                </div>
                            </div>
                            <div v-if="form.GrpType == 2">
                                <h4>{{form.ItemGrpName}}</h4>
                                <div v-for="(detail,checkIndex) in form.Details">
                                    <label>
                                        <input type="checkbox" :value="detail.ItemOptCd" v-model="checkboxVal[checkIndex]" :rate="detail.Rate" @click="calculateTotal(form.ItemGrpCd, checkIndex, detail.Name, $event)">
                                        {{detail.Name}}
                                    </label>
                                    <label class="float-right">{{detail.Rate}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer" style="bottom: 0px;background-color: #ffffff;padding: 3px;padding-left: 15px;/right: 0px;bottom: 0px;left: 0px;z-index: 1050;outline: 0px;position: fixed; width: 100%;">
            <button type="button" class="btn col-5" data-dismiss="modal">Back</button>
            <label class="col-2" style="padding: 3px;height: 25px;text-align: center;"><b>{{parseInt(total) + parseInt(defaultPrice)}}</b></label>
            <button type="button" class="btn col-5" @click="addItem()" style="background: #ffc245;">Add</button>
        </div>
    </div>

    <!-- offers modal -->
    <div class="modal" id="offers-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title offers-txt">Offers</p>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body" style="border: none;padding: 0px;overflow-y: scroll;height: 425px;">
                    <?php if(!empty($offers)){?>
                        <?php foreach($offers as $key){
                            $name = '';
                            if(!empty($key['ItemNm'])){
                                $name  .=  $key['ItemNm'];
                            }
                            if(!empty($key['portionName'])){
                                $name  .=  ' ('.$key['portionName'].')';
                            }
                            if(!empty($key['MCatgNm'])){
                                $name  .=  ' - '.$key['MCatgNm'];
                            }
                            if(!empty($key['Name'])){
                                $name  .=  ' - '.$key['Name'];
                            }
                            ?>

                            <div class="ad-listing-list mt-20">
							    <div class="row p-1">
							        <div class="col-lg-4 col-md-4 col-sm-4 col-4 align-self-center">
							            <img src="<?= base_url($key['SchImg']); ?>" alt="" style="height: 80px;width: 100px;background-size: cover;">
							        </div>
							        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
							            <h6><?= $key['SchNm'].' - '.$key['SchDesc'];?></h6>
							            <p class="pr-5"><?= $name; ?></p>
							        </div>
							    </div>
							</div>
                            
                        <?php }?>
                            
                    <?php }?>
                </div>
            </div>
        </div>
    </div>



<!-- 
Essential Scripts
=====================================-->
<?php $this->load->view('layouts/customer/script'); ?>

</body>
<script type="text/javascript">
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
        // $("body").tooltip({ selector: '[data-toggle=tooltip]' });

        $('#add-qty').click(function() {
            $('#qty-val').val(parseInt($('#qty-val').val()) + 1);
            $('#minus-qty').prop('disabled', false);
            if ($('#qty-val').val() == 99) {
                $('#add-qty').prop('disabled', true);
            }
        });
        $('#minus-qty').click(function() {
            $('#qty-val').val(parseInt($('#qty-val').val()) - 1);
            $('#add-qty').prop('disabled', false);
            if ($('#qty-val').val() == 1) {
                $('#minus-qty').prop('disabled', true);
            }
        });
    });

    var cidg = '<?= $cid; ?>';
    var mcatIdg = '';
    var filterg = '';
    getCuisineList(cidg);
    function getCuisineList(cid){

        cidg = cid;
        console.log('cid='+cidg);
        $.post('<?= base_url('customer') ?>',{cid:cid},function(res){
            if(res.status == 'success'){
                
                var mCatList = res.response.list.mcat;
                var filter = res.response.list.filter;
                var MCatgId = mCatList[0].MCatgId;
                mcatIdg = MCatgId;
              // console.log(mCatList[0].MCatgId);
              console.log('ff'+filter.length);
              var mcat = '';
              var fltr = '';
              if(mCatList.length > 0){
                $('#mcatgBlock').show();
                for (var i = 0; i < mCatList.length; i++) {
                    var sts = '';
                    if(MCatgId == mCatList[i].MCatgId){
                        sts = 'active';
                    }
                    // mcat += '<li role="presentation" ><a href="#" aria-controls="home" role="tab" data-toggle="tab">'+mCatList[i].MCatgNm+'</a></li> data-filter="*"';
                    mcat +='<li class="list-inline-item '+sts+'" data-filter="*" onclick="clickMcat('+mCatList[i].MCatgId+')" style="font-size:14px;">'+mCatList[i].MCatgNm+'</li>';
                }
            }else{
                $('#mcatgBlock').hide();
            }

                if(filter.length > 0){
                    $('#filterBlock').show();
                    fltr += '<label class="btn btn-b veg-btn active">\
                        <input id="both-v-nv" type="radio" value="0" name="veg-nonveg" autocomplete="off" checked="" onchange="filterChange(0)">ALL</label>\
                    <label class="btn btn-b nonveg-btn">\
                        <input type="radio" value="'+filter[0].FID+'" name="veg-nonveg" autocomplete="off" onchange="filterChange('+filter[0].FID+')">'+filter[0].Opt+'</label>\
                    <label class="btn btn-b both-btn">\
                        <input type="radio" value="'+filter[0].FIdA+'" name="veg-nonveg" autocomplete="off" onchange="filterChange('+filter[0].FIdA+')">'+filter[0].AltOpt+'</label>';
                    $('#filters').html(fltr);
                }else{
                    $('#filterBlock').hide();
                }
                $('#mCategory').html(mcat);
                // call grid view
                console.log('mcat='+mcatIdg);
                console.log('filter='+filterg);
                getItemDetails(cid, mcatIdg, filterg);
            }else{
              alert(res.response);
              // show error msg pending
            }
        });

    }

    function clickMcat(mcatId){
        mcatIdg = mcatId; 
        console.log('cid='+cidg+',mcat='+mcatIdg+',fl='+filterg);
        getItemDetails(cidg, mcatIdg, filterg);
    }

    function filterChange(filter){
        filterg = filter; 
        console.log('cid='+cidg+',mcat='+mcatIdg+',fl='+filterg);
        getItemDetails(cidg, mcatIdg, filterg);
    }

    function getItemDetails(cid, mcatId, filter){
        // var cid = '10';
        // var mcatId = '1';
        // var filter = '3';
        $("#gridView").html('<div class="loader"></div>');
        $.post('<?= base_url('customer/getItemDetailsData') ?>',{cid:cid,mcatId:mcatId,filter:filter},function(res){
            if(res.status == 'success'){
              var data = res.response;
              console.log(data);
              var total = data.length;
              var listView = '';
              var grid = '';
              if(total > 0){
                  for (var i = 0; i< data.length; i++) {
                    var openModal = '#itemModal';
                    if(data[i].ItemTyp > 0 )
                    {
                        openModal = '#customizeModal';
                    }

                      grid += '<div class="col-lg-3 col-md-4 col-sm-6 col-6 col-mbl">\
							<div class="product-item bg-light">\
								<div class="card">\
									<div class="thumb-content">\
										<div class="price">$200</div>\
										<?php if(!empty($this->session->userdata('CustId'))){ ?>
										<a href="#" data-toggle="modal" data-target="'+openModal+'" onclick="getItemDeatils(this,'+data[i].ItemTyp+');" item-id="'+data[i].ItemId+'" item-nm="'+data[i].ItemNm+'"  item-portion="'+data[i].Portion+'" item-portion-code="'+data[i].Itm_Portion+'" item-value="'+data[i].Value+'" item-avgrtng="'+data[i].AvgRtng+'" item-dedc="'+data[i].ItmDesc+'" item-imgsrc="<?= base_url(); ?>'+data[i].imgSrc+'" item-type="'+data[i].ItemTyp+'" item-kitcd="'+data[i].KitCd+'" cid="'+data[i].CID+'" mcatgid="'+data[i].MCatgId+'" item-fid="'+data[i].FID+'" TaxType="'+data[i].TaxType+'" tbltyp="'+data[i].TblTyp+'"  style="cursor: pointer;" item-prepTime="'+data[i].PrepTime+'" item-NV="'+data[i].NV+'">\
											<img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+data[i].ItemNm+'">\
										</a>\
										<?php } else{ ?>
										<a href="<?= base_url('customer/signup') ?>" >\
											<img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+data[i].ItemNm+'">\
										</a>\
									<?php } ?>
									</div>\
									<div class="card-body">\
									    <p data-toggle="tooltip" data-placement="top" title="'+data[i].ItemNm+'" class="strTruncate">'+data[i].ItemNm+'</p>\
									    <ul class="list-inline product-meta">\
									    	<li class="list-inline-item">\
									    		<i class="fa fa-star ratings text-warning" aria-hidden="true"></i> '+data[i].AvgRtng+'\
									    	</li>\
									    	<li class="list-inline-item">\
									    		<i class="fa fa-heartbeat" style="color:green;"></i> '+data[i].NV+'\
									    	</li>\
									    	<li class="list-inline-item">\
									    		<i class="fa fa-inr " aria-hidden="true" style="color:blue;"></i> '+data[i].ItmRate+'\
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
					            <a data-toggle="modal" data-target="'+openModal+'" onclick="getItemDeatils(this,'+data[i].ItemTyp+');" item-id="'+data[i].ItemId+'" item-nm="'+data[i].ItemNm+'"  item-portion="'+data[i].Portion+'" item-portion-code="'+data[i].Itm_Portion+'" item-value="'+data[i].Value+'" item-avgrtng="'+data[i].AvgRtng+'" item-dedc="'+data[i].ItmDesc+'" item-imgsrc="<?= base_url(); ?>'+data[i].imgSrc+'" item-type="'+data[i].ItemTyp+'" item-kitcd="'+data[i].KitCd+'" cid="'+data[i].CID+'" mcatgid="'+data[i].MCatgId+'" item-fid="'+data[i].FID+'" TaxType="'+data[i].TaxType+'" tbltyp="'+data[i].TblTyp+'" item-NV="'+data[i].NV+'"  style="cursor: pointer;" item-prepTime="'+data[i].PrepTime+'">\
					                <img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+data[i].ItemNm+'">\
					            </a>\
					            <?php } else{ ?>
					            	<a href="<?= base_url('customer/signup') ?>" >\
					                <img class="item_img" src="<?= base_url(); ?>'+data[i].imgSrc+'" alt="'+data[i].ItemNm+'">\
					            </a>\
					            <?php } ?>
					        </div>\
					        <div class="col-lg-8 col-md-8 col-sm-8 col-8">\
			                    <div class="ad-listing-content">\
			                        <div>\
			                            <a data-toggle="tooltip" data-placement="top" title="'+data[i].ItemNm+'" class="font-weight-bold">'+data[i].ItemNm+'</a>\
			                        </div>\
			                        <ul class="list-inline mt-2 mb-3">\
			                            <li class="list-inline-item">\
								    		<i class="fa fa-star ratings text-warning" aria-hidden="true"></i> '+data[i].AvgRtng+'\
								    	</li>\
								    	<li class="list-inline-item">\
								    		<i class="fa fa-heartbeat" style="color:green;"></i> '+data[i].NV+'\
								    	</li>\
								    	<li class="list-inline-item">\
								    		<i class="fa fa-inr " aria-hidden="true" style="color:blue;"></i> '+data[i].ItmRate+'\
								    	</li>\
			                        </ul>\
			                        <p class="pr-5">'+data[i].ItmDesc+'</p>\
			                    </div>\
					        </div>\
					    </div>\
					</div>';
                  }
                }else{
                    grid += '<div class="text-center text-danger">Data Not Found! </div>';
                }
              $('#gridView').html(grid);
              $('.view').html(listView);
            }else{
              alert(res.response);
              // show error msg pending
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
</script>

<script>
        $("#search-item").keyup(function(event) {
            var itemName = $(this).val();
            if (itemName != '') {
                $.ajax({
                    url: '<?= base_url('restaurant/order_ajax_3p') ?>',
                    type: "post",
                    data: {
                        searchItemCust: 1,
                        itemName: itemName
                    },
                    dataType: "json",
                    success: (response) => {
                        console.log(response);
                        if (response.status) {
                            var template = `<ul style='list-style-type:none;padding:5px;'>`;
                            response.items.forEach((item) => {
                                var targetModal = "#itemModal";
                                if (item.ItemTyp > 0) {
                                    targetModal = "#customizeModal";
                                }

                                // add cid and mcatgid to me
                                template += `
                                <li data-toggle="modal" data-target="${targetModal}" onclick="getItemDeatils(this,${item.ItemTyp});" item-id="${item.ItemId}" item-nm="${item.ItemNm}"  item-portion="${item.Portion}" item-portion-code="${item.Itm_Portion}" item-value="${item.Value}" item-avgrtng="${item.AvgRtng}" item-dedc="${item.ItmDesc}" item-imgsrc="${item.imgSrc}" item-type="${item.ItemTyp}" item-kitcd="${item.KitCd}" cid="${item.CID}" mcatgid="${item.MCatgId}" item-fid="${item.FID}" TaxType="${item.TaxType}" item-NV="${item.NV}" style="cursor: pointer;" item-prepTime="${item.PrepTime}">${item.ItemNm}</li>
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
            window.itemMaxQtyValidation = $(item).attr('item-maxqty');
            $('#item-list-modal').modal('hide');
            console.log(item);

            itemId = $(item).attr('item-id');
            cid = $(item).attr('cid');
            itemTyp = $(item).attr('item-type');
            mCatgId = $(item).attr('mcatgid');
            itemPortion = $(item).attr('item-portion');
            PrepTime = $(item).attr('item-prepTime');
            
            // console.log('itemPortion - '+itemPortion);
            // 

            itemKitCd = $(item).attr('item-kitcd');
            // console.log('itemKitCd - '+itemKitCd);

            //get item portion from data base 
            getItemPortion(itemId, itemPortion, cid, itemTyp, mCatgId);

            $('#minus-serve').attr("disabled", true);
            $('#serve-val').val($(item).attr('item-preptime'));
            $('#confirm-order').attr('tax_type',$(item).attr('taxtype'));
            $('#confirm-order').attr('tbltyp',$(item).attr('tbltyp'));

            if (itemTyp == 0) {

                $("#item-name-modal").text($(item).attr('item-nm'));
                // console.log('item-nm - '+$(item).attr('item-nm'));

                $("#item-prepare-time").text(PrepTime + ' min to prepare');
                $("#item-prepare-time").attr('time', PrepTime);

                // console.log('itemPortion - '+itemPortion+' min to prepare');

                $("#item-rating-modal").text($(item).attr('item-avgrtng'));
                // console.log('item-avgrtng - '+$(item).attr('item-avgrtng'));

                if($(item).attr('item-dedc') != '-'){
                    $("#item-desc-modal").text($(item).attr('item-dedc'));
                }
                // console.log('item-dedc - '+$(item).attr('item-dedc'));

                $("#product-img").attr('src', $(item).attr('item-imgsrc'));
                // console.log('item-imgsrc - '+$(item).attr('item-imgsrc'));

                $("#product-price").text(' ' + $(item).attr('item-value'));
                $("#nvRating").text(' ' + $(item).attr('item-NV'));
                // console.log('item-value - '+$(item).attr('item-value'));
            } else {
                customizeModalVue.getCustomItem($(item).attr('item-id'), itemTyp, $(item).attr('item-nm'), $(item).attr('item-value'), itemPortion, itemKitCd, $(item).attr('item-dedc'), $(item).attr('item-imgsrc'), $(item).attr('item-prepTime'), $(item).attr('item-portion-code'), $(item).attr('item-fid'));
            }
            getCustOffer(itemId, $(item).attr('item-nm'), cid, itemTyp, mCatgId);
            getItemOffers(itemId, $(item).attr('item-nm'), cid, itemTyp, mCatgId);
            
        }

        function getItemPortion(itemId, itemPortion, cid, itemTyp, mCatgId) {
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
                        html += `<option value="`+response[index]['IPCode']+`" rate="` + response[index]['ItmRate'] + `" offer_remark="` + response[index]['Remarks'] + `"  sdetcd="` + response[index]['SDetCd'] + `"  schcd="` + response[index]['SchCd'] + `"> ` + response[index]['Name'] + ` </option>`;
                    }
                    $('#item_portions').html(html);
                    $('#item_portions_custome').html(html);
                    $("#item_offer_remark").text(response[0]['Remarks']);
                    // $('#sdetcd').val(response[0]['SDetCd']);
                    // $('#schcd').val(response[0]['SchCd']);
                } else {
                    var html = `<option> ` + itemPortion + ` </option>`;
                    $('#item_portions').html(html);
                    $('#item_portions_custome').html(html);
                }
            }

            ajaxCall('<?= base_url('customer/get_item_portion_ajax') ?>', 'post', data, handleData);
        }

        function getCustOffer(itemId, itemNm, cid, itemTyp, mCatgId) {

            $.ajax({
                url: '<?= base_url('customer/offer_cust_ajax') ?>',
                type: 'post',
                data: {
                    getOrderData: 1,
                    itemId: itemId,
                    cid:cid,
                    itemTyp:itemTyp,
                    MCatgId:mCatgId
                },
                success: function(response) {
                    console.log(response);
                    if (response != 0) {
                        
                            $('#itemOffer').modal('show');
                            $('#carouselExampleCaptions').html(response);
                            $('.modal-title').html(itemNm + ' Offer');
                        
                    }
                }
            });
        }

        function getItemOffers(itemId, itemNm, cid, itemTyp, mCatgId) {

            $.ajax({
                url: '<?= base_url('customer/get_item_offer_ajax') ?>',
                type: 'post',
                data: {
                    getOrderData: 1,
                    itemId: itemId,
                    cid:cid,
                    itemTyp:itemTyp,
                    MCatgId:mCatgId
                },
                success: function(res) {
                    if(res.status == 'success'){
                        var data = res.response;
                        var temp = '<option value="0">Select Offer</option>';
                        for(i=0; i<data.length; i++){
                            temp += '<option value="'+data[i].SchCd+'" sdcode="'+data[i].SDetCd+'">'+data[i].SchNm+'-'+data[i].SchDesc+'</option>';
                        }
                        $('#schcd').html(temp);
                    }else{
                      alert(res.response);
                    }
                }
            });
        }

        $('#schcd').change(function(){
            var schcd = $(this).val();
            // console.log($('option:selected', this).attr('sdcode'));
            if(schcd > 0){
                $('#sdetcd').val($('option:selected', this).attr('sdcode'));
            }else{
                $('#sdetcd').val(0);
            }
        })

        $('#item_portions').change(function(){
            // alert("aaaa");
            var element = $(this);
            var rate = $('option:selected', this).attr('rate');
            $("#product-price").text(' ' + rate);
            var remarks = $('option:selected', this).attr('offer_remark');
            // var det = $('option:selected', this).attr('sdetcd');
            // var sch = $('option:selected', this).attr('schcd');
            // alert(remarks);
            if(remarks !== 'null'){
                // remarks comment by me
                // $("#item_offer_remark").text(remarks);
                // $("#sdetcd").val(det);
                // $('#schcd').val(sch);
            }else{
                $("#item_offer_remark").text('');
                // $("#sdetcd").val("");
                // $('#schcd').val("");
            }
        });


        var customizeModalVue = new Vue({
            el: "#customizeModal",
            data: {
                itemId: 0,
                itemTyp: 0,
                defaultPrice: 0,
                msg: "test",
                customForm: [],
                radioVal: [],
                radioRate: [],
                raidoGrpCd: [],
                reqIndex: [],
                radioName: [],
                checkboxVal: [],
                checkboxRate: [],
                checkboxItemCd: [],
                checkboxGrpCd: "",
                checkboxName: [],
                total: 0,
                itemName: '',
                itemPortion: '',
                custRemarks: '',
                qty: 1,
                del: 5,
                itemKitCd: '',
                takeAway: false,
                itemDescription: '',
                itemImg: '',
                item_prepTime: '',
                TaxType:''
            },
            methods: {
                addItem() {

                    var mandatory = false;
                    this.reqIndex.forEach(item => {
                        if (this.radioVal[item] == 0) {
                            alert(this.customForm[item].ItemGrpName + " is Mandatory");
                            mandatory = true;
                        }
                    });

                    if (mandatory) {
                        return;
                    }

                    var setCustomItem = false;

                    if (this.total == 0) {
                        if (confirm("Place Order For Item Without Customisation?")) {
                            setCustomItem = true;
                        }
                    } else {
                        setCustomItem = true;
                    }

                    if (setCustomItem) {

                        formData = new FormData();
                        formData.append('setCustomItem', 1);
                        formData.append('itemId', this.itemId);
                        formData.append('itemTyp', this.itemTyp);
                        formData.append('radioVal', this.radioVal);
                        formData.append('radioRate', this.radioRate);
                        formData.append('raidoGrpCd', this.raidoGrpCd);
                        formData.append('radioName', this.radioName);
                        formData.append('checkboxVal', this.checkboxVal);
                        formData.append('checkboxRate', this.checkboxRate);
                        formData.append('checkboxItemCd', this.checkboxItemCd);
                        formData.append('checkboxGrpCd', this.checkboxGrpCd);
                        formData.append('checkboxName', this.checkboxName);
                        formData.append('itemPortion', $('#item_portions').val());
                        formData.append('custRemarks', this.custRemarks);
                        formData.append('item_prepTime', this.item_prepTime);
                        formData.append('qty', this.qty);
                        formData.append('del', this.del);
                        formData.append('itemKitCd', this.itemKitCd);
                        formData.append('tax_type',$('#confirm-order').attr('tax_type'));
                        

                        if (this.takeAway) {
                            this.takeAway = 1;
                        } else {
                            this.takeAway = 0;
                        }

                        formData.append('takeAway', this.takeAway);
                        formData.append('rate', parseInt(this.total) + parseInt(this.defaultPrice));
                        formData.append('total', parseInt(this.total));

                        axios.post("<?php echo base_url('customer/item_details_ajax'); ?>", formData)
                            .then(response => {
                                if (response.data.status == 100) {
                                    alert(response.msg);
                                    window.location.reload();
                                } else if (response.data.status == 1) {
                                    console.log(response.data);
                                    window.location = `${response.data.redirectTo}`;
                                } else {
                                    console.log(response.data);
                                }
                            }).
                        catch(err => console.log(err));
                    }
                },
                calculateTotal(itemGrpCd, index, itemName, event) {
                    element = event.currentTarget;
                    var rate = element.getAttribute('rate');
                    // console.log(index);
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

                    this.getTotal();
                    // console.log(event.target.type);
                },

                getCustomItem(itemId, itemTyp, itemName, defaultPrice, itemPortion, itemKitCd, itemDescription, itemImg, item_prepTime, itemPortionCode, FID) {
                    this.itemId = itemId;
                    this.itemTyp = itemTyp;
                    this.defaultPrice = defaultPrice;
                    this.customForm = [];
                    this.radioVal = [];
                    this.radioRate = [];
                    this.reqIndex = [];
                    this.radioName = [];
                    this.checkboxVal = [];
                    this.checkboxRate = [];
                    this.checkboxItemCd = [];
                    this.checkboxName = [];
                    this.total = 0;
                    this.itemName = itemName;
                    this.itemPortion = itemPortion;
                    this.custRemarks = '';
                    this.qty = 1;
                    this.del = 5;
                    this.itemKitCd = itemKitCd;
                    this.itemDescription = itemDescription;
                    this.itemImg = itemImg;
                    this.item_prepTime = item_prepTime;
                    this.FID = FID;

                    var formData = new FormData();
                    formData.append('getCustomItem', 1);
                    formData.append('itemId', itemId);
                    formData.append('itemTyp', itemTyp);
                    formData.append('itemPortionCode', itemPortionCode);
                    formData.append('FID', FID);

                    axios.post("<?php echo base_url('customer/item_details_ajax'); ?>", formData)
                        .then(response => {
                            if (response.data.status == 100) {
                                alert(response.msg);
                                window.location.reload();
                            } else if (response.data.status == 1) {
                                console.log(response.data);
                                this.customForm = response.data.customDetails;
                                response.data.customDetails.forEach((item, index) => {
                                    if (item.GrpType == 1) {
                                        this.radioVal.push(0);
                                        this.radioRate.push(0);
                                        this.raidoGrpCd.push(0);
                                        this.radioName.push(0);
                                        if (item.Reqd == 1) {
                                            this.reqIndex.push(index);
                                        }
                                        // console.log(this.radioVal);
                                    } else {
                                        this.checkboxGrpCd = item.ItemGrpCd;
                                        item.Details.forEach(checkboxItem => {
                                            this.checkboxRate.push(0);
                                            this.checkboxVal.push(0);
                                            this.checkboxItemCd.push(checkboxItem.ItemOptCd);
                                            this.checkboxName.push(0);
                                        });
                                    }
                                });
                            } else {
                                console.log(response.data);
                            }
                        }).
                    catch(err => console.log(err));
                },

                getTotal() {
                    var radioTotal = 0;

                    this.radioRate.forEach(item => {
                        radioTotal += parseInt(item);
                    });

                    var checkTotal = 0;

                    this.checkboxRate.forEach(item => {
                        checkTotal += parseInt(item);
                    });

                    this.total = parseInt(radioTotal) + parseInt(checkTotal);
                },
                selectPortion(){
                    var element = $("option:selected", '#item_portions');
                    var myTag = $('#item_portions_custome option:selected').attr('rate');
                    var itemPortionCode = $('#item_portions_custome option:selected').attr('value');
                    this.total = 0;
                    this.defaultPrice = myTag;
                    i=0;
                    while(i<this.radioVal.length) {
                        this.radioRate[i] = 0;
                        i++;
                    };

                    i=0;
                    while(i<this.checkboxVal.length) {
                        this.checkboxRate[i] = 0;
                        i++;
                    };
                    // $("#product-price").text(' ' + myTag);
                    $('input:checked').prop('checked', false);
                    $('input:radio:checked').prop('checked', false);

                    this.getCustomItem(this.itemId, this.itemTyp, this.itemName, this.defaultPrice, this.itemPortion, this.itemKitCd, this.itemDescription, this.itemImg, this.item_prepTime, itemPortionCode, FID);
                }
            }
        });

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
            var custRemarks = $("#cust-remarks").val();
            var qty = $("#qty-val").val();
            var takeAway = 0;
            var serveTime = $("#serve-val").val();
            var itmrate = $("#product-price").text();
            var itemPortionText = itemPortion;
            var prepration_time = $('#item-prepare-time').attr('time');
            var tax_type = $('#confirm-order').attr('tax_type');
            var TblTyp = $('#confirm-order').attr('tbltyp');
            var sdetcd = $('#sdetcd').val();
            var schcd = $('#schcd').val();

            if ($("#take-away").prop('checked') == true) {
                takeAway = 1;
            }
            // return false;
            $.ajax({
                url: "<?php echo base_url('customer/item_details_ajax'); ?>",
                type: "post",
                data: {
                    orderToCart: 1,
                    // confirmOrder: 1,
                    itemId: itemId,
                    custRemarks: custRemarks,
                    qty: qty,
                    itemPortionText: $('#item_portions').val(),
                    takeAway: takeAway,
                    serveTime: serveTime,
                    itemKitCd: itemKitCd,
                    itmrate: itmrate,
                    prepration_time: serveTime,
                    tax_type:tax_type,
                    sdetcd:sdetcd,
                    schcd:schcd,
                    TblTyp:TblTyp
                },
                dataType: "json",
                beforeSend: function() {
                    $('.loder_bg').css('display','flex');
                },
                success: (response) => {
                    if (response == 1) {
                        window.location.assign("cust_registration.php");
                        // not implement 
                    }
                    $("#cust-remarks").val('');
                    $("#qty-val").val(1);
                    $('#minus-qty').prop('disabled', true);
                    $("#take-away").prop('checked', false);
                    $("#serve-val").val(5);
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

        
    </script>
</html>



