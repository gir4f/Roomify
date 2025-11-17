<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
  bookings: Array,
});

// Fungsi helper yang sama dari halaman user
const formatStatus = (status) => {
  if (status === 'pending') return { text: 'Menunggu Persetujuan', class: 'bg-yellow-100 text-yellow-800' };
  if (status === 'approved') return { text: 'Disetujui', class: 'bg-green-100 text-green-800' };
  if (status === 'rejected') return { text: 'Ditolak', class: 'bg-red-100 text-red-800' };
  return { text: status, class: 'bg-gray-100 text-gray-800' };
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script>

<template>
  <Head title="Manajemen Booking" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Booking</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          
          <!-- Notifikasi Sukses -->
          <div vD-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ $page.props.flash.success }}
          </div>
          
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan (User)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="booking in bookings" :key="booking.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ booking.user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ booking.room.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ booking.title }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(booking.start_time) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="formatStatus(booking.status).class" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ formatStatus(booking.status).text }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  
                  <!-- TOMBOL AKSI (Hanya tampil jika status masih 'pending') -->
                  <div v-if="booking.status === 'pending'">
                    <!-- Tombol Approve -->
                    <Link
                      :href="route('admin.bookings.approve', booking.id)"
                      method="patch"
                      as="button"
                      class="text-green-600 hover:text-green-900"
                      preserve-scroll
                    >
                      Approve
                    </Link>
                    
                    <!-- Tombol Reject -->
                    <Link
                      :href="route('admin.bookings.reject', booking.id)"
                      method="patch"
                      as="button"
                      class="text-red-600 hover:text-red-900 ml-2"
                      preserve-scroll
                    >
                      Reject
                    </Link>
                  </div>
                </td>
              </tr>
              <tr v-if="bookings.length === 0">
                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                  Belum ada booking yang masuk.
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>