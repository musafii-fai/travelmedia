<style type="text/css">
	.icheckbox_minimal {
		display: none;
	}

	.label-default {
		margin-top: 15px;
	}

</style>
<?php
 	if (isset($tujuanSalah)) {
 		echo "<h2 style='color:red;'>".$tujuanSalah."</h2>";
 	} else {
 		if (isset($forbiddenTujuan)) {
 			echo "<h2 style='color:red;'>".$forbiddenTujuan."</h2>";
 		} else {
 			$btnProsses = '<button type="button" class="btn btn-primary btn-flat" id="btnProsess"><i class="fa fa-check-circle"></i> Prosess Pemesanan</button>';
 ?>
			<?php echo form_open(site_url("pemesanan/prosess_ajax"),array("id" => "formProsess")); ?>
			<!-- <form id="formProsess" method="POST"> -->
			 	<?php echo openBox("primary",$btnProsses); ?>
			 		<!-- For Form Tujuan -->
			 		<?php $this->load->view("pemesanan/form_tujuan"); ?> <!-- FOR FORM TUJUAN -->

			 	<?php echo closeBox(); ?>

			<?php echo form_close(); ?>
 <?php 
 		}
	} 
 ?>

<script type="text/javascript">

	var kursiPilih = "<?php echo base_url('assets/image/kursi-terpilih.png'); ?>";
	var kursiKosong = "<?php echo base_url('assets/image/kursi-kosong.png'); ?>";
	var kursiTerisi = "<?php echo base_url('assets/image/kursi-terisi.png'); ?>";

	/* check tujuan berdasarkan asal shuttle */
	$("#asal").change(function() {	// for super admin
		if ($(this).val() !== "") {
			if ($("#tujuan").val() !== "") {
				$("#errorTujuan").html("");
			} else {
				$("#errorTujuan").html("<span class='text-red'>Silahkan pilih Tujuan shuttle</span>");
			}
			checkTujuan();
		} else {
			$("#tujuan").html("");
			$("#jamkeberangkatan").html("");
			$("#checkLabelJam").html("");
			$("#checkBus").html("");
			$("#checkLabelBus").html(""); 
			emptyInfoLabelBusAll(); // for bus all

		}
	});

	if ($("#asal").val() !== "") {	// for user admin
		checkTujuan();
	}

	function checkTujuan() {
		$.ajax({
			url: '<?php echo site_url("route/checkRouteTujuan") ?>',
			type:'POST',
			dataType:'json',
			data:'asal='+$("#asal").val(),
			success: function(json) {
				if (json.status == true) {
					var html;
					html = "<option value=''>--Pilih Tujuan--</option>";
					$.each(json.data,function(i,val) {
						html += "<option value='"+val.tujuan_shuttle+"'>"+val.tujuan_shuttle+"</option>";
					});

					$("#tujuan").html(html);
					$("#checkLabelTujuan").html(json.message);
					$("#jamkeberangkatan").html("");
					$("#checkLabelJam").html("");
					$("#checkBus").html("");
					$("#checkLabelBus").html(""); 
					emptyInfoLabelBusAll(); // for bus all
				} else {
					$("#checkLabelTujuan").html(json.message);
					$("#tujuan").html("");
					$("#jamkeberangkatan").html("");
					$("#checkLabelJam").html("");
					$("#checkBus").html("");
					$("#checkLabelBus").html(""); 
					emptyInfoLabelBusAll(); // for bus all

				}
			}
		});
	}

	/* check jam keberangkatan berdasarkan asal dan tujuan shuttle*/
	$("#tujuan, #tanggal").change(function() {
		if ($(this).val() !== "") {
			if ($("#jamkeberangkatan").val() != "") {
				$("#errorJam").html("");
			} else {
				$("#errorJam").html("<span class='text-red'>Silahkan pilih jam keberangkatan</span>");
			}
			$("#errorTujuan").html("");

			var asal_shuttle = $("#asal").val();
			var tujuan_shuttle = $("#tujuan").val();
			var tanggal = $("#tanggal").val();

			$.ajax({
				url: '<?php echo site_url("route/checkRouteJam") ?>',
				type:'POST',
				dataType:'json',
				data:'asal='+asal_shuttle+'&tujuan='+tujuan_shuttle+"&tanggal="+tanggal,
				success: function(json) {
					if (json.status == true) {
						var html;
						html = "<option value=''>--Pilih Jam keberangkatan--</option>";
						$.each(json.data,function(i,val) {
							html += "<option value='"+val.id+"'>Jam = "+val.jam+"</option>";
						});
						$("#inputMessage").html("");
						$("#jamkeberangkatan").html(html);
						$("#checkLabelJam").html(json.message);
						$("#checkBus").html("");
						$("#checkLabelBus").html(""); 
						emptyInfoLabelBusAll(); // for bus all
					} else {
						$("#inputMessage").html("");
						$("#jamkeberangkatan").html("");
						$("#checkLabelJam").html(json.message);
						$("#checkBus").html("");
						$("#checkLabelBus").html(""); 
						emptyInfoLabelBusAll(); // for bus all
					}
				}
			});
		} else {
			$("#errorJam").html("");
			$("#checkLabelJam").html("");
			$("#jamkeberangkatan").html("");
			$("#checkBus").html("");
			$("#checkLabelBus").html("");
			$("#errorBus").html("");
			emptyInfoLabelBusAll(); // for bus all
		}
		for (var i = 1; i <= 12; i++) {
			checkKursi(i,kursiKosong);
		}
	});

	$("#tanggal").change(function() {
		if ($(this).val() == "") {
			$("#errorTanggal").html("<span class='text-red'>Silahkan pilih tanggal pemesanan untuk check jumlah dan sisa kursi yang tersedia..!</span>");
			$("#jamkeberangkatan").val("");
			$("#checkLabelBus").html(""); 
			$("#checkBus").html("");
			$("#jamkeberangkatan").attr("disabled",true);
			$("#errorJam").html("<span class='text-red'>Silahkan pilih tanggal.!</span>");
			$("#checkBus").attr("disabled",true);
			$("#errorBus").html("<span class='text-red'>Silahkan pilih tanggal.!</span>");
			$("#noPolisi").html("");
			$("#nama_supir").html("");
			$("#jumlahKursi").html("");
			$("#hargaDiv").hide();
			$("#hargaTiket").html("");
		} else {
			$("#errorTanggal").html("");
			$("#errorJam").html("");
			$("#errorBus").html("");
			$("#jamkeberangkatan").attr("disabled",false);
			$("#checkBus").attr("disabled",false);
			$("#jamkeberangkatan").val("");
			$("#checkBus").val("");
			$("#sisaKursi").html("");
		}
	})

	/* check data bus dropdown select option berdasarkan asal,tujuan dan jam keberangkatan dari data route perjalanan*/
	$("#jamkeberangkatan").change(function() {
		var asal_shuttle = $("#asal").val();
		var tujuan_shuttle = $("#tujuan").val();
		var jam = $("#jamkeberangkatan").val();
		if ($(this).val() !== "") {
			if ($("#tujuan").val() == "") {
				$("#errorTujuan").html("<span class='text-red'>Silahkan pilih tujuan nya.!</span>");
			} else {
				$("#errorTujuan").html("");
			}
			$("#errorJam").html("");
			
			$.ajax({
				url: '<?php echo site_url("route/checkRouteBus") ?>',
				type:'POST',
				dataType:'json',
				data:'asal='+asal_shuttle+'&tujuan='+tujuan_shuttle+'&jamkeberangkatan='+jam,
				success: function(json) {
					if (json.status == true) {
						var html;
						html = "<option value=''>--Pilih Bus--</option>";
						$.each(json.data,function(i,val) {
							html += "<option value="+val.bus_id+">"+val.no_polisi+"=>"+val.nama_supir+"</option>";
						});

						$("#checkBus").html(html);
						$("#checkLabelBus").html(json.message);
						$("#errorBus").html("<span class='text-red'>Silahkan pilih Bus</span>");
						emptyInfoLabelBusAll(); // for bus all
					} else {
						$("#checkBus").html("");
						$("#checkLabelBus").html(json.message); 
						$("#errorBus").html("");
						emptyInfoLabelBusAll(); // for bus all
					}
				}
			});
		} else {
			$("#errorJam").html("<span class='text-red'>Silahkan pilih jam keberangkatan</span>");
			$("#checkBus").html("");
			$("#checkLabelBus").html(""); 
			emptyInfoLabelBusAll(); // for bus all
		}

		for (var i = 1; i <= 12; i++) {
			checkKursi(i,kursiKosong);
		}
	});

	/* check data bus untuk keterangan di bawahnya */
	$("#checkBus").change(function() {
		var asal_shuttle = $("#asal").val();
		var tujuan_shuttle = $("#tujuan").val();
		var jam = $("#jamkeberangkatan").val();
		var checkBus = $("#checkBus").val();
		if ($(this).val() !== "") {
			$("#errorBus").html("");
			$.ajax({
				url: '<?php echo site_url("route/checkRouteBus") ?>',
				type:'POST',
				dataType:'json',
				data:'asal='+asal_shuttle+'&tujuan='+tujuan_shuttle+'&jamkeberangkatan='+jam+'&bus='+checkBus,
				success: function(json) {
					if (json.status == true) {
						$("#noPolisi").html(json.data[0].no_polisi);
						$("#nama_supir").html(json.data[0].nama_supir);
						$("#jumlahKursi").html(json.data[0].jumlah_kursi);
						$("#hargaDiv").show();
						$("#hargaTiket").html(json.data[0].harga_tiket);
					} else {
						emptyInfoLabelBusAll(); // for bus all
					}
				}
			});
		} else {
			$("#errorBus").html("<span class='text-red'>Silahkan pilih Bus</span>");
			emptyInfoLabelBusAll(); // for bus all
		}
	})

	function emptyInfoLabelBusAll() {
		$("#noPolisi").html("");
		$("#nama_supir").html("");
		$("#jumlahKursi").html("");
		$("#sisaKursi").html("");
		$("#hargaDiv").hide();
		$("#hargaTiket").html("");
		$("#nohp").val("");
		$("#errorNohp").html("");
	}

	/* check bus berdasarkan tanggal dan jam*/
	$("#tanggal, #checkBus").change(function() {
		if ($(this).val() !== "") {
			var asal = $("#asal").val();
			var tujuan = $("#tujuan").val();
			var tgl = $("#tanggal").val();
			var jam = $("#jamkeberangkatan").val();
			var checkBus = $("#checkBus").val();
			$.ajax({
				url: '<?php echo site_url("pemesanan/jumlah_kursi"); ?>',
				type: 'POST',
				dataType: 'json',
				data:"asal="+asal+"&tujuan="+tujuan+"&tanggal="+tgl+"&jamkeberangkatan="+jam+"&bus="+checkBus,
				success: function(json) {
					if (json.status == true) {
						$("#sisaKursi").html(json.data.sisa_kursi);
						$("#sisaKursi").addClass("text-purple");

						json.kursi.pilih1 == "" ? checkKursi(1,kursiKosong) : checkKursi(1,kursiTerisi);
						json.kursi.pilih2 == "" ? checkKursi(2,kursiKosong) : checkKursi(2,kursiTerisi);
						json.kursi.pilih3 == "" ? checkKursi(3,kursiKosong) : checkKursi(3,kursiTerisi);
						json.kursi.pilih4 == "" ? checkKursi(4,kursiKosong) : checkKursi(4,kursiTerisi);
						json.kursi.pilih5 == "" ? checkKursi(5,kursiKosong) : checkKursi(5,kursiTerisi);
						json.kursi.pilih6 == "" ? checkKursi(6,kursiKosong) : checkKursi(6,kursiTerisi);
						json.kursi.pilih7 == "" ? checkKursi(7,kursiKosong) : checkKursi(7,kursiTerisi);
						json.kursi.pilih8 == "" ? checkKursi(8,kursiKosong) : checkKursi(8,kursiTerisi);
						json.kursi.pilih9 == "" ? checkKursi(9,kursiKosong) : checkKursi(9,kursiTerisi);
						json.kursi.pilih10 == "" ? checkKursi(10,kursiKosong) : checkKursi(10,kursiTerisi);
						json.kursi.pilih11 == "" ? checkKursi(11,kursiKosong) : checkKursi(11,kursiTerisi);
						json.kursi.pilih12 == "" ? checkKursi(12,kursiKosong) : checkKursi(12,kursiTerisi);
					}
				}
			});
		} else {
			if ($("#tanggal").val() == "") {
				$("#sisaKursi").html("opps, tanggal kosong.!");
				$("#sisaKursi").removeClass("text-purple");
				$("#sisaKursi").addClass("text-red");
			}
				
			for (var i = 1; i <= 12; i++) {
				checkKursi(i,kursiKosong);
			}
		}	
	});

	function checkKursi(no,kursiNya) {
		$("#pilihKursi"+no).attr("src",kursiNya);
		if (kursiNya == kursiTerisi) {
			$("#pilihKursi"+no).attr("title","Kursi "+no+" sudah terisi");
		}
	}

	$("#nohp").keypress(function() {
		if ($("#nohp").val() != "") {
			$("#errorNohp").html("");
		}
	});

	/* INGAT SELANJUTNYA BUAT PROSESS PEMESANAN TIKET*/
	$("#btnProsess").click(function() {
		$("#btnProsess").attr("disabled",true);
		$("#btnProsess").html("Loading... <i class='fa fa-spinner fa-spin'></i>");
		$.ajax({
			url: '<?php echo site_url("pemesanan/prosess_ajax"); ?>',
			type: 'POST',
			dataType: 'json',
			data: $("#formProsess").serialize(),
			success: function(json) {
				if (json.status == true) {
					$("#inputMessage").html(json.message);
						url = "<?php echo site_url('history/cetakPdf/'); ?>"+json.id_history;
						window.open(url);

					setTimeout(function() {
						$("#btnProsess").attr("disabled",false);
						$("#btnProsess").html("<i class='fa fa-check-circle'></i> Prosess Pemesanan");
						document.location.href = '<?php echo site_url("pemesanan"); ?>';
					},2500);
				} else {
					if (json.message == "") {
						$("#errorTanggal").html(json.error.tanggal);
						$("#errorTujuan").html(json.error.tujuan);
						$("#errorAsal").html(json.error.asal);
						$("#errorJam").html(json.error.jamkeberangkatan);
						$("#errorBus").html(json.error.bus);
						$("#errorNohp").html(json.error.nohp);
						$("#errorPenumpang").html(json.errorPenumpang);
						$("#inputMessage").html(json.message);
						$("#errorKursi").html(json.errorKursi);
						
						setTimeout(function() {
							$("#btnProsess").attr("disabled",false);
							$("#btnProsess").html("<i class='fa fa-check-circle'></i> Prosess Pemesanan");
							$("#errorPenumpang").html("");
						},5000);
					}

					if (json.errorJumlah) {
						$("#errorPenumpang").html(json.errorJumlah);
						if ($("#nohp").val() != "") {
							$("#errorNohp").html("");
						}
						setTimeout(function() {
							$("#errorPenumpang").html("");
							$("#btnProsess").attr("disabled",false);
							$("#btnProsess").html("<i class='fa fa-check-circle'></i> Prosess Pemesanan");
							$("#errorPenumpang").html("");
						},3000);
					}
					if (json.errorKursi) {
						$("#errorPenumpang").html(json.message);
						$("#errorKursi").html(json.errorKursi);
						if ($("#nohp").val() != "") {
							$("#errorNohp").html("");
						}
						setTimeout(function() {
							$("#btnProsess").attr("disabled",false);
							$("#btnProsess").html("<i class='fa fa-check-circle'></i> Prosess Pemesanan");
							$("#errorPenumpang").html("");
							$("#errorKursi").html("");
						},3000);
					}

					if (json.errorTanggalPemesanan) {
						$("#inputMessage").html(json.errorTanggalPemesanan);
						$("#errorTanggal").html(json.message);
						setTimeout(function() {
							$("#btnProsess").attr("disabled",false);
							$("#btnProsess").html("<i class='fa fa-check-circle'></i> Prosess Pemesanan");
						},1000);
					}
				}
			}
		});
	});
 	
	$("#btnAddJumlahOrang").click(function() {
		var nomor = $("#jumlahPenumpang div div").length + 1;
		var fieldWrapper = $('<div class="col-md-4" id="col" style="margin-bottom: 15px;">');
		var inputGroup = $('<div class="input-group" id="field-'+nomor+'">');
		var inputNama = $('<input type="text" name="nama[]" class="form-control" id="nama" placeholder="Nama penumpang">');
		var selectKelamin = $('<select name="jenis_kelamin[]" class="form-control">');
		var optionPilih = $('<option value="">--jenis kelamin--</option>');
		var optionMale = $('<option value="laki-laki">Laki-laki</option>');
		var optionFemale = $('<option value="perempuan">Perempuan</option>');
		var inputUmur = $('<input type="number" name="umur[]" min="0" max="150" class="form-control" id="umur" placeholder="Umur">');
		var spanRemove = $('<span class="input-group-addon btn btn-danger btn-flat remove" id="btnRemove">');
		var iRemove = $('<i class="fa fa-times"></i>');

		selectKelamin.append(optionPilih);
		selectKelamin.append(optionMale);
		selectKelamin.append(optionFemale);

		spanRemove.append(iRemove);

		inputGroup.append(inputNama);
		inputGroup.append(selectKelamin);
		inputGroup.append(inputUmur);
		inputGroup.append(spanRemove);

		fieldWrapper.append(inputGroup);
		$("#jumlahPenumpang").append(fieldWrapper);
	});

	$("#jumlahPenumpang").on('click','span#btnRemove',function(event) {
		event.preventDefault();
		$(this).parent().parent().remove();
	});


	/* for pilih gambar kursi bus */
	var image;
	$(document).ready(function() {

	  	var chk;
	  	function kursiNomor(no) {
	  		image = document.getElementById("pilihKursi"+no);
			if (!image.src.match("terisi")) {
	  			chk = $('#check_img'+no).get(0);
				$("#pilihKursi"+no).attr("src",!chk.checked ? kursiPilih : kursiKosong);
				$('#check_img'+no).prop('checked', !chk.checked);

				checked = $(".icheckbox_minimal").addClass("checked");
				unchecked = $(".icheckbox_minimal").removeClass("checked");
				!chk.checked ? checked : unchecked;	

				$(".icheckbox_minimal").attr("aria-checked",!chk.checked ? false : true);
			} else {
				$("#pilihKursi"+no).attr("title","Kursi "+no+" sudah terisi");
			}
	  	}

		$('#pilihKursi1').click(function () {
			kursiNomor(1);
		});
		$("#pilihKursi2").click(function() {
			kursiNomor(2);
		});
		$('#pilihKursi3').click(function () {
			kursiNomor(3);
		});
		$("#pilihKursi4").click(function() {
			kursiNomor(4);
		});
		$('#pilihKursi5').click(function () {
			kursiNomor(5);
		});
		$("#pilihKursi6").click(function() {
			kursiNomor(6);
		});
		$('#pilihKursi7').click(function () {
			kursiNomor(7);
		});
		$('#pilihKursi8').click(function () {
			kursiNomor(8);
		});
		$("#pilihKursi9").click(function() {
			kursiNomor(9);
		});
		$('#pilihKursi10').click(function () {
			kursiNomor(10);
		});
		$("#pilihKursi11").click(function() {
			kursiNomor(11);
		});
		$("#pilihKursi12").click(function() {
			kursiNomor(12);
		});

	});

</script>