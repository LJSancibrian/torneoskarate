<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title><?php echo strtoupper($page_header); ?>
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
            height: 30px;
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

<body style="word-spacing:normal;padding:10px 0px 0px 0px;">
    <footer>
        <?php echo $page_header; ?>
    </footer>
    <table class="header">
        <tr>
            <td style="font-family: Arial, Helvetica, sans-serif; text-align:left;padding-left: 10px;">
                Competición: 
            </td>
        </tr>
    </table>
    
    <div class="page_break">
        <table style="width: 100%;">
			<thead>
				<tr>
					<th style="text-align:left; font-size:14px;">AO</th>
					<th colspan="2"></th>
					<th style="text-align:right; font-size:14px;">AKA</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($i=0; $i < 18; $i++) { ?>
					<tr>
						<td style="border: 1px solid #008081; height:40px; vertical-align:bottom; text-align: right; padding-right:4px;">
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
						</td>
					</tr>
					<tr>
						<td colspan="4" style="height:5px;"></th>
					</tr>
            	<?php } ?>
			</tbody>
        </table>
    </div>
</body>

</html>