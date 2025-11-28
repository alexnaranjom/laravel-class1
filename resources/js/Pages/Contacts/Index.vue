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
  <div class="page">
    <header class="page-header">
      <div>
        <p class="eyebrow">Contacts API</p>
        <h1>Contacts Dashboard</h1>
        <p class="subtitle">
          Browse contacts served from <code>/api/contacts</code> with search, sorting, and pagination.
        </p>
      </div>

      <dl v-if="total !== null" class="page-summary">
        <div>
          <dt>Total</dt>
          <dd>{{ total }}</dd>
        </div>
        <div>
          <dt>Showing</dt>
          <dd>{{ from ?? 0 }} – {{ to ?? 0 }}</dd>
        </div>
        <div>
          <dt>Per page</dt>
          <dd>10</dd>
        </div>
      </dl>
    </header>

    <section class="search-bar">
      <label>
        <span>Search contacts</span>
        <input
          v-model="searchTerm"
          @input="resetAndSearch"
          type="text"
          name="search"
          placeholder="Search by name, email, or phone..."
          autocomplete="off"
        />
      </label>
    </section>

    <section v-if="loading" class="state-card loading">
      <span class="spinner" aria-hidden="true"></span>
      <p>Loading contacts…</p>
    </section>

    <section v-else-if="error" class="state-card error" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="fetchContacts(currentPage)">Try again</button>
    </section>

    <section v-else class="contacts-card">
      <div v-if="!contacts || contacts.length === 0" class="empty-state">
        <h2>No contacts yet</h2>
        <p>Try clearing the search or add a new contact from the API.</p>
      </div>

      <div v-else>
        <div class="table-wrapper">
          <table class="contacts-table">
            <caption class="sr-only">Contacts list</caption>
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Created</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in contacts" :key="c.id">
                <td data-label="Name">
                  <span class="cell-primary">{{ c.name || 'No name' }}</span>
                </td>
                <td data-label="Email">
                  <a v-if="c.email" :href="`mailto:${c.email}`">{{ c.email }}</a>
                  <span v-else class="muted">No email</span>
                </td>
                <td data-label="Phone">
                  <a v-if="c.phone" :href="`tel:${c.phone}`">{{ c.phone }}</a>
                  <span v-else class="muted">No phone</span>
                </td>
                <td data-label="Created">
                  <time :datetime="c.created_at">
                    {{ new Date(c.created_at).toLocaleDateString() }}
                  </time>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <nav class="pagination" aria-label="Pagination">
          <button
            type="button"
            class="page-btn"
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
          >
            Prev
          </button>
          <p class="page-info">Page {{ currentPage }} of {{ lastPage }}</p>
          <button
            type="button"
            class="page-btn"
            :disabled="currentPage === lastPage"
            @click="goToPage(currentPage + 1)"
          >
            Next
          </button>
        </nav>
      </div>
    </section>
  </div>
</template>

<style scoped>
:global(body) {
  background: #f3f4f6;
  color: #0f172a;
  font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.page {
  max-width: 960px;
  margin: 0 auto;
  padding: 2.5rem 1.5rem 3rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.page-header {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  background: #fff;
  padding: 1.75rem;
  border-radius: 1.25rem;
  box-shadow: 0 15px 35px rgb(15 23 42 / 0.08);
}

.page-header h1 {
  margin: 0.15rem 0;
  font-size: clamp(2rem, 4vw, 2.7rem);
  font-weight: 700;
  color: #0b1a33;
}

.eyebrow {
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  color: #64748b;
  margin-bottom: 0.25rem;
}

.subtitle {
  margin: 0;
  color: #475569;
  max-width: 40ch;
}

.page-summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 0.75rem;
  background: #0f172a;
  color: #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 0.9rem;
}

.page-summary dt {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  opacity: 0.8;
}

.page-summary dd {
  margin: 0.2rem 0 0;
  font-size: 1.2rem;
  font-weight: 600;
}

.search-bar {
  background: #fff;
  border-radius: 1.1rem;
  padding: 1.5rem;
  box-shadow: 0 15px 30px rgb(15 23 42 / 0.06);
}

.search-bar label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.95rem;
  color: #475569;
  font-weight: 500;
}

.search-bar input {
  border: 1px solid #cbd5f5;
  border-radius: 0.9rem;
  padding: 0.85rem 1.1rem;
  font-size: 1rem;
  color: #0f172a;
  transition: border 0.2s, box-shadow 0.2s;
}

.search-bar input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 4px rgb(37 99 235 / 0.15);
}

.state-card {
  background: #fff;
  padding: 2rem;
  border-radius: 1.1rem;
  text-align: center;
  box-shadow: 0 12px 25px rgb(15 23 42 / 0.08);
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  align-items: center;
  justify-content: center;
  min-height: 160px;
}

.loading .spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e2e8f0;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

.error {
  border: 1px solid #fecaca;
  background: #fff1f2;
  color: #991b1b;
}

.error button {
  background: #991b1b;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 0.55rem 1.25rem;
  cursor: pointer;
  font-weight: 600;
}

.contacts-card {
  background: #fff;
  border-radius: 1.2rem;
  box-shadow: 0 20px 45px rgb(15 23 42 / 0.08);
  padding: 1.25rem;
}

.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #475569;
}

.empty-state h2 {
  margin-bottom: 0.5rem;
  font-size: 1.4rem;
  color: #0f172a;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

.contacts-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 640px;
}

.contacts-table th,
.contacts-table td {
  text-align: left;
  padding: 0.9rem 1rem;
  border-bottom: 1px solid #e2e8f0;
}

.contacts-table th {
  font-size: 0.9rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: #f8fafc;
}

.contacts-table tbody tr:hover {
  background: #f5f7fb;
}

.cell-primary {
  font-weight: 600;
  color: #0f172a;
}

.contacts-table a {
  color: #2563eb;
  text-decoration: none;
}

.contacts-table a:hover {
  text-decoration: underline;
}

.muted {
  color: #94a3b8;
  font-style: italic;
}

.pagination {
  margin-top: 1.25rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  align-items: center;
  justify-content: center;
}

.page-btn {
  border: none;
  border-radius: 999px;
  padding: 0.55rem 1.4rem;
  font-weight: 600;
  background: #2563eb;
  color: #fff;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}

.page-btn:disabled {
  background: #94a3b8;
  cursor: not-allowed;
  transform: none;
}

.page-btn:not(:disabled):hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}

.page-info {
  font-size: 0.95rem;
  color: #475569;
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  border: 0;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 700px) {
  .page-header,
  .search-bar,
  .contacts-card {
    padding: 1.25rem;
  }

  .page-summary {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  }

  .contacts-table {
    min-width: 100%;
  }

  .contacts-table td {
    font-size: 0.95rem;
  }

  .contacts-table td::before {
    content: attr(data-label);
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
  }
}
</style>

