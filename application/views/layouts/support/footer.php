<style>
    .footer{
        border-top :1px solid #1f233c1f;
    }
</style>
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                copyright - <script>document.write(new Date().getFullYear())</script> © <a href="#">Eat-Out</a>
            </div>
            <div class="col-sm-6">
                <input type="hidden" value="<?php echo $this->session->userdata('site_lang'); ?>" id="site_lang">
                <!-- <div class="text-sm-right d-none d-sm-block">
                    Developed <i class="mdi mdi-heart text-danger"></i> by vijayyadav132200@gmail.com
                </div> -->
            </div>
        </div>
    </div>
</footer>