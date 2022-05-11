<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        p {
            font-size: 20px;
        }

        p>small {
            font-size: 15px;
        }

        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 18px;
            text-transform: capitalize;
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
        .bg-primary {
            background-color: #CCCCCC;
        }
        .bg-secondary {
            background-color: #008081;
        }
        .text-white {
            color: #FFFFFF;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 2px;
            border: 1px solid #cccccc;
        }
        .table tr {
            border: 1px solid !important;
            border-color: #008081 !important;
        }
        .table thead th {
            border-bottom-width: 2px;
            font-weight: 600;
            border-color: #008081;
            background-color: #008081;
            color: #ffffff;
        }
        .table td, .table th {
            font-size: 16px;
            border-color: #008081 !important;
            border: 1px solid #008081;
            padding: 5px 15px;
           
            vertical-align: middle !important;
        }

       /* td {
         height: 15px;
        }*/

        h2,
        h3,
        h4,
        h5 {
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .page-number:before {  content: counter(page);}
    </style>
</head>

<body style="word-spacing:normal;padding:50px 0px 0px 0px;">
    <footer>
        <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?> - página <span class="page-number"></span>
    </footer>
    <header>
    <h2 class="bg-primary text-white" style="padding: 5px 15px; text-transform:uppercase">
        <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?>
    </h2>
    </header>

    <caption>#: Nº de combate. R: Ronda. G: Grupo. AKA: Rojo. AO: Azul. (*)Espacio inferior para observaciones.</caption>
    <table class="table" id="tablakata">
        <thead>
            <tr>
                <th style="padding: 2px;">#</th>
                <th style="padding: 2px;">R</th>
                <th style="padding: 2px;">G</th>
                <th>AKA</th>
                <th></th>
                <th></th>
                <th>AO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grupos = [];
            $grupos_id = [];
            foreach ($matches as $key => $value) { ?>
                <tr height="50px">
                    <td style="padding: 2px !important;text-align:center;"><?php echo $value->match_id; ?></td>
                    <td style="padding: 2px !important;text-align:center;"><?php echo $value->ronda; ?></td>
                    <td style="padding: 2px !important;text-align:center;"><?php echo $value->grupo; ?></td>
                    <td style="width:200px;"><div style="height:30px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($value->rojo->nombre))); ?></div></td>
                    <td></td>
                    <td></td>
                    <td style="width:200px;"><div style="height:30px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($value->azul->nombre))); ?></div></td>
                </tr>
                <tr height="50px">
                    <td colspan="7"><div style="height:30px;"></div></td>
                </tr>
            <?php 
                if(!in_array($value->grupo, $grupos_id)){
                    $grupos_id[] = $value->grupo;
                    $grupos[$value->grupo] = [];
                }
                if(!array_key_exists($value->user_rojo, $grupos[$value->grupo])){
                    $grupos[$value->grupo][$value->user_rojo] = $value->rojo->nombre;
                }
                if(!array_key_exists($value->user_azul, $grupos[$value->grupo])){
                    $grupos[$value->grupo][$value->user_azul] = $value->azul->nombre;
                }
            } ?>
        </tbody>
    </table>

            
    <div style="width: 100%; page-break-inside: avoid; margin-top: 20px;">
            <h3 class="bg-primary text-white">Clasificación por grupos: Puntos totales</h3>
            <span>V: Victorias. PF: Puntos a favor. PC: Puntos en contra. S: Shenshu. H: Hantei. CF: Clasificación final.</span>
            <?php foreach ($grupos as $key => $grupo) { ?>
                <table class="table" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th class="text-left">Grupo <?php echo $key;?></th>
                            <th class="text-left">V</th>
                            <th class="text-left">PF</th>
                            <th class="text-left">PC</th>
                            <th class="text-left">S</th>
                            <th class="text-left">H</th>
                            <th class="text-left">CF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grupo as $k => $value) { ?>
                            <tr>
                                <td class="text-left" style="width:230px;"><div style="height:30px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($value))); ?></div></td>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }?>
    </div>

    <div style="width: 100%; margin-top: 20px;">
        <h3 class="bg-secondary text-white" style="padding-left: 10px;">Eliminatorias</h3>

        <?php  
        function clasificado($string, $eliminatorias){
            $primer = $string[0];
            switch ( $primer) {
                case 'g':
                    $grupo = explode('|',substr($string, 1))[0];
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">'.'Ganador Grupo '.$grupo.'</span>';
                    break;
                
                case 'm':
                    $posicion = explode('|',substr($string, 1))[0];
                    $explode = explode('|', $string);
                    $puesto = end($explode);
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">'.$puesto.'º mejor '.$posicion.'º clasificado</span>';
                    break;
                case 'r':
                    $last = substr($string, -1, 1);
                    $puesto = ($last == '-') ? 'Perdedor' : 'Ganador';
                    $explode = explode('|', $string);
                    $ronda = str_replace('r', '', $explode[0]);
                    $nmatch  =  ($last == '-') ? str_replace($last, '', end($explode)) : end($explode);
                    $combate = $eliminatorias[$ronda][$nmatch - 1];
                    echo  '<span style="font-size: 12px; color: #CCCCCC;">'.$puesto.' '.$combate->match_id.'</span>';
                  
                default:
                    
                    break;
            }
        }
        foreach ($eliminatorias as $key => $eliminatoria) {
            if ($key  == count($eliminatorias)) {
                $ronda = 'FINAL y 3er y 4º PUESTO';
            } elseif ($key  == count($eliminatorias) - 1) {
                $ronda = 'SEMI - FINAL';
            } else {
                $ronda = 'Ronda ' . $key;
            } ?>

            <h4 class="bg-primary text-white p-2 mb-3" style="padding-left: 10px;"><?php echo $ronda; ?></h4>
            <table class="table" id="tablakata">
                <thead>
                    <tr>
                        <th style="padding: 2px;">#</th>
                        <th>AKA</th>
                        <th></th>
                        <th></th>
                        <th>AO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($eliminatoria as $value) {?>
                        <tr height="50px">
                            <td style="padding: 2px !important;text-align:center;"><?php echo $value->match_id; ?></td>
                            <td style="width:200px;"><div style="height:30px;"><?php echo clasificado($value->parent_rojo, $eliminatorias); ?></div></td>
                            <td></td>
                            <td></td>
                            <td style="width:200px;"><div style="height:30px;"><?php echo clasificado($value->parent_azul, $eliminatorias); ?></div></td>
                        </tr>
                        <tr height="50px">
                            <td colspan="5"><div style="height:30px;"></div></td>
                        </tr>

                    <?php } ?>
                </tbody>
                        </table>
        <?php } ?>
    </div>
</body>

</html>