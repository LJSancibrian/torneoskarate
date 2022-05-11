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
            font-size: 22px;
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
            height: auto;
            vertical-align: middle !important;
        }

        td {
            height: 25px;
        }

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

    <table class="table" id="tablakata">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-left columnfixed">Deportista</th>
                <th>J1</th>
                <th>J2</th>
                <th>M1</th>
                <th>J1</th>
                <th>J2</th>
                <th>M2</th>
                <th>TOT</th>
                <th>MED</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                <tr height="50px">
                    <td style=""><?php echo $value->orden; ?></td>
                    <td style="width:200px;"><div style="height:30px;"><?php echo ucwords(strtolower($value->first_name.' '.$value->last_name)); ?></div></td>
                    <td></td>
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
    <div style="width: 100%; page-break-inside: avoid;">
    <h3>Final</h3>
    <table class="table table-striped table-bordered text-center">
        <thead>
            <tr>
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
                <tr>
                <td style="width:150px;"><div style="height:30px;"></div></td>
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