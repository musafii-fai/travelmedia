<?php echo openBox("success","Table Users"); ?>
	<?php if ($this->user->users_roleid == 1) : ?>
		<button id="btnAdd" onclick="add_person()" class="btn btn-sm btn-primary btn-flat"><span class="fa fa-plus"></span> Tambah</button> &nbsp;&nbsp;&nbsp;
	<?php endif; ?>
	<button id="btnRefresh" class="btn btn-sm btn-flat"><span class="fa fa-refresh"></span> Refresh</button><br><br>
	<div class="table-responsive">
		<table id="tableUsers" class="table table-bordered table-hover table-striped" style="width: 100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Photo</th>
					<th>Nama</th>
					<th>Shuttle</th>
					<th>Role</th>
					<th>Status</th>
					<?php if ($this->user_role == "admin") { ?>
					<th>Action</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<br>
<?php echo closeBox(); ?>

<!-- modal form data -->
<div id="formAJA">
<?php 
	echo modalSaveOpen("modal_form");
		echo form_open("#",array('id' => 'form'));
?>		
		<div class="row">
			<div class="col-md-8">
		        <div id="responseInput"></div>
				<div class="form-group">
		            <label>Username</label>
		            <input type="hidden" name="id" id="userid">
		            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
		            <input type="text" class="form-control" id="usernameReadonly" readonly>
		            <div id="errorUsername"></div>
		        </div>
				<div class="form-group password">
		            <label>Password</label>
		            <input type="text" class="form-control" name="password" id="password" placeholder="Password">
		            <div id="errorPassword"></div>
		        </div>
				<div class="form-group">
		            <label>Nama</label>
		            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama">
		            <div id="errorNama"></div>
		        </div>
		        <div class="form-group">
		            <label>Shuttle</label>
		            <select name="shuttle" id="shuttleid" class="form-control " style="width: 100%;">
		                <option value="">--Pilih Shuttle--</option>
		                <?php foreach($shuttle as $item) : ?>
		                	<option value="<?php echo $item->id; ?>"><?php echo $item->nama." [ ".$item->kota." ]"; ?></option>
		            	<?php endforeach ?>
		            </select>
		            <div id="errorShuttle"></div>
		        </div>
		        <div class="form-group">
		            <label>Role</label>
		            <select name="role" id="role" class="form-control">
		                <option value="">--Pilih Role--</option>
		            <?php foreach($users_role AS $val) : ?>
		                <option value="<?php echo $val->id; ?>"><?php echo $val->role; ?></option>
		            <?php endforeach; ?>
		            </select>
		            <div id="errorRole"></div>
		        </div>
		    </div>
		    <div class="col-md-4">
		    	<div class="form-group">
				<label>Photo</label>
		    	<div class="panel panel-default">
					<div class="panel-body">
						<center>
				    		<img src='<?php echo base_url('assets/image/user_image.png'); ?>' id='img_photo' class='img-responsive img-thumbnail' style='width:154px; height:119px;'>
		            		<input name="photo" id="photo_user" type="file" style="display: none;">
				    		<input type="hidden" name="is_delete" id="id_delete" value="0">
			    		</center>
					</div>
					<div class="panel-footer">
						<center>
					  	<button type="button" id="ganti_photo" class="btn btn-sm btn-flat btn-info" title="pilih photo">
			    			<i class="fa fa-upload"></i>
			    		</button>
			    		<button type="button" id="hapus_photo" class="btn btn-sm btn-flat btn-danger" title="hapus photo">
			    			<i class="fa fa-times-circle"></i>
				    	</button>
				    	</center>
					</div>
					<div id="errorUpload"></div>
				</div>	
				</div>
		    </div>
		</div>
<?php 
		echo form_close();
	echo modalSaveClose();
?>
</div>

<!-- modal form change password  -->
<div id="changepass">
	<?php 
		echo modalSaveOpen("modalChangePass","sm");
			echo form_open("",array("id" => "formChangePassword"));
	?>
		<div id="responseInputChange"></div>
		<div class="form-group">
            <label>Username</label>
            <input type="hidden" name="id" id="useridChange">
            <input type="text" class="form-control" id="usernameChange" readonly>
        </div>
		<div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password" id="passwordChange" placeholder="Password">
            <div id="errorPasswordChange"></div>
        </div>
	<?php 
			echo form_close();
		echo modalSaveClose("Change","modalButtonChange");
	?>
</div>

<!-- modal delete -->
<div id="modalDelete">
	<?php echo modalDeleteShow(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		<?php if ($this->user_role == "admin") { ?>
			columnFalse6 = 6;
		<?php } else { ?>
			columnFalse6 = 1;
		<?php } ?>
   		$("#tableUsers").DataTable({
	       	processing: true, //Feature control the processing indicator.
	        serverSide: true, //Feature control DataTables' server-side processing mode.
	        responsive: true,

	        // Load data for the table's content from an Ajax source
	        ajax: {
	            url: '<?php echo site_url("user/ajax_list");?>',
	            type: 'POST',
	        },
	        order: [[2, 'asc']],
	        //Set column definition initialisation properties.
	        columnDefs: [
	        {
	            targets: [ 0,1,columnFalse6], //first column / numbering column
	            orderable: false, //set not orderable
	        },],
	    });
	});

	function status(id){
		$.ajax({
			url: '<?php echo site_url("user/status/") ?>'+id,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				if (data.status == true) {
					reload_table();
				}
			}
		});
	}

	function reload_table() {
		$("#tableUsers").DataTable().ajax.reload(null,false);
	}

    $("#btnRefresh").click(function() {
    	reload_table();
    });

    var save_method;
    var idDelete;
    function add_person(){
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
		$("#username").show();
		$(".password").show();
		$("#modalButtonSave").text("Save");
	    $("#img_photo").attr("src","<?php echo base_url('assets/image/user_image.png'); ?>");
	    $("#usernameReadonly").hide();
	}

	function changePass(id){
		$("#modalChangePass").modal("show");
		$(".modal-title").text("Change Password");
		$("#modalButtonSave").text("Ganti");
		$("#formChangePassword").each(function() {
			this.reset();
		});
		$.ajax({
			url: '<?php echo site_url("user/getbyid/") ?>'+id,
			type: 'POST',
			dataType: 'json',
			success: function(json) {
				$("#useridChange").val(json.data.id);
				$("#usernameChange").val(json.data.username);
			}
		});
	}

	$("#modalButtonChange").click(function() {
		$.ajax({
			url:'<?php echo site_url("user/changepass"); ?>',
			type:'POST',
			dataType: 'json',
			data: $("#formChangePassword").serialize(),
			success:function(json) {
				if (json.status == true) {
					$("#responseInputChange").html(json.message);
					setTimeout(function() {
						reload_table();
						$("#modalChangePass").modal("hide");
						$("#formChangePassword").each(function(){
							this.reset();
						});
						$("#responseInputChange").html("");
					},1500);
				} else {
					$("#errorPasswordChange").html(json.message);
					setTimeout(function() {
						$("#errorPasswordChange").html("");
					},2500);
				}
			}
		});
	});

	function edit(id) {
		$("#modal_form").modal("show");
		$(".modal-title").text("Update user");
		$(".password").hide();
		$("#modalButtonSave").text("Update");
		$("#id_delete").val("0");
		$("#usernameReadonly").show();
		$("#username").hide();
		save_method = "update";
		$.ajax({
			url: '<?php echo site_url("user/getbyid/") ?>'+id,
			type: 'POST',
			dataType: 'json',
			success: function(json) {
				$("#userid").val(json.data.id);
				$("#usernameReadonly").val(json.data.username);
				$("#nama").val(json.data.nama);
				$("#shuttleid").val(json.data.shuttleid);
				$("#role").val(json.data.users_roleid);
				dataPhoto = json.data.photo == "" ? "<?php echo base_url('assets/image/user_image.png'); ?>":"<?php echo base_url('uploads/'); ?>"+json.data.photo;
				$("#img_photo").attr("src",dataPhoto);
			}
		});
	}

	// function save()
	$("#modalButtonSave").click(function() {
	    var url;

	    if(save_method == 'add') {
	        url = "<?php echo site_url('user/add')?>";
	    } else {
	        url = "<?php echo site_url('user/update')?>";
	    }

	    var formData = new FormData($('#form')[0]);
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        dataType: "JSON",
	        success: function(json) {
				if (json.status == true) {
					$("#responseInput").html(json.message);
					setTimeout(function() {
						$("#form").each(function() {
							this.reset();
						});
						$("#responseInput").html("");
						$("#modal_form").modal("hide");
						reload_table();
						seeAllProfile();
					},1500);
				} else {
					if (json.errorUsernameUnique) {
						$("#responseInput").html(json.errorUsernameUnique);
						setTimeout(function() {
							$("#responseInput").html("");
						},5000);
					} else if (json.errorUpload) {
						$("#errorUpload").html(json.errorUpload);
						setTimeout(function() {
							$("#errorUpload").html("");
						},5000);
					} else {
						$("#errorUsername").html(json.error.username);
						$("#errorNama").html(json.error.nama);
						$("#errorPassword").html(json.error.password);
						$("#errorShuttle").html(json.error.shuttle);
						$("#errorRole").html(json.error.role);

						setTimeout(function() {
							$("#errorUsername").html("");
							$("#errorNama").html("");
							$("#errorPassword").html("");
							$("#errorShuttle").html("");
							$("#errorRole").html("");
						},3000);
					}
				}
			}
	    });

	});

	function btnDelete(id) {
		$("#deleteModal").modal("show");
		$(".modal-title").text("Delete User");
		idDelete = id;
		$.ajax({
			url: '<?php echo site_url("user/getbyid/") ?>'+id,
			type: 'POST',
			dataType: 'json',
			success: function(json) {
				$("#inputMessageDelete").html(json.data.nama);
			}
		});
	}

	$("#modalButtonDelete").click(function() {
		if (idDelete != ""){
			$.ajax({
				url:'<?php echo site_url("user/delete/") ?>'+idDelete,
				type: 'POST',
				cache: false,
				dataType: 'json',
				success: function(json) {
					if (json.status == true) {
						$("#contentDelete").hide();
						$("#inputMessageDelete").html(json.message);
						setTimeout(function() {
							$("#contentDelete").show();
							$("#inputMessageDelete").html("");
							$("#deleteModal").modal("hide");
							reload_table();
						},1500);
					} else {
						$("#contentDelete").hide();
						$("#inputMessageDelete").html(json.message);
					}
				}
			});
		}
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		/* prosessing photo change*/
		$("#ganti_photo").click(function() {
			$("#photo_user").click();
		});

		$("#photo_user").change(function(event){
			readURL(document.getElementById('photo_user'));
			$('#id_delete').val(0);
		});

		$("#hapus_photo").click(function() {
		   $('#img_photo').attr('src','<?php echo base_url('assets/image/user_image.png'); ?>');
		   $("#photo_user").val("");
		   $('#id_delete').val(1);	
		});

		function readURL(input)
		{
		   if (input.files && input.files[0])
		   {
		     var reader = new FileReader();
		     reader.onload = function (e)
		     {
		       $('#img_photo').attr('src',e.target.result);
		     };
		     reader.readAsDataURL(input.files[0]);
		   }
		}
		/* end photo change*/
	});
</script>