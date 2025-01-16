<?php $this->load->view('gestion/common/head'); ?>
<style>
    html {
        height: 100%;
    }

    body {
        background-size: cover;
        background-image: url('<?php echo base_url(); ?>assets/img/background-pielagos.jpg');
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 5vw;
    }


    #entatami {
        font-size: 7vw;
        text-align: center;
        text-transform: capitalize;
        padding-top: 1rem;
        padding-bottom: 1rem;

    }

    #clasificaionkatafinal {
        width: 90vw;
        position: absolute;
        top: calc(10vw + 14vh);
        background-color: #ffffff;
    }

    .table th,
    .table td {
        font-size: 3vw;

    }

    .tracking-in-expand {
        -webkit-animation: tracking-in-expand 0.9s linear both;
        animation: tracking-in-expand 0.9s linear both;
    }

    @-webkit-keyframes tracking-in-expand {
        0% {
            letter-spacing: -0.5em;
            opacity: 0;
        }

        40% {
            opacity: 0.6;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes tracking-in-expand {
        0% {
            letter-spacing: -0.5em;
            opacity: 0;
        }

        40% {
            opacity: 0.6;
        }

        100% {
            opacity: 1;
        }
    }


</style>

<body id="clasificaicon" onClick="toggleFullScreen()">

    <div id="entatami" style="display:none"></div>

    <table class="table table-striped d-none" id="clasificaionkatafinal" data-competicion="<?php echo $competicion_torneo_id; ?>">
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