<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="d-flex align-items-center">
				<?php if(isset($table_title)){ ?>
					<h4 class="card-title"><?php echo $table_title;?></h4>
				<?php } ?>
				<?php if(isset($table_button)){
					if(!isset($table_button['href'])){
						echo form_button($table_button['name'], $table_button['content'], $table_button['extra']);
						if(isset($table_button['dropdown'])){ ?>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<?php foreach ($table_button['dropdown'] as $key => $drop) {
									//echo anchor($drop['href'], $drop['content'], $drop['extra']);
									echo form_button('', $drop['content'], $drop['extra']);
								}?>
							</div>
						<?php }
					} else{
						echo anchor($table_button['href'], $table_button['content'], $table_button['extra']);
					}
				} ?>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="table_datatable" class="display table table-striped table-sm w-100" data-default="<?php if(isset($default_filter)){echo $default_filter;}?>"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_open();echo form_close();?>
