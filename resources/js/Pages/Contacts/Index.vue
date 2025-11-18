<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const contacts    = ref([]);
const loading     = ref(true);
const error       = ref(null);
const searchTerm  = ref('');
const currentPage = ref(1);
const lastPage    = ref(1);
const from        = ref(null);
const to          = ref(null);
const total       = ref(null);

const fetchContacts = async (page = 1) => {
  loading.value = true;
  error.value = null;
  currentPage.value = page;

  try {
    const response = await axios.get('/api/contacts', {
      params: {
        search: searchTerm.value || undefined,
        page: currentPage.value,
      },
    });

    const data = response.data;

    contacts.value    = data.data ?? [];
    currentPage.value = data.current_page ?? 1;
    lastPage.value    = data.last_page ?? 1;
    from.value        = data.from ?? null;
    to.value          = data.to ?? null;
    total.value       = data.total ?? null;
  } catch (e) {
    console.error(e);
    error.value = 'Could not load contacts.';
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => {
  if (page < 1 || page > lastPage.value) return;
  fetchContacts(page);
};

const resetAndSearch = () => {
  // Always go back to page 1 when changing the filter
  fetchContacts(1);
};

onMounted(() => {
  fetchContacts(1);
});
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto">
    <!-- Header -->
    <header class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-baseline sm:justify-between">
      <div>
        <h1 class="text-3xl font-bold">Contacts</h1>
        <p class="text-sm text-gray-600">
          Public contacts list using Laravel API, Inertia, Vue, search, and pagination.
        </p>
      </div>

      <!-- Small summary (desktop) -->
      <div v-if="total !== null" class="text-xs text-gray-500 sm:text-right">
        Showing
        <span class="font-semibold">{{ from ?? 0 }}</span>
        –
        <span class="font-semibold">{{ to ?? 0 }}</span>
        of
        <span class="font-semibold">{{ total }}</span>
        contacts
      </div>
    </header>

    <!-- Search -->
    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
      <input
        v-model="searchTerm"
        @input="resetAndSearch"
        type="text"
        placeholder="Search by name, email, or phone..."
        class="border rounded px-3 py-2 w-full sm:max-w-md focus:outline-none focus:ring focus:ring-blue-200"
      />
    </div>

    <!-- Loading / Error -->
    <div v-if="loading" class="py-8 text-gray-500">
      Loading contacts...
    </div>

    <div v-else-if="error" class="py-4 text-red-600">
      {{ error }}
    </div>

    <!-- Empty state / List -->
    <div v-else>
      <div v-if="!contacts || contacts.length === 0" class="py-8 text-gray-500 italic">
        No contacts found. Try a different search.
      </div>

      <ul v-else class="space-y-2">
        <li
          v-for="c in contacts"
          :key="c.id"
          class="border rounded-lg p-3 flex flex-col gap-1 bg-white shadow-sm"
        >
          <div class="font-semibold text-gray-900">
            {{ c.name || 'No name' }}
          </div>
          <div class="text-sm text-gray-600">
            {{ c.email || 'No email' }}
          </div>
          <div class="text-sm text-gray-600">
            {{ c.phone || 'No phone' }}
          </div>
        </li>
      </ul>

      <!-- Pagination -->
      <div
        v-if="lastPage > 1"
        class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
      >
        <div class="text-sm text-gray-500">
          Page
          <span class="font-semibold">{{ currentPage }}</span>
          of
          <span class="font-semibold">{{ lastPage }}</span>
        </div>

        <div class="flex gap-2">
          <button
            class="px-3 py-1 border rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
          >
            Previous
          </button>

          <button
            class="px-3 py-1 border rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="currentPage === lastPage"
            @click="goToPage(currentPage + 1)"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
