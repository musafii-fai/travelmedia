<?php 
/**
* 
*/
class History extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("History_model","historyModel");
		$this->load->model("Pemesanan_model",'pemesananModel');
	}

	public function index()
	{
		$this->headerTitle("History","Laporan pemesanan tiket");
		$this->breadcrumbs(array("History Laporan" => "history"));

		$this->view();
	}

	public function ajax_list_admin()
	{
		return self::ajax_list(array("user_id" => $this->user->id));
	}

	public function ajax_list($user_id=false)
	{
		if ($user_id) {
			$where = $user_id;
		} else {
			$where = false;
		}
		$select = array("history.id","history.tanggal_input","users.nama AS nama_admin","asal_shuttle","kota_asal","tujuan_shuttle","kota_tujuan");
		$orderBy = array(null,"tanggal_input","users.nama","asal_shuttle","kota_asal","tujuan_shuttle","kota_tujuan");
		$search = array(
				"tanggal_input" => $this->input->post("search")["value"],
				"users.nama"	=>	$this->input->post("search")["value"],
				"asal_shuttle"	=>	$this->input->post("search")["value"],
				"kota_asal"	=>	$this->input->post("search")["value"],
				"tujuan_shuttle"	=>	$this->input->post("search")["value"],
				"kota_tujuan"	=>	$this->input->post("search")["value"],
			);
		$join = array(
					array("users","users.id = history.user_id","LEFT"),
					array("perjalanan","perjalanan.id = history.perjalanan_id","left"),
			);
		// return $this->historyModel->findDataTableObject($where,$select,$orderBy,$search,$join);

		$input = $this->input;
		$result = $this->historyModel->findDataTable($where,$select,$orderBy,$search,$join);
		$data = array();
		$no = $input->post("start");
		foreach ($result as &$item) {
			$no++;
			$item->no = $no;
			$item->tanggal_input = date_ind("l, d F Y,  H:i:s", $item->tanggal_input);
			$data[] = $item;
		}
		return $this->historyModel->findDataTableOutput($data,$where,$search,$join);
	}

	public function historyData($id)
	{
		$selectHistory = array("users.nama","shuttle.nama AS nama_shuttle","kota","tanggal_input","asal_shuttle","kota_asal","tujuan_shuttle","kota_tujuan","transaksi_tiket_id","bus_no_polisi","bus_nama_supir");
		$joinHistory = array(
						array("users","users.id = history.user_id","LEFT"),
						array("shuttle","shuttle.id = users.shuttleid","LEFT"),
						array("perjalanan","perjalanan.id = history.perjalanan_id","LEFT"),
					);
		$history = $this->historyModel->getByWhere(array("history.id" => $id),$selectHistory,$joinHistory);
		$history->tanggal_input = date_ind("l, d F Y, H:i:s", $history->tanggal_input);
		return $history;
	}

	public function detail($id)
	{
		if ($this->isPost()) {
			$history = self::historyData($id);

			$select = array("transaksi_tiket.*","jam","users.nama as admin","traveller.*",);
			$orderBy = array("traveller.nama ASC");
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),
				);
			$whereIn = explode(" ",$history->transaksi_tiket_id);
			$whereIn = array("transaksi_tiket.id" => $whereIn);
			
			$data = $this->pemesananModel->findData(false,$select,$orderBy,false,0,0,true,$join,$whereIn);
			$no=1;
			foreach ($data as $item) {
				$item->no = $no++;
				$item->tanggal_pemesanan = date_ind("l, d F Y", $item->tanggal_pemesanan);
				$item->harga_nominal = "Rp.".number_format($item->harga,0,",",".");
				$item->jam = "Keberangkatan <b>".$item->jam."</b>";
			}

			$headData = array(
						"admin" 		 => $history->nama,
						"shuttle_admin"	 =>	"<i>".$history->nama_shuttle."</i> [ ".$history->kota." ]</u>",
						"tanggal_input"  => $history->tanggal_input,
						"asal_shuttle"	 =>	$history->asal_shuttle." [ <b>".$history->kota_asal."</b> ]",
						"tujuan_shuttle" =>	$history->tujuan_shuttle." [ <b>".$history->kota_tujuan."</b> ]",
						"bus_no_polisi"	 =>	$history->bus_no_polisi,
						"bus_nama_supir" =>	$history->bus_nama_supir,
					);
			if ($data) {
				$this->response->status = true;
				$this->response->headData = $headData;
				$this->response->data = $data;
				$totalBayar = count($data) * $data[0]->harga;
				$this->response->totalBayar = "Rp.".number_format($totalBayar,0,",",".");
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada, atau sudah di hapus oleh <u>Admin : <i>".$history->nama."</i></u>, dari <u>Shuttle : <i>".$history->nama_shuttle."</i> [ ".$history->kota." ]</u>");
				$this->response->headData = $headData;
			}

			return $this->json();
		}
	}

	public function headerPdf($pdf,$history)
	{
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Nama Admin ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->nama,0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Shuttle Admin ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->nama_shuttle." [ ".$history->kota." ] ",0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Tanggal Input ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->tanggal_input,0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Asal Shuttle ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->asal_shuttle." [ ".$history->kota_asal." ] ",0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Tujuan Shuttle ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->tujuan_shuttle." [ ".$history->kota_tujuan." ] ",0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"No Polisi Bus ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->bus_no_polisi,0,0,"L");
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(30,5,"Nama Supir Bus ",0,0,"L");
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(85,5,":    ".$history->bus_nama_supir,0,0,"L");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont("Arial","",10);
	}

	public function cetakPdf($id)
	{
		$this->load->library("libfpdf");

		$history = self::historyData($id);

		if($history) {
			$select = array("transaksi_tiket.*","jam","users.nama as admin","traveller.*",);
			$orderBy = array("traveller.nama ASC");
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),
				);
			$whereIn = explode(" ",$history->transaksi_tiket_id);
			$whereIn = array("transaksi_tiket.id" => $whereIn);
			
			$data = $this->pemesananModel->findData(false,$select,$orderBy,false,0,0,true,$join,$whereIn);
			$totalBayar = count($data) * $data[0]->harga;
			$totalBayar = "Rp.".number_format($totalBayar,0,",",".");

			$pdf = new FPDF("L","mm","A4");
			$pdf->SetTitle("TravelMedia_".$history->tanggal_input);
			$pdf->AddPage();

			self::headerPdf($pdf,$history); // header
			/* Field Column */
			$pdf->Cell(35,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- ",0,0,"L");
			$pdf->Ln();
			$pdf->SetFont("Arial","B",10);
			$pdf->Cell(8,5,"No",0,0,"L");
			$pdf->Cell(46,5,"Tanggal Pemesanan",0,0,"L");
			$pdf->Cell(38,5,"Jam",0,0,"L");
			$pdf->Cell(55,5,"Nama Pemesanan",0,0,"L");
			$pdf->Cell(27,5,"Jenis Kelamin",0,0,"L");
			$pdf->Cell(18,5,"Umur",0,0,"L");
			$pdf->Cell(28,5,"No Hp",0,0,"L");
			$pdf->Cell(21,5,"No Kursi",0,0,"L");
			$pdf->Cell(30,5,"Harga",0,0,"L");
			$pdf->SetFont("Arial","",10);
			$pdf->Ln();
			$pdf->Cell(35,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- ",0,0,"L");

			$no=1;
			$pdf->SetFont("Arial","",9);
			foreach ($data as $item) {
				$item->harga_nominal = "Rp.".number_format($item->harga,0,",",".");
				$item->tanggal_pemesanan = date_ind("l, d F Y", $item->tanggal_pemesanan);
				$item->jam = "Keberangkatan ".$item->jam;
				$item->nama = strlen($item->nama) >= 30 ? substr($item->nama, 0, 30)."..." : $item->nama;

				$pdf->Ln();
				$pdf->Cell(8,8,$no++,0,0,"L");
				$pdf->Cell(46,8,$item->tanggal_pemesanan,0,0,"L");
				$pdf->Cell(38,8,$item->jam,0,0,"L");
				$pdf->Cell(55,8,$item->nama,0,0,"L");
				$pdf->Cell(25,8,$item->jenis_kelamin,0,0,"L");
				$pdf->Cell(20,8,$item->umur." tahun",0,0,"C");
				$pdf->Cell(18,8,$item->no_hp,0,0,"L");
				$pdf->Cell(39,8,$item->nokursi,0,0,"C");
				$pdf->Cell(35,8,$item->harga_nominal,0,0,"L");
			}
			$pdf->SetFont("Arial","",10);
			$pdf->Ln();
			$pdf->Cell(35,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- ",0,0,"L");
			$pdf->Ln();
			$pdf->SetFont("Arial","B",10);
			$pdf->Cell(237,5,"Total Bayar :",0,0,"R");
			$pdf->Cell(31,5,$totalBayar,0,0,"R");
			$pdf->Ln();
			$pdf->SetFont("Arial","",10);
			$pdf->Cell(35,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- ",0,0,"L");
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(130, 5, "Terimakasih telah mempercayai jasa travel kepada kami.", 0, 0, 'L');
		
			$pdf->Output("I","TravelMedia_".$history->tanggal_input.".pdf");
		} else {
			echo "<h1 style='color:red;'>Mau cari apa..?<br>Kalok tidak ada kembali saja jangan banyak tingkah..!</h1>";
		}
	}

	public function cetakPdfTable($id)
	{
		$this->load->library("libfpdf");

		$history = self::historyData($id);
		if($history) {
			$select = array("transaksi_tiket.*","jam","users.nama as admin","traveller.*",);
			$orderBy = array("traveller.nama ASC");
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),
				);
			$whereIn = explode(" ",$history->transaksi_tiket_id);
			$whereIn = array("transaksi_tiket.id" => $whereIn);
			
			$data = $this->pemesananModel->findData(false,$select,$orderBy,false,0,0,true,$join,$whereIn);

			$pdf = new FPDF("L","mm","A4");
			$pdf->SetTitle("TravelMedia_".$history->tanggal_input);
			$pdf->AddPage();

			self::headerPdf($pdf,$history); // header
			/* header column table */
			$pdf->SetFont("Arial","B",10);
			$pdf->Cell(8,8,"No",1,0,"L");
			$pdf->Cell(46,8,"Tanggal Pemesanan",1,0,"L");
			$pdf->Cell(38,8,"Jam",1,0,"L");
			$pdf->Cell(55,8,"Nama Pemesanan",1,0,"L");
			$pdf->Cell(27,8,"Jenis Kelamin",1,0,"L");
			$pdf->Cell(18,8,"Umur",1,0,"L");
			$pdf->Cell(35,8,"No Hp",1,0,"L");
			$pdf->Cell(21,8,"No Kursi",1,0,"L");
			$pdf->Cell(37,8,"Harga",1,0,"L");
			/* End header column table */

			$no=1;

			$pdf->SetFont("Arial","",9);
			if($data){
				$totalBayar = count($data) * $data[0]->harga;
				$totalBayar = "Rp.".number_format($totalBayar,0,",",".");
				foreach ($data as $item) {
					$item->tanggal_pemesanan = date_ind("l, d F Y", $item->tanggal_pemesanan);
					$item->harga_nominal = "Rp.".number_format($item->harga,0,",",".");
					$item->jam = "Keberangkatan ".$item->jam;
					$item->nama = strlen($item->nama) >= 30 ? substr($item->nama, 0, 30)."..." : $item->nama;

					$pdf->Ln();
					$pdf->Cell(8,8,$no++,1,0,"L");
					$pdf->Cell(46,8,$item->tanggal_pemesanan,1,0,"L");
					$pdf->Cell(38,8,$item->jam,1,0,"L");
					$pdf->Cell(55,8,$item->nama,1,0,"L");
					$pdf->Cell(27,8,$item->jenis_kelamin,1,0,"L");
					$pdf->Cell(18,8,$item->umur." tahun",1,0,"C");
					$pdf->Cell(35,8,$item->no_hp,1,0,"L");
					$pdf->Cell(21,8,$item->nokursi,1,0,"C");
					$pdf->Cell(37,8,$item->harga_nominal."  ",1,0,"R");
				}

				$pdf->Ln();
				$pdf->SetFont("Arial","B",10);
				$pdf->Cell(248,8,"Total Bayar ",1,0,"R");
				$pdf->Cell(37,8,$totalBayar."  ",1,0,"R");
				$pdf->Ln();
			} else {
				$pdf->Ln();
				$pdf->Cell(275,8,"Data sudah tidak ada atau sudah di hapus. ",1,0,"C");
			}

			$pdf->SetFont("Arial","",10);
			$pdf->Ln();
			$pdf->Cell(130, 5, "*Terimakasih telah mempercayai jasa travel kepada kami.", 0, 0, 'L');

			$pdf->Output("I","TravelMedia_".$history->tanggal_input.".pdf");
		} else {
			echo "<h1 style='color:red;'>Mau cari apa..?<br>Kalok tidak ada kembali saja jangan banyak tingkah..!</h1>";
		}
	}

	public function delete($id)
	{
		$this->load->model("Perjalanan_model","perjalananModel");
		if ($this->isPost()) {

			$history = $this->historyModel->getById($id);
			if ($history) {
				$deleteHistory = $this->historyModel->delete($history->id);
				$deletePerjalanan = $this->perjalananModel->delete($history->perjalanan_id);
				$this->response->status = true;
				$this->response->message = alertSuccess("Data Berhasil di hapus..!");
				$this->response->deleteHistory = $deleteHistory;
				$this->response->deletePerjalanan = $deletePerjalanan;
			} else {
				$this->response->message = alertDanger("Opps, Data Gagal di hapus..!");
			}
			return parent::json();
		}
	}
}