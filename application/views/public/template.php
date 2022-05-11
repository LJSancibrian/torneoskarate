<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('public/common/head'); ?>

<body id="body">
    <?php // $this->load->view('public/common/preload'); ?>
    <div id="contenedor">
    <?php $this->load->view('public/common/header'); ?>
    <?php if (isset($page_header)) {
        $this->load->view('public/secciones/singlepageheader');
    } ?>
    <?php if (isset($view)) {
        if (is_array($view)) {
            foreach ($view as $key => $view_item) {
                $this->load->view($view_item);
            }
        } else {
            $this->load->view($view);
        }
    } ?>
    </div>
    <?php $this->load->view('public/common/footer'); ?>
    <?php $this->load->view('public/common/scripts'); ?>
</body>
</html>