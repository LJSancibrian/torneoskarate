<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title><?php echo strtoupper($competicion); ?></title>
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
            height: 40px;
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
            margin-bottom: 5px;
            padding-bottom: 5px;
            padding-top: 0px;
        }
        .page-number:before {  content: counter(page);}
        .page_break { page-break-after: always; }
        .page_break:last-child {
            page-break-after: avoid;
        }
        .nopagebreak{
            margin-bottom: 30px;
        }
    </style>
</head>

<body style="word-spacing:normal;padding:30px 0px 0px 0px;">
    
    <table class="header">
        <tr>
            <td>
            </td>
        </tr>
    </table>

    <?php if(isset($matches)){ 
        $nmatch = 0;
        $g = 1;
        for ($r=1; $r <= $rondas ; $r++) { 
            $pagebreak = 'nopagebreak';
            if ($r == $rondas) {
                $ronda = '3º y 4º PUESTO';
            } elseif ($r  == $rondas - 1) {
                $ronda = 'SEMI - FINAL';
            } else {
                $ronda = 'Ronda ' . $r;
                $pagebreak = 'page_break';
            } ?>
            <div class="<?php echo $pagebreak;?>" style="border: 1px solid #008081; padding-left:10px; padding-right: 10px;">
                <h3 ><?php echo  $ronda;?></h3>
                <table class="table">
                    <thead>
                        <tr class="border">
                            <th style="padding: 1px 2px;">#</th>
                            <th>AKA</th>
                            <th></th>
                            <th></th>
                            <th>AO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  if($r == 1){ 
                            $height = (count($matches) > 8) ? '30px' : '35px';
                            $height2 = (count($matches) > 8) ? '12px' : '25px';
                            foreach ($matches as $key => $match) {?>
                                <tr height="50px" class="border">
                                    <td style="padding: 2px !important;text-align:center;" class="border"><?php $nmatch++; echo $nmatch; ?></td>
                                    <td style="width:200px;"><div style="height:<?php echo $height;?>;"></div></td>
                                    <td></td>
                                    <td></td>
                                    <td style="width:200px;"><div style="height:<?php echo $height;?>;"></div></td>
                                </tr>
                                <tr height="30px">
                                    <td colspan="5"><div style="height:<?php echo $height2;?>;"></div></td>
                                </tr>
                            <?php }
                            $matches_r = count($matches);
                        }else{
                            $matches_r = $matches_r / 2;
    
                            for ($m=0; $m < $matches_r; $m++) { ?>
                                <?php  if ($r == $rondas) { ?>
                                    <tr height="50px" class="border">
                                        <td style="padding: 2px !important;text-align:center;" class="border"><?php $nmatch++; echo $nmatch;  ?></td>
                                        <td style="width:200px;"><div style="height:35px;"><span style="font-size: 12px; color: #CCCCCC;">Perdedor <?php echo $g;?></span></div></td>
                                        <td></td>
                                        <td></td>
                                        <td style="width:200px;"><div style="height:35px;"><span style="font-size: 12px; color: #CCCCCC;">Perdedor <?php echo $g;?></span></div></td>
                                    </tr>
                                    <tr height="30px">
                                        <td colspan="5"><div style="height:15px;"></div></td>
                                    </tr>
                                    <tr height="50px">
                                        <td colspan="5"><div style="height:40px;"> <h1>FINAL</h1></div></td>
                                    </tr>  
                                <?php } ?>
                                <tr height="50px" class="border">
                                    <td style="padding: 2px !important;text-align:center;" class="border"><?php $nmatch++; echo $nmatch;  ?></td>
                                    <td style="width:200px;"><div style="height:35px;"><span style="font-size: 12px; color: #CCCCCC;">Ganador <?php echo $g; $g++;?></span></div></td>
                                    <td></td>
                                    <td></td>
                                    <td style="width:200px;"><div style="height:35px;"><span style="font-size: 12px; color: #CCCCCC;">Ganador <?php echo $g; $g++;?></span></div></td>
                                </tr>
                                <tr height="30px">
                                    <td colspan="5"><div style="height:15px;"></div></td>
                                </tr>     
                            <?php } 
                        }?>
                    </tbody>
                </table>
            </div>
        <?php }
    } ?> 
</body>
</html>