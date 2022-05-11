
<div class="mt--3">
    <?php if($this->ion_auth->in_group([1,2,3])){
        $this->load->view('gestion/torneos/categorias_form_2');
    }else{
        $this->load->view('gestion/torneos/categorias_list');
    }?>
</div>