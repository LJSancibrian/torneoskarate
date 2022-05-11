<!doctype html>
<html>
<head>

<meta charset="UTF-8">
<title>Empresa registrada '<?php echo site_title();?>'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">

<style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap');
 
    p{font-family: 'Roboto Condensed', sans-serif; font-weight: normal; font-style: normal; color:#5f727f; font-size:16px; text-align:left; margin:0; text-decoration:none; margin-bottom: 10px; line-height: 20px}
    li{font-family: 'Roboto Condensed', sans-serif; font-weight: normal; font-style: normal; color:#5f727f; font-size:16px;margin-left: 25px;}

</style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="font-family: 'Roboto Condensed', sans-serif;">
    <table class="content" width="600" border="0" align="center" cellpadding="0" cellspacing="0" id="Tabla_01" style="font-family: 'Roboto Condensed', sans-serif;">
        <tr>
            <td colspan="4" valign="middle" style="font-family: 'Roboto Condensed', sans-serif;">
            <a href="<?php echo base_url();?>">
                <img src="<?php echo assets_url();?>img/logo_principal.png" width="120" alt=""/></a>
                <p>&nbsp;</p>
            </td>
        </tr>
        
        <tr>
            <td>
                <h3>Empresa registrada en <?php echo site_title();?>.</h3>
                <p>&nbsp;</p>
                <p>A la atención de <strong><?php echo $responsable;?></strong>:</p>
                <p>La inscripción de la empresa <strong><?php echo $razon_social;?></strong> en la APP '<?php echo site_title();?>' ha sido realizada.<strong></p>
                <p>Los datos de acceso a la APP son:
                    <ul style="font-size: 20px; color:#000000;">
                        <li>Usuario: <strong><?php echo $cif;?></strong></li>
                        <li>Contraseña: <strong><?php echo $password;?></strong></li>
                    </ul>
                </p>
                <?php if ($this->config->item('linkappgoogle') != '' && $this->config->item('linkappapple') != ''){?>
                    <p>Puede descargar la APP desde las tiendas de aplicaciones:</p>
                    <p style="text-align:left">
                        <a href="<?php echo $this->config->item('linkappgoogle');?>" style="width:150px;">
                            <img alt="Disponible en Google Play" src="<?php echo base_url();?>assets/img/email/googlebtn.jpeg" width="150" align="center" style="width:150px; display: inline;" />
                        </a> 
                        &nbsp;&nbsp;           
                        <a href="<?php echo $this->config->item('linkappapple');?>" style="width:150px;">
                            <img alt="Disponible en AppStore" src="<?php echo base_url();?>assets/img/email/applebtn.jpeg"  width="150" align="center" style="width:150px; display: inline;" />
                        </a>                               
                    </p>
                <?php }elseif($this->config->item('linkappgoogle') != '' && $this->config->item('linkappapple') == ''){?>
                    <p>Puede descargar la APP para ANDROID desde el siguiente botón:</p>
                    <p style="text-align:left">
                        <a href="<?php echo $this->config->item('linkappgoogle');?>" style="width:150px;">
                            <img alt="Disponible en Google Play" src="<?php echo base_url();?>assets/img/email/googlebtn.jpeg" width="150" align="center" style="width:150px; display: inline;" />
                        </a>                           
                    </p>
                    <p style="text-align:left">  También puedes acceder a la Web/App a través del siguiente enlace: <a href="<?php echo base_url();?>webapp/">Abrir Web/App</a></p>   
                <?php }elseif($this->config->item('linkappgoogle') == '' && $this->config->item('linkappapple') != ''){?>
                    <p>Puede descargar la APP para IOS desde el siguiente botón:</p>
                    <p style="text-align:left">          
                        <a href="<?php echo $this->config->item('linkappapple');?>" style="width:150px;">
                            <img alt="Disponible en AppStore" src="<?php echo base_url();?>assets/img/email/applebtn.jpeg" width="150" align="center" style="width:150px; display: inline;" />
                        </a>                               
                    </p>
                    <p>También puedes acceder a la Web/App a través del siguiente enlace: <a href="<?php echo base_url();?>webapp/">Abrir Web/App</a></p>  
                <?php }else{?>
                    <p style="text-align:left">Puede descargar la APP a partir del 25 de Noviembre de 2021.</p>
                    <p>También puedes acceder a la Web/App a través del siguiente enlace: <a href="<?php echo base_url();?>webapp/">Abrir Web/App</a></p>  
                <?php } ?>

                
                <p>Un cordial saludo.</p>
            </td>
        </tr>
    </table>	
</body>
</html>


