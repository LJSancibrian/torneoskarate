<?php $this->load->view('gestion/common/head');?>
<body>
    
	<div class="wrapper <?php if($this->user->group->id > 3){ echo 'sidebar_minimize';} ?>">
        <?php $this->load->view('gestion/common/navbar');?>
        <?php $this->load->view('gestion/common/sidebar');?>
        <div class="main-panel">
			<div class="content">
            <?php 
            if($this->user->group->id > 3){
                if(isset($page_header)){ // <div class="panel-header bg-primary-gradient bg-image">?>
                    <div class="panel-header bg-image"  <?php if($_SERVER['HTTP_HOST'] != 'localhost'){echo 'style="background-image: url(/assets/img/fondo1.jpg);"';}?>>
                        <div class="page-inner py-5">
                            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                                <div>
                                    <h2 class="text-white pb-2 fw-bold text-center text-uppercase"><?php echo $page_header;?></h2>
                                    <div class="text-white">
                                    <?php //echo $this->utilidades->breadcrumb();?>
                                    </div>
                                </div>
                                <?php /* <div class="ml-md-auto py-2 py-md-0">
                                    <a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a>
                                    <a href="#" class="btn btn-secondary btn-round">Add Customer</a>
                                </div> */?>
                            </div>
                        </div>
                    </div>
                    <div class="page-inner mt--5">  
                <?php }else{ printr($page_header);?>
                    <div class="page-inner">  
                <?php } 
            }else{ ?>
                <div class="page-inner">  
                    <?php if(isset($page_header)){?>
                        <div class="page-header">
                            <h4 class="page-title"><?php echo $page_header;?></h4>
                            <?php echo $this->utilidades->breadcrumb();?>
                        </div>
                    <?php }
            }?>
                    <?php if(isset($view)){
                        if(is_array($view)){
                            foreach ($view as $key => $view_item) {
                                $this->load->view($view_item);
                            }
                        }else{
                            $this->load->view($view);
                        }
                    }?>
                </div>
            </div>
            <?php $this->load->view('gestion/common/footer');?>
        </div>
    </div>
    <?php $this->load->view('gestion/common/scripts');?>
</body>
</html>