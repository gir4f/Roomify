<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

// DIPERBARUI: Tambahkan key baru
const form = useForm({
  name: '',
  capacity: '',
  facilities: '',
  gedung: '', // <-- TAMBAHKAN INI
  lantai: '', // <-- TAMBAHKAN INI
});

const submit = () => {
  form.post(route('rooms.store'));
};
</script>

<template>
  <Head title="Tambah Ruangan" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Ruangan Baru</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <form @submit.prevent="submit" class="max-w-lg mx-auto">
            
            <!-- === INPUT GEDUNG & LANTAI BARU === -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <InputLabel for="gedung" value="Nama Gedung" />
                <TextInput
                  id="gedung"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.gedung"
                />
                <InputError class="mt-2" :message="form.errors.gedung" />
              </div>
              <div>
                <InputLabel for="lantai" value="Lantai Ke-" />
                <TextInput
                  id="lantai"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.lantai"
                />
                <InputError class="mt-2" :message="form.errors.lantai" />
              </div>
            </div>
            <!-- === AKHIR INPUT BARU === -->

            <!-- Nama Ruangan -->
            <div class="mt-4">
              <InputLabel for="name" value="Nama Ruangan" />
              <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Kapasitas -->
            <div class="mt-4">
              <InputLabel for="capacity" value="Kapasitas (orang)" />
              <TextInput
                id="capacity"
                type="number"
                class="mt-1 block w-full"
                v-model="form.capacity"
                required
              />
              <InputError class="mt-2" :message="form.errors.capacity" />
            </div>

            <!-- Fasilitas -->
            <div class="mt-4">
              <InputLabel for="facilities" value="Fasilitas (pisahkan dengan koma)" />
              <TextInput
                id="facilities"
                type="text"
                class="mt-1 block w-full"
                v-model="form.facilities"
              />
              <InputError class="mt-2" :message="form.errors.facilities" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Simpan Ruangan
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>