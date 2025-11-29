<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    rooms: Array // Data ruangan dari Controller
});

// State Form (Ditambah field date, split start_time/end_time)
const form = useForm({
    room_id: null,
    title: '',
    description: '',
    date: '',
    start_time: '',
    end_time: '',
});

// === LOGIKA DROPDOWN BERTINGKAT ===

// 1. State Filter Lokasi
const selectedGedung = ref(null);
const selectedLantai = ref(null);

// 2. List Gedung Unik
const gedungList = computed(() => {
    const list = props.rooms.map(r => r.gedung).filter(Boolean);
    return [...new Set(list)].sort();
});

// 3. List Lantai (Berdasarkan Gedung yg dipilih)
const lantaiList = computed(() => {
    if (!selectedGedung.value) return [];
    const floors = props.rooms
        .filter(r => r.gedung === selectedGedung.value)
        .map(r => r.lantai)
        .filter(l => l !== null && l !== undefined);
    return [...new Set(floors)].sort((a, b) => a - b);
});

// 4. List Ruangan (Berdasarkan Gedung & Lantai)
const ruanganList = computed(() => {
    if (!selectedGedung.value || selectedLantai.value === null) return [];
    return props.rooms.filter(r => 
        r.gedung === selectedGedung.value && 
        r.lantai == selectedLantai.value
    );
});

// 5. Reset jika filter induk berubah
watch(selectedGedung, () => {
    selectedLantai.value = null;
    form.room_id = null;
});
watch(selectedLantai, () => {
    form.room_id = null;
});

// === LOGIKA TAMBAHAN ===

// Preview Detail Ruangan yang dipilih
const selectedRoomDetail = computed(() => {
    return props.rooms.find(r => r.id === form.room_id);
});

// Fungsi Submit (Menggabungkan Date + Time)
const submit = () => {
    form.transform((data) => ({
        ...data,
        start_time: `${data.date} ${data.start_time}`, // Gabung: 2025-11-20 09:00
        end_time: `${data.date} ${data.end_time}`,     // Gabung: 2025-11-20 11:00
    })).post(route('bookings.store'), {
        onFinish: () => form.reset('title', 'description'),
    });
};
</script>

<template>
    <Head title="Ajukan Booking" />

    <AuthenticatedLayout>
        <div class="py-6 w-full">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Ajukan Booking Ruangan</h2>
                        <p class="text-sm text-gray-600">Isi formulir di bawah ini untuk mengajukan peminjaman.</p>
                    </div>
                    <Link 
                        :href="route('bookings.index')"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-gray-600 text-xs font-medium hover:bg-gray-50 hover:text-gray-900 transition shadow-sm w-fit"
                    >
                        &larr; Kembali
                    </Link>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- KOLOM KIRI: FORMULIR -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                            
                            <form @submit.prevent="submit" class="space-y-6">
                                
                                <!-- BAGIAN 1: LOKASI (DROPDOWN BERTINGKAT) -->
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-200 pb-2">Pilih Lokasi</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Pilih Gedung -->
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Gedung</label>
                                            <select v-model="selectedGedung" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                                                <option :value="null" disabled>-- Pilih Gedung --</option>
                                                <option v-for="g in gedungList" :key="g" :value="g">{{ g }}</option>
                                            </select>
                                        </div>

                                        <!-- Pilih Lantai -->
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Lantai</label>
                                            <select v-model="selectedLantai" :disabled="!selectedGedung" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm disabled:bg-gray-100 disabled:text-gray-400">
                                                <option :value="null" disabled>-- Pilih Lantai --</option>
                                                <option v-for="l in lantaiList" :key="l" :value="l">Lantai {{ l }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Pilih Ruangan (Final) -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Ruangan Tersedia</label>
                                        <select v-model="form.room_id" :disabled="!selectedLantai" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm disabled:bg-gray-100 disabled:text-gray-400">
                                            <option :value="null" disabled>-- Pilih Ruangan --</option>
                                            <option v-for="room in ruanganList" :key="room.id" :value="room.id">
                                                {{ room.name }} (Kapasitas: {{ room.capacity }} orang)
                                            </option>
                                        </select>
                                        <div v-if="form.errors.room_id" class="text-red-500 text-xs mt-1">{{ form.errors.room_id }}</div>
                                    </div>
                                </div>

                                <!-- Info Detail Ruangan (Muncul Otomatis) -->
                                <div v-if="selectedRoomDetail" class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-start gap-4 transition-all animate-fade-in">
                                    <div class="bg-white p-2 rounded-lg shadow-sm text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-indigo-900 text-sm">{{ selectedRoomDetail.name }}</h4>
                                        <p class="text-xs text-indigo-700 mt-1">
                                            Fasilitas: {{ selectedRoomDetail.facilities || 'Standar' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- BAGIAN 2: DETAIL KEGIATAN -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="title" class="block text-sm font-bold text-gray-700 mb-1">Judul Kegiatan</label>
                                        <input 
                                            type="text" 
                                            id="title" 
                                            v-model="form.title"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                            placeholder="Contoh: Rapat HIMA, Kuliah Tambahan..."
                                            required
                                        />
                                        <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                                        <textarea 
                                            id="description" 
                                            v-model="form.description"
                                            rows="3"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                            placeholder="Tambahkan detail keperluan..."
                                        ></textarea>
                                    </div>
                                </div>

                                <!-- BAGIAN 3: WAKTU (SPLIT DATE & TIME) -->
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-200 pb-2">Waktu Pelaksanaan</h3>
                                    
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
                                        <input 
                                            type="date" 
                                            v-model="form.date"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                            required
                                        />
                                        <div v-if="form.errors.date" class="text-red-500 text-xs mt-1">{{ form.errors.date }}</div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Jam Mulai</label>
                                            <input 
                                                type="time" 
                                                v-model="form.start_time"
                                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                                required
                                            />
                                            <div v-if="form.errors.start_time" class="text-red-500 text-xs mt-1">{{ form.errors.start_time }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Jam Selesai</label>
                                            <input 
                                                type="time" 
                                                v-model="form.end_time"
                                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                                required
                                            />
                                            <div v-if="form.errors.end_time" class="text-red-500 text-xs mt-1">{{ form.errors.end_time }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="pt-4 border-t border-gray-100 flex justify-end">
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="bg-gray-900 text-white font-bold py-3 px-6 rounded-xl hover:bg-gray-800 transition shadow-lg transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                    >
                                        <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ form.processing ? 'Mengirim...' : 'Ajukan Booking' }}
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    <!-- KOLOM KANAN: TIPS -->
                    <div class="hidden lg:block">
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 sticky top-6">
                            <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tips Booking
                            </h3>
                            <ul class="space-y-3 text-sm text-blue-800">
                                <li class="flex gap-2">
                                    <span class="font-bold">•</span>
                                    Cek ketersediaan ruangan di menu Kalender sebelum mengajukan.
                                </li>
                                <li class="flex gap-2">
                                    <span class="font-bold">•</span>
                                    Pastikan durasi waktu mencakup persiapan dan bersih-bersih.
                                </li>
                                <li class="flex gap-2">
                                    <span class="font-bold">•</span>
                                    Admin akan meninjau pengajuan Anda dalam 1x24 jam.
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>