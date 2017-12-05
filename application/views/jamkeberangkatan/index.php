
<?php echo openBox("success","Table Jam Keberangkatan"); ?>
	<?php if($this->user_role == "admin") : ?>
		<button id="btnAdd" class="btn btn-sm btn-primary btn-flat"><span class="fa fa-plus"></span> Tambah</button> &nbsp;&nbsp;&nbsp;
	<?php endif; ?>
	<button id="btnRefresh" class="btn btn-sm btn-flat"><span class="fa fa-refresh"></span> Refresh</button><br><br>
	<div class="table-responsive">
		<table id="tableJam" class="table table-bordered table-hover table-striped" style="width: 100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Jam</th>
					<?php if($this->user_role == "admin") : ?>
						<th>Action</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<br>
<?php echo closeBox(); ?>

<div id="modalFormData">
	<?php 
		echo modalSaveOpen("modalForm","sm");
			echo form_open("",array("id"=>"formJam"));
	?>	
			<div id="inputSuccess"></div>
			<div class="form-group">
	            <label>Jam</label>
	            <input type="hidden" name="idJam" id="idJam">
                <div class="input-group bootstrap-timepicker">  
	            	<input type="text" class="form-control timepicker" name="jam" id="jam" value="<?php echo date("H:i"); ?>" placeholder="jam">
                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                </div><!-- /.input group -->
	            <div id="errorJam"></div>
	        </div>
	<?php 
			echo form_close();
		echo modalSaveClose();
	?>
</div>

<!-- timepicker -->
<script type="text/javascript">

	$(document).ready(function(){
		//Timepicker
	    $(".timepicker").timepicker({
	    	maxHours : 24,
            showInputs: false,
            // defaultTime: false,
            showMeridian: false,
	    });

	    $("#modalButtonClose").click(function(){
	    	$("#formJam").each(function(){
	    		this.reset();
	    	})
	    });
	});
</script>

<div id="modalDelete">
	<?php echo modalDeleteShow("Delete Jam"); ?>
</div>

<script type="text/javascript">

	var save_method;

	$("#btnAdd").click(function(){
		$("#modalForm").modal("show");
		$(".modal-title").text("Tambah Jam");
		$("#modalButtonSave").text("Tambah");
		$("#formJam").each(function(){
			this.reset();
		});
		save_method = "add";
	});

	function edit_jam(id) {
		
		$.ajax({
			url:'<?php echo site_url("jamkeberangkatan/edit_id/"); ?>'+id,
			type:'POST',
			dataType:'json',
			success: function(json){
				if (json.status == true) {
					$("#idJam").val(json.data.id);
					$("#jam").val(json.data.jam);
					$("#modalForm").modal("show");
					$(".modal-title").text("Update Jam");
					$("#modalButtonSave").text("Update");
					save_method = "update";
				}
			}
		});
	}

	var idDelete;
	function delete_jam(id) {
		$("#deleteModal").modal("show");
		$(".modal-title").text("Delete Jam");
		idDelete = id;
		$.ajax({
			url:'<?php echo site_url("jamkeberangkatan/edit_id/"); ?>'+id,
			type:'POST',
			dataType:'json',
			success: function(json){
				if (json.status == true) {
					var infoData = "Jam keberangkatan <b>"+json.data.jam+"</b>";
        			$("#inputMessageDelete").html(infoData);
				}
			}
		});
	}

	$("#modalButtonDelete").click(function(){
		$.ajax({
			url:'<?php echo site_url("jamkeberangkatan/delete/"); ?>'+idDelete,
			type:'POST',
			dataType:'json',
			success: function(json){
				if (json.status == true) {
					$("#contentDelete").hide();
					$("#inputMessageDelete").html(json.message);
					setTimeout(function(){
						$("#contentDelete").show();
						$("#inputMessageDelete").html("");
						reload_table();
						$("#deleteModal").modal("hide");
					},1500);
				}
			}
		});
	});

	$("#modalButtonSave").click(function(){
		var url;
		if (save_method == "add") {
			url = '<?php echo site_url("jamkeberangkatan/add"); ?>';
		} else {
			url = '<?php echo site_url("jamkeberangkatan/update"); ?>';
		}

		$.ajax({
			url:url,
			type:'POST',
			dataType:'json',
			data:$("#formJam").serialize(),
		    success: function(json){
		        if (json.status == true) {
		          $("#inputSuccess").html(json.message);
		          reload_table();
		          setTimeout(function(){
		            $("#inputSuccess").html("");
					$("#modalForm").modal("hide");
		            $("#formJam").each(function(){
		                this.reset();
		            });
		          },2000);
		        } else {
		          $("#errorJam").html(json.error.jam);
		          setTimeout(function(){
			          $("#inputSuccess").html("");
			          $("#errorJam").html("");
		          },2000);
		        }
		    }
		});
	});

	$("#btnRefresh").click(function(){
		reload_table();
	});

	function reload_table() {
		$("#tableJam").DataTable().ajax.reload(null,false);
	}
</script>

<?php $order = $this->user_role == "admin" ? 2 : 0; ?>

<script type="text/javascript">
	$(document).ready(function() {
		var orderTarget = <?php echo intval($order); ?>;

   		$("#tableJam").DataTable({
	        processing: true, //Feature control the processing indicator.
	        serverSide: true, //Feature control DataTables' server-side processing mode.
	        responsive: true,

	        // Load data for the table's content from an Ajax source
	        ajax: {
	            url: '<?php echo site_url("jamkeberangkatan/ajax_list");?>',
	            type: 'POST',
	        },
	        order: [[1, 'asc']],
	        //Set column definition initialisation properties.
	        columnDefs: [
	        {
	            targets: [ 0,orderTarget ], //first column / numbering column
	            orderable: false, //set not orderable
	        },],
	    });
	});


</script>
	