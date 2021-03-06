$(document).ready(function(){
    //  if(notif == "gagal") {
    //     $.toast({
    //         heading: 'Pesan Error',
    //         text: 'Data Tidak Ditemukan',
    //         position: 'top-right',
    //         loaderBg: '#ff6849',
    //         icon: 'error',
    //         hideAfter: 3000,
    //         stack: 6
    //     });
    // }
    // else if(notif != false) {
    //     $.toast({
    //         heading: 'Pesan Berhasil',
    //         text: notif,
    //         position: 'top-right',
    //         loaderBg: '#ff6849',
    //         icon: 'success',
    //         hideAfter: 3000,
    //         stack: 6
    //     });
    // };

	// setting datatable
    var tabel_stok_produk = $("#tabel_stok_produk").DataTable({
        "language" : {
            "lengthMenu": "Tampilkan _MENU_ data/page",
            "zeroRecords": "Data Tidak Ada",
            "info": "Menampilkan _START_ s.d _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 s.d 0 dari 0 data",
            "search": "Pencarian:",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "lengthMenu": [ 25, 50, 75, 100 ],
        "pageLength": 25,
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"app/controllers/Stok.php",
            type: 'POST',
            data: {
                "action" : "list_stok_produk",
            }
        },
        "columnDefs": [
            {
                "targets":[0, 3, 4, 6, 7], //
                "orderable":false,
            }
        ],
    });


    // $("#exportPdf").click(function(){
    //     createPDF();
    // });

});

// Function export PDF
    // function createPDF(){
    //     getDataPDF(function(data){
    //         var doc = new jsPDF('l');
    //         var totalPagesExp = "{total_pages_count_string}";

    //         var pageContent = function(data_){
    //             // header
    //             doc.setFontSize(14);
    //             doc.setTextColor(40);
    //             doc.setFontStyle('normal');
    //             doc.text("Data Supplier", data_.settings.margin.left, 22);

    //             // footer
    //             var str = "Page " + data_.pageCount;
    //             if (typeof doc.putTotalPages === 'function') {
    //                 str = str + " of " + totalPagesExp;
    //             }
    //             doc.setFontSize(10);
    //             doc.text(str, data_.settings.margin.left, doc.internal.pageSize.height - 10);
    //         };

    //         doc.autoTable(data.columns, data.rows, {
    //             addPageContent: pageContent,
    //             margin: {top: 30, horizontal: 10},
    //             styles: {overflow: 'linebreak', columnWidth: 'wrap'},
    //             columnStyles: {text: {columnWidth: 'auto'}}
    //         });

    //         if (typeof doc.putTotalPages === 'function') {
    //             doc.putTotalPages(totalPagesExp);
    //         }

    //         doc.save('data_supplier.pdf');
    //     });
    // }

    // function getDataPDF(handleData){
    //     // pecah var data menjadi kolom dan basris
    //     $.ajax({
    //         url: base_url+"app/controllers/Supplier.php",
    //         type: "post",
    //         dataType: "json",
    //         data: {
    //             "action": "getPdf",
    //             "jenis": "default",
    //         },
    //         beforeSend: function(){
    //             $.blockUI({
    //                 message: '<h4><img src="'+base_url+'assets/plugins/images/busy.gif" /> Mohon Menunggu...</h4>',
    //                 css: {
    //                     border: '1px solid #fff'
    //                 }
    //             });
    //         },
    //         success: function(data){
    //             $.unblockUI();
    //             handleData(data);
    //         },
    //         error: function (jqXHR, textStatus, errorThrown){
    //             console.log(jqXHR, textStatus, errorThrown);
    //         }
    //     })
    // }
// =============================================


