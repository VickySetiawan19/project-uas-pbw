<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-4">
        <h1 class="mb-4">Input Buku</h1>
        <form method="POST" action="input_data.php">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku:</label>
                <input type="text" id="judul" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun Terbit:</label>
                <input type="number" id="tahun" name="tahun" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="penulis" class="form-label">Nama Penulis:</label>
                <input type="text" id="penulis" name="penulis" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>