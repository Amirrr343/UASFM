<svelte:head>
    <title>film</title>
</svelte:head>

<script>
  import { onMount } from 'svelte';

  let films = [];
  let showForm = false;
  let title = '';
  let description = '';
  let genre = '';
  let release_year = '';
  let editingId = null;

  let currentPage = 1;
  let totalPages = 1;
  let totalFilms = 0;
  let limit = 5;

  const api = 'http://localhost:8000/api/films';

  async function loadFilms(page = 1) {
    const res = await fetch(`${api}?page=${page}&limit=${limit}`);
    if (res.ok) {
      const json = await res.json();
      films = json.data;
      currentPage = json.current_page;
      totalPages = json.last_page;
      totalFilms = json.total;
    } else {
      console.error('Gagal mengambil data dari API');
    }
  }

  onMount(() => {
    loadFilms();
  });

  function tampilkanForm() {
    showForm = true;
    title = '';
    description = '';
    genre = '';
    release_year = '';
    editingId = null;
  }

  function batalkanForm() {
    const yakin = confirm("Yakin batal proses saat ini?");
    if (yakin) {
      showForm = false;
      editingId = null;
    }
  }

  async function simpanFilm() {
    const filmData = { title, description, genre, release_year };

    let res;
    if (editingId) {
      res = await fetch(`${api}/${editingId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filmData)
      });
    } else {
      res = await fetch(api, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filmData)
      });
    }

    if (res.ok) {
      showForm = false;
      editingId = null;
      await loadFilms(currentPage);
      alert('Film berhasil disimpan!');
    } else {
      alert('Ups sepertinya ada kesalahan saat menyimpan film.');
    }
  }

  async function editFilm(id) {
    const film = films.find(f => f.id === id);
    if (!film) return;

    title = film.title;
    description = film.description;
    genre = film.genre;
    release_year = film.release_year;
    editingId = film.id;
    showForm = true;
  }

  async function deleteFilm(id) {
    const yakin = confirm("Apakah Anda yakin ingin menghapus film ini?");
    if (!yakin) return;
    const res = await fetch(`${api}/${id}`, { method: 'DELETE' });
    if (res.ok) {
      await loadFilms(currentPage);
    } else {
      alert('Gagal menghapus film.');
    }
  }

  function changePage(page) {
    if (page >= 1 && page <= totalPages) {
      currentPage = page;
      loadFilms(currentPage);
    }
  }
</script>

<h1 class="text-2xl font-semibold p-5 text-center text-white bg-gray-800 uppercase">Daftar Film</h1>

<div class="max-w-6xl mx-auto px-4">
  <div class="flex justify-end mb-4">
    <!-- <button
      class="bg-blue-500 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition-colors duration-200"
      on:click={loadFilms}
    >
      🔄 Refresh Data
    </button> -->
    <button
      class="bg-green-500 text-white px-4 py-2 text-sm rounded-md hover:bg-green-600 transition-colors duration-200"
      on:click={tampilkanForm}
    >
      ➕ Tambah Film
    </button>
  </div>

  {#if !showForm}
    <div class="overflow-x-auto shadow rounded-lg">
      <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Genre</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun Rilis</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        {#if films.length > 0}
          <tbody class="divide-y divide-gray-200">
            {#each films as film}
              <tr class="hover:bg-gray-100 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-900">{film.title}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{film.description}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{film.genre}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{film.release_year}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <button class="bg-blue-500 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition-colors duration-200 cursor-pointer" on:click={() => editFilm(film.id)}>Edit</button>
                  <button class="bg-red-500 text-white px-4 py-2 text-sm rounded-md hover:bg-red-600 transition-colors duration-200 cursor-pointer" on:click={() => deleteFilm(film.id)}>Hapus</button>
                </td>
              </tr>
            {/each}
          </tbody>
        {:else}
          <tbody>
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                Tidak ada data film yang tersedia.
              </td>
            </tr>
          </tbody>
        {/if}
      </table>

      <!-- PAGINATION -->
<div class="flex gap-2 mt-4 px-4">
  {#each Array(totalPages) as _, i}
    <button
      class="{i + 1 === currentPage 
                ? 'bg-blue-500 text-white' 
                : 'bg-gray-200 text-gray-500'} 
              px-4 py-2 text-sm rounded-md hover:bg-red-600 transition-colors duration-200 cursor-pointer"
      on:click={() => changePage(i + 1)}>
      {i + 1}
    </button>
  {/each}
</div>
      
    </div>
  {/if}

  {#if showForm}
    <div class="max-w-3xl mx-auto mt-8 px-4">
      <div class="bg-white border border-gray-300 p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Form Tambah Film</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Judul</label>
            <input bind:value={title} class="w-full border border-gray-300 p-2 rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea bind:value={description} class="w-full border border-gray-300 p-2 rounded-md"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Genre</label>
            <input bind:value={genre} class="w-full border border-gray-300 p-2 rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Tahun Rilis</label>
            <input type="number" bind:value={release_year} class="w-full border border-gray-300 p-2 rounded-md" />
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button on:click={simpanFilm} class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Simpan</button>
          <button on:click={batalkanForm} class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Batal</button>
        </div>
      </div>
    </div>
  {/if}
</div>
