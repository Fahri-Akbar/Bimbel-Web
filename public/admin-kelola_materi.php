<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Kelola Materi";
?>
<?php include '../components/header.php'; ?>

<main class="flex-1 overflow-y-auto p-4 lg:p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Materi</h1>
            <p class="text-gray-600">Kelola materi pokok, sub materi, dan topik pembelajaran</p>
        </div>
        <div class="mt-4 lg:mt-0 flex space-x-3">
            <a href="../includes/kelola-materi/template-excel.php" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
            <button onclick="openTambahModal()" 
                    class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Materi Pokok
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi Pokok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Materi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materiTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Tambah Materi Pokok -->
<div id="materiPokokModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="materiPokokModalTitle" class="text-lg font-semibold">Tambah Materi Pokok</h3>
            <button onclick="closeMateriPokokModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formMateriPokok" class="p-4 space-y-4">
            <input type="hidden" name="id" id="materiPokokId">
            <input type="hidden" name="action" id="formAction" value="create">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas *</label>
                <select name="kelas_id" id="kelasId" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                        onchange="loadMataPelajaran(this.value)">
                    <option value="">Pilih Kelas</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran *</label>
                <select name="mata_pelajaran_id" id="mataPelajaranId" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Mata Pelajaran</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi Pokok *</label>
                <input type="text" name="judul_materi_pokok" id="judulMateriPokok" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="Contoh: Aljabar Dasar">
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeMateriPokokModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
                    <span id="submitButtonText">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kelola Sub Materi -->
<div id="subMateriModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="subMateriModalTitle" class="text-lg font-semibold">Kelola Sub Materi</h3>
            <button onclick="closeSubMateriModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-4 space-y-6">
            <!-- Info Materi Pokok -->
            <div id="materiPokokInfo" class="bg-blue-50 p-4 rounded-lg">
                <!-- Info akan diisi via JavaScript -->
            </div>
            
            <!-- Daftar Sub Materi -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-semibold text-gray-800">Daftar Sub Materi</h4>
                    <button type="button" onclick="tambahSubMateriField()" 
                            class="text-sm bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i>Tambah Sub Materi
                    </button>
                </div>
                
                <div id="subMateriContainer" class="space-y-4">
                    <!-- Field sub materi akan ditambahkan di sini -->
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeSubMateriModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Tutup</button>
                <button type="button" onclick="simpanSemuaSubMateri()" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
                    Simpan Semua
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Topik -->
<div id="uploadTopikModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="uploadTopikModalTitle" class="text-lg font-semibold">Upload Topik</h3>
            <button onclick="closeUploadTopikModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formUploadTopik" class="p-4 space-y-4">
            <input type="hidden" name="sub_materi_id" id="subMateriId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Excel Topik *</label>
                <input type="file" name="excel_topik" accept=".xlsx,.xls,.csv" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Format harus sesuai template. Maksimal 2MB</p>
            </div>
            
            <div class="bg-yellow-50 p-3 rounded-lg">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    File Excel harus berisi kolom: JUDUL_TOPIK, PENJELASAN, LINK_VIDEO
                </p>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeUploadTopikModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
                    Upload & Proses
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentMateriPokokId = null;
let subMateriCounter = 0;
let subMateriData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadMateriData();
    loadKelasOptions();
});

function loadMateriData() {
    fetch('../includes/kelola-materi/materi-data.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('materiTableBody');
            tbody.innerHTML = '';
            
            data.forEach(materi => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${materi.judul_materi_pokok}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${materi.nama_mapel}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${materi.nama_kelas}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${materi.jumlah_sub_materi} Sub</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${materi.jumlah_topik} Topik</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="kelolaSubMateri(${materi.id}, '${materi.judul_materi_pokok}', '${materi.nama_mapel}', '${materi.nama_kelas}')" 
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Kelola Sub Materi">
                                <i class="fas fa-layer-group"></i>
                            </button>
                            <button onclick="editMateriPokok(${materi.id})" 
                                    class="text-green-600 hover:text-green-900 p-1 rounded" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusMateriPokok(${materi.id})" 
                                    class="text-red-600 hover:text-red-900 p-1 rounded" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        });
}

function loadKelasOptions() {
    fetch('../includes/kategori-kelas/kelas-data.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('kelasId');
            select.innerHTML = '<option value="">Pilih Kelas</option>';
            
            data.forEach(kelas => {
                select.innerHTML += `<option value="${kelas.id}">${kelas.nama_kelas}</option>`;
            });
        });
}

function loadMataPelajaran(kelasId) {
    const select = document.getElementById('mataPelajaranId');
    select.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
    
    if (!kelasId) return;
    
    fetch(`../includes/mata-pelajaran/mapel-data.php?kelas_id=${kelasId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(mapel => {
                select.innerHTML += `<option value="${mapel.id}">${mapel.nama_mapel}</option>`;
            });
        });
}

// Materi Pokok Functions
function openTambahModal() {
    document.getElementById('materiPokokModalTitle').textContent = 'Tambah Materi Pokok';
    document.getElementById('formAction').value = 'create';
    document.getElementById('submitButtonText').textContent = 'Simpan';
    document.getElementById('formMateriPokok').reset();
    document.getElementById('materiPokokId').value = '';
    document.getElementById('mataPelajaranId').innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
    
    document.getElementById('materiPokokModal').classList.remove('hidden');
    document.getElementById('materiPokokModal').classList.add('flex');
}

function closeMateriPokokModal() {
    document.getElementById('materiPokokModal').classList.add('hidden');
    document.getElementById('materiPokokModal').classList.remove('flex');
}

function editMateriPokok(id) {
    fetch(`../includes/kelola-materi/materi-data.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('materiPokokModalTitle').textContent = 'Edit Materi Pokok';
                document.getElementById('formAction').value = 'update';
                document.getElementById('submitButtonText').textContent = 'Update';
                
                document.getElementById('materiPokokId').value = data.id;
                document.getElementById('kelasId').value = data.kelas_id;
                document.getElementById('judulMateriPokok').value = data.judul_materi_pokok;
                
                // Load mata pelajaran berdasarkan kelas
                loadMataPelajaran(data.kelas_id);
                
                // Set mata pelajaran setelah options loaded
                setTimeout(() => {
                    document.getElementById('mataPelajaranId').value = data.mata_pelajaran_id;
                }, 500);
                
                document.getElementById('materiPokokModal').classList.remove('hidden');
                document.getElementById('materiPokokModal').classList.add('flex');
            }
        });
}

function hapusMateriPokok(id) {
    if (confirm('Apakah Anda yakin ingin menghapus materi pokok ini? Semua sub materi dan topik juga akan terhapus.')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'delete');
        
        fetch('../includes/kelola-materi/materi-crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Materi pokok berhasil dihapus');
                loadMateriData();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Sub Materi Functions
function kelolaSubMateri(materiPokokId, judulMateri, namaMapel, namaKelas) {
    currentMateriPokokId = materiPokokId;
    
    // Set info materi pokok
    document.getElementById('materiPokokInfo').innerHTML = `
        <h4 class="font-semibold text-gray-800">${judulMateri}</h4>
        <p class="text-sm text-gray-600">${namaMapel} - ${namaKelas}</p>
    `;
    
    // Load sub materi
    loadSubMateri(materiPokokId);
    
    document.getElementById('subMateriModal').classList.remove('hidden');
    document.getElementById('subMateriModal').classList.add('flex');
}

function closeSubMateriModal() {
    document.getElementById('subMateriModal').classList.add('hidden');
    document.getElementById('subMateriModal').classList.remove('flex');
    currentMateriPokokId = null;
    subMateriData = [];
}

function loadSubMateri(materiPokokId) {
    fetch(`../includes/kelola-materi/sub-materi-data.php?materi_pokok_id=${materiPokokId}`)
        .then(response => response.json())
        .then(data => {
            subMateriData = data;
            renderSubMateriFields();
        });
}

function renderSubMateriFields() {
    const container = document.getElementById('subMateriContainer');
    container.innerHTML = '';
    subMateriCounter = 0;
    
    subMateriData.forEach((subMateri, index) => {
        tambahSubMateriField(subMateri.id, subMateri.judul_sub_materi, subMateri.jumlah_topik, false);
    });
    
    // Tambah field kosong untuk input baru
    tambahSubMateriField('', '', 0, true);
}

function tambahSubMateriField(id = '', judul = '', jumlahTopik = 0, isNew = false) {
    subMateriCounter++;
    const container = document.getElementById('subMateriContainer');
    
    const fieldId = `sub-materi-field-${subMateriCounter}`;
    
    const fieldHTML = `
        <div class="border border-gray-200 rounded-lg p-4 bg-white" id="${fieldId}">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-700">Sub Materi ${subMateriCounter}</span>
                <div class="flex space-x-2">
                    ${jumlahTopik > 0 ? `
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">${jumlahTopik} Topik</span>
                    ` : ''}
                    ${id ? `
                        <button type="button" onclick="uploadTopik(${id})" class="text-green-600 hover:text-green-800" title="Upload/Update Topik">
                            <i class="fas fa-upload"></i>
                        </button>
                    ` : ''}
                    <button type="button" onclick="hapusSubMateriField('${fieldId}', ${id})" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="sub_materi_id[]" value="${id}">
            <input type="text" name="sub_judul[]" value="${judul}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                   placeholder="Judul Sub Materi" ${isNew ? '' : 'required'}>
        </div>
    `;
    
    container.innerHTML += fieldHTML;
}

function hapusSubMateriField(fieldId, subMateriId) {
    if (subMateriId) {
        // Hapus dari database
        if (confirm('Apakah Anda yakin ingin menghapus sub materi ini? Semua topik yang terkait juga akan terhapus.')) {
            const formData = new FormData();
            formData.append('id', subMateriId);
            formData.append('action', 'delete');
            
            fetch('../includes/kelola-materi/sub-materi-crud.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus dari array data
                    subMateriData = subMateriData.filter(item => item.id !== subMateriId);
                    // Render ulang fields
                    renderSubMateriFields();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    } else {
        // Hapus field baru yang belum disimpan
        const field = document.getElementById(fieldId);
        if (field) {
            field.remove();
        }
    }
}

function uploadTopik(subMateriId) {
    document.getElementById('subMateriId').value = subMateriId;
    
    // Cari judul sub materi untuk modal title
    const subMateri = subMateriData.find(item => item.id === subMateriId);
    const judul = subMateri ? subMateri.judul_sub_materi : 'Sub Materi';
    
    document.getElementById('uploadTopikModalTitle').textContent = `Upload Topik - ${judul}`;
    document.getElementById('uploadTopikModal').classList.remove('hidden');
    document.getElementById('uploadTopikModal').classList.add('flex');
}

function closeUploadTopikModal() {
    document.getElementById('uploadTopikModal').classList.add('hidden');
    document.getElementById('uploadTopikModal').classList.remove('flex');
    document.getElementById('formUploadTopik').reset();
}

function simpanSemuaSubMateri() {
    const fields = document.querySelectorAll('[id^="sub-materi-field-"]');
    let promises = [];
    let savedCount = 0;
    let totalFields = fields.length;
    
    fields.forEach((field) => {
        const subMateriId = field.querySelector('input[name="sub_materi_id[]"]').value;
        const judul = field.querySelector('input[name="sub_judul[]"]').value;
        
        // Skip empty fields for new entries
        if (!subMateriId && !judul.trim()) {
            totalFields--;
            return;
        }
        
        const formData = new FormData();
        formData.append('materi_pokok_id', currentMateriPokokId);
        formData.append('judul_sub_materi', judul);
        
        if (subMateriId) {
            // Update existing
            formData.append('id', subMateriId);
            formData.append('action', 'update');
        } else {
            // Create new - skip if empty
            if (!judul.trim()) {
                totalFields--;
                return;
            }
            formData.append('action', 'create');
        }
        
        const promise = fetch('../includes/kelola-materi/sub-materi-crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            savedCount++;
            if (!data.success) {
                throw new Error(`Gagal menyimpan: ${data.message}`);
            }
            return data;
        });
        
        promises.push(promise);
    });
    
    if (promises.length === 0) {
        alert('Tidak ada data yang disimpan');
        return;
    }
    
    // Show loading
    const submitButton = document.querySelector('#subMateriModal button[onclick="simpanSemuaSubMateri()"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;
    
    Promise.all(promises)
        .then(results => {
            alert(`Berhasil menyimpan ${savedCount} dari ${totalFields} sub materi`);
            closeSubMateriModal(); // Auto close modal
            loadMateriData(); // Refresh table utama
        })
        .catch(error => {
            alert('Error: ' + error.message);
        })
        .finally(() => {
            // Restore button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
}

// Tambah field sub materi baru
function tambahFieldSubMateri() {
    tambahSubMateriField('', '', 0, true);
}

// Event Listeners
document.getElementById('formMateriPokok').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = document.getElementById('formAction').value;
    
    fetch('../includes/kelola-materi/materi-crud.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(action === 'create' ? 'Materi pokok berhasil ditambahkan' : 'Materi pokok berhasil diupdate');
            closeMateriPokokModal();
            loadMateriData();
        } else {
            alert('Error: ' + data.message);
        }
    });
});

document.getElementById('formUploadTopik').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    submitButton.disabled = true;
    
    fetch('../includes/kelola-materi/topik-crud.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = `Berhasil memproses ${data.processed} topik`;
            if (data.errors && data.errors.length > 0) {
                message += `. ${data.errors.length} error: ` + data.errors.slice(0, 3).join(', ');
            }
            alert(message);
            closeUploadTopikModal();
            loadSubMateri(currentMateriPokokId);
            loadMateriData(); // Refresh table utama
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        // Restore button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});
</script>

<?php include '../components/footer.php'; ?>