<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
  rooms: Array
});

const form = useForm({});

const deleteRoom = (id) => {
  if (confirm('Anda yakin ingin menghapus ruangan ini?')) {
    form.delete(route('rooms.destroy', id));
  }
};
</script>

<!-- ... (script setup) ... -->
<template>
  <Head title="Manajemen Ruangan" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Ruangan</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          
        <div class="flex justify-start mb-4">
          <Link
            :href="route('rooms.create')"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
          >
            + Tambah Ruangan
          </Link>
        </div>
          <!-- ... (Tombol Tambah & Notifikasi) ... -->
          
          <!-- Tabel Ruangan -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ruangan</th>
                
                <!-- === KOLOM BARU === -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                <!-- ================ -->

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="room in rooms" :key="room.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ room.name }}</td>
                
                <!-- === DATA BARU === -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ room.gedung }}</div>
                  <div class="text-sm text-gray-500">Lantai {{ room.lantai }}</div>
                </td>
                <!-- ================ -->

                <td class="px-6 py-4 whitespace-nowrap">{{ room.capacity }} orang</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <Link :href="route('rooms.edit', room.id)" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                  <button @click="deleteRoom(room.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
              </tr>
              <!-- ... (v-if rooms.length === 0) ... -->
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>