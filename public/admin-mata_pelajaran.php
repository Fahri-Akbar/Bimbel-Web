<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Mata Pelajaran";
?>
<?php include '../components/header.php'; ?>

<main class="flex-1 overflow-y-auto p-4 lg:p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mata Pelajaran</h1>
            <p class="text-gray-600">Kelola mata pelajaran bimbel</p>
        </div>
        <button onclick="openTambahModal()" class="mt-4 lg:mt-0 bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Mapel
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="mapelTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Tambah/Edit Mapel -->
<div id="mapelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="mapelModalTitle" class="text-lg font-semibold">Tambah Mata Pelajaran</h3>
            <button onclick="closeMapelModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formMapel" class="p-4 space-y-4">
            <input type="hidden" name="id" id="mapelId">
            <input type="hidden" name="action" id="formAction" value="create">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" id="kelasId" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Kelas</option>
                    <!-- Options akan diisi via JavaScript -->
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" id="namaMapel" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                <input type="file" name="gambar" id="gambar" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
            </div>
            
            <!-- Preview -->
            <div id="previewContainer" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div id="imagePreview" class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mx-auto mb-3">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                    <div>
                        <p id="previewNama" class="text-sm font-medium text-gray-800"></p>
                        <p class="text-xs text-gray-500">Tombol mata pelajaran</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeMapelModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
                    <span id="submitButtonText">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let kelasData = [];
let editId = null;

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadMapelData();
    loadKelasOptions();
});

function loadMapelData() {
    fetch('../includes/mata-pelajaran/mapel-data.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('mapelTableBody');
            tbody.innerHTML = '';
            
            data.forEach(mapel => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    ${mapel.gambar ? 
                                        `<img src="../uploads/${mapel.gambar}" alt="${mapel.nama_mapel}" class="w-8 h-8 object-cover rounded">` :
                                        `<i class="fas fa-book text-gray-400"></i>`
                                    }
                                </div>
                                <div class="text-sm font-medium text-gray-900">${mapel.nama_mapel}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${mapel.nama_kelas}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="editMapel(${mapel.id})" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusMapel(${mapel.id})" class="text-red-600 hover:text-red-900 p-1 rounded" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}

function loadKelasOptions() {
    fetch('../includes/kategori-kelas/kelas-data.php')
        .then(response => response.json())
        .then(data => {
            kelasData = data;
            const select = document.getElementById('kelasId');
            select.innerHTML = '<option value="">Pilih Kelas</option>';
            
            data.forEach(kelas => {
                select.innerHTML += `<option value="${kelas.id}">${kelas.nama_kelas}</option>`;
            });
        });
}

function openTambahModal() {
    document.getElementById('mapelModalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('formAction').value = 'create';
    document.getElementById('submitButtonText').textContent = 'Simpan';
    document.getElementById('formMapel').reset();
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('mapelId').value = '';
    
    document.getElementById('mapelModal').classList.remove('hidden');
    document.getElementById('mapelModal').classList.add('flex');
}

function closeMapelModal() {
    document.getElementById('mapelModal').classList.add('hidden');
    document.getElementById('mapelModal').classList.remove('flex');
    document.getElementById('formMapel').reset();
    document.getElementById('previewContainer').classList.add('hidden');
    editId = null;
}

function editMapel(id) {
    editId = id;
    
    fetch(`../includes/mata-pelajaran/mapel-data.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('mapelModalTitle').textContent = 'Edit Mata Pelajaran';
                document.getElementById('formAction').value = 'update';
                document.getElementById('submitButtonText').textContent = 'Update';
                
                document.getElementById('mapelId').value = data.id;
                document.getElementById('kelasId').value = data.kelas_id;
                document.getElementById('namaMapel').value = data.nama_mapel;
                
                // Update preview
                updatePreview(data.nama_mapel, data.gambar);
                
                document.getElementById('mapelModal').classList.remove('hidden');
                document.getElementById('mapelModal').classList.add('flex');
            }
        });
}

function hapusMapel(id) {
    if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'delete');
        
        fetch('../includes/mata-pelajaran/mapel-crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mata pelajaran berhasil dihapus');
                loadMapelData();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Update preview saat input berubah
document.getElementById('namaMapel').addEventListener('input', function() {
    updatePreview(this.value, null);
});

document.getElementById('gambar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            updatePreview(document.getElementById('namaMapel').value, e.target.result, true);
        }
        reader.readAsDataURL(file);
    }
});

function updatePreview(nama, gambar, isDataUrl = false) {
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const previewNama = document.getElementById('previewNama');
    
    if (nama || gambar) {
        previewContainer.classList.remove('hidden');
        
        if (gambar) {
            if (isDataUrl) {
                imagePreview.innerHTML = `<img src="${gambar}" alt="Preview" class="w-full h-full object-cover">`;
            } else {
                imagePreview.innerHTML = `<img src="../uploads/${gambar}" alt="Preview" class="w-full h-full object-cover">`;
            }
        } else {
            imagePreview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
        
        previewNama.textContent = nama || 'Nama Mapel';
    } else {
        previewContainer.classList.add('hidden');
    }
}

// Handle form submit
document.getElementById('formMapel').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = document.getElementById('formAction').value;
    formData.append('action', action);
    
    fetch('../includes/mata-pelajaran/mapel-crud.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(action === 'create' ? 'Mata pelajaran berhasil ditambahkan' : 'Mata pelajaran berhasil diupdate');
            closeMapelModal();
            loadMapelData();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});
</script>

<?php include '../components/footer.php'; ?>