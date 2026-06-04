// Admin Kelola Anggota page script (extracted from Blade)
(function () {
  const cfg = window.KelolaAnggotaCfg || {};
  const toast = (m, t) => window.AdminUi?.toast?.(m, t);
  const validationToast = (j, f) => window.AdminUi?.validationToast?.(j, f);

  let anggotaData = [];
  const rowsPerPage = cfg.perPage || 8;
  let currentPage = 1;
  let lastPage = 1;
  let totalRows = 0;
  let currentQuery = "";
  let editTargetId = null;
  let pendingDeleteId = null;
  let searchDebounce = null;

  const tbody = document.getElementById("anggotaTableBody");
  const paginationContainer = document.getElementById("paginationContainer");
  const searchInput = document.getElementById("searchInput");
  const anggotaEmptyEl = document.getElementById("anggota-empty");

  function escHtml(s) {
    return String(s).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;");
  }

  function tipeBadge(tipe) {
    return tipe === "Admin"
      ? '<span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2 py-0.5 text-xs font-semibold text-white"><i class="bi bi-shield-fill-check"></i> Admin</span>'
      : '<span class="inline-flex items-center gap-1 rounded-md bg-[#1E376E] px-2 py-0.5 text-xs font-semibold text-white"><i class="bi bi-mortarboard-fill"></i> Mahasiswa</span>';
  }

  async function fetchAnggota() {
    const params = new URLSearchParams();
    if (currentQuery) params.set("q", currentQuery);
    params.set("page", String(currentPage));
    params.set("per_page", String(rowsPerPage));

    const res = await fetch(`${cfg.listUrl}?${params.toString()}`, { headers: { Accept: "application/json" } });
    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      toast(json?.message || "Gagal memuat data anggota.", "error");
      anggotaData = [];
      lastPage = 1;
      totalRows = 0;
      renderTable();
      return;
    }

    anggotaData = (json.data || []).map((r) => ({ ...r }));
    const meta = json.meta || {};
    lastPage = Number(meta.last_page || 1) || 1;
    totalRows = Number(meta.total || 0) || 0;
    renderTable();
  }

  function renderTable() {
    if (!tbody) return;
    tbody.innerHTML = "";
    if (anggotaEmptyEl) anggotaEmptyEl.classList.toggle("hidden", anggotaData.length > 0);

    anggotaData.forEach((item) => {
      const initials = escHtml(item.initials || "");
      const nim = escHtml(item.nim);
      const rowId = escHtml(item.id);

      tbody.innerHTML += `
        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-4 py-3 align-middle font-medium text-slate-700 whitespace-nowrap">${nim}</td>
          <td class="px-4 py-3 align-middle">
            <div class="flex items-center gap-3 min-w-[180px]">
              <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-sm font-bold text-[#1E376E]">${initials || "—"}</div>
              <span class="font-semibold text-slate-800">${escHtml(item.nama)}</span>
            </div>
          </td>
          <td class="px-4 py-3 align-middle">${tipeBadge(item.tipe)}</td>
          <td class="px-4 py-3 align-middle text-slate-600">${escHtml(item.email)}</td>
          <td class="px-4 py-3 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white"><i class="bi bi-check-circle-fill"></i> ${escHtml(item.status)}</span>
          </td>
          <td class="px-4 py-3 align-middle">
            <div class="flex items-center justify-center gap-1.5">
              <button type="button" onclick="openEditAnggotaModal('${rowId}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500 text-white hover:bg-sky-600" title="Edit"><i class="bi bi-pencil text-sm"></i></button>
              <button type="button" onclick="deleteAnggota('${rowId}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500 text-white hover:bg-rose-600" title="Hapus"><i class="bi bi-trash text-sm"></i></button>
            </div>
          </td>
        </tr>
      `;
    });

    renderPagination();
  }

  function renderPagination() {
    if (!paginationContainer) return;
    paginationContainer.innerHTML = "";
    if (lastPage <= 1) return;

    paginationContainer.innerHTML += `
      <button onclick="setPage(${Math.max(1, currentPage - 1)})"
        class="px-2 py-1 text-slate-500 hover:text-slate-700">Previous</button>
    `;

    for (let i = 1; i <= lastPage; i++) {
      paginationContainer.innerHTML += `
        <button onclick="setPage(${i})"
          class="rounded-lg px-3 py-1.5 ${i === currentPage ? 'bg-[#1E376E] text-white' : 'text-slate-600 hover:bg-slate-100'}">${i}</button>
      `;
    }

    paginationContainer.innerHTML += `
      <button onclick="setPage(${Math.min(lastPage, currentPage + 1)})"
        class="px-2 py-1 text-slate-500 hover:text-slate-700">Next</button>
    `;
  }

  function setPage(page) {
    if (page < 1 || page > lastPage) return;
    currentPage = page;
    fetchAnggota();
  }

  function openTambahAnggotaModal() {
    editTargetId = null;
    document.getElementById("anggotaModalTitle").textContent = "Informasi Anggota";
    document.getElementById("anggotaSubmitButton").textContent = "Tambah";
    document.getElementById("newNama").value = "";
    document.getElementById("newNim").value = "";
    document.getElementById("newTipe").value = "Mahasiswa";
    document.getElementById("newEmail").value = "";
    document.getElementById("newPassword").value = "";
    fbShow("tambahAnggotaModal");
  }

  function closeTambahAnggotaModal() {
    fbHide("tambahAnggotaModal");
  }

  async function submitAnggotaForm() {
    const nama = document.getElementById("newNama").value.trim();
    const nim = document.getElementById("newNim").value.trim();
    const tipe = document.getElementById("newTipe").value;
    const email = document.getElementById("newEmail").value.trim();
    const password = document.getElementById("newPassword").value;

    if (!nama || !nim || !email) {
      toast("Nama, NIM/NIP, dan email wajib diisi.", "error");
      return;
    }

    const payload = { nama, nim, tipe, email, password };
    const url = editTargetId ? `${cfg.listUrl}/${encodeURIComponent(editTargetId)}` : cfg.storeUrl;
    const method = editTargetId ? "PUT" : "POST";

    const res = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": cfg.csrf || "",
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      validationToast(err, "Gagal menyimpan data anggota.");
      return;
    }

    await fetchAnggota();
    closeTambahAnggotaModal();
    toast(editTargetId ? "Anggota berhasil diperbarui." : "Anggota berhasil ditambahkan.", "success");
  }

  function fillAnggotaForm(data) {
    document.getElementById("newNama").value = data.nama || "";
    document.getElementById("newNim").value = data.nim || "";
    document.getElementById("newTipe").value = data.tipe || "Mahasiswa";
    document.getElementById("newEmail").value = data.email || "";
    document.getElementById("newPassword").value = "";
  }

  async function openEditAnggotaModal(id) {
    editTargetId = id;
    document.getElementById("anggotaModalTitle").textContent = "Informasi Anggota";
    document.getElementById("anggotaSubmitButton").textContent = "Simpan";

    if (typeof cfg.showUrl === "function") {
      try {
        const res = await fetch(cfg.showUrl(id), { headers: { Accept: "application/json" } });
        const json = await res.json().catch(() => ({}));
        if (res.ok && json.data) {
          fillAnggotaForm(json.data);
          fbShow("tambahAnggotaModal");
          return;
        }
      } catch {
        /* fallback cache list */
      }
    }

    const data = anggotaData.find((item) => String(item.id) === String(id));
    if (!data) {
      toast("Anggota tidak ditemukan.", "error");
      return;
    }
    fillAnggotaForm(data);
    fbShow("tambahAnggotaModal");
  }

  function deleteAnggota(id) {
    pendingDeleteId = id;
    fbShow("hapusAnggotaModal");
  }

  function closeHapusAnggotaModal() {
    pendingDeleteId = null;
    fbHide("hapusAnggotaModal");
  }

  async function confirmDeleteAnggota() {
    if (!pendingDeleteId) return;

    const res = await fetch(`${cfg.listUrl}/${encodeURIComponent(pendingDeleteId)}`, {
      method: "DELETE",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": cfg.csrf || "" },
    });

    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      toast(err.message || "Gagal menghapus anggota.", "error");
      return;
    }

    await fetchAnggota();
    closeHapusAnggotaModal();
    toast("Anggota berhasil dihapus.", "success");
  }

  // expose inline handlers
  window.openTambahAnggotaModal = openTambahAnggotaModal;
  window.closeTambahAnggotaModal = closeTambahAnggotaModal;
  window.submitAnggotaForm = submitAnggotaForm;
  window.openEditAnggotaModal = openEditAnggotaModal;
  window.deleteAnggota = deleteAnggota;
  window.closeHapusAnggotaModal = closeHapusAnggotaModal;
  window.confirmDeleteAnggota = confirmDeleteAnggota;

  // search
  if (searchInput) {
    searchInput.addEventListener("keyup", () => {
      const next = (searchInput.value || "").trim();
      currentQuery = next;
      currentPage = 1;
      if (searchDebounce) clearTimeout(searchDebounce);
      searchDebounce = setTimeout(fetchAnggota, 250);
    });
  }

  fetchAnggota();
})();

