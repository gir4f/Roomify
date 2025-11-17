<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
  bookings: Array,
});

// Fungsi helper untuk format status
const formatStatus = (status) => {
  if (status === 'pending') return { text: 'Menunggu Persetujuan', class: 'bg-yellow-100 text-yellow-800' };
  if (status === 'approved') return { text: 'Disetujui', class: 'bg-green-100 text-green-800' };
  if (status === 'rejected') return { text: 'Ditolak', class: 'bg-red-100 text-red-800' };
  return { text: status, class: 'bg-gray-100 text-gray-800' };
};

// Fungsi helper untuk format tanggal (bisa disesuaikan)
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script>

<template>
  <Head title="Booking Saya" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Booking Saya</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          
          <Link 
            :href="route('bookings.create')" 
            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            + Buat Booking Baru
          </Link>

          <!-- Notifikasi Sukses -->
          <div vD-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ $page.props.flash.success }}
          </div>
          
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="booking in bookings" :key="booking.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <!-- Tampilkan nama ruangan -->
                  <div class="text-sm font-medium text-gray-900">{{ booking.room.name }}</div>
                  <!-- Tampilkan lokasi (jika ada) -->
                  <div class="text-sm text-gray-500">
                    <span v-if="booking.room.gedung">{{ booking.room.gedung }}, </span>
                    <span v-if="booking.room.lantai">Lt. {{ booking.room.lantai }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ booking.title }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(booking.start_time) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(booking.end_time) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="formatStatus(booking.status).class" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ formatStatus(booking.status).text }}
                  </span>
                </td>
              </tr>
              <tr v-if="bookings.length === 0">
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                  Anda belum memiliki booking.
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>