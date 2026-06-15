// Admin Kelola Buku page script (extracted from Blade)
(function () {
  const cfg = window.KelolaBukuCfg || {};
  const toast = (m, t) => window.AdminUi?.toast?.(m, t);
  const validationToast = (j, f) => window.AdminUi?.validationToast?.(j, f);

  let books = [];
  let selectedBook = null;
  let currentPage = 1;
  let lastPage = 1;
  let totalBooks = 0;
  let currentQuery = "";
  let currentCategory = "all";
  let searchDebounce = null;

  const bookContainer = document.getElementById("bookContainer");
  const paginationContainer = document.getElementById("paginationContainer");
  const bookCountEl = document.getElementById("bookCount");
  const bookEmptyEl = document.getElementById("book-empty");
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");

  function escHtml(s) {
    return String(s).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;");
  }

  function fillYearSelect(selectEl, selected) {
    if (!selectEl) return;
    const now = new Date().getFullYear();
    let html = "";
    for (let y = now; y >= 1990; y--) {
      html += `<option value="${y}"${String(selected) === String(y) ? " selected" : ""}>${y}</option>`;
    }
    selectEl.innerHTML = html;
  }

  function updateCategoryFilter(categories) {
    if (!categoryFilter) return;
    const current = categoryFilter.value;
    let html = `<option value="all">Semua Kategori</option>`;
    (categories || []).forEach((cat) => {
      html += `<option value="${escHtml(cat)}">${escHtml(cat)}</option>`;
    });
    categoryFilter.innerHTML = html;
    if ([...categoryFilter.options].some((o) => o.value === current)) {
      categoryFilter.value = current;
    }
  }

  async function fetchBooks() {
    const params = new URLSearchParams();
    if (currentQuery) params.set("q", currentQuery);
    if (currentCategory && currentCategory !== "all") params.set("category", currentCategory);
    params.set("page", String(currentPage));
    params.set("per_page", String(cfg.perPage || 6));

    const res = await fetch(`${cfg.listUrl}?${params.toString()}`, { headers: { Accept: "application/json" } });
    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      toast(json?.message || "Gagal memuat data buku.", "error");
      books = [];
      lastPage = 1;
      totalBooks = 0;
      renderBooks([]);
      return;
    }

    books = json.data || [];
    updateCategoryFilter(json.categories || []);
    const meta = json.meta || {};
    lastPage = Number(meta.last_page || 1) || 1;
    totalBooks = Number(meta.total || 0) || 0;
    renderBooks(books);
  }

  function previewCoverInput(input, imgEl, placeholderEl) {
    const file = input.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      imgEl.src = e.target.result;
      imgEl.classList.remove("hidden");
      if (placeholderEl) placeholderEl.classList.add("hidden");
    };
    reader.readAsDataURL(file);
  }

  function resetCoverPreview(imgEl, placeholderEl, inputEl) {
    if (inputEl) inputEl.value = "";
    if (imgEl) {
      imgEl.src = "";
      imgEl.classList.add("hidden");
    }
    if (placeholderEl) placeholderEl.classList.remove("hidden");
  }

  function showCoverPreview(imgEl, placeholderEl, url) {
    if (!imgEl) return;
    imgEl.src = url || cfg.defaultCover;
    imgEl.classList.remove("hidden");
    if (placeholderEl) placeholderEl.classList.add("hidden");
  }

  function appendBookFields(formData, payload) {
    Object.entries(payload).forEach(([key, value]) => formData.append(key, value ?? ""));
  }

  function bookPayload(prefix) {
    const v = (id) => (document.getElementById(prefix + id)?.value ?? "");
    return {
      code: String(v("Code")).trim(),
      title: String(v("Title")).trim(),
      author: String(v("Author")).trim(),
      publisher: String(v("Publisher")).trim(),
      category: String(v("Category")),
      year: String(v("Year")),
      stock: String(v("Stock")),
      isbn: String(v("Isbn")).trim(),
      rack: String(v("Rack")).trim(),
      description: String(v("Description")).trim(),
    };
  }

  async function saveEditBook() {
    if (!selectedBook) return;
    const payload = bookPayload("edit");
    if (!payload.code || !payload.title || !payload.author || !payload.publisher) {
      toast("Nomor panggil, judul, pengarang, dan penerbit wajib diisi.", "error");
      return;
    }

    const formData = new FormData();
    appendBookFields(formData, payload);
    formData.append("_method", "PUT");
    const coverFile = document.getElementById("editCover")?.files?.[0];
    if (coverFile) formData.append("cover", coverFile);

    const res = await fetch(`${cfg.listUrl}/${encodeURIComponent(selectedBook.id)}`, {
      method: "POST",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": cfg.csrf || "" },
      body: formData,
    });

    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      validationToast(json, "Validasi gagal");
      return;
    }

    closeEditModal();
    await fetchBooks();
    toast(json.message || "Buku berhasil diperbarui.", "success");
  }

  async function saveTambahBook() {
    const payload = bookPayload("tambah");
    if (!payload.code || !payload.title || !payload.author || !payload.publisher) {
      toast("Nomor panggil, judul, pengarang, dan penerbit wajib diisi.", "error");
      return;
    }
    if (payload.stock === "" || Number(payload.stock) < 0) {
      toast("Jumlah buku wajib diisi (minimal 0).", "error");
      return;
    }
    if (!payload.year) {
      toast("Tahun terbit wajib dipilih.", "error");
      return;
    }

    const formData = new FormData();
    appendBookFields(formData, payload);
    const coverFile = document.getElementById("tambahCover")?.files?.[0];
    if (coverFile) formData.append("cover", coverFile);

    const res = await fetch(cfg.storeUrl, {
      method: "POST",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": cfg.csrf || "" },
      body: formData,
    });

    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      validationToast(json, "Validasi gagal");
      return;
    }

    closeTambahModal();
    await fetchBooks();
    toast(json.message || "Buku berhasil ditambahkan.", "success");
  }

  function renderBooks(data) {
    if (!bookContainer) return;
    bookContainer.innerHTML = "";
    if (bookEmptyEl) bookEmptyEl.classList.toggle("hidden", data.length > 0);
    if (bookCountEl) bookCountEl.textContent = totalBooks ? `${totalBooks} buku` : "";

    data.forEach((book) => {
      const code = book.nomor_panggil || book.code || "-";
      bookContainer.innerHTML += `
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
          <img src="${escHtml(book.img)}" alt="${escHtml(book.title)}" onerror="this.onerror=null;this.src=cfg.defaultCover;" class="h-44 w-full object-cover">
          <div class="p-3">
            <p class="text-[11px] font-semibold text-teal-700">${escHtml(code)}</p>
            <h6 class="line-clamp-2 font-bold text-indigo-900" title="${escHtml(book.title)}">${escHtml(book.title)}</h6>
            <p class="mt-1 text-sm text-slate-500">${escHtml(book.author)}</p>
            <p class="text-sm text-slate-500">${escHtml(book.category)}</p>
            <p class="text-sm text-slate-500">Tersedia : ${book.stock}</p>
            <button type="button" data-id="${escHtml(book.id)}" class="js-detail-book mt-3 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-indigo-900 py-2 text-sm font-semibold text-white transition hover:bg-indigo-950">
              <i class="bi bi-eye"></i> Lihat Detail
            </button>
          </div>
        </article>
      `;
    });

    renderPagination();
  }

  function renderPagination() {
    if (!paginationContainer) return;
    paginationContainer.innerHTML = "";
    if (lastPage <= 1) return;

    let html = `<div class="inline-flex overflow-hidden rounded-lg border border-slate-200">`;
    html += `<button type="button" onclick="changePage(${Math.max(1, currentPage - 1)})" class="border-r border-slate-200 bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50"${currentPage <= 1 ? " disabled" : ""}>&lt;</button>`;
    for (let i = 1; i <= lastPage; i++) {
      html += `<button type="button" onclick="changePage(${i})" class="border-r border-slate-200 px-4 py-1.5 text-xs ${i === currentPage ? "bg-indigo-900 text-white" : "bg-white text-slate-700 hover:bg-slate-50"}">${i}</button>`;
    }
    html += `<button type="button" onclick="changePage(${Math.min(lastPage, currentPage + 1)})" class="bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50"${currentPage >= lastPage ? " disabled" : ""}>&gt;</button>`;
    html += `</div>`;
    paginationContainer.innerHTML = html;
  }

  function changePage(page) {
    if (page < 1 || page > lastPage) return;
    currentPage = page;
    fetchBooks();
  }

  function onSearchOrCategoryChanged() {
    if (searchInput) currentQuery = (searchInput.value || "").trim();
    if (categoryFilter) currentCategory = categoryFilter.value || "all";
    currentPage = 1;
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(fetchBooks, 250);
  }

  function fillDetailModal(book) {
    selectedBook = book;
    document.getElementById("detailTitle").textContent = book.title;
    document.getElementById("detailCode").textContent = book.nomor_panggil || book.code || "-";
    document.getElementById("detailAuthor").textContent = book.author;
    document.getElementById("detailPublisher").textContent = book.publisher;
    document.getElementById("detailYear").textContent = book.year;
    document.getElementById("detailCategory").textContent = book.category;
    document.getElementById("detailStock").textContent = `${book.stock} tersedia`;
    document.getElementById("detailRack").textContent = book.rack;
    document.getElementById("detailIsbn").textContent = book.isbn;
    document.getElementById("detailDescription").textContent = book.description && book.description !== "-" ? book.description : "-";
    document.getElementById("detailImg").src = book.img;
  }

  async function openDetailModal(bookOrId) {
    const id = typeof bookOrId === "object" ? bookOrId?.id : bookOrId;
    if (!id) return;

    if (typeof cfg.showUrl === "function") {
      try {
        const res = await fetch(cfg.showUrl(id), { headers: { Accept: "application/json" } });
        const json = await res.json().catch(() => ({}));
        if (res.ok && json.data) {
          fillDetailModal(json.data);
          fbShow("detailModal");
          return;
        }
      } catch {
        /* fallback ke cache list */
      }
    }

    const cached = books.find((b) => String(b.id) === String(id));
    if (cached) {
      fillDetailModal(cached);
      fbShow("detailModal");
      return;
    }
    toast("Buku tidak ditemukan.", "error");
  }

  function closeDetailModal() {
    fbHide("detailModal");
  }

  function openHapusBukuModal() {
    if (!selectedBook) return;
    document.getElementById("hapusBukuTitle").textContent = selectedBook.title;
    fbShow("hapusBukuModal");
  }

  function closeHapusBukuModal() {
    fbHide("hapusBukuModal");
  }

  async function confirmDeleteBuku() {
    if (!selectedBook) return;
    const res = await fetch(`${cfg.listUrl}/${encodeURIComponent(selectedBook.id)}`, {
      method: "DELETE",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": cfg.csrf || "" },
    });
    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      toast(json.message || "Gagal menghapus buku.", "error");
      return;
    }
    closeHapusBukuModal();
    closeDetailModal();
    selectedBook = null;
    await fetchBooks();
    toast(json.message || "Buku berhasil dihapus.", "success");
  }

  function openEditModal() {
    if (!selectedBook) return;
    document.getElementById("editCode").value = selectedBook.nomor_panggil || selectedBook.code || "";
    document.getElementById("editIsbn").value = selectedBook.isbn === "-" ? "" : selectedBook.isbn;
    document.getElementById("editTitle").value = selectedBook.title;
    document.getElementById("editPublisher").value = selectedBook.publisher;
    document.getElementById("editAuthor").value = selectedBook.author;
    document.getElementById("editRack").value = selectedBook.rack === "-" ? "" : selectedBook.rack;
    fillYearSelect(document.getElementById("editYear"), selectedBook.year);
    document.getElementById("editCategory").value = selectedBook.category;
    document.getElementById("editStock").value = selectedBook.stock;
    document.getElementById("editDescription").value = selectedBook.description && selectedBook.description !== "-" ? selectedBook.description : "";
    showCoverPreview(document.getElementById("editPreviewImg"), document.getElementById("editCoverPlaceholder"), selectedBook.img);
    document.getElementById("editCover").value = "";
    closeDetailModal();
    fbShow("editModal");
  }

  function closeEditModal() {
    fbHide("editModal");
  }

  function openTambahModal() {
    document.getElementById("tambahCode").value = "";
    document.getElementById("tambahTitle").value = "";
    document.getElementById("tambahIsbn").value = "";
    document.getElementById("tambahPublisher").value = "";
    document.getElementById("tambahAuthor").value = "";
    document.getElementById("tambahRack").value = "";
    document.getElementById("tambahStock").value = "0";
    document.getElementById("tambahDescription").value = "";
    resetCoverPreview(document.getElementById("tambahPreviewImg"), document.getElementById("tambahCoverPlaceholder"), document.getElementById("tambahCover"));
    fillYearSelect(document.getElementById("tambahYear"), new Date().getFullYear());
    document.getElementById("tambahCategory").value = "Pendidikan";
    fbShow("tambahModal");
  }

  function closeTambahModal() {
    fbHide("tambahModal");
  }

  // expose needed inline handlers
  window.openTambahModal = openTambahModal;
  window.closeTambahModal = closeTambahModal;
  window.openEditModal = openEditModal;
  window.closeEditModal = closeEditModal;
  window.openDetailModal = openDetailModal;
  window.closeDetailModal = closeDetailModal;
  window.openHapusBukuModal = openHapusBukuModal;
  window.closeHapusBukuModal = closeHapusBukuModal;
  window.confirmDeleteBuku = confirmDeleteBuku;
  window.saveTambahBook = saveTambahBook;
  window.saveEditBook = saveEditBook;
  window.changePage = changePage;

  // events
  if (bookContainer) {
    bookContainer.addEventListener("click", (e) => {
      const btn = e.target.closest(".js-detail-book");
      if (!btn) return;
      const id = btn.getAttribute("data-id");
      if (id) openDetailModal(id);
    });
  }
  if (searchInput) searchInput.addEventListener("keyup", onSearchOrCategoryChanged);
  if (categoryFilter) categoryFilter.addEventListener("change", onSearchOrCategoryChanged);

  document.getElementById("tambahCover")?.addEventListener("change", (e) => {
    previewCoverInput(e.target, document.getElementById("tambahPreviewImg"), document.getElementById("tambahCoverPlaceholder"));
  });
  document.getElementById("editCover")?.addEventListener("change", (e) => {
    previewCoverInput(e.target, document.getElementById("editPreviewImg"), document.getElementById("editCoverPlaceholder"));
  });

  fetchBooks();
})();

