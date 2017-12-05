<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i>Table Traveller</li>
        
        <li><a href="#dataTravellerAll" data-toggle="tab">Table Traveller All</a></li>
        <li class="active"><a href="#dataTravellerAdmin" data-toggle="tab">Table Traveller Admin</a></li>
    </ul>

    <div class="tab-content">
    	<div class="tab-pane active" id="dataTravellerAdmin">
    		<div class="table-responsive">
				<button class="btn btn-sm btn-flat" id="btnRefreshAdmin"><i class="fa fa-refresh"></i> Refresh</button>
				<br><br>
				<?php //var_dump($user); ?>
				<table id="tableTravellerAdmin" class="table table-striped table-bordered" style="width: 100%;">
					<thead>
						<th>No</th>
						<th>Admin</th>
						<th>Tanggal Input</th>
						<th>Nama</th>
						<th>Umur</th>
						<th>Jenis Kelamin</th>
						<th>No Hp</th>
						<th>Jam</th>
						<th>Asal Shuttle</th>
						<th>Tujuan Shuttle</th>
						<th>Action</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
    	</div>
    	<div class="tab-pane" id="dataTravellerAll">
    		<div class="table-responsive">
				<button class="btn btn-sm btn-flat" id="btnRefreshAll"><i class="fa fa-refresh"></i> Refresh</button>
				<br><br>
				<table id="tableTravellerAll" class="table table-striped table-bordered" style="width: 100%;">
					<thead>
						<th>No</th>
						<th>Admin</th>
						<th>Tanggal Input</th>
						<th>Nama</th>
						<th>Umur</th>
						<th>Jenis Kelamin</th>
						<th>No Hp</th>
						<th>Jam</th>
						<th>Asal Shuttle</th>
						<th>Tujuan Shuttle</th>
					<?php if($this->user_role == "admin") : ?>
						<th>Action</th>
					<?php endif; ?>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
    	</div>
    </div>
</div>

<!-- form edit traveller -->
<?php echo modalSaveOpen("modalForm","sm"); ?>
	<div id="inputMessage"></div>
	<form method="post" id="formData">
		<div class="form-group">
			<label>Nama</label>
			<input type="text" name="nama" id="inputNama" class="form-control" placeholder="Nama Traveller">
			<div id="errorNama"></div>
		</div>
		<div class="form-group">
			<label>Umur</label>
			<input type="number" name="umur" id="inputUmur" max="200" min="0" class="form-control" placeholder="Umur Traveller">
			<div id="errorUmur"></div>
		</div>
		<div class="form-group">
			<label>Jenis Kelamin</label>
			<select name="jenis_kelamin" id="inputJenisKelamin" class="form-control">
				<option value="">--Pilih Jenis Kelamin</option>
				<option value="laki-laki">Laki-laki</option>
				<option value="perempuan">Perempuan</option>
			</select>
			<div id="errorJenisKelamin"></div>
		</div>
		<div class="form-group">
			<label>No Hp</label>
			<input type="number" name="no_hp" id="inputNoHp" class="form-control" placeholder="No Hp">
			<div id="errorNohp"></div>
		</div>
	</form>
<?php echo modalSaveClose(); ?>

<script type="text/javascript">

	var idAction;

	$("#btnRefreshAdmin").click(function() {
		reloadTable();
	});

	$("#btnRefreshAll").click(function() {
		reloadTable();
	});

	function reloadTable() {
		$("#tableTravellerAdmin").DataTable().ajax.reload(null,false);
		$("#tableTravellerAll").DataTable().ajax.reload(null,false);
	}

	function btnUpdateAdmin(id) {
		$("#modalForm").modal("show");
		$(".modal-title").text("Edit data traveller Admin");
		idAction = id;
		dataById(idAction);
	}

	function btnUpdateAll(id) {
		$("#modalForm").modal("show");
		$(".modal-title").text("Edit data traveller All");
		idAction = id;
		dataById(idAction);
	}

	function dataById(id) {
		$.post("<?php echo site_url("traveller/getById/"); ?>"+idAction,function(json) {
			if (json.status == true) {
				$("#inputNama").val(json.data.nama);
				$("#inputUmur").val(json.data.umur);
				$("#inputJenisKelamin").val(json.data.jenis_kelamin);
				$("#inputNoHp").val(json.data.no_hp);
			} else {
				$("#inputMessage").html(json.message);
				$("#inputNama").val("");
				$("#inputUmur").val("");
				$("#inputJenisKelamin").val("");
				$("#inputNoHp").val("");
				setTimeout(function() {
					$("#inputMessage").html("");
					$("#modalForm").modal("hide");
				},5000);
			}
		});
	}

	$("#modalButtonSave").click(function() {
		
		$.ajax({
			url: "<?php echo site_url('traveller/update/'); ?>"+idAction,
			type: "POST",
			dataType:'json',
			data: $("#formData").serialize(),
			success: function(json) {
				if (json.status == true) {
					$("#inputMessage").html(json.message);
					reloadTable();
					setTimeout(function() {
						$("#inputMessage").html("");
						$("#modalForm").modal("hide");
					},1000);
				} else {
					if (json.message == "") {
						$("#errorNama").html(json.error.nama);
						$("#errorUmur").html(json.error.umur);
						$("#errorJenisKelamin").html(json.error.jenis_kelamin);
						$("#errorNohp").html(json.error.no_hp);

						setTimeout(function() {
							$("#errorNama").html("");
							$("#errorUmur").html("");
							$("#errorJenisKelamin").html("");
							$("#errorNohp").html("");
						},3000);
					} else {
						$("#inputMessage").html(json.message);

						setTimeout(function() {
							$("#inputMessage").html("");
						},3000);
					}
				}
			}
		});
	})

	$(document).ready(function() {

		$("#tableTravellerAdmin").DataTable({
			serverSide:true,
			processing:true,
			responsive:true,

			ajax:{
				url: '<?php echo site_url("traveller/ajax_list_admin"); ?>',
				type:'POST',
			},

			order:[[2,'DESC']],
			columns:[
				{ 
					data: 'no',
					orderable:false,
					searchable:false
				},
				{ data: 'admin'},
				{ data: 'tanggal_input'},
				{ data: 'nama'},
				{ data: 'umur'},
				{ data: 'jenis_kelamin'},
				{ data: 'no_hp'},
				{ data: 'jam_keberangkatan'},
				{ data: 'asal_shuttle'},
				{ data: 'tujuan_shuttle'},
				{ 
					data: null,
					orderable:false,
					searchable:false,
					defaultContent: "<button type='button' class='btn btn-sm btn-flat btn-warning'>Edit</button>",
				},
			],
			aoColumnDefs:[{
				aTargets: [10],
				fnCreatedCell: function(nTd,sData,oData,iRow,iCol){
					if (iCol == 10) {
						$(nTd.children[0]).attr("onclick","btnUpdateAdmin("+oData.traveller_id+")");
					}
				}
			}],
		});
		
		$("#tableTravellerAll").DataTable({
			serverSide:true,
			processing:true,
			responsive:true,

			ajax:{
				url: '<?php echo site_url("traveller/ajax_list"); ?>',
				type:'POST',
			},

			order:[[2,'DESC']],
			columns:[
				{ 
					data: 'no',
					orderable:false,
					searchable:false
				},
				{ data: 'admin'},
				{ data: 'tanggal_input'},
				{ data: 'nama'},
				{ data: 'umur'},
				{ data: 'jenis_kelamin'},
				{ data: 'no_hp'},
				{ data: 'jam_keberangkatan'},
				{ data: 'asal_shuttle'},
				{ data: 'tujuan_shuttle'},
				<?php if($this->user_role == "admin") : ?>
				{ 
					data: null,
					orderable:false,
					searchable:false,
					defaultContent: "<button type='button' class='btn btn-sm btn-flat btn-warning'>Edit</button>",
				},
				<?php endif; ?>
			],
			<?php if($this->user_role == "admin") : ?>
			aoColumnDefs:[{
				aTargets:[10],
				fnCreatedCell:function (nTd,sData,oData,iRow,iCol) {
					$(nTd.children[0]).attr("onclick","btnUpdateAll("+oData.traveller_id+")");
				}
			}],
			<?php endif; ?>
		});

	});
</script>