<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body{
            font-family: Arial, Helvetica, sans-serif;
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
            height: 50px;
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            font-weight: 900;
            border: 1px solid #008081;
            color: #008081;
            vertical-align:middle;
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
            border: 0px solid #cccccc;
        }

        .table thead th {
            border-bottom-width: 2px;
            font-weight: 600;
            border-color: #008081;
            background-color: #008081;
            color: #ffffff;
        }
        .table tr.border td, .table tr.border th{
            font-size: 16px;
            border-color: #008081 !important;
            border: 1px solid #008081;
            padding: 5px 15px;
           
            vertical-align: middle !important;
        }
        .border-y{
            border-top: 1px solid #008081;
            border-bottom: 1px solid #008081;
        }

        h2,
        h3,
        h4,
        h5 {
            margin-bottom: 10px;
            padding-bottom: 5px;
            padding-top: 5px;
        }
        .page-number:before {  content: counter(page);}
        .page_break { page-break-after: always; }
        .page_break:last-child {
            page-break-after: avoid;
        }
    </style>
</head>

<body style="word-spacing:normal;padding:50px 0px 0px 0px;">
    <footer>
        <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?> - página <span class="page-number"></span>
    </footer>
    <table class="header">
        <tr>
            <td>
            <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?>
            </td>
        </tr>
    </table>

    <?php foreach ($matches as $key => $grupo) {?>
        <div class="page_break" style="border: 1px solid #008081; padding-left:10px; padding-right: 10px;">
            <h2>Grupo <?php echo $key;?></h2>
            <table class="table">
                <thead>
                    <tr class="border">
                        <th style="padding: 2px;">#</th>
                        <th style="padding: 2px;">R</th>
                        <th>AKA</th>
                        <th></th>
                        <th></th>
                        <th>AO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $usuers = [];
                    foreach ($grupo as $m => $match) { ?>
                        <tr height="50px" class="border">
                            <td style="padding: 2px !important;text-align:center;" class="border"><?php echo $match->match_id; ?></td>
                            <td style="padding: 2px !important;text-align:center;"><?php echo $match->ronda; ?></td>
                            <td style="width:200px;"><div style="height:35px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($match->rojo->nombre))); ?></div></td>
                            <td></td>
                            <td></td>
                            <td style="width:200px;"><div style="height:35px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($match->azul->nombre))); ?></div></td>
                        </tr>
                        <tr height="30px">
                            <td colspan="6"><div style="height:10px;"></div></td>
                        </tr>
                    <?php 
                    if(!array_key_exists($match->user_rojo, $usuers)){
                        $usuers[$match->user_rojo] = $match->rojo->nombre;
                    }
                    if(!array_key_exists($match->user_azul, $usuers)){
                        $usuers[$match->user_azul] = $match->azul->nombre;
                    }
                
                } ?>
                </tbody>
            </table>
            
            <table class="table" style="margin-bottom: 10px;">
                <thead>
                    <tr class="border">
                        <th class="text-left">Clasificación Grupo <?php echo $key;?></th>
                        <th class="text-left">V</th>
                        <th class="text-left">PF</th>
                        <th class="text-left">PC</th>
                        <th class="text-left">S</th>
                        <th class="text-left">H</th>
                        <th class="text-left">CF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuers as $k => $value) { ?>
                        <tr class="border">
                            <td class="text-left" style="width:230px;"><div style="height:35px;"><?php echo ucwords(str_replace('<br>', '<br> ', strtolower($value))); ?></div></td>
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
            <span>V: Victorias. PF: Puntos a favor. PC: Puntos en contra. S: Shenshu. H: Hantei. CF: Clasificación final.</span>
        </div>
    <?php } ?> 

    <div class="page_break" style="border: 1px solid #008081; padding-left:10px; padding-right: 10px;">
        <h2>Eliminatorias</h2>
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

            <h4 class="border-y" style="padding-left: 10px;"><?php echo $ronda; ?></h4>
            <table class="table" id="tablakata">
                <thead>
                    <tr class="border">
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
                         <tr class="border">
                            <td style="padding: 2px !important;text-align:center;"><?php echo $value->match_id; ?></td>
                            <td style="width:200px;"><div style="height:35px;"><?php echo clasificado($value->parent_rojo, $eliminatorias); ?></div></td>
                            <td></td>
                            <td></td>
                            <td style="width:200px;"><div style="height:35px;"><?php echo clasificado($value->parent_azul, $eliminatorias); ?></div></td>
                        </tr>
                        <tr height="50px">
                            <td colspan="5"><div style="height:10px;"></div></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

        <?php /*
    <caption>#: Nº de combate. R: Ronda. AKA: Rojo. AO: Azul. (*)Espacio inferior para observaciones.</caption>
    <table class="table" id="tablakata">
        <thead>
            <tr>
                <th style="padding: 2px;">#</th>
                <th style="padding: 2px;">R</th>
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

    
    */ ?>
</body>

</html>