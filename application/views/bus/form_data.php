<!-- modal Form Data -->
<div>
	<?php echo modalSaveOpen(); ?>
		<?php echo form_open("",array("id" => "formData")); ?>
			<div id="responseInput"></div>
			<div class="form-group">
	            <label>No Polisi</label>
	            <input type="hidden" name="id" id="id">
	            <input type="text" class="form-control" name="no_polisi" id="no_polisi" placeholder="Nomor Polisi">
	            <div id="errorNoPolisi"></div>
	        </div>
			<div class="form-group">
	            <label>Nama Supir</label>
	            <input type="text" class="form-control" name="nama_supir" id="nama_supir" placeholder="Nama Supir">
	            <div id="errorNamaSupir"></div>
	        </div>
			<div class="form-group">
	            <label>Nama Kenek</label>
	            <input type="text" class="form-control" name="nama_kenek" id="nama_kenek" placeholder="Nama Kenek">
	            <div id="errorNamaKenek"></div>
	        </div>
			<!-- <div class="form-group">
	            <label>Jumlah Kursi</label>
	            <input type="number" class="form-control" min="0" max="12" name="jumlah_kursi" id="jumlah_kursi" placeholder="Jumlah Kursi">
	            <div id="errorJumlahKursi"></div>
	        </div> -->
		<?php echo form_close(); ?>
	<?php echo modalSaveClose(); ?>
</div>

<!-- modal Delet -->
<div>
	<?php echo modalDeleteShow(); ?>
</div>

<script type="text/javascript">

	var action;
	var idDelete;

	function reloadTable() {
		$("#tableBus").DataTable().ajax.reload(null,false);
	}

	$("#btnRefresh").click(function() {
		reloadTable();
	});

	$("#btnAdd").click(function() {
		$("#modalForm").modal("show");
		$(".modal-title").text("Tambah Bus");
		$("#formData").each(function() {
			this.reset();
		});
		action = "add";
	});

	function btnEdit(id) {
		$("#modalForm").modal("show");
		$(".modal-title").text("Update Bus");
		action = "update";

		$.ajax({
			url: '<?php echo site_url("bus/getbyid/") ?>'+id,
			type:'POST',
			dataType:'json',
			success: function(json) {
				if (json.status == true) {
					$("#id").val(json.data.id);
					$("#no_polisi").val(json.data.no_polisi);
					$("#nama_supir").val(json.data.nama_supir);
					$("#nama_kenek").val(json.data.nama_kenek);
					$("#jumlah_kursi").val(json.data.jumlah_kursi);
				}
			}
		});
	}

	$("#modalButtonSave").click(function() {
		var url;
		if (action == "add") {
			url = '<?php echo site_url("bus/add"); ?>';
		} else {
			url = '<?php echo site_url("bus/update"); ?>';
		}

		$.ajax({
			url: url,
			type:'POST',
			dataType:'json',
			data: $("#formData").serialize(),
			success: function(json){
				if (json.status == true) {
					$("#responseInput").html(json.message);
					reloadTable();
					setTimeout(function(){
						$("#modalForm").modal("hide");
						$("#responseInput").html("");
						$("#formData").each(function(){
							this.reset();
						});
					},1500);
				} else {
					$("#responseInput").html(json.message);
					$("#errorNoPolisi").html(json.error.no_polisi);
					$("#errorNamaSupir").html(json.error.nama_supir);
					$("#errorNamaKenek").html(json.error.nama_kenek);
					$("#errorJumlahKursi").html(json.error.jumlah_kursi);
					setTimeout(function() {
						$("#errorNoPolisi").html("");
						$("#errorNamaSupir").html("");
						$("#errorNamaKenek").html("");
						$("#errorJumlahKursi").html("");
					},3000);
				}
			}
		});
	});

	function btnDelete(id) {
		$("#deleteModal").modal("show");
		$(".modal-title").text("Delete Bus");
		idDelete = id;
		$.ajax({
			url: '<?php echo site_url("bus/getbyid/") ?>'+id,
			type:'POST',
			dataType:'json',
			success: function(json) {
				if (json.status == true) {
					info = json.data.no_polisi+" - Nama supir : "+json.data.nama_supir;
					$("#inputMessageDelete").text(info);
				}
			}
		});
	}

	$("#modalButtonDelete").click(function() {
		$.ajax({
			url: '<?php echo site_url("bus/delete/"); ?>'+idDelete,
			type:'POST',
			dataType:'json',
			success: function(json) {
				if (json.status == true) {
					$("#contentDelete").hide();
					$("#inputMessageDelete").html(json.message);
					reloadTable();
					setTimeout(function() {		
						$("#deleteModal").modal("hide");				
						$("#contentDelete").show();
						$("#inputMessageDelete").html("");	
					},1500);
				} else {
					$("#contentDelete").hide();
					$("#inputMessageDelete").html(json.message);
				}
			}
		});
	});

</script>