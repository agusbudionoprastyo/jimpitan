<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Transaksi</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Form Input Transaksi</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal">
            Tambah Transaksi
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Form Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dataForm">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode</label>
                            <input type="number" class="form-control" id="kode" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label for="reff" class="form-label">Reff</label>
                            <textarea class="form-control" id="reff" name="reff" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

    <!-- jQuery (Optional for extra functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Event listener untuk form submit
        $('#dataForm').on('submit', function(e) {
            e.preventDefault();  // Mencegah refresh halaman

            // Ambil data input dari form
            const tanggal = $('#tanggal').val();
            const kode = $('#kode').val();
            const reff = $('#reff').val();

            // Tampilkan data di konsol (bisa disesuaikan)
            console.log(`tanggal: ${tanggal}, kode: ${kode}, reff: ${reff}`);

            // Reset form dan tutup modal
            $(this).trigger('reset');
            $('#inputModal').modal('hide');
        });
    </script>
</body>
</html>
