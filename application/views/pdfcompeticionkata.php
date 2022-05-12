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
            padding: 5px;
            vertical-align: middle;
        }
        .border-y{
            border-top: 1px solid #008081;
            border-bottom: 1px solid #008081;
        }

        h2,
        h3,
        h4,
        h5 {
            font-family: Arial, Helvetica, sans-serif;
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
            <td style="font-family: Arial, Helvetica, sans-serif;">
                <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?>
            </td>
        </tr>
    </table>
    <div class="page_break" style="border: 1px solid #008081; padding-left:10px; padding-bottom:10px; padding-right: 10px;">
        <h2>Rondas</h2>
        <table class="table" id="tablakata">
            <thead>
                <tr class="border">
                    <th class="">#</th>
                    <th class="text-left columnfixed"></th>
                    <th colspan="3">Ronda 1</th>
                    <th colspan="3">Ronda 2</th>
                    <th>Total</th>
                </tr>
                <tr class="border">
                    <th class="">#</th>
                    <th class="text-left columnfixed">Deportista</th>
                    <th>J1</th>
                    <th>J2</th>
                    <th>M</th>
                    <th>J1</th>
                    <th>J2</th>
                    <th>M</th>
                    <th>TOT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                    <tr class="border">
                        <td style="text-align: center;"><?php echo $value->orden; ?></td>
                        <td style="width:200px; height:35px;"><?php echo ucwords(strtolower($value->first_name.' '.$value->last_name)); ?></td>
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
    <div class="page_break" style="border: 1px solid #008081; padding-left:10px; padding-bottom:10px; padding-right: 10px;">
        <h2>Final</h2>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr class="border">
                    <th class="text-left columnfixed">Deportista</th>
                    <th>J1</th>
                    <th>J2</th>
                    <th>J3</th>
                    <th>Total</th>
                    <th>Media</th>
                    <th>Posicion final</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i < 8; $i++) {?>
                    <tr class="border">
                        <td style="width:150px;"><div style="height:35px;"></div></td>
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
</body>

</html>