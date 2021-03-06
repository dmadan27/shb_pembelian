<?php
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../library/datatable.php");

	include_once("../models/Pembelian_model.php");
	include_once("../models/Bahan_baku_model.php");
	include_once("../models/Supplier_model.php");
	include_once("../models/Analisa_harga_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;
	$id = isset($_POST['id']) ? $_POST['id'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				listPembelian($koneksi);
				break;
			
			case 'tambah':
				actionAdd($koneksi);
				break;

			case 'getedit':
				getEdit($koneksi, $id);
				break;

			case 'edit':
				actionEdit($koneksi);
				break;

			case 'getview':
				getView($koneksi, $id);
				break;

			case 'gethapus':
				getHapus($koneksi, $id);
				break;

			case 'get_invoice_pembelian':
				get_invoice_pembelian($koneksi);
				break;

			case 'get_select_supplier':
				get_select_supplier($koneksi);
				break;

			case 'get_select_analisa_harga':
				get_select_analisa_harga($koneksi);
				break;

			case 'get_select_bahanbaku':
				get_select_bahanBaku($koneksi);
				break;

			case 'get_jenis_pph':
				get_jenis_pph($koneksi);
				break;

			default:
				# code...
				break;
		}
	}

	function listPembelian($koneksi){
		$config_db = array(
			'tabel' => 'v_pembelian',
			'kolomOrder' => array(null, 'tgl_pembelian', 'invoice', 'nama_supplier', 'bahan_baku', 'colly', 'jumlah', 'harga_beli', 'pph', 'total', 'status', null),
			'kolomCari' => array('tgl_pembelian', 'invoice', 'nama_supplier', 'bahan_baku', 'colly', 'jumlah', 'harga_beli', 'pph', 'total', 'status'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_pembelian = get_datatable_pembelian($koneksi, $config_db);

		session_start();

		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_pembelian as $row){
			$no_urut++;
			
			$btnAksi = array(
				'view' => '<button type="button" class="btn btn-info btn-outline btn-circle m-r-5" title="Lihat Detail Data" onclick="getView('."'".$row["id"]."'".')"><i class="ti-zoom-in"></i></button>',
				'edit' => '<button type="button" class="btn btn-info btn-outline btn-circle m-r-5" title="Edit Data" onclick="getEdit('."'".$row["id"]."'".')"><i class="ti-pencil-alt"></i></button>',
				'hapus' => '<button type="button" class="btn btn-danger btn-outline btn-circle m-r-5" title="Hapus Data" onclick="getHapus('."'".$row["id"]."'".')"><i class="ti-trash"></i></button>',
				'status' => '<button type="button" class="btn btn-primary btn-outline btn-circle m-r-5" title="Ubah Status" onclick="getStatus('."'".$row["id"]."'".')"><i class="ti-check-box"></i></button>',
			);
			$aksi = get_btn_aksi('pembelian', $_SESSION['sess_akses_menu'], $btnAksi);

			$supplier = $row['npwp'].' - '.$row['nama_supplier'];
			if(empty($row['npwp'])){ // jika nik kosong
				// jika npwp ada
				$supplier = (!empty($row['nik'])) ? $row['nik'].' (NIK) - '.$row['nama_supplier'] : $row['nama_supplier'];
			}

			$status = strtolower($row['status'])=='lunas' ? '<span class="label label-success label-rouded">'.$row['status'].'</span>' : '<span class="label label-info label-rouded">'.$row['status'].'</span>';

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = cetakTgl($row['tgl_pembelian'], 'full');
			$dataRow[] = $row['invoice'];
			$dataRow[] = $supplier;
			$dataRow[] = cetakListItem($row['bahan_baku']);
			$dataRow[] = cetakListItem($row['colly']);
			$dataRow[] = cetakListItem($row['jumlah']);
			$dataRow[] = cetakListItem($row['harga_beli']);
			$dataRow[] = rupiah($row['pph']);
			$dataRow[] = rupiah($row['total']);
			$dataRow[] = $status;
			$dataRow[] = $aksi;

			$data[] = $dataRow;
		}

		$output = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => recordTotal($koneksi, $config_db),
			'recordsFiltered' => recordFilter($koneksi, $config_db),
			'data' => $data,
		);

		echo json_encode($output);
	}

	function actionAdd($koneksi){
		$dataPembelian = isset($_POST['dataPembelian']) ? json_decode($_POST['dataPembelian'],true) : false;
		$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'],true) : false;

		// validasi
			$status = $errorDB = $cekArray = false;
			if($dataDetail){ // cek isi list komposisi ada / tidak
				if(cekArray($dataDetail)) $cekArray = false; // array kosong
				else $cekArray = true;
			}

			$configData = setRule_validasi($dataPembelian);
			$validasi = set_validasi($configData);
			$cek = $validasi['cek'];
			$setError = $validasi['setError'];
			$setValue = $validasi['setValue'];

			if(!$cekArray) $cek = false;

		// =============================== //
		if($cek){
			$dataPembelian = array(
				'id_pembelian' => validInputan($dataPembelian['id_pembelian'], false, false),
				'tgl' => validInputan($dataPembelian['tgl'], false, false),
				'invoice' => validInputan($dataPembelian['invoice'], false, false),
				'supplier' => validInputan($dataPembelian['supplier'], false, false),
				'status' => validInputan($dataPembelian['status'], false, false),
				'jenis_pembayaran' => validInputan($dataPembelian['jenis_pembayaran'], false, false),
				'jenis_pph' => validInputan($dataPembelian['jenis_pph'], false, false),
				'ket' => (empty($dataPembelian['ket'])) ? NULL : validInputan($dataPembelian['ket'], false, false),
				'pph' => validInputan($dataPembelian['pph'], false, false),
				'total' => validInputan($dataPembelian['total'], false, false),
				'total_pph' => validInputan($dataPembelian['total_pph'], false, false),
			);

			// insert pembelian
			if(insertPembelian($koneksi, $dataPembelian)){
				foreach($dataDetail as $index => $array){
					// insert hanya yg statusnya bukan hapus
					if($dataDetail[$index]['status'] != "hapus"){
						$dataInsert['tgl'] = $dataPembelian['tgl'];
						// get data list item
						foreach ($dataDetail[$index] as $key => $value) {
							$dataInsert[$key] = $value;
						}
						insertDetail_pembelian($koneksi, $dataInsert);
					}
				}
				$status = true;
				session_start();
				$_SESSION['notif'] = "Tambah Data Berhasil";
			}
			else{
				$status = false;
				$errorDB = true;
			}
		}
		else $status = false;

		$output = array(
			'data_pembelian' => $dataPembelian,
			'data_detail' => $dataDetail,
			'status' => $status,
			'cek' => $cek,
			'cekList' => $cekArray,
			'setError' => $setError,
			'setValue' => $setValue,
		);

		// $output = array(
		// 	'dataPembelian' => $dataPembelian,
		// 	'dataDetail' => $dataDetail,
		// );

		echo json_encode($output);
	}

	function getHapus($koneksi, $id){
		$cek = false;

		// get data detail pembelian
		$dataDetail = getDetail_pembelian_by_id_pembelian($koneksi, $id);

		foreach($dataDetail as $index => $array){
			foreach ($dataDetail[$index] as $key => $value) {
				$dataDelete[$key] = $value;
			}
			if(deleteDetail_pembelian($koneksi, $dataDelete)) $cek = true;
		}

		if($cek){
			if(deletePembelian_bahan_baku($koneksi, $id))$status = true;
			else $status = false;
		}
		else $status = false;

		echo json_encode($status);
	}

	function get_invoice_pembelian($koneksi){
		$invoice = get_inc_invoice_pembelian($koneksi);
		echo json_encode($invoice);
	}

	// function get select supplier
	function get_select_supplier($koneksi){
		$data_supplier = get_all_supplier($koneksi);
		$data = array(
			array(
				'value' => "",
				'text' => "-- Pilih Supplier --",
			),
		);
		foreach ($data_supplier as $row) {
			$text = $row['npwp'].' - '.$row['nama'];

			if(empty($row['npwp'])){ // jika nik kosong
				// jika npwp ada
				$text = (!empty($row['nik'])) ? $row['nik'].' (NIK) - '.$row['nama'] : $row['nama'];
			}
			$dataRow = array();
			$dataRow['value'] = $row['id'];
			$dataRow['text'] = $text;

			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	// function get analisa harga yg belum dibayar
	function get_select_analisa_harga($koneksi){
		$id_supplier = isset($_POST['id_supplier']) ? $_POST['id_supplier'] : false;
		$data_analisa = get_analisaHarga_belumBayar_byId($koneksi, $id_supplier);
		$data = array(
			array(
				'value' => "",
				'text' => "-- Pilih Analisa Harga --",
			),
		);

		foreach($data_analisa as $row){
			$dataRow = array();

			$dataRow['value'] = $row['id_analisa_harga'];
			$dataRow['text'] = $row['kd_kir']." - ".$row['harga_beli'];

			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	// function get select bahan baku
	function get_select_bahanBaku($koneksi){
		$data_bahan_baku = get_all_bahan_baku($koneksi);
		$data = array(
			array(
				'value' => "",
				'text' => "-- Pilih Bahan Baku --",
			),
		);
		foreach ($data_bahan_baku as $row) {
			$dataRow = array();

			if((strtolower($row['kd_bahan_baku'])=="kp-asl") || (strtolower($row['kd_bahan_baku'])=="ld-asl")){
				$dataRow['value'] = $row['id'];
				$dataRow['text'] = $row['kd_bahan_baku']." - ".$row['nama'];

				$data[] = $dataRow;
			}
		}

		echo json_encode($data);
	}

	function get_jenis_pph($koneksi){
		$id_supplier = isset($_POST['id_supplier']) ? $_POST['id_supplier'] : false;

		// get npwp
		$data_supplier = getSupplier_by_id($koneksi, $id_supplier);

		$npwp = empty($data_supplier['npwp']) ? 0.05 : 0.025;

		echo json_encode($npwp);
	}

	function setRule_validasi($data){
		$ruleData = array(
			// tgl
			array(
				'field' => $data['tgl'], 'label' => 'Tanggal', 'error' => 'tglError',
				'value' => 'tgl', 'rule' => 'string | 1 | 25 | required',
			),
			// invoice
			array(
				'field' => $data['invoice'], 'label' => 'Invoice Pembelian', 'error' => 'invoiceError',
				'value' => 'invoice', 'rule' => 'string | 1 | 25 | required',
			),
			// id_supplier
			array(
				'field' => $data['supplier'], 'label' => 'Supplier', 'error' => 'supplierError',
				'value' => 'supplier', 'rule' => 'angka | 1 | 9999 | required',
			),
			// jenis_Pembayaran
			array(
				'field' => $data['jenis_pembayaran'], 'label' => 'Jenis Pembayaran', 'error' => 'jenis_pembayaranError',
				'value' => 'jenis_pembayaran', 'rule' => 'string | 1 | 1 | required',
			),
			// jenis_pph
			array(
				'field' => $data['jenis_pph'], 'label' => 'Jenis PPH', 'error' => 'jenis_pphError',
				'value' => 'jenis_pph', 'rule' => 'nilai | 0 | 1 | required',
			),
			// // pph
			// array(
			// 	'field' => $data['pph'], 'label' => 'PPH', 'error' => 'stokError',
			// 	'value' => 'stok', 'rule' => 'nilai | 1 | 999999999999 | '.$required,
			// ),
			// // total
			// array(
			// 	'field' => $data['stok'], 'label' => 'Stok Awal', 'error' => 'stokError',
			// 	'value' => 'stok', 'rule' => 'nilai | 1 | 999999999999 | '.$required,
			// ),
			// ket
			array(
				'field' => $data['ket'], 'label' => 'Keterangan', 'error' => 'ketError',
				'value' => 'ket', 'rule' => 'string | 1 | 255 | not_required',
			),
			// status
			array(
				'field' => $data['status'], 'label' => 'Status', 'error' => 'statusError',
				'value' => 'status', 'rule' => 'string | 1 | 1 | required',
			),
		);

		return $ruleData;
	}