<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Buku</title>
</head>
<body>
    <h1>Input Buku</h1>
    <form method="POST" action="input_data.php">
        <label for="judul">Judul Buku:</label><br>
        <input type="text" id="judul" name="judul" required><br><br>

        <label for="tahun">Tahun Terbit:</label><br>
        <input type="number" id="tahun" name="tahun" required><br><br>

        <label for="penulis">Nama Penulis:</label><br>
        <input type="text" id="penulis" name="penulis" required><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>