<div class="row">
	<div class="col-md-9">
		<div class="panel panel-primary">
			<div class="panel-body">
				<div id="inputMessage"></div>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
				 			<label>Keberangkatan atau asal shuttle :</label>
				 			<?php if($this->user_role != "admin") : ?>
				 				<input type="text" name="asal" id="asal" class="form-control" value="<?php echo $asalText; ?>" readonly >
				 			<?php else : ?>
					 			<select name="asal" id="asal" class="form-control">
					 				<optgroup label="<?php //echo $keberangkatan[0]->kota; ?>">
					 					<option value="">--pilih keberangkatan--</option>
					 					<?php foreach ($keberangkatan as $b) { ?>
						 					<option value="<?php echo $b->nama; ?>"><?php echo $b->nama; ?></option>
						 				<?php } ?>
					 				</optgroup>
					 			</select>
				 			<?php endif; ?>
				 			<div id="errorAsal"></div>
				 		</div>
				 		<div class="form-group">
				 			<label>Tujuan :</label> <span id="checkLabelTujuan"></span>
				 			<select name="tujuan" id="tujuan" class="form-control">
				 			</select>
				 			<div id="errorTujuan"></div>
				 		</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
				 			<label>Tanggal Pemesanan :</label>
				 			<input type="text" name="tanggal" class="form-control datepicker" id="tanggal" value="<?php echo date("Y-m-d"); ?>" placeholder="Pilih tanggal">
				 			<div id="errorTanggal"></div>
				 		</div>
				 		<div class="form-group">
				 			<label>Jam Keberangkatan :</label> <span id="checkLabelJam"></span>
				 			<select name="jamkeberangkatan" id="jamkeberangkatan" class="form-control">
				 			</select>
				 			<div id="errorJam"></div>
				 		</div>
					</div>
				</div>
			</div>
		</div>
		<div class="penumpang">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<label>Data Penumpang</label>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>No Hp :</label>
					 			<input type="number" min="0" name="nohp" class="form-control" id="nohp" placeholder="No HP">
					 			<div id="errorNohp"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="errorPenumpang"></div>
				 			<div id="errorJumlah"></div>
						</div>
					</div>
							
			 		<div class="form-group">
			 			<label>Jumlah Orang :</label>
			 			<button type="button" id="btnAddJumlahOrang" class="btn btn-flat btn-xs btn-success"><i class="fa fa-plus"></i></button>
			 			<div class="panel panel-primary">
			  				<div class="panel-body">
			  					<div class="row" id="jumlahPenumpang">
			  					</div>
			  				</div>
			  			</div>
			 		</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="form-group">
		 			<label>Bus : </label> <span id="checkLabelBus"></span>
		 			<select name="bus" id="checkBus" class="form-control">
		 			</select>
		 			<div id="hargaDiv" style="display: none;">
		 				<label class="small"><u>Harga Tiket</u> : </label><b id="hargaTiket" class="text-orange"></b>
		 			</div>
		 			<div id="errorBus"></div>
		 		</div>
		 		<div class="panel panel-primary">
		 			<div class="panel-body">
		 				<div class="row">
							<div class="col-md-6">
								<div class="small">
									<span><u>No Polisi</u> : <b id="noPolisi" class="text-green"></b></span><br>
									<span><u>Nama Supir</u> : <b id="nama_supir" class="text-green"></b></span><br>
								</div>
							</div>
							<div class="col-md-6">
								<div class="small">
									<span><u>Jumlah kursi</u> : <b id="jumlahKursi" class="text-yellow"></b></span><br>
									<span><u>Sisa kursi</u> : <b id="sisaKursi" class="text-purple"></b></span>
								</div>
							</div>
						</div>	
		 			</div>
		 		</div>	
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<label>Pilih Kursi : </label>
						<div class="panel panel-primary">
							<div class="panel-body">
								<div id="errorKursi"></div>
								<div class="table-responsive">
									<table id="tableKursi" style="width: 100%">
						              	<tbody>
							              	<tr>
							                    <td align="center"><img src="<?php echo base_url();?>assets/image/kursi-terisi.png" class="img-responsive" alt="Kursi Kenek" data-toggle="tooltip" title="Kursi Kenek"></td>
							                    <td></td>
							                    <td align="center"><img src="<?php echo base_url();?>assets/image/kursi-sopir.png" class="img-responsive" alt="Kursi Supir" data-toggle="tooltip" title="Kursi Supir"></td>
							                </tr>
							                <tr>
							                	<?php for ($i=1; $i <= 3; $i++) { ?>
							                    	<td align="center">
								                    	<img id="pilihKursi<?php echo $i;?>" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi <?php echo $i;?>">
								                    	<input type="checkbox" name="kursi[]" value="<?php echo $i;?>" id="check_img<?php echo $i;?>">
							                    	</td>
							                    <?php } ?>
							                </tr>
							                <tr>
							                	<?php for ($i=4; $i <= 6; $i++) { ?>
							                    	<td align="center">
								                    	<img id="pilihKursi<?php echo $i;?>" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi <?php echo $i;?>">
								                    	<input type="checkbox" name="kursi[]" value="<?php echo $i;?>" id="check_img<?php echo $i;?>">
							                    	</td>
							                    <?php } ?>
							                </tr>
							                <tr>
							                	<?php for ($i=7; $i <= 9; $i++) { ?>
							                    	<td align="center">
								                    	<img id="pilihKursi<?php echo $i;?>" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi <?php echo $i;?>">
								                    	<input type="checkbox" name="kursi[]" value="<?php echo $i;?>" id="check_img<?php echo $i;?>">
							                    	</td>
							                    <?php } ?>
							                </tr>
							                <tr>
							                	<?php for ($i=10; $i <= 12; $i++) { ?>
							                    	<td align="center">
								                    	<img id="pilihKursi<?php echo $i;?>" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi <?php echo $i;?>">
								                    	<input type="checkbox" name="kursi[]" value="<?php echo $i;?>" id="check_img<?php echo $i;?>">
							                    	</td>
							                    <?php } ?>
							                </tr>
						                </tbody>
						            </table>
					            </div>
							</div>
						</div>
					</div>	
				</div>	
			</div>
		</div>
	</div>
</div>

<!-- Modal Print PDF -->