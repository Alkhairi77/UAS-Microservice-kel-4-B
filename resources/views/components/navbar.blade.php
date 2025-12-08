<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ecocycle Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"  rel="stylesheet">
  <style>
    /* Custom styles */
    body {
      background-color: #f4f5f7;
    }
    .bg-image {
      background-image: url('https://source.unsplash.com/random/1600x900/?business');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>
<body class="flex flex-col md:flex-row h-screen">

  <!-- Sidebar -->
  <div class="md:w-1/2 bg-image bg-cover bg-center flex items-center justify-center">
    <img src="https://source.unsplash.com/random/600x600/?teamwork" alt="Business Illustration" class="max-w-lg">
  </div>

  <!-- Form Section -->
  <div class="md:w-1/2 bg-white p-8 flex flex-col items-center justify-center">
    <!-- Logo -->
    <div class="mb-8">
      <img src="https://source.unsplash.com/random/200x200/?logo" alt="Ecocycle Logo" class="w-32">
      <p class="text-green-500 text-sm">Nama Sustainability</p>
    </div>

    <!-- Form -->
    <form class="w-full max-w-sm">
      <div class="mb-4">
        <input type="text" placeholder="Nama" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500">
      </div>
      <div class="mb-4">
        <input type="email" placeholder="Email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500">
      </div>
      <div class="mb-4">
        <input type="password" placeholder="Password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500">
      </div>
      <div class="mb-4">
        <input type="password" placeholder="Konfirmasi Password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500">
      </div>
      <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">Register</button>
    </form>

    <!-- Link to Login -->
    <div class="mt-4 text-center">
      <p>Sudah Punya Akun? <a href="#" class="text-green-500 hover:text-green-600">Masuk sekarang</a></p> 
    </div>
  </div>

</body>
</html>