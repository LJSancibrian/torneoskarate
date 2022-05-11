<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/secore.min.css">

    <style type="text/css">
        p {
            font-size: 20px;
            ;
            font-weight: bold;
        }

        p>small {
            font-size: 14px;
        }
        footer {
                position: fixed; 
                bottom: -30px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
            }
            .bg-primary{
                background-color: #CCCCCC;

            }
            .text-white{
                color: #FFFFFF;
            }
            .table{
                width: 100%;
            }
            td {
    padding: 8px;
}
h2,h3,h4,h5{
    margin-bottom: 10px;
    padding-bottom: 5px;
}
    </style>
</head>

<body style="word-spacing:normal;padding:0px 0px 0px 0px;">
<footer>
<?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?> <?php echo date("Y");?> 
</footer>
    <h2 class="bg-primary text-white" style="padding: 5px 15px;">
        <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?>
    </h2>

    <?php
    foreach ($matches as $grupo) { // printr($grupo);
    ?>
        <div style="width: 100%;page-break-inside: avoid; padding: 0;margin: 0;">
            <h4 class="bg-primary text-white" style="padding-left:15px; border: 3px solid red;">GRUPO <?php echo $grupo->grupo; ?></h4>
            <table class="table">
                <tr>
                    <?php foreach ($grupo->rondas as $ronda) { ?>
                        <td style="border: 2px solid #cccccc; padding:10px;">
                            <h5>Ronda <?php echo $ronda->ronda; ?></h5>
                            <?php foreach ($ronda->matches as $match) { ?>
                                <div style="margin-bottom: 15px;">
                                    <span style="border: 3px solid red;  padding:5px 10px; display:block;">
                                        <?php echo $match->rojo->nombre; ?></span>
                                    </span>
                                    <span style="border: 3px solid blue; padding:5px 10px; display:block;">
                                        <?php echo $match->azul->nombre; ?></span>
                                    </span>
                                </div>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    <?php } ?>
    
</body>

</html>