<!DOCTYPE html>
<html>
<head>
    <title>View PPSX File</title>
    <!-- Tambahkan link Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-4">TEST</h1>
                <p>Klik tautan di bawah untuk melihat presentasi:</p>
                <button class="btn btn-primary" onclick="loadPpsx()">Tampilkan Presentasi</button>
                <div id="pptx-container" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        function loadPpsx() {
            var pptxContainer = document.getElementById('pptx-container');
            pptxContainer.innerHTML = ''; // Bersihkan konten sebelumnya (jika ada)

            // Ganti URL_PRESENTASI dengan URL presentasi PPSX dari Google Slides
            var pptxFileUrl = "https://docs.google.com/presentation/d/1dAYala7R5jP8oUETUo4X7iygBj8894poksBRoP5Pu0A/embed";
            var iframe = document.createElement('iframe');
            iframe.src = pptxFileUrl;
            iframe.width = '100%';
            iframe.height = '500';
            iframe.frameBorder = '0';

            // Menambahkan atribut allowfullscreen ke iframe
            iframe.setAttribute('allowfullscreen', '');

            pptxContainer.appendChild(iframe);
        }
    </script>

    <!-- Tambahkan link Bootstrap JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
