<?php $this->load->view('gestion/common/head'); ?>
<style>
    html {
        height: 100%;
    }

    body {
        background-size: cover;
        background-image: url('<?php echo base_url(); ?>assets/img/background-pielagos.jpg');
        /*display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;*/
        padding: 5vw;
    }


    #entatami {
        font-size: 4vw;
        position: absolute;
        top: 5vw;
        left: 5vw;
        right: 5vw;
        text-align: center;
        text-transform: capitalize;
        background-color: #ffffff;
        border: 2px solid #cccccc;
        padding-top: 1rem;
        padding-bottom: 1rem;
        height: 14vh;
    }
    #clasificaionkatafinal{
        width: 90vw;
        position: absolute;
        top: calc(10vw + 14vh);
        background-color: #ffffff;
    }
    .table th, .table td{
        font-size: 3vw;
        
    }
</style>



<body id="clasificaicon" onClick="toggleFullScreen()">

        <div id="entatami" style="display:none"></div>
        
            <table class="table table-striped" id="clasificaionkatafinal" data-competicion="<?php echo $competicion_torneo_id; ?>">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="columnfixed">Deportista</th>
                        <th>Equipo</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody id="clasificacion_final_competicion"></tbody>
            </table>

    <?php $this->load->view('gestion/common/scripts'); ?>
</body>

</html>