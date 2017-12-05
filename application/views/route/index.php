 <!-- Custom tabs (Tables with tabs)-->
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li><a href="#shuttleAll" id="anchorRouteAll" data-toggle="tab">Shuttle All</a></li>
        <li class="active"><a href="#shuttleAdmin" id="anchorRouteAdmin" data-toggle="tab">Shuttle Admin</a></li>
        <li class="pull-left header"><i class="fa fa-list"></i> Table Route Perjalanan</li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="shuttleAdmin">
        	<div class="table-responsive">
        		<?php if($this->user_role == "admin") : ?>
					<button type="button" id="btnAddAdmin" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Tambah</button> &nbsp;&nbsp;&nbsp;&nbsp;
				<?php endif; ?>
				<button type="button" id="btnRefreshAdmin" class="btn btn-default btn-sm btn-flat"><i class="fa fa-refresh"></i> Refresh</button><br><br>
				<table id="tableRouteAdmin" class="table table-bordered table-hover table-striped table-condensed" style="width: 100%;">
					<thead>
						<tr>
							<th>No</th>
							<th>Asal Shuttle</th>
							<th>Kota Asal</th>
							<th>Tujuan shuttle</th>
							<th>Kota Tujuan</th>
							<th>Jam</th>
							<th>No Polisi Bus</th>
							<th>Nama Supir</th>
							<th>Harga tiket</th>
							<?php if($this->user_role == "admin") : ?>
								<th>Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
        </div>
        <div class="tab-pane" id="shuttleAll">
        	<div class="table-responsive">
        		<?php if($this->user_role == "admin") : ?>
					<button type="button" id="btnAddAll" class="btn btn-success btn-sm btn-flat"><i class="fa fa-plus"></i> Tambah</button> &nbsp;&nbsp;&nbsp;&nbsp;
				<?php endif; ?>
				<button type="button" id="btnRefreshAll" class="btn btn-default btn-sm btn-flat"><i class="fa fa-refresh"></i> Refresh</button><br><br>
				<table id="tableRouteAll" class="table table-bordered table-hover table-striped table-condensed" style="width: 100%;">
					<thead>
						<tr>
							<th>No</th>
							<th>Asal Shuttle</th>
							<th>Kota Asal</th>
							<th>Tujuan shuttle</th>
							<th>Kota Tujuan</th>
							<th>Jam</th>
							<th>No Polisi Bus</th>
							<th>Nama Supir</th>
							<th>Harga tiket</th>
							<?php if($this->user_role == "admin") : ?>
								<th>Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
        </div>
    </div>
</div><!-- /.nav-tabs-custom -->

<!-- modalForm add and update -->
<?php echo modalSaveOpen(); ?>
	<?php echo form_open("",array("id" => "formData")); ?>
		<div id="responseInput"></div>
		<div class="form-group">
			<label>Asal Shuttle</label>
			<input type="text" name="asal_shuttle" id="asal_shuttleText" readonly="readonly" class="form-control" value="<?php echo $name_shuttle ?>">
			<select name="asal_shuttle" id="asal_shuttleSelectOption" class="form-control">
				<option value="">--Pilih Asal Shuttle--</option>
				<?php foreach($shuttle as $val) : ?>
					<option value="<?php echo $val->nama; ?>"><?php echo $val->nama." [ ".$val->kota." ]"; ?></option>
				<?php endforeach; ?>
			</select>
			<div id="errorAsalShuttle"></div>
		</div>
		<div class="form-group">
			<label>Tujuan Shuttle</label>
			<select name="tujuan_shuttle" id="tujuan_shuttle" class="form-control">
				<!-- <option value="">-Pilih Tujuan Shuttle-</option> -->
				<?php //foreach($shuttle as $val) : ?>
					<!-- <option value="<?php //echo $val->nama; ?>"><?php //echo $val->nama." [ ".$val->kota." ]"; ?></option> -->
				<?php //endforeach; ?>
			</select>
			<div id="errorTujuanShuttle"></div>
		</div>
		<div class="form-group">
			<label>Jam Keberangkatan</label>
			<select name="jam" id="jam" class="form-control">
				<option value="">--Pilih Jam--</option>
				<?php foreach($jamkeberangkatan as $val) : ?>
					<option value="<?php echo $val->id; ?>">Jam Keberangkatan [ <?php echo $val->jam; ?> ]</option>
				<?php endforeach; ?>
			</select>
			<div id="errorJam"></div>
		</div>
		<div class="form-group">
			<label>Bus</label>
			<select name="bus" id="bus" class="form-control">
				<option value="">--Pilih Bus--</option>
				<?php foreach($bus_list as $val) : ?>
					<option value="<?php echo $val->id; ?>"><?php echo "No_Polisi [ ".$val->no_polisi." ] => Nama Supir [ ".$val->nama_supir." ]"; ?></option>
				<?php endforeach; ?>
			</select>
			<div id="errorBus"></div>
		</div>
		<div class="form-group">
			<label>Harga Tiket</label>
			<input type="number" name="harga_tiket" id="harga_tiket" min="0" class="form-control">
			<div id="errorHargaTiket"></div>
		</div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- end modalForm add and update -->

<!-- modal delete -->
<?php echo modalDeleteShow(); ?>
<!-- end modal delete -->

<!-- prosess ajax -->
<script type="text/javascript">
	var action_method;
	var idAction;
	var name_shuttle = "<?php echo $name_shuttle; ?>";
	$("#btnAddAll").click(function() {
		$("#modalForm").modal("show");
		$("#formData").each(function() {
			this.reset();
		});
		$(".modal-title").text("Tambah Route Perjalanan Shuttle All");
		$("#asal_shuttleText").hide();
		$("#asal_shuttleText").attr("name","asal_aja_lah");

		$("#asal_shuttleSelectOption").show();
		$("#asal_shuttleSelectOption").attr("name","asal_shuttle");
		action_method = "add";
		tujuanShuttle(""); // for select option tujuan shuttle all
		$("#responseInput").html("");
	});

	$("#btnAddAdmin").click(function() {
		$("#modalForm").modal("show");
		$("#formData").each(function() {
			this.reset();
		});
		$(".modal-title").text("Tambah Route Perjalanan Shuttle Admin");
		$("#asal_shuttleSelectOption").hide();
		$("#asal_shuttleSelectOption").attr("name","asal_aja_lah");

		$("#asal_shuttleText").show();
		$("#asal_shuttleText").attr("name","asal_shuttle");
		action_method = "add";
		tujuanShuttle("<?php echo intval($this->user->id); ?>"); // for select option tujuan shuttle admin
		$("#responseInput").html("");
	});

	function tujuanShuttle(adminId) {
		var url = "<?php echo site_url('route/tujuanShuttle/'); ?>";
		$.post(url+adminId,function(json) {
			if (json.status == true) {
				var option;
				option = "<option value=''>--Pilih Tujuan Shuttle--</option>";
				$.each(json.data,function(i,val) {
					option += '<option value="'+val.nama+'">'+val.nama+' ['+val.kota+'] </option>';
				});
				$("#tujuan_shuttle").html(option);
			} else {
				$("errorTujuanShuttle").html(json.message);
			}
		});
	}

	$("#btnRefreshAll").click(function() {
		$(".fa-refresh").addClass("fa-spin");
		setTimeout(function() {
			reloadTableAll();
			$(".fa-refresh").removeClass("fa-spin");
		},1000);
	});

	$("#btnRefreshAdmin").click(function() {
		$(".fa-refresh").addClass("fa-spin");
		setTimeout(function() {
			reloadTableAdmin();
			$(".fa-refresh").removeClass("fa-spin");
		},1000);
	});

	function btnUpdate(id) {
		$("#modalForm").modal("show");
		$(".modal-title").text("Update Route Perjalanan");

		$("#asal_shuttleText").hide();
		$("#asal_shuttleText").attr("name","asal_aja_lah");
		$("#asal_shuttleSelectOption").show();
		$("#asal_shuttleSelectOption").attr("name","asal_shuttle");
		tujuanShuttle(""); // for select option tujuan shuttle admin

		action_method = "update";
		idAction = id;
		$.post("<?php echo site_url("route/getById/"); ?>"+id,function(json) {
			if (json.status == true) {
				$("#jam").val(json.data.jam_id);
				$("#bus").val(json.data.bus_id);
				$("#asal_shuttleSelectOption").val(json.data.asal_shuttle);
				$("#tujuan_shuttle").val(json.data.tujuan_shuttle);
				$("#harga_tiket").val(json.data.harga_tiket);
			} else {
				$("#responseInput").html(json.message);
			}
		});
	}

	function btnUpdateAdmin(id) {
		$("#modalForm").modal("show");
		$(".modal-title").text("Update Route Perjalanan");

		$("#asal_shuttleSelectOption").hide();
		$("#asal_shuttleSelectOption").attr("name","asal_aja_lah");
		$("#asal_shuttleText").show();
		$("#asal_shuttleText").attr("name","asal_shuttle");
		tujuanShuttle("<?php echo intval($this->user->id); ?>"); // for select option tujuan shuttle admin

		action_method = "update";
		idAction = id;
		$.post("<?php echo site_url("route/getById/"); ?>"+id,function(json) {
			if (json.status == true) {
				$("#jam").val(json.data.jam_id);
				$("#bus").val(json.data.bus_id);
				$("#asal_shuttleText").val(json.data.asal_shuttle);
				$("#tujuan_shuttle").val(json.data.tujuan_shuttle);
				$("#harga_tiket").val(json.data.harga_tiket);
			} else {
				$("#responseInput").html(json.message);
			}
		});
	}

	$("#modalButtonSave").click(function() {
		$("#modalButtonSave").attr("disabled",true);
		$("#modalButtonSave").html("Loading... <i class='fa fa-spinner fa-spin'></i>");

		var url;
		if (action_method == "add") {
			url = "<?php echo site_url("route/add"); ?>";
		} else {
			url = "<?php echo site_url("route/update/"); ?>"+idAction;
		}

		var formData = new FormData($("#formData")[0]);
		$.ajax({
			url: url,
			type: "POST",
			dataType: "json",
			data: $("#formData").serialize(),
			success: function(json) {
				if (json.status == true) {
					$("#responseInput").html(json.message);
					reloadTableAll();
					reloadTableAdmin();
					setTimeout(function() {
						$("#modalForm").modal("hide");
						$("#responseInput").html("");
						$("#modalButtonSave").attr("disabled",false);
						$("#modalButtonSave").text("Save");
					},1000);
				} else {
					if (json.errorCheckBus) {
						$("#responseInput").html(json.errorCheckBus);
						setTimeout(function() {
							$("#modalButtonSave").attr("disabled",false);
							$("#modalButtonSave").text("Save");
						},1000);
					} else if(json.errorBentrok) {
						$("#responseInput").html(json.errorBentrok);
						setTimeout(function() {
							$("#modalButtonSave").attr("disabled",false);
							$("#modalButtonSave").text("Save");
						},1000);
					} else {
						$("#errorJam").html(json.error.jam);
						$("#errorBus").html(json.error.bus);
						$("#errorAsalShuttle").html(json.error.asal_shuttle);
						$("#errorTujuanShuttle").html(json.error.tujuan_shuttle);
						$("#errorHargaTiket").html(json.error.harga_tiket);
						setTimeout(function() {
							$("#errorJam").html("");
							$("#errorBus").html("");
							$("#errorAsalShuttle").html("");
							$("#errorTujuanShuttle").html("");
							$("#errorHargaTiket").html("");
							$("#modalButtonSave").attr("disabled",false);
							$("#modalButtonSave").text("Save");
						},3000);
					}
				}
			}
		});
	});

	function btnDelete(id) {
		$("#deleteModal").modal("show");
		idAction = id;
		$.post("<?php echo site_url("route/getById/"); ?>"+id,function(json) {
			if (json.status == true) {
				var deleteMessage = "Asal Shuttle: "+json.data.asal_shuttle+"<b>["+json.data.kota_asal+"]</b> <br>";
				deleteMessage += "Tujuan Shuttle: "+json.data.tujuan_shuttle+"<b>["+json.data.kota_tujuan+"]</b> <br>";
				deleteMessage += "Jam: <b>"+json.data.jam+"</b> <br> No_Polisi: <b>"+json.data.no_polisi+"</b> <br>";
				deleteMessage += "Nama Supir: <b>"+json.data.nama_supir+"</b><br> Harga Tiket: <b>Rp."+json.data.harga_tiket+"</b>";

				$("#inputMessageDelete").html(deleteMessage);
			} else {
				$("#inputMessageDelete").html(json.message);
			}
		});
	}

	$("#modalButtonDelete").click(function() {
		$.post("<?php echo site_url("route/delete/"); ?>"+idAction,function(json) {
			if (json.status == true) {
				$("#contentDelete").hide();
				$("#inputMessageDelete").html(json.message);
				reloadTableAll();
				reloadTableAdmin();
				setTimeout(function() {		
					$("#deleteModal").modal("hide");				
					$("#contentDelete").show();
					$("#inputMessageDelete").html("");	
				},1500);
			} else {
				$("#contentDelete").hide();
				$("#inputMessageDelete").html(json.message);
			}
		});
	});

	function reloadTableAll() {
		$("#tableRouteAll").DataTable().ajax.reload(null,false);
	}

	function reloadTableAdmin() {
		$("#tableRouteAdmin").DataTable().ajax.reload(null,false);
	}

</script>
<?php 
	$order = $this->user_role == "admin" ? 9 : 0; 
?>
<script type="text/javascript">
	$(document).ready(function() {
		var orderTarget = <?php echo intval($order); ?>;

		$("#tableRouteAdmin").dataTable({
			serverSide:true,
			processing:true,
			responsive:true,
			ajax:{
				url:'<?php echo site_url("route/ajax_list_admin/").$this->user->id; ?>',
				type:'POST',
			},

			order: [[3,'ASC']],
			columnDefs:[
				{
					targets: [0,orderTarget],
					orderable:false,
				}
			]
		});

		$("#tableRouteAll").dataTable({
			serverSide:true,
			processing:true,
			responsive:true,
			ajax:{
				url:'<?php echo site_url("route/ajax_list/"); ?>',
				type:'POST',
			},

			order: [[1,'ASC']],
			columnDefs:[
				{
					targets: [0,orderTarget],
					orderable:false,
				}
			]
		});
	});
</script>