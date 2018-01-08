<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>
<!-- Breadcrumb -->
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Data Bahan Baku</h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url; ?>">Beranda</a></li>
            <li>Data Master</li>
            <li class="active">Data Bahan Baku</li>
        </ol>
    </div>
</div>

<!-- panel utama -->
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
        	<div class="panel-heading">List Data Bahan Baku</div>
    	 	<div class="panel-wrapper collapse in">
    	 		<div class="panel-wrapper collapse in">
    	 			<div class="panel-body">
    	 				<!-- panel button -->
    	 				<div class="row">
    	 					<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    	 						<div class="btn-group m-b-30">
    	 							<button id="tambah_bahanBaku" class="fcbtn btn btn-info btn-outline waves-effect waves-light btn-1b" data-toggle="tooltip" data-placement="top" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
    	 							<button id="exportExcel" class="fcbtn btn btn-success btn-outline waves-effect waves-light btn-1b" data-toggle="tooltip" data-placement="top" title="Export Data Ke Excel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
    	 							<button id="exportPdf" class="fcbtn btn btn-danger btn-outline waves-effect waves-light btn-1b" data-toggle="tooltip" data-placement="top" title="Export Data Ke Pdf"><i class="fa fa-file-pdf-o"></i> Export Pdf</button>
    	 						</div>
    	 					</div>
    	 				</div>
	 					<table id="tabel_bahanBaku" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
    	 					<thead>
    	 						<tr>
    	 							<th style="width: 15px">No</th>
    	 							<th>Kode Bahan Baku</th>
    	 							<th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
    	 							<th>Stok</th>
    	 							<th>Aksi</th>
    	 						</tr>
    	 					</thead>
    	 				</table>
    	 			</div>
    	 		</div>
    	 	</div>
        </div>
    </div>
</div>

<!-- Form Modal dan view Supplier -->
<?php 
    include_once('form.php');
?>

<!-- js list -->
<script type="text/javascript" src="<?= base_url."app/views/bahan_baku/js/initList.js"; ?>"></script>
<!-- js form modal -->
<script type="text/javascript" src="<?= base_url."app/views/bahan_baku/js/initForm.js"; ?>"></script>
