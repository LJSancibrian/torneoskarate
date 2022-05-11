<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <!-- 
    Essential Scripts
    =====================================-->
    <!-- Main jQuery -->
    <script src="<?php echo base_url();?>assets/public/plugins/jquery/jquery.min.js"></script>
    <!-- Google Map -->
    <?php /* <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu5nZKbeK-WHQ70oqOWo-_4VmwOwKP9YQ"></script>
    <script  src="<?php echo base_url();?>assets/public/plugins/google-map/gmap.js"></script> */ ?>

    <!-- Form Validation -->
    <script src="<?php echo base_url();?>assets/public/plugins/form-validation/jquery.form.js"></script> 
    <script src="<?php echo base_url();?>assets/public/plugins/form-validation/jquery.validate.min.js"></script>
    
    <!-- Bootstrap4 -->
    <script src="<?php echo base_url();?>assets/public/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Parallax -->
    <script src="<?php echo base_url();?>assets/public/plugins/parallax/jquery.parallax-1.1.3.js"></script>
    <!-- lightbox -->
    <script src="<?php echo base_url();?>assets/public/plugins/lightbox2/dist/js/lightbox.min.js"></script>
    <!-- Owl Carousel -->
    <script src="<?php echo base_url();?>assets/public/plugins/slick/slick.min.js"></script>
    <!-- filter -->
    <script src="<?php echo base_url();?>assets/public/plugins/filterizr/jquery.filterizr.min.js"></script>
    <!-- Smooth Scroll js -->
    <script src="<?php echo base_url();?>assets/public/plugins/smooth-scroll/smooth-scroll.min.js"></script>
    <?php if(isset($js_files) && is_array($js_files) && count($js_files)>0){
        foreach ($js_files as $kjs => $file) {
            if($file != ''){
                echo '<script src="'.$file.'?v_'.$this->config->item('version').'"></script>'."\n";
            }
        }
    }?>
    <!-- Custom js -->
    <script src="<?php echo base_url();?>assets/public/js/script.js?v_<?php echo $this->config->item('version');?>"></script>
   