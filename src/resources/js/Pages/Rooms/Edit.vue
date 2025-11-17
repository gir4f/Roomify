<script setup>
// ... (imports)
import { defineProps } from 'vue';

const props = defineProps({
  room: Object
});

// DIPERBARUI: Tambahkan key baru
const form = useForm({
  name: props.room.name,
  capacity: props.room.capacity,
  facilities: props.room.facilities,
  gedung: props.room.gedung, // <-- TAMBAHKAN INI
  lantai: props.room.lantai, // <-- TAMBAHKAN INI
});

const submit = () => {
  form.put(route('rooms.update', props.room.id));
};
</script>

<template>
  <Head title="Edit Ruangan" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Ruangan: {{ room.name }}</h2>
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
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- ... (Sisa form Anda: capacity, facilities, button) ... -->
<!-- ... existing code ... -->
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
                Perbarui Ruangan
              </PrimaryButton>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>