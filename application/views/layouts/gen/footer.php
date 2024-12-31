<style>
    .btnScanner{
        height: 50px !important;
        width: 50px !important;
        line-height: 32px;
    }
    
    .ml-1, .mx-1 {
        margin-left: 1rem !important;
    }

    .btn-skype{
        background: #0e0e0e !important;
    }
    .btn-youtube{
        background: #ee0004 !important;   
    }
    .btn-success{
        background: #00c207 !important;      
    }
</style>
<div class="container fixed-bottom">
    <div class="row" style="background: #fff;padding: 10px;">
        <div class="col-md-12 text-center">
            <div class="button-list btn-social-icon">
                <a href="<?= base_url('general/profile'); ?>" class="btn btn-youtube btn-rounded ml-1" title="Profile">
                    <i class="far fa-user"></i>
                </a>

                <a href="<?= base_url('general/bill_history'); ?>" class="btn btn-success btn-rounded ml-1" title="History">
                    <i class="fas fa-scroll"></i>
                </a>

                <a href="<?= base_url('general/qrscan'); ?>" class="btn btn-skype btn-rounded ml-1 btnScanner" title="Scanner">
                    <i class="mdi mdi-qrcode-scan" style="font-size: 25px;"></i>
                </a>

                <a href="<?= base_url('general/rest_details'); ?>" class="btn btn-success btn-rounded ml-1" title="Details">
                    <i class=" fas fa-eye"></i>
                </a>

                <button type="button" class="btn btn-youtube  btn-rounded  ml-1" title="Discover">
                    <i class="fas fa-binoculars"></i>
                </button>                
            </div>
        </div>
    </div>
</div>