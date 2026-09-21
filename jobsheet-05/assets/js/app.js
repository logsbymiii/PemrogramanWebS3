// membuat file app.js
function initNavToggle() {
  //4.2 Mengambil Dua Elemen yang Dibutuhkan
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");

  // 4.3 Penjaga Keamanan (Guard Clause)
  if (!toggleBtn || !nav) return;
  // 4.4 Memasang Event Listener
  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});

// ===== Konfirmasi hapus =====
function initHapusConfirm() {
  // 5.2 Memasang Event Listener ke Banyak Tombol Sekaligus
  document.querySelectorAll(".btn-hapus").forEach(function (btn) {
    btn.addEventListener("click", function () {
      // 5.3 Mencari Baris Tabel yang Jadi Induk Tombol
      const row = btn.closest("tr");
      // 5.4 Mengambil Nama/Judul dari Baris Itu
      const nama = row ? row.querySelector("td")?.textContent : "data ini";
      // 5.5 Menampilkan Dialog Konfirmasi
      const yakin = confirm('Yakin ingin menghapus "' + nama + '"?');
      // 5.6 Menghapus Baris dari Tampilan
      if (yakin && row) {
        row.remove();
      }
    });
  });
}

// ===== Filter/pencarian tabel real-time =====
function initTableFilter() {
  // 6.2 Mengambil Kotak Input dan Tabelnya
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");

  if (!input || !table) return;

  // 6.3 Event keyup: Bereaksi Setiap Ketikan
  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
      // filter kolom judul
      const judul = row.querySelector("td")?.textContent.toLowerCase() || "";
      // 6.5 Mengulang Setiap Baris Tabel
      row.style.display = judul.includes(keyword) ? "" : "none";
    });
  });
}

// ===== Validasi form (client-side) =====
// 7.3 Fungsi Pembantu: tampilkanError
function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

// 7.4 Fungsi Pembantu: hapusError
function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

// 7.5 Fungsi Utama: initValidasiForm
function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    // 7.6 Pola Pengecekan per Field
    // Judul (buku) atau Nama (anggota)
    const judul = form.querySelector("[name='judul'], [name='nama']");
    if (judul && judul.value.trim() === "") {
      tampilkanError(judul, "Field ini wajib diisi.");
      valid = false;
    } else if (judul) {
      hapusError(judul);
    }

    // Pengarang (khusus form buku)
    const pengarang = form.querySelector("[name='pengarang']");
    if (pengarang && pengarang.value.trim() === "") {
      tampilkanError(pengarang, "Field ini wajib diisi.");
      valid = false;
    } else if (pengarang) {
      hapusError(pengarang);
    }

    // Tahun (khusus form buku)
    const tahun = form.querySelector("[name='tahun']");
    if (tahun) {
      const nilai = parseInt(tahun.value, 10);
      if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
        tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    // Stok (khusus form buku)
    const stok = form.querySelector("[name='stok']");
    if (stok) {
      const nilai = parseInt(stok.value, 10);
      if (isNaN(nilai) || nilai < 0) {
        tampilkanError(stok, "Stok tidak boleh negatif.");
        valid = false;
      } else {
        hapusError(stok);
      }
    }

    // 8.4 Latihan
    // 1 Menambah validasi field baru untuk isbn
    const isbn = form.querySelector("[name='isbn']");
    if (isbn && isbn.value.trim() !== "") {
      const polaIsbn = /^[0-9-]+$/;
      if (!polaIsbn.test(isbn.value)) {
        tampilkanError(
          isbn,
          "ISBN hanya boleh berisi angka dan tanda hubung (-).",
        );
        valid = false;
      } else {
        hapusError(isbn);
      }
    }

    // 7.7 Mencegah Submit Jika Tidak Valid
    if (!valid) {
      e.preventDefault();
    }
  });
}
