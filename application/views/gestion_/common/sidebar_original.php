<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">
        <ul class="nav navbar-nav text-light mt-5">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url();?>area-privada"><i class="fas fa-user"></i><span>Mi perfil</span></a>
            </li>

            <?php if($this->ion_auth->in_group([1])){?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>usuarios"><i class="far fa-user mr-1"></i><span>Gestores web</span></a>
                </li>
            <?php } ?>

            <?php if($this->ion_auth->in_group([1,2,3])){?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>gestion-empresas"><i class="fas fa-store"></i><span>Empresas</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Descuentos"><i class="fas fa-tags"></i><span>Descuentos aplicados</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Compras"><i class="fas fa-shopping-cart"></i><span>Compras realizadas</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Tarjetas"><i class="fas fa-credit-card"></i><span>Tarjetas Vamos!</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Extractos"><i class="fas fa-funnel-dollar"></i><span>Extractos</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Extractos/pagos"><i class="fas fa-file-invoice-dollar"></i><span>Pagos realizados</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Codigos/participaciones"><i class="fas fa-th"></i><span>Participaciones</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>Codigos"><i class="fas fa-th"></i><span>Códigos</span></a>
                </li>

            <?php } ?>
              
            <?php if($this->ion_auth->in_group([1])){?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>gestion-promociones"><i class="fas fa-store"></i><span>Promociones</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#collapseGestionextractos" role="button" aria-expanded="false" aria-controls="collapse"><i class="fas fa-cog"></i><span>Extractos</span></a>
                    <div class="collapse" id="collapseGestionextractos">
                        <ul class="bg-white p-3">
                            <li class="mb-2"><a href="<?php echo base_url();?>extractos/premiados"><i class="fas fa-gift mr-1"></i><span>De los premiados</span></a></li>
                            <li><a href="<?php echo base_url();?>extractos/comercios"><i class="fas fa-store mr-1"></i><span>De los comercios</span></a></li>
                        </ul>
                    </div>
                </li>
            <?php } ?>

            <?php /*}else{ ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url();?>area-privada"><i class="fas fa-user"></i><span>Mi perfil</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>mis-compras"><i class="far fa-list-alt mr-1"></i><span>Mis compras</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>mis-bonos"><i class="fas fa-qrcode mr-1"></i><span>Mis bonos</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url();?>mis-canjeos"><i class="fas fa-cash-register  mr-1"></i><span>Mis canjeos</span></a>
                </li>
            <?php } */?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url();?>logout"><i class="fas fa-sign-out-alt  mr-1"></i><span>Cerrar sesión</span></a>
            </li>

        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
    </div>
</nav>