// Data storage untuk simulasi database
let beasiswaData = JSON.parse(localStorage.getItem('beasiswaData')) || [];
let currentIPK = 0;

// Fungsi untuk menampilkan section
function showSection(sectionId) {
    // Sembunyikan semua section
    const sections = document.querySelectorAll('.form-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Tampilkan section yang dipilih
    document.getElementById(sectionId).classList.add('active');
    
    // Update tampilan jika masuk ke section hasil atau admin
    if (sectionId === 'hasil') {
        displayHasil();
    } else if (sectionId === 'admin') {
        displayAdmin();
    }
}

// Fungsi untuk generate IPK secara random
function generateIPK() {
    // Generate IPK random antara 2.0 - 4.0
    const ipkOptions = [2.5, 2.7, 2.9, 3.1, 3.2, 3.4, 3.6, 3.8];
    currentIPK = ipkOptions[Math.floor(Math.random() * ipkOptions.length)];
    
    document.getElementById('ipkDisplay').textContent = currentIPK.toFixed(1);
    
    // Logika enable/disable form berdasarkan IPK
    const pilihanBeasiswa = document.getElementById('pilihan_beasiswa');
    const berkas = document.getElementById('berkas');
    const submitBtn = document.getElementById('submitBtn');
    
    if (currentIPK < 3.0) {
        // IPK di bawah 3.0 - disable semua
        pilihanBeasiswa.disabled = true;
        berkas.disabled = true;
        submitBtn.disabled = true;
        
        pilihanBeasiswa.classList.add('disabled');
        berkas.classList.add('disabled');
        submitBtn.classList.add('disabled');
        
        // Tampilkan pesan
        showAlert('danger', 'IPK Anda di bawah 3.0. Anda tidak dapat melanjutkan pendaftaran beasiswa.');
    } else {
        // IPK di atas atau sama dengan 3.0 - enable form
        pilihanBeasiswa.disabled = false;
        berkas.disabled = false;
        submitBtn.disabled = false;
        
        pilihanBeasiswa.classList.remove('disabled');
        berkas.classList.remove('disabled');
        submitBtn.classList.remove('disabled');
        
        // Focus ke pilihan beasiswa
        setTimeout(() => {
            pilihanBeasiswa.focus();
        }, 100);
        
        showAlert('success', 'IPK Anda memenuhi syarat! Silakan lanjutkan pengisian form.');
    }
}

// Fungsi untuk menampilkan alert
function showAlert(type, message) {
    // Hapus alert sebelumnya jika ada
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Tambahkan alert setelah form
    const form = document.getElementById('beasiswaForm');
    form.parentNode.insertBefore(alertDiv, form.nextSibling);
    
    // Auto hide setelah 5 detik
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Validasi email format
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validasi nomor HP (hanya angka)
function validateHP(hp) {
    const hpRegex = /^[0-9]+$/;
    return hpRegex.test(hp) && hp.length >= 10;
}

// Handle form submission
document.getElementById('beasiswaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ambil data dari form
    const nama = document.getElementById('nama').value.trim();
    const email = document.getElementById('email').value.trim();
    const hp = document.getElementById('hp').value.trim();
    const semester = document.getElementById('semester').value;
    const pilihanBeasiswa = document.getElementById('pilihan_beasiswa').value;
    const berkas = document.getElementById('berkas').files[0];
    
    // Validasi
    if (!nama || !email || !hp || !semester || !pilihanBeasiswa || !berkas) {
        showAlert('danger', 'Mohon lengkapi semua field yang wajib diisi!');
        return;
    }
    
    if (!validateEmail(email)) {
        showAlert('danger', 'Format email tidak valid!');
        return;
    }
    
    if (!validateHP(hp)) {
        showAlert('danger', 'Nomor HP harus berupa angka dan minimal 10 digit!');
        return;
    }
    
    if (currentIPK === 0) {
        showAlert('danger', 'Mohon generate IPK terlebih dahulu!');
        return;
    }
    
    // Validasi ukuran file (max 5MB)
    if (berkas.size > 5 * 1024 * 1024) {
        showAlert('danger', 'Ukuran file maksimal 5MB!');
        return;
    }
    
    // Buat data pendaftaran
    const pendaftaran = {
        id: Date.now(),
        nama: nama,
        email: email,
        hp: hp,
        semester: semester,
        ipk: currentIPK,
        pilihan_beasiswa: pilihanBeasiswa,
        berkas_name: berkas.name,
        berkas_size: formatFileSize(berkas.size),
        tanggal_daftar: new Date().toLocaleDateString('id-ID'),
        status_ajuan: 'belum di verifikasi'
    };
    
    // Simpan ke storage
    beasiswaData.push(pendaftaran);
    localStorage.setItem('beasiswaData', JSON.stringify(beasiswaData));
    
    // Reset form
    document.getElementById('beasiswaForm').reset();
    document.getElementById('ipkDisplay').textContent = '-';
    currentIPK = 0;
    
    // Disable form kembali
    const pilihanBeasiswaEl = document.getElementById('pilihan_beasiswa');
    const berkasEl = document.getElementById('berkas');
    const submitBtnEl = document.getElementById('submitBtn');
    
    pilihanBeasiswaEl.disabled = true;
    berkasEl.disabled = true;
    submitBtnEl.disabled = true;
    
    pilihanBeasiswaEl.classList.add('disabled');
    berkasEl.classList.add('disabled');
    submitBtnEl.classList.add('disabled');
    
    // Tampilkan pesan sukses
    showAlert('success', 'Pendaftaran berhasil! Data Anda telah tersimpan dan menunggu verifikasi.');
    
    // Pindah ke halaman hasil
    setTimeout(() => {
        showSection('hasil');
    }, 2000);
});

// Fungsi untuk format ukuran file
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Fungsi untuk menampilkan hasil pendaftaran
function displayHasil() {
    const hasilList = document.getElementById('hasilList');
    
    if (beasiswaData.length === 0) {
        hasilList.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>Belum ada pendaftaran yang tersimpan</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    beasiswaData.forEach((data, index) => {
        const statusClass = getStatusClass(data.status_ajuan);
        html += `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> ${data.nama}
                    </h5>
                    <span class="status-badge ${statusClass}">${data.status_ajuan}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${data.email}</p>
                            <p><strong><i class="fas fa-phone"></i> No. HP:</strong> ${data.hp}</p>
                            <p><strong><i class="fas fa-calendar"></i> Semester:</strong> ${data.semester}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-star"></i> IPK:</strong> ${data.ipk}</p>
                            <p><strong><i class="fas fa-graduation-cap"></i> Beasiswa:</strong> ${getBeasiswaName(data.pilihan_beasiswa)}</p>
                            <p><strong><i class="fas fa-file"></i> Berkas:</strong> ${data.berkas_name} (${data.berkas_size})</p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <p class="mb-0"><strong><i class="fas fa-clock"></i> Tanggal Daftar:</strong> ${data.tanggal_daftar}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    hasilList.innerHTML = html;
}

// Fungsi untuk menampilkan admin panel
function displayAdmin() {
    const adminList = document.getElementById('adminList');
    
    if (beasiswaData.length === 0) {
        adminList.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-users-cog fa-3x mb-3"></i>
                <p>Belum ada pengajuan yang perlu diverifikasi</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    beasiswaData.forEach((data, index) => {
        const statusClass = getStatusClass(data.status_ajuan);
        html += `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> ${data.nama}
                    </h5>
                    <span class="status-badge ${statusClass}">${data.status_ajuan}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${data.email}</p>
                                    <p><strong><i class="fas fa-phone"></i> No. HP:</strong> ${data.hp}</p>
                                    <p><strong><i class="fas fa-calendar"></i> Semester:</strong> ${data.semester}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-star"></i> IPK:</strong> ${data.ipk}</p>
                                    <p><strong><i class="fas fa-graduation-cap"></i> Beasiswa:</strong> ${getBeasiswaName(data.pilihan_beasiswa)}</p>
                                    <p><strong><i class="fas fa-file"></i> Berkas:</strong> ${data.berkas_name}</p>
                                </div>
                            </div>
                            <p><strong><i class="fas fa-clock"></i> Tanggal Daftar:</strong> ${data.tanggal_daftar}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group-vertical" role="group">
                                <button class="btn btn-success btn-sm mb-2" onclick="updateStatus(${index}, 'disetujui')">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button class="btn btn-danger btn-sm mb-2" onclick="updateStatus(${index}, 'ditolak')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="updateStatus(${index}, 'belum di verifikasi')">
                                    <i class="fas fa-clock"></i> Pending
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    adminList.innerHTML = html;
}

// Fungsi untuk update status
function updateStatus(index, newStatus) {
    beasiswaData[index].status_ajuan = newStatus;
    localStorage.setItem('beasiswaData', JSON.stringify(beasiswaData));
    displayAdmin();
    
    // Tampilkan notifikasi
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-info alert-dismissible fade show';
    alertDiv.innerHTML = `
        Status pendaftaran ${beasiswaData[index].nama} berhasil diubah menjadi "${newStatus}"
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.getElementById('adminList').prepend(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Fungsi helper untuk mendapatkan class status
function getStatusClass(status) {
    switch (status) {
        case 'belum di verifikasi':
            return 'status-pending';
        case 'disetujui':
            return 'status-verified';
        case 'ditolak':
            return 'status-rejected';
        default:
            return 'status-pending';
    }
}

// Fungsi helper untuk mendapatkan nama beasiswa
function getBeasiswaName(value) {
    switch (value) {
        case 'akademik':
            return 'Beasiswa Akademik';
        case 'non_akademik':
            return 'Beasiswa Non-Akademik';
        case 'sosial_ekonomi':
            return 'Beasiswa Sosial Ekonomi';
        default:
            return value;
    }
}

// Event listener untuk validasi real-time
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    if (email && !validateEmail(email)) {
        this.classList.add('is-invalid');
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'Format email tidak valid';
            this.parentNode.appendChild(feedback);
        }
    } else {
        this.classList.remove('is-invalid');
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});

document.getElementById('hp').addEventListener('blur', function() {
    const hp = this.value.trim();
    if (hp && !validateHP(hp)) {
        this.classList.add('is-invalid');
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'Nomor HP harus berupa angka dan minimal 10 digit';
            this.parentNode.appendChild(feedback);
        }
    } else {
        this.classList.remove('is-invalid');
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Load data yang sudah ada
    displayHasil();
    displayAdmin();
});