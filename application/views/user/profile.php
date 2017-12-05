<?php echo openBox("info","Panel profile"); ?>

<div class="row">
	<div class="col-md-5">
		<div class="panel panel-primary">
			<!-- <div class="panel-heading">Ganti Password</div> -->
			<span class="label label-lg label-primary">Ganti Password</span>
  				<?php echo form_open("",array("id" => "formChangePassword")); ?>
  			<div class="panel-body">
				<div id="responseInputChange"></div>
				<div class="form-group">
		            <label>Username</label>
		            <input type="hidden" name="id" id="useridChange">
		            <input type="text" class="form-control" id="usernameChange" readonly>
		        </div>
				<div class="form-group">
		            <label>Password saat ini</label>
		            <input type="password" class="form-control" name="passwordCurrent" id="passwordCurrent" placeholder="Password saat ini">
		            <div id="errorPasswordCurrent"></div>
		        </div>
				<div class="form-group">
		            <label>Password baru</label>
		            <input type="password" class="form-control" name="passwordNew" id="passwordBaru" placeholder="Password baru">
		            <div id="errorPasswordNew"></div>
		        </div>
				<div class="form-group">
		            <label>Confirm password baru</label>
		            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password">
		            <div id="errorConfirmPassword"></div>
		        </div>
        	</div>
        	<div class="panel-footer">
        		<button type="button" id="btnChangePassword" class="btn btn-flat btn-primary">Save Password</button>
        	</div>
		        <?php echo form_close(); ?>
        </div>
	</div>
	<div class="col-md-7">
		<div class="panel panel-primary">
			<?php echo form_open("#",array('id' => 'form')); ?>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
				        <div id="responseInput"></div>
						<div class="form-group">
				            <label>Nama</label>
				            <input type="hidden" name="id" id="userid">
				            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama">
				            <div id="errorNama"></div>
				        </div>
				        <div class="form-group">
				            <label>Shuttle</label>
				            <input type="text" class="form-control" id="shuttleProfile" readonly>
				            <input type="hidden" name="shuttle" id="shuttle_id">
				        </div>
				        <div class="form-group">
				            <label>Kota</label>
				            <input type="text" class="form-control" id="kotaProfile" readonly>
				            <input type="hidden" name="role" id="role_id">
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
	    	</div>
	    	<div class="panel-footer">
        		<button type="button" id="btnSaveProfile" class="btn btn-flat btn-primary">Save Profile</button>
        	</div>
        	<?php echo form_close(); ?>
		</div>
	</div>
</div>

<?php echo closeBox(); ?>

<script type="text/javascript">
	$.ajax({
        url:'<?php echo site_url("user/ajax_user_admin"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            if (json.status == true) {
            	$("#useridChange").val(json.data.user_id);
                $("#userid").val(json.data.user_id);
				$("#usernameChange").val(json.data.username);
				$("#nama").val(json.data.nama_admin);
				$("#shuttleProfile").val(json.data.shuttle);
				$("#kotaProfile").val(json.data.kota);
				$("#shuttle_id").val(json.data.shuttle_id);
				$("#role_id").val(json.data.role_id);
				dataPhoto = json.data.photo_admin == "" ? "<?php echo base_url('assets/image/user_image.png'); ?>":"<?php echo base_url('uploads/'); ?>"+json.data.photo_admin;
				$("#img_photo").attr("src",dataPhoto);
            }
        }
    });

	/*save change password*/
	$("#btnChangePassword").click(function() {
		// confirm("apa yah..?");
		$.ajax({
			url:'<?php echo site_url("user/changepass_profile") ?>',
			type:'POST',
			data:$("#formChangePassword").serialize(),
			dataType:'json',
			success: function(json) {
				if (json.status == true) {
					$("#responseInputChange").html(json.message);
				} else {
					if (json.errorPasswordCurrent) {
						$("#responseInputChange").html(json.errorPasswordCurrent);
						setTimeout(function() {
							$("#responseInputChange").html("");
						},3000);
					} else if (json.errorConfirmPassword) {
						$("#errorConfirmPassword").html(json.errorConfirmPassword);
						setTimeout(function() {
							$("#errorConfirmPassword").html("");
						},3000);
					} else {
						$("#errorPasswordCurrent").html(json.error.passwordCurrent);
						$("#errorPasswordNew").html(json.error.passwordNew);
						$("#errorConfirmPassword").html(json.error.confirmPassword);
						setTimeout(function() {
							$("#errorPasswordCurrent").html("");
							$("#errorPasswordNew").html("");
							$("#errorConfirmPassword").html("");
						},3000);
					}
				}
			}
		});
	})

	/*save profile*/
	$("#btnSaveProfile").click(function() {

		var formData = new FormData($('#form')[0]);
	    $.ajax({
	        url : "<?php echo site_url('user/update')?>",
	        type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        dataType: "JSON",
	        success: function(json) {
				if (json.status == true) {
					$("#responseInput").html(json.message);
					setTimeout(function() {
						$("#responseInput").html("");
						// document.location.href = '<?php //echo site_url("user/profile"); ?>';
						seeAllProfile();
					},1500);
				} else {
					if (json.errorUpload) {
						$("#errorUpload").html(json.errorUpload);
						setTimeout(function() {
							$("#errorUpload").html("");
						},5000);
					} else {
						$("#errorNama").html(json.error.nama);

						setTimeout(function() {
							$("#errorNama").html("");
						},3000);
					}
				}
			}
	    });
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