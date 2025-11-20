<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Kategori Kelas";
?>
<?php include '../components/header.php'; ?>

<main class="flex-1 overflow-y-auto p-4 lg:p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kategori Kelas</h1>
            <p class="text-gray-600">Kelola kategori kelas bimbel</p>
        </div>
        <button onclick="openTambahModal()" class="mt-4 lg:mt-0 bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Kelas
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kelasTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Tambah Kelas -->
<div id="tambahModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold">Tambah Kelas Baru</h3>
            <button onclick="closeTambahModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formTambahKelas" class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeTambahModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Mapel -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="detailModalTitle" class="text-lg font-semibold">Detail Mata Pelajaran</h3>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <ul id="detailMapelList" class="space-y-2">
                <!-- List mapel akan diisi via JavaScript -->
            </ul>
        </div>
    </div>
</div>

<script>
// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadKelasData);

function loadKelasData() {
    fetch('../includes/kategori-kelas/kelas-data.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('kelasTableBody');
            tbody.innerHTML = '';
            
            data.forEach(kelas => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${kelas.nama_kelas}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${kelas.jumlah_mapel} Mata Pelajaran</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="showDetail(${kelas.id}, '${kelas.nama_kelas}')" class="bg-blue-600 px-3 py-2 rounded-md text-white hover:bg-blue-900"><i class="fas fa-eye"></i> Detail</button>
                            <button onclick="hapusKelas(${kelas.id})" class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-900"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}

function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
    document.getElementById('tambahModal').classList.add('flex');
}

function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
    document.getElementById('tambahModal').classList.remove('flex');
    document.getElementById('formTambahKelas').reset();
}

function showDetail(kelasId, namaKelas) {
    fetch(`../includes/kategori-kelas/kelas-data.php?kelas_id=${kelasId}`)
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('detailMapelList');
            const title = document.getElementById('detailModalTitle');
            
            title.textContent = `Mata Pelajaran Kelas ${namaKelas}`;
            list.innerHTML = '';
            
            if (data.mapel && data.mapel.length > 0) {
                data.mapel.forEach(mapel => {
                    list.innerHTML += `<li class="flex items-center space-x-2">
                        <i class="fas fa-book text-blue-500"></i>
                        <span>${mapel.nama_mapel}</span>
                    </li>`;
                });
            } else {
                list.innerHTML = '<li class="text-gray-500">Belum ada mata pelajaran</li>';
            }
            
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

function hapusKelas(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'delete');
        
        fetch('../includes/kategori-kelas/kelas-crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Kelas berhasil dihapus');
                loadKelasData();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Handle form submit
document.getElementById('formTambahKelas').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'create');
    
    fetch('../includes/kategori-kelas/kelas-crud.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kelas berhasil ditambahkan');
            closeTambahModal();
            loadKelasData();
        } else {
            alert('Error: ' + data.message);
        }
    });
});
</script>

<?php include '../components/footer.php'; ?>