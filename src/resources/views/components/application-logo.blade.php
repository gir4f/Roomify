<template>
    <!-- Hapus semua kode <svg> yang lama -->
    <!-- Ganti dengan ---------------->
    
    <!--
      Kita menggunakan <img> standar.
      'src="/logo.png"' menunjuk ke 'public/logo.png' Anda.
      'v-bind="$attrs"' PENTING: Ini akan mengambil 'class'
      dari layout induk (GuestLayout/AuthenticatedLayout)
      sehingga logo Anda otomatis punya ukuran yang benar.
    -->
    <img src="/logo-u.png" alt="Roomify Logo" v-bind="$attrs" />

</template>