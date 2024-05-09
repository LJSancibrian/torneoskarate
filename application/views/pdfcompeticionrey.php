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


    <?php foreach ($grupos as $key => $grupo) { ?>
        <div class="page_break" style="padding-bottom:10px;">
            <h3>Grupo <?= $key ?></h3>
            <table class="table" id="tablAOta">
                <thead class="bg">
                    <tr class="border">
                        <th>Deportista</th>
                        <th>Penal.</th>
                        <th>V</th>
                        <th>E</th>
                        <th>D</th>
                        <th>Pt.F</th>
                        <th>Com.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupo as $k => $value) { ?>
                        <tr class="border">
                            <td><?= mb_convert_case($value->first_name . ' ' . $value->last_name, MB_CASE_TITLE, 'UTF-8') ?></td>
                            <td></td>
                            <td></td>
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
    <?php } ?>


    <div class="page_break">
        <h3>Eliminatorias</h3>
        <?php
        function clasificado($string, $eliminatorias)
        {
            $primer = $string[0];
            switch ($primer) {
                case 'g':
                    $tipo = explode('|', $string)[1];
                    switch ($tipo) {
                        case 1:
                            $posicion = 'Primero';
                            break;
                        case 2:
                            $posicion = 'Segundo';
                            break;
                        case 3:
                            $posicion = 'Tercero';
                            break;
                        case 4:
                            $posicion = 'Cuarto';
                            break;
                        case 5:
                            $posicion = 'Quinto';
                            break;
                        case 6:
                            $posicion = 'Sexto';
                            break;
                        default:
                        $posicion = '';
                            break;
                    }
                    $tipo = ($tipo == 1) ? 'Primero' : 'Segundo';


                    $grupo = explode('|', substr($string, 1))[0];
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">' . $posicion . ' Grupo ' . $grupo . '</span>';
                    break;

                case 'm':
                    $posicion = explode('|', substr($string, 1))[0];
                    $explode = explode('|', $string);
                    $puesto = end($explode);
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">' . $puesto . 'º mejor ' . $posicion . 'º clasificado</span>';
                    break;
                case 'r':
                    $last = substr($string, -1, 1);
                    $puesto = ($last == '-') ? 'Perdedor' : 'Ganador';
                    $explode = explode('|', $string);
                    $ronda = str_replace('r', '', $explode[0]);
                    $nmatch  =  ($last == '-') ? str_replace($last, '', end($explode)) : end($explode);
                    $combate = $eliminatorias[$ronda][$nmatch - 1];
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">' . $puesto . ' ' . $combate->match_id . '</span>';

                default:

                    break;
            }
        }
        foreach ($eliminatorias as $key => $eliminatoria) {
            if ($key  == count($eliminatorias)) {
                $ronda = '3er y 4º PUESTO';
            } elseif ($key  == count($eliminatorias) - 1) {
                $ronda = 'SEMI - FINAL';
            } else {
                $ronda = 'Ronda ' . $key;
            } ?>
            <div style="padding-bottom:10px;">
                <table style="width: 103%;  margin-left: -3%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="text-align:left; font-size:14px;">AO</th>
                            <th colspan="2"><?php echo $ronda; ?></th>
                            <th style="text-align:right; font-size:14px;">AKA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($eliminatoria as $k => $value) {
                            if ($key  == count($eliminatorias) && $k == count($eliminatoria) - 2) {
                                $final = $value;
                            } else { ?>
                                <tr>
                                    <td style="text-align:center; width: 10px; font-size: 12px;" class="text-vertical"><?php echo $value->match_id; ?></td>
                                    <td style="border: 1px solid #008081; height:40px; vertical-align:bottom; text-align: right; padding-right:4px;">
                                        <?php echo clasificado($value->parent_azul, $eliminatorias); ?>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>

                                    </td>
                                    <td style="border: 1px solid #008081; height:40px; width: 40px;vertical-align:top;">
                                        <div style="height:8px; width:8px; border: 1px solid #008081; border-radius: 5px; display:inline-block;"></div>
                                    </td>
                                    <td style="border: 1px solid #008081; height:40px; width: 40px;vertical-align:top;text-align: right;">
                                        <div style="height:8px; width:8px; border: 1px solid #008081; border-radius: 5px; display:inline-block;"></div>

                                    </td>
                                    <td style="border: 1px solid #008081; height:40px; vertical-align:bottom; text-align: left; padding-left:4px;">
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                        <?php echo clasificado($value->parent_rojo, $eliminatorias); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="height:5px;">
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php  } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (isset($final)) {
            $value = $final; ?>
            <div style="padding-bottom:10px;">
                <table style="width: 103%;  margin-left: -3%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="text-align:left; font-size:14px;">AO</th>
                            <th colspan="2">FINAL</th>
                            <th style="text-align:right; font-size:14px;">AKA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align:center; width: 10px; font-size: 12px;" class="text-vertical"><?php echo $value->match_id; ?></td>
                            <td style="border: 1px solid #008081; height:40px; vertical-align:bottom; text-align: right; padding-right:4px;">
                                <?php echo clasificado($value->parent_rojo, $eliminatorias); ?>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>

                            </td>
                            <td style="border: 1px solid #008081; height:40px; width: 40px;vertical-align:top;">
                                <div style="height:8px; width:8px; border: 1px solid #008081; border-radius: 5px; display:inline-block;"></div>
                            </td>
                            <td style="border: 1px solid #008081; height:40px; width: 40px;vertical-align:top;text-align: right;">
                                <div style="height:8px; width:8px; border: 1px solid #008081; border-radius: 5px; display:inline-block;"></div>

                            </td>
                            <td style="border: 1px solid #008081; height:40px; vertical-align:bottom; text-align: left; padding-left:4px;">
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <div style="height:8px; width:8px; border: 1px solid #008081; display:inline-block;"></div>
                                <?php echo clasificado($value->parent_azul, $eliminatorias); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="height:5px;">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body>

</html>