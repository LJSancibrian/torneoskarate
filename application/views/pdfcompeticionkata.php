<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title><?php echo strtoupper($competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel); ?> - <?php echo strtoupper(($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto')); ?>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        @font-face {
            font-family: 'Lato';
            src: url('<?php echo FCPATH ?>assets/admin/fonts/Lato/Lato-Regular.ttf') format('truetype');  /* Formato TTF */
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'LatoBlack';
            src: url('<?php echo FCPATH ?>assets/admin/fonts/Lato/Lato-Black.ttf') format('truetype');  /* Formato TTF */
            font-weight: bolder;
        }
        @font-face {
            font-family: 'LatoLight';
            src: url('<?php echo FCPATH ?>assets/admin/fonts/Lato/Lato-Light.ttf') format('truetype');  /* Formato TTF */
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'Lato', Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        p {
            font-size: 14px;
        }

        p>small {
            font-size: 12px;
        }

        .header {
            position: fixed;
            width: 100%;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
            font-family: 'LatoBlack', Arial, Helvetica, sans-serif;
            font-weight: bolder;
            border: 4px solid #008081;
            color: #008081;
            vertical-align: middle;
        }

        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 12px;
            text-transform: capitalize;
        }

        .bg-secondary {
            background-color: #008081;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 2px;
            border: 0px dotted #008081;
            page-break-inside: avoid;
            margin-top: 20px; 
        }

        .table thead th {
            border-bottom-width: 2px;
            font-weight: bolder;
            border-color: #008081;
            /*background-color: #008081;
            color: #ffffff;*/
            color: #008081;
            text-transform: uppercase;
            font-family: 'LatoBlack', Arial, Helvetica, sans-serif;

        }

        .table tr td,
        .table tr th {
            font-size: 16px;
            border-color: #008081 !important;
            border: 1px dashed #008081;
            padding: 5px 15px;
            vertical-align: middle !important;
        }
        .table tr th {
            border: 4px solid #008081;
            padding: 4px 14px;
        }

        h2,
        h3,
        h4,
        h5 {
            margin-bottom: 10px;
            padding-bottom: 5px;
            padding-top: 5px;
            font-family: 'LatoBlack', Arial, Helvetica, sans-serif;
            font-weight: bolder;
        }

        .page-number:before {
            content: counter(page);
        }

        .page_break {
            page-break-after: always;
        }

        .page_break:last-child {
            page-break-after: avoid;
        }

        .text-vertical {
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            transform: rotate(-90deg);
        }
    </style>
</head>

<body style="word-spacing:normal;padding:10px 0px 0px 0px;">
    <footer>
        <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?> - página <span class="page-number"></span>
    </footer>
    <table class="header">
        <tr>
            <th style="padding-left: 20px; text-align: left;">
                <?php echo $competicion->modalidad ; ?>
            </th>
            <th style="padding-right: 20px; text-align: right;">
                <?php echo $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?>
            </th>
        </tr>
    </table>

    <div class="page_break" style="padding-bottom:10px;">
        <h2>Rondas</h2>
        <?php $grupo = 0;
        foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
            <?php if($value->grupo != $grupo){ ?>
                <?php if($key > 0){ ?>
                            </tbody>
                        </table>
                <?php } 
                $grupo = $value->grupo; ?>
                <table class="table" id="tablAOta_<?=$grupo?>">
                    <thead class="bg">
                        <tr >
                            <th>Grupo <?=$grupo?></th>
                            <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                                <th colspan="2">Ronda <?=$i?></th>
                            <?php } ?>
                            <th>Total</th>
                        </tr>
                        <tr >
                            <th>Deportista</th>
                            <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                                <th>J1</th>
                                <th>J2</th>
                            <?php } ?>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php } ?>
            <tr >
                <td><?= mb_convert_case($value->first_name . ' ' . $value->last_name, MB_CASE_TITLE, 'UTF-8'); ?></td>
                <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                    <td></td>
                    <td></td>
                <?php } ?>
                <td></td>
            </tr>
            <?php if($key == count($ordenparticipacion['ordenados']) - 1){ ?>
                    </tbody>
                </table>
            <?php }
		} ?>


        <?php /*
        <table class="table" id="tablAOta">
            <thead class="bg">
                <tr >
                    
                    <th></th>
                    <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
						<th colspan="2">Ronda <?=$i?></th>
					<?php } ?>
                    <th>Total</th>
                </tr>
                <tr >
                    <th>Deportista</th>
                    <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
						<th>J1</th>
						<th>J2</th>
					<?php } ?>
                    <th>TOTAL</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                    <tr >
                        <td><?= mb_convert_case($value->first_name . ' ' . $value->last_name, MB_CASE_TITLE, 'UTF-8'); ?></td>
                        <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                            <th></th>
                            <th></th>
                        <?php } ?>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        */ ?>
    </div>

    <div class="page_break" style="padding-bottom:10px;">
        <h2>Final</h2>
        <table class="table" id="tablAOta">
            <thead class="bg">
                <tr >
                    <th>Deportista</th>
                    <th>J1</th>
                    <th>J2</th>
                    <th>J3</th>
                    <th>Total</th>
                    <th>Posicion final</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 8; $i++) { ?>
                    <tr >
                        <td style="width:150px;">
                            <div style="height:35px;"></div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>