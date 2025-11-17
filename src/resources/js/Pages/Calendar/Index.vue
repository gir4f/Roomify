<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import FullCalendar from '@fullcalendar/vue3'; // <-- Import FullCalendar
import dayGridPlugin from '@fullcalendar/daygrid'; // <-- Import plugin
import { ref } from 'vue';

// Terima 'events' dari CalendarController
const props = defineProps({
  events: Array
});

// Opsi konfigurasi untuk FullCalendar
const calendarOptions = ref({
  plugins: [ dayGridPlugin ], // Gunakan plugin
  initialView: 'dayGridMonth', // Tampilan awal (bulan)s
  headerToolbar: { // Konfigurasi tombol navigasi
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay' // Tombol ganti view
  },
  events: props.events, // Masukkan data event dari prop
  editable: false, // User tidak bisa drag-drop event
  selectable: false, // User tidak bisa memilih rentang tanggal
  
  // (Opsional) Tampilkan detail event saat di-klik
  eventClick: (info) => {
    alert(
      `Kegiatan: ${info.event.title}\n` +
      `Status: ${info.event.extendedProps.status}\n` +
      `Pemesan: ${info.event.extendedProps.user}\n` +
      `Mulai: ${info.event.start.toLocaleString('id-ID')}\n` +
      `Selesai: ${info.event.end.toLocaleString('id-ID')}`
    );
  }
});
</script>

<style>
/* Perbaiki beberapa style FullCalendar agar cocok dengan Tailwind */
.fc .fc-toolbar-title {
  font-size: 1.25rem !important; /* text-xl */
}
.fc .fc-button {
  background-color: #3B82F6 !important; /* bg-blue-500 */
  border: none !important;
}
.fc .fc-button:hover {
  background-color: #2563EB !important; /* bg-blue-700 */
}
.fc .fc-daygrid-day.fc-day-today {
  background-color: #EEF2FF !important; /* bg-indigo-50 */
}
</style>

<template>
  <Head title="Kalender Ruangan" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kalender Ketersediaan Ruangan</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          
          <!-- Legenda/Keterangan Warna -->
          <div class="flex space-x-4 mb-4">
            <div class="flex items-center">
              <span class="w-4 h-4 rounded-full bg-green-500 mr-2" style="background-color: #10B981;"></span>
              <span>Disetujui (Approved)</span>
            </div>
            <div class="flex items-center">
              <span class="w-4 h-4 rounded-full bg-yellow-500 mr-2" style="background-color: #F59E0B;"></span>
              <span>Menunggu (Pending)</span>
            </div>
          </div>

          <!-- Komponen FullCalendar -->
          <FullCalendar :options="calendarOptions" />
          
        </div>
      </div>
    </div>
  </Authenticatedlayout>
</template>