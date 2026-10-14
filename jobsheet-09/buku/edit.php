<?php
// 2.1 buku/edit.php, Kode Lengkap 
$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// 2.2 Mengambil id dari URL: $_GET['id']
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

// 2.3 Mengambil Data Lama: SELECT ... WHERE id = :id
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header('Location: list.php');
    exit;
}
?>
<section>
  <h2>Edit Buku</h2>

  <?php if ($flash): ?>
  <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
  <?php endif; ?>

  <form id="form-tambah" method="post" action="proses_edit.php">
    2.5 Kotak Tersembunyi: <input type="hidden" name="id">
    <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
    <p>
      <label for="judul">Judul</label><br>
      <!-- 2.4 Mengisi Form dengan Data Lama: Atribut value -->
      <input type="text" id="judul" name="judul" value="<?php echo $buku['judul']; ?>" required>
    </p>
    <p>
      <label for="pengarang">Pengarang</label><br>
      <input type="text" id="pengarang" name="pengarang" value="<?php echo $buku['pengarang']; ?>" required>
    </p>
    <p>
      <label for="tahun">Tahun Terbit</label><br>
      <input type="number" id="tahun" name="tahun" min="1900" max="2026" value="<?php echo $buku['tahun']; ?>" required>
    </p>
    <p>
      <label for="isbn">ISBN</label><br>
      <input type="text" id="isbn" name="isbn" value="<?php echo $buku['isbn']; ?>">
    </p>
    <p>
      <label for="stok">Stok</label><br>
      <input type="number" id="stok" name="stok" min="0" value="<?php echo $buku['stok']; ?>" required>
    </p>
    <p>
        <!-- Kasus khusus untuk <select>: -->
      <label for="kategori">Kategori</label><br>
      <select id="kategori" name="kategori">
        <?php foreach (['fiksi' => 'Fiksi', 'non-fiksi' => 'Non-Fiksi', 'referensi' => 'Referensi'] as $value => $label): ?>
        <option value="<?php echo $value; ?>" <?php echo $buku['kategori'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p>
      <button type="submit">Simpan Perubahan</button>
    </p>
  </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>