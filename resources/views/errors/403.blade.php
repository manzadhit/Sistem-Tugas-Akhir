<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>403 – Akses Ditolak</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full text-center">
    <!-- Icon -->
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-amber-100 mb-6">
      <i class="fas fa-lock text-amber-500 text-4xl"></i>
    </div>

    <!-- Code -->
    <p class="text-6xl font-extrabold text-gray-200 mb-2">403</p>

    <!-- Title -->
    <h1 class="text-2xl font-bold text-gray-800 mb-3">Akses Ditolak</h1>

    <!-- Message -->
    <p class="text-gray-500 mb-8">
      {{ $exception->getMessage() ?: 'Anda belum memiliki akses ke halaman ini.' }}
    </p>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="javascript:history.back()"
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-100 transition-all">
        <i class="fas fa-arrow-left"></i>
        Kembali
      </a>
      <a href="{{ url('/') }}"
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-all">
        <i class="fas fa-home"></i>
        Ke Beranda
      </a>
    </div>
  </div>
</body>

</html>
