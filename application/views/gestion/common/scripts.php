<script src="<?php echo base_url(); ?>assets/admin/js/core/jquery.3.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/core/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/core/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/chart.js/chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/chart-circle/circles.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/DataTables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/DataTables/settings.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/moment-with-locales.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/tempusdominus-bootstrap-4.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/html2canvas.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/FileSaver.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/atlantis.min.js"></script>

<?php if (isset($js_files) && is_array($js_files) && count($js_files) > 0) {
    foreach ($js_files as $kjs => $file) {
        if ($file != '') {
            echo '<script src="' . $file . '?v_' . time() . '"></script>' . "\n";
        }
    }
} ?>
<script src="<?php echo base_url(); ?>assets/admin/js/theme.js?v_<?php echo time(); ?>"></script>
<?php if (isset($error) || $this->session->flashdata('error') != '') {
    
    $error = ($this->session->flashdata('error') != '') ? $this->session->flashdata('error') : $error;
    printr($error);
    $error = preg_replace("/\r|\n/", "", $error); ?>
    <script>
        swal.fire({
            icon: 'error',
            html: '<?php echo $error; ?>',
        })
    </script>
<?php $this->session->unset_userdata('error');} ?>

<?php if (isset($info)) {
    $info = preg_replace("/\r|\n/", "", $info); ?>
    <script>
        swal.fire({
            icon: 'info',
            html: '<?php echo $info; ?>',
        })
    </script>
<?php $this->session->unset_userdata('info'); } ?>

<?php if (isset($success)) {
    $success = preg_replace("/\r|\n/", "", $success); ?>
    <script>
        swal.fire({
            icon: 'success',
            html: '<?php echo $success; ?>',
        })
    </script>
    
<?php $this->session->unset_userdata('success'); } ?>