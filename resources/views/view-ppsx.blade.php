<!DOCTYPE html>
<html>
<head>
    <title>View PPSX File with Reveal.js</title>
    <!-- Tambahkan link Reveal.js CSS -->
    <link rel="stylesheet" href=" {{ URL::asset('node_modules/reveal.js/dist/reset.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('node_modules/reveal.js/dist/reveal.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('node_modules/reveal.js/dist/theme/black.css') }}">

    <!-- Tambahkan link Bootstrap CSS CDN untuk tampilan yang lebih baik -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-4">View PPSX File with Reveal.js</h1>
                <p>Klik tautan di bawah untuk melihat presentasi:</p>
                <button class="btn btn-primary" onclick="loadPpsx()">Tampilkan Presentasi</button>
                <div id="pptx-container" class="mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Tambahkan script Reveal.js dan plugin yang diperlukan -->
    <script src="{{ URL::asset('node_modules/reveal.js/dist/reveal.js') }}"></script>
    <script src="{{ URL::asset('node_modules/reveal.js/plugin/zoom/zoom.js') }}"></script>
    <script src="{{ URL::asset('node_modules/reveal.js/plugin/menu/menu.js') }}"></script>

    <script>
        function loadPpsx() {
            var pptxContainer = document.getElementById('pptx-container');
            pptxContainer.innerHTML = ''; // Bersihkan konten sebelumnya (jika ada)

            // Ganti FILE_PRESENTASI dengan nama file presentasi PPSX Anda
            var pptxFilePath = "{{ URL::asset('novita.ppsx') }}";
            var iframe = document.createElement('iframe');
            iframe.src = pptxFilePath;
            iframe.width = '100%';
            iframe.height = '500';
            iframe.frameBorder = '0';

            // Menambahkan atribut allowfullscreen ke iframe
            iframe.setAttribute('allowfullscreen', '');

            pptxContainer.appendChild(iframe);

            // Initialize Reveal.js setelah iframe dimuat
            var reveal = Reveal();
            reveal.initialize();
        }
    </script>

    <!-- Tambahkan link Bootstrap JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
