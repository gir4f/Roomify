<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { defineProps, ref, computed, watch } from 'vue'; // <-- Import ref, computed, watch

// Menerima daftar 'rooms' dari BookingController@create
const props = defineProps({
  rooms: Array
});

// State form tetap sama, hanya butuh room_id pada akhirnya
const form = useForm({
  room_id: null,
  title: '',
  date: '',
  start_time: '',
  end_time: '',
});

// === LOGIKA BARU UNTUK DROPDOWN BERTINGKAT ===

// 1. Buat state untuk melacak pilihan filter
const selectedGedung = ref(null);
const selectedLantai = ref(null);

// 2. Buat daftar unik untuk 'Gedung'
const gedungList = computed(() => {
    // Ambil semua nama gedung, hapus yang kosong/null
    const gedungNames = props.rooms.map(room => room.gedung).filter(Boolean); 
    // Kembalikan hanya nama yang unik
    return [...new Set(gedungNames)]; 
});

// 3. Buat daftar 'Lantai' berdasarkan 'Gedung' yang dipilih
const lantaiList = computed(() => {
    if (!selectedGedung.value) return []; // Kosongkan jika gedung belum dipilih
    
    const floors = props.rooms
        .filter(room => room.gedung === selectedGedung.value) // Filter ruangan di gedung itu
        .map(room => room.lantai) // Ambil nomor lantainya
        .filter(lantai => lantai !== null && lantai !== undefined); // Hapus yang null
    
    // Kembalikan daftar lantai unik, diurutkan
    return [...new Set(floors)].sort((a, b) => a - b); 
});

// 4. Buat daftar 'Ruangan' berdasarkan 'Gedung' DAN 'Lantai'
const ruanganList = computed(() => {
    if (!selectedGedung.value || selectedLantai.value === null) return []; // Kosongkan jika belum lengkap
    
    return props.rooms
        .filter(room => room.gedung === selectedGedung.value && room.lantai == selectedLantai.value);
});

// 5. Watcher untuk mereset pilihan jika Ganti Gedung
watch(selectedGedung, (newGedung) => {
    selectedLantai.value = null; // Reset pilihan lantai
    form.room_id = null;         // Reset pilihan ruangan
});

// 6. Watcher untuk mereset pilihan jika Ganti Lantai
watch(selectedLantai, (newLantai) => {
    form.room_id = null; // Reset pilihan ruangan
});

// === AKHIR LOGIKA BARU ===

// Fungsi submit yang menggabungkan tanggal + jam (tetap sama)
const submit = () => {
  form.transform((data) => ({
    ...data,
    start_time: `${data.date} ${data.start_time}`,
    end_time: `${data.date} ${data.end_time}`,
  })).post(route('bookings.store'), {
    onError: (errors) => {
        form.reset(); 
    }
  });
};
</script>

<template>
  <Head title="Buat Booking" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Booking Baru</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <form @submit.prevent="submit" class="max-w-lg mx-auto">
            
            <!-- === TAMPILAN DROPDOWN BERTINGKAT === -->
            
            <!-- 1. Pilih Gedung -->
            <div class="mt-4">
              <InputLabel for="gedung" value="Pilih Gedung" />
              <select 
                id="gedung" 
                v-model="selectedGedung" 
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              >
                <option :value="null" disabled>-- Pilih Gedung --</option>
                <option v-for="gedung in gedungList" :key="gedung" :value="gedung">
                  {{ gedung }}
                </option>
              </select>
            </div>

            <!-- 2. Pilih Lantai -->
            <div class="mt-4">
              <InputLabel for="lantai" value="Pilih Lantai" />
              <select 
                id="lantai" 
                v-model="selectedLantai" 
                :disabled="!selectedGedung"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-50"
              >
                <option :value="null" disabled>-- Pilih Lantai --</option>
                <option v-for="lantai in lantaiList" :key="lantai" :value="lantai">
                  Lantai {{ lantai }}
                </option>
              </select>
            </div>

            <!-- 3. Pilih Ruangan -->
            <div class="mt-4">
              <InputLabel for="room_id" value="Pilih Ruangan" />
              <select 
                id="room_id" 
                v-model="form.room_id" 
                :disabled="!selectedLantai"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-50"
                required
              >
                <option :value="null" disabled>-- Pilih Ruangan --</option>
                <option v-for="room in ruanganList" :key="room.id" :value="room.id">
                  {{ room.name }} (Kapasitas: {{ room.capacity }} orang)
                </option>
              </select>
              <InputError class="mt-2" :message="form.errors.room_id" />
            </div>

            <!-- === AKHIR TAMPILAN DROPDOWN === -->


            <!-- Nama Kegiatan (Title) -->
            <div class="mt-4">
              <InputLabel for="title" value="Nama Kegiatan" />
              <TextInput
                id="title"
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                required
              />
              <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <!-- Input Tanggal -->
            <div class="mt-4">
              <InputLabel for="date" value="Tanggal Booking" />
              <TextInput
                id="date"
                type="date"
                class="mt-1 block w-full"
                v-model="form.date"
                required
              />
              <InputError class="mt-2" :message="form.errors.date || form.errors.start_time" />
            </div>

            <!-- Input Jam Mulai dan Selesai -->
            <div class="mt-4 grid grid-cols-2 gap-4">
              <div>
                <InputLabel for="start_time" value="Jam Mulai" />
                <TextInput
                  id="start_time"
                  type="time"
                  class="mt-1 block w-full"
                  v-model="form.start_time"
                  required
                />
              </div>
              <div>
                <InputLabel for="end_time" value="Jam Selesai" />
                <TextInput
                  id="end_time"
                  type="time"
                  class="mt-1 block w-full"
                  v-model="form.end_time"
                  required
                />
                <InputError class="mt-2" :message="form.errors.end_time" />
              </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex items-center justify-end mt-4">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Ajukan Booking
              </PrimaryButton>
            </div>

          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>