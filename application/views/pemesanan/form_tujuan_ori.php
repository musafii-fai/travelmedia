<div class="row">
	<div id="inputMessage"></div>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-body">
	 		<div class="form-group">
	 			<label>Tanggal Pemesanan</label>
	 			<input type="text" name="tanggal" class="form-control datepicker" id="tanggal" value="<?php echo date("Y-m-d"); ?>" placeholder="Pilih tanggal">
	 			<div id="errorTanggal"></div>
	 		</div>
	 		<div class="form-group">
	 			<label>Keberangkatan atau asal shuttle</label>
	 			<select name="asal" class="form-control">
	 				<optgroup label="<?php echo $keberangkatan[0]->kota; ?>">
	 					<option value="">--pilih keberangkatan--</option>
	 					<?php foreach ($keberangkatan as $b) { ?>
		 					<option value="<?php echo $b->nama; ?>"><?php echo $b->nama; ?></option>
		 				<?php } ?>
	 				</optgroup>
	 				
	 			</select>
	 			<div id="errorAsal"></div>
	 		</div>
	 		<div class="form-group">
	 			<label>Tujuan</label>
	 			<select name="tujuan" class="form-control">
	 				<optgroup label="<?php echo $tujuan[0]->kota; ?>">
	 					<option value="">--pilih tujuan--</option>
	 					<?php foreach ($tujuan as $tj) { ?>
		 					<option value="<?php echo $tj->nama; ?>"><?php echo $tj->nama; ?></option>
		 				<?php } ?>
	 				</optgroup>
	 			</select>
	 			<div id="errorTujuan"></div>
	 		</div>
	 		<div class="form-group">
	 			<label>Jam Keberangkatan</label>
	 			<select name="jamkeberangkatan" id="jamkeberangkatan" class="form-control">
	 				<option value="">--pilih Jam--</option>
	 				<?php foreach($jamkeberangkatan as $jk ) : ?>
	 					<option value="<?php echo $jk->id; ?>">Jam = <?php echo $jk->jam; ?></option>
	 				<?php endforeach; ?>
	 			</select>
	 			<div id="errorJam"></div>
	 		</div>
	 		<div class="form-group">
	 			<label>Bus</label>
	 			<select name="jamkeberangkatan" id="jamkeberangkatan" class="form-control">
	 				<option value="">--pilih Bus--</option>
	 				<?php //foreach($jamkeberangkatan as $jk ) : ?>
	 					<!-- <option value="<?php //echo $jk->id; ?>">Jam = <?php //echo $jk->jam; ?></option> -->
	 				<?php //endforeach; ?>
	 				<option>BK0349LL</option>
	 				<option>BK9009KK</option>
	 			</select>
	 			<div id="errorJam"></div>
	 		</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<label>Penumpang</label>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>No Hp</label>
		 			<input type="number" min="0" name="nohp" class="form-control" id="nohp" style="width: 50%" placeholder="No HP">
		 			<div id="errorNohp"></div>
				</div>
		 		<div class="form-group">
		 			<label>Jumlah Orang</label>
		 			<button type="button" id="btnAddJumlahOrang" class="btn btn-flat btn-xs btn-success"><i class="fa fa-plus"></i></button>
		 			<div class="panel panel-primary">
		  				<div class="panel-body">
			 				<div id="errorPenumpang"></div>
			 				<div id="errorJumlah"></div>
		  					<div class="row" id="jumlahPenumpang">
		  					</div>
		  				</div>
		  			</div>
		 		</div>
			</div>
		</div>
	</div>

	<div class="col-md-2">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<label>Pilih Kursi Bus</label>
			</div>
				<div class="container small">
					<span><u>No Polisi</u> : <b id="noPolisi" class="text-green"><!-- BK8948LO --></b></span><br>
					<span><u>Jumlah kursi</u> : <b id="jumlahKursi" class="text-yellow"><!-- 12 --></b></span><br>
					<span><u>Sisa kursi</u> : <b id="sisaKursi" class="text-blue"><!-- 6 --></b></span>
				</div>
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
                    <td align="center"><div><img id="pilihKursi1" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 1"><input type="checkbox" name="kursi[]" value="1" id="check_img1"></div></td>
                    <td align="center"><div><img id="pilihKursi2" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 2"><input type="checkbox" name="kursi[]" value="2" id="check_img2"></div></td>
                    <td align="center"><div><img id="pilihKursi3" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 3"><input type="checkbox" name="kursi[]" value="3" id="check_img3"></div></td>
                </tr>
                <tr>
                    <td align="center"><div><img id="pilihKursi4" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 4"><input type="checkbox" name="kursi[]" value="4" id="check_img4"></div></td>
                    <td align="center"><div><img id="pilihKursi5" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 5"><input type="checkbox" name="kursi[]" value="5" id="check_img5"></div></td>
                    <td align="center"><div><img id="pilihKursi6" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 6"><input type="checkbox" name="kursi[]" value="6" id="check_img6"></div></td>
                </tr>
                <tr>
                    <td align="center"><div><img id="pilihKursi7" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 7"><input type="checkbox" name="kursi[]" value="7" id="check_img7"></div></td>
                   	<td align="center"><div><img id="pilihKursi8" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 8"><input type="checkbox" name="kursi[]" value="8" id="check_img8"></div></td>
                   	<td align="center"><div><img id="pilihKursi9" src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 9"><input type="checkbox" name="kursi[]" value="9" id="check_img9"></div></td>
                </tr>
                <tr>
                   	<td align="center""><div><img id="pilihKursi10"  src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 10"><input type="checkbox" name="kursi[]" value="10" id="check_img10"></div></td>
                	<td align="center""><div><img id="pilihKursi11"  src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 11"><input type="checkbox" name="kursi[]" value="11" id="check_img11"></div></td>
                	<td align="center""><div><img id="pilihKursi12"  src="<?php echo base_url();?>assets/image/kursi-kosong.png" class="img-responsive" data-toggle="tooltip" title="Kursi 12"><input type="checkbox" name="kursi[]" value="12" id="check_img12"></div></td>
                </tr>
                </tbody>
            </table>
            </div>
			</div>
		</div>
	</div>
</div>