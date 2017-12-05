<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i>Tujuan dan Table Pemesanan</li>
        
        <li><a href="#dataPemesananAll" data-toggle="tab">Table Pemesanan All</a></li>
        <li><a href="#dataPemesananAdmin" data-toggle="tab">Table Pemesanan Admin</a></li>
        <li class="active"><a href="#tujuanPemesanan" data-toggle="tab">Tujuan Pemesanan</a></li>
    </ul>
    <div class="tab-content">
		<div class="tab-pane active" id="tujuanPemesanan">	<!-- Tab Tujuan Pemesanan -->
			<div class="row">
			<?php if($this->user_role == "admin") : ?>	<!-- Tujuan untuk Super admin -->
				<div class="col-md-4">
			        <div class="box box-warning">
			            <div class="box-header">
			                <h3 class="box-title">
			                	<a href="<?php echo site_url("pemesanan/tujuan/medan"); ?>">Tujuan Ke Medan</a>
			                </h3>
			                <div class="box-tools pull-right">
			                    <button class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			                </div>
			            </div>
			            <div class="box-body">
			            	<a href="<?php echo site_url("pemesanan/tujuan/medan"); ?>">
			            		<img src="<?php echo base_url("assets/image/medan.jpg"); ?>" style="height: 320px;" class="img img-responsive img-thumbnail">
			            	</a>
			            </div>
			        </div>
			    </div>
			    <div class="col-md-4">
			        <div class="box box-warning">
			            <div class="box-header">
			                <h3 class="box-title"><a href="<?php echo site_url("pemesanan/tujuan/riau"); ?>">Tujuan Ke Riau</a></h3>
			                <div class="box-tools pull-right">
			                    <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                </div>
			            </div>
			            <div class="box-body">
				            <a href="<?php echo site_url("pemesanan/tujuan/riau"); ?>">
				            	<img src="<?php echo base_url("assets/image/riau.jpg"); ?>" style="height: 320px;" class="img img-responsive img-thumbnail">
				            </a>
			            </div>
			        </div>
			    </div>
			    <div class="col-md-4">
				    <div class="box box-solid bg-navy">
                        <div class="box-header">
                            <h3 class="box-title">Informasi Pemesanan : </h3>
                        </div>
                        <div class="box-body">
                           	<p>
                                Untuk prosess pemesanan tiket, silahkan klik gambar yang ada di kotak 
                                <code>Tujuan Ke Medan</code> atau di kotak <code>Tujuan Ke Riau</code>
                            </p>
                        </div>
                    </div>
                </div>
			<?php else : ?>
				<?php if($shuttle_admin->kota == "Riau") : ?>	<!-- Tujuan untuk admin kota Riau -->
					<div class="col-md-4">
				        <div class="box box-danger">
				            <div class="box-header">
				                <h3 class="box-title"><a href="#">Keberangkatan atau Asal <br> <i>Shuttle: <?php echo "<u>".$shuttle_admin->nama."</u> Kota: <u>".$shuttle_admin->kota."</u>"; ?></i></a></h3>
				            </div>
				            <div class="box-body">
					            <a href="#">
					            	<img src="<?php echo base_url("assets/image/riau.jpg"); ?>" style="height: 300px;" class="img img-responsive img-thumbnail">
					            </a>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-4">
				        <div class="box box-success">
				            <div class="box-header">
				                <h3 class="box-title">
				                	<a href="<?php echo site_url("pemesanan/tujuan/medan"); ?>">Tujuan Ke Medan</a>
				                </h3>
				                <div class="box-tools pull-right">
				                    <button class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
				                </div>
				            </div>
				            <div class="box-body">
				            	<a href="<?php echo site_url("pemesanan/tujuan/medan"); ?>">
				            		<img src="<?php echo base_url("assets/image/medan.jpg"); ?>" style="height: 320px;" class="img img-responsive img-thumbnail">
				            	</a>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-4">
					    <div class="box box-solid bg-navy">
	                        <div class="box-header">
	                            <h3 class="box-title">Informasi Pemesanan :</h3>
	                        </div>
	                        <div class="box-body">
	                            <p>
	                                Untuk prosess pemesanan tiket, silahkan klik gambar yang ada di kotak 
	                                <code>Tujuan Ke Medan</code>
	                            </p>
	                        </div>
	                    </div>
	                </div>
				<?php else : ?>		<!-- Tujuan untuk admin kota Medan -->
					<div class="col-md-4">
				        <div class="box box-danger">
				            <div class="box-header">
				                <h3 class="box-title"><a href="#">Keberangkatan atau Asal <br> <i>Shuttle: <?php echo "<u>".$shuttle_admin->nama."</u> Kota: <u>".$shuttle_admin->kota."</u>"; ?></i></a></h3>
				            </div>
				            <div class="box-body">
					            <a href="#">
					            	<img src="<?php echo base_url("assets/image/medan.jpg"); ?>" style="height: 300px;" class="img img-responsive img-thumbnail">
					            </a>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-4">
				        <div class="box box-success">
				            <div class="box-header">
				                <h3 class="box-title"><a href="<?php echo site_url("pemesanan/tujuan/riau"); ?>">Tujuan Ke Riau</a></h3>
				                <div class="box-tools pull-right">
				                    <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
				                </div>
				            </div>
				            <div class="box-body">
					            <a href="<?php echo site_url("pemesanan/tujuan/riau"); ?>">
					            	<img src="<?php echo base_url("assets/image/riau.jpg"); ?>" style="height: 320px;" class="img img-responsive img-thumbnail">
					            </a>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-4">
					    <div class="box box-solid bg-navy">
	                        <div class="box-header">
	                            <h3 class="box-title">Informasi Pemesanan : </h3>
	                        </div>
	                        <div class="box-body">
	                            <p>
	                                Untuk prosess pemesanan tiket, silahkan klik gambar yang ada di kotak 
	                                <code>Tujuan Ke Riau</code>
	                            </p>
	                        </div>
	                    </div>
	                </div>
				<?php endif; ?>
			<?php endif; ?>
			</div>
		</div><!-- End Tab Tujuan Pemesanan -->

		<!-- Tab Table pemesanan untuk admin -->
    	<div class="tab-pane" id="dataPemesananAdmin">
			<div class="table-responsive">
				<table id="tablePemesananAdmin" class="table table-bordered table-hover table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th>No</th>
							<th>Admin</th>
							<th>Tanggal Pemesanan</th>
							<th>Jam</th>
							<th>Asal shuttle</th>
							<th>Tujuan shuttle</th>
							<th>No kursi</th>
							<th>Nama</th>
							<th>Harga tiket</th>
							<th>Info</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div> <!-- End Table pemesanan untuk admin -->

		<!-- Tab Table untuk pemesanan All -->
    	<div class="tab-pane" id="dataPemesananAll">
			<div class="table-responsive">
				<table id="tablePemesanan" class="table table-bordered table-hover table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th>No</th>
							<th>Admin</th>
							<th>Tanggal Pemesanan</th>
							<th>Jam</th>
							<th>Asal shuttle</th>
							<th>Tujuan shuttle</th>
							<th>No kursi</th>
							<th>Nama</th>
							<th>Harga tiket</th>
							<th>Info</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div> <!-- End Tab Table untuk pemesanan All -->

	</div>
</div><!-- /.nav-tabs-custom -->

<!-- modal detail -->
<div class="modal-detail">
	<?php echo modalSaveOpen("modalDetail","","Detail Pemesanan"); ?>

		<div class="panel panel-primary">
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>Nama Admin</dt>
                    	<dd id="namaAdmin"></dd>
                    <dt>Tanggal Input</dt>
                    	<dd id="tanggalInput"></dd>
                    <dt>Jam</dt>
                    	<dd id="jam"></dd>
                    <dt>Asal Shuttle</dt>
                    	<dd id="asal"></dd>
                    <dt>Tujuan Shuttle</dt>
                    	<dd id="tujuan"></dd>
                    <dt>No Polisi Bus</dt>
                    	<dd id="no_polisi"></dd>
                    <dt>Nama Supir</dt>
                    	<dd id="nama_supir"></dd>
                    <dt>No Kursi</dt>
                    	<dd id="nokursi"></dd>
                    <dt>Tanggal Pemesanan</dt>
                    	<dd id="tanggal"></dd>
                    <dt>Nama Pemesan</dt>
                    	<dd id="namaPemesan"></dd>
                    <dt>Jenis Kelamin</dt>
                    	<dd id="jk"></dd>
                    <dt>Umur</dt>
                    	<dd id="umur"></dd>
                    <dt>No Hp</dt>
                    	<dd id="nohp"></dd>
                    <dt>Harga Tiket</dt>
                    	<dd id="harga"></dd>
                </dl>
            </div><!-- /.box-body -->
        </div>
	<?php echo modalSaveClose("Cetak","modalButtonCetak"); ?>
</div>

<!-- modal delete -->
<div class="modal-delete">
	<?php echo modalDeleteShow("Delete pemesanan"); ?>
</div>

<script type="text/javascript">

	function btnDetail(id){
		$("#modalDetail").modal("show");
		$("#modalButtonCetak").hide();
		$.ajax({
			url: '<?php echo site_url("pemesanan/detail/") ?>'+id,
			type: 'POST',
			dataType: 'json',
			success: function(json){
				if (json.status == true) {
					// $("#no").text(id);
					$("#namaAdmin").text(json.data.admin);
					$("#tanggal").text(json.data.tanggal_pemesanan);
					$("#jam").html("Keberangkatan <b>"+json.data.jam+"</b>");
					$("#asal").html(json.data.asal_shuttle+" <i><b>"+json.data.kota_asal+"</i></b>");
					$("#tujuan").html(json.data.tujuan_shuttle+" <i><b>"+json.data.kota_tujuan+"</i></b>");
					$("#nama_supir").text(json.data.nama_supir);
					$("#no_polisi").text(json.data.no_polisi);
					$("#nokursi").text(json.data.nokursi);
					$("#tanggalInput").text(json.data.tanggal_input);
					$("#namaPemesan").html("<u><i>"+json.data.nama_pemesan+"</i></u>");
					$("#jk").text(json.data.jenis_kelamin);
					$("#umur").text(json.data.umur+" tahun");
					$("#nohp").text(json.data.no_hp);
					$("#harga").text(json.data.harga);
				} else {
					$("#errorMessage").html(json.message);
				}
			}
		});
	}

	var idDelete;
	function btnDelete(id) {
		$("#deleteModal").modal("show");
		idDelete = id;
		$.post("<?php echo site_url("pemesanan/detail/"); ?>"+idDelete,function(json) {
			if (json.status == true) {
				$("#inputMessageDelete").html("<b>Nama Pemesan: </b> <u>"+json.data.nama_pemesan+"</u>");			} else {
				$("#inputMessageDelete").html(json.message);
				$("#contentDelete").hide();

				setTimeout(function() {
					$("#inputMessageDelete").html("");
					$("#contentDelete").show();
					$("#deleteModal").modal("hide");
				},5000);
			}
		});
	}

	$("#modalButtonDelete").click(function() {
		$.post("<?php echo site_url("pemesanan/delete/"); ?>"+idDelete,function(json) {
			if (json.status == true) {
				$("#inputMessageDelete").html(json.message);
				$("#contentDelete").hide();
				reloadTable();

				setTimeout(function() {
					$("#inputMessageDelete").html("");
					$("#contentDelete").show();
					$("#deleteModal").modal("hide");
				},1500);
			} else {
				$("#inputMessageDelete").html(json.message);
				$("#contentDelete").hide();

				setTimeout(function() {
					$("#inputMessageDelete").html("");
					$("#contentDelete").show();
					$("#deleteModal").modal("hide");
				},5000);
			}
		});
	});

	function buttonRefresh() {
		reloadTable();
	}

	function buttonRefreshAdmin() {
		reloadTable();
	}

	function reloadTable() {
		$("#tablePemesananAdmin").DataTable().ajax.reload(null,false);
		$("#tablePemesanan").DataTable().ajax.reload(null,false);
	}
</script>

<!-- datatables -->
<script type="text/javascript">

	$(document).ready(function() {
		var user_role = '<?php echo $this->user_role; ?>';
		var btnRefreshAdmin = '<button id="btnRefreshAdmin" onclick="buttonRefreshAdmin()" class="btn btn-sm btn-flat"><span class="fa fa-refresh"></span> Refresh</button>';
		var btnDetail = '<button type="type" class="btn btn-info btn-xs btn-flat" onclick=""><i class="fa fa-info"></i> Detail</button>';
		var btnDelete = '<button type="type" class="btn btn-danger btn-xs btn-flat" onclick=""><i class="fa fa-trash-o"></i> Delete</button>';
		$("#tablePemesananAdmin").dataTable({
			processing: true, //Feature control the processing indicator.
	        serverSide: true, //Feature control DataTables' server-side processing mode.
	        ordering: true,
	        bFilter: true,
	        lengthChange: true,
	        responsive: true,
			oLanguage:{
	          sSearch: "<i class='fa fa-search fa-fw'></i> Pencarian : ",
	          sLengthMenu: "_MENU_ &nbsp;&nbsp;Data Per Halaman &nbsp;&nbsp;"+btnRefreshAdmin,
	          sInfo: "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
	          sInfoFiltered: "(difilter dari _MAX_ total data)", 
	          // sZeroRecords: "Pencarian tidak ditemukan", 
	          sLoadingRecords: "Harap Tunggu...", 
	          oPaginate: {
	            "sPrevious": "Prev",
	            "sNext": "Next"
	          }
	        },
			ajax:{
				url:'<?php echo site_url("pemesanan/ajax_list_admin"); ?>',
				type:'POST',
			},
			order:[[2,'DESC']],
			columns:[
				{
					data:'no',
					searchable:false,
					orderable:false,
				},
				{ data:'admin' },
				{ data:'tanggal_pemesanan' },
				{ data:'jam' },
				{ data:'asal_shuttle' },
				{ data:'tujuan_shuttle' },
				{ data:'nokursi' },
				{ data:'nama_traveller' },
				{ data:'harga' },
				{
					data:null,
					searchable:false,
					orderable:false,
					defaultContent:btnDetail+" &nbsp; "+ btnDelete,
				}
			],
			aoColumnDefs:[{
				aTargets:[9],
				fnCreatedCell:function(nTd,sData,oData,iRow,iCol) {
					if (iCol == 9) {
						$(nTd.children[0]).attr("onclick","btnDetail("+oData.id_pemesanan+")");
						$(nTd.children[1]).attr("onclick","btnDelete("+oData.id_pemesanan+")");
					}
				}
			}],
		});

		var btnRefresh = '<button id="btnRefresh" onclick="buttonRefresh()" class="btn btn-sm btn-flat"><span class="fa fa-refresh"></span> Refresh</button>';
		btnDelete = user_role == "admin" ? btnDelete : "";
		$("#tablePemesanan").dataTable({
			processing: true, //Feature control the processing indicator.
	        serverSide: true, //Feature control DataTables' server-side processing mode.
	        ordering: true,
	        bFilter: true,
	        lengthChange: true,
	        responsive: true,
			oLanguage:{
	          sSearch: "<i class='fa fa-search fa-fw'></i> Pencarian : ",
	          sLengthMenu: "_MENU_ &nbsp;&nbsp;Data Per Halaman &nbsp;&nbsp;"+btnRefresh,
	          sInfo: "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
	          sInfoFiltered: "(difilter dari _MAX_ total data)", 
	          // sZeroRecords: "Pencarian tidak ditemukan", 
	          sLoadingRecords: "Harap Tunggu...", 
	          oPaginate: {
	            "sPrevious": "Prev",
	            "sNext": "Next"
	          }
	        },
			ajax:{
				url:'<?php echo site_url("pemesanan/ajax_list"); ?>',
				type:'POST',
			},
			order:[[2,'DESC']],
			columns:[
				{
					data:'no',
					searchable:false,
					orderable:false,
				},
				{ data:'admin' },
				{ data:'tanggal_pemesanan' },
				{ data:'jam' },
				{ data:'asal_shuttle' },
				{ data:'tujuan_shuttle' },
				{ data:'nokursi' },
				{ data:'nama_traveller' },
				{ data:'harga' },
				{
					data:null,
					searchable:false,
					orderable:false,
					defaultContent:btnDetail+" &nbsp; "+btnDelete,
				}
			],
			aoColumnDefs:[{
				aTargets:[9],
				fnCreatedCell:function(nTd,sData,oData,iRow,iCol) {
					if (iCol == 9) {
						$(nTd.children[0]).attr("onclick","btnDetail("+oData.id_pemesanan+")");
						$(nTd.children[1]).attr("onclick","btnDelete("+oData.id_pemesanan+")");
					}
				}
			}],
		});

	});
	
</script>