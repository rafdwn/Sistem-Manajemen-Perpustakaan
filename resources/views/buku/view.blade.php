<div class="modal fade" id="modalView" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-custom">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="viewTitle" class="modal-title font-weight-bold"></h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <div class="d-flex" style="gap:30px;">
                    
                    <!-- Cover -->
                    <img id="imgCover" src="" 
                        style="height:300px; border-radius:10px; object-fit:cover;">

                    <!-- Detail -->
                    <table class="table table-borderless" style="font-size:17px;">
                        <tr><th>ID Buku</th><td id="viewIdBuku"></td></tr>
                        <tr><th>ISBN</th><td id="viewIsbn"></td></tr>
                        <tr><th>Pengarang</th><td id="viewPengarang"></td></tr>
                        <tr><th>Penerbit</th><td id="viewPenerbit"></td></tr>
                        <tr><th>Tahun</th><td id="viewTahun"></td></tr>
                        <tr><th>Stok</th><td id="viewStok"></td></tr>
                        <tr><th>Tersedia</th><td id="viewTersedia"></td></tr>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
