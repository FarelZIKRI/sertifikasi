// Script untuk Sistem Pendaftaran Beasiswa

document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const navTabs = document.querySelectorAll('.nav-tab');
    const contentSections = document.querySelectorAll('.content-section');
    
    navTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            
            // Remove active class from all tabs and sections
            navTabs.forEach(t => t.classList.remove('active'));
            contentSections.forEach(s => s.classList.remove('active'));
            
            // Add active class to clicked tab and target section
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
    
    // Email input untuk cek IPK otomatis
    const emailInput = document.getElementById('email');
    const ipkDisplay = document.getElementById('ipk-display');
    const ipkValue = document.getElementById('ipk-value');
    const ipkStatus = document.getElementById('ipk-status');
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const berkasUpload = document.getElementById('berkas_syarat');
    const submitBtn = document.getElementById('submit-btn');
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            if (validateEmail(email)) {
                checkIPK(email);
            }
        });
    }
    
    // Validasi form
    const form = document.getElementById('form-beasiswa');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
});

// Fungsi untuk cek IPK berdasarkan email
function checkIPK(email) {
    // Simulasi AJAX call - dalam praktik nyata gunakan fetch() ke server
    const ipkData = {
        'john@email.com': 3.40,
        'jane@email.com': 2.90,
        'bob@email.com': 3.75,
        'alice@email.com': 2.65,
        'charlie@email.com': 3.85,
        'diana@email.com': 2.55,
        'edward@email.com': 3.20,
        'fiona@email.com': 3.95
    };
    
    let ipk = ipkData[email] || (Math.random() * 1.5 + 2.5).toFixed(2);
    ipk = parseFloat(ipk);
    
    displayIPK(ipk);
    toggleFormElements(ipk >= 3.0);
}

// Fungsi untuk menampilkan IPK
function displayIPK(ipk) {
    const ipkDisplay = document.getElementById('ipk-display');
    const ipkValue = document.getElementById('ipk-value');
    const ipkStatus = document.getElementById('ipk-status');
    
    if (ipkDisplay && ipkValue && ipkStatus) {
        ipkValue.textContent = ipk.toFixed(2);
        ipkDisplay.style.display = 'block';
        
        if (ipk >= 3.0) {
            ipkDisplay.className = 'ipk-display ipk-success';
            ipkStatus.textContent = 'IPK memenuhi syarat untuk mendaftar beasiswa';
        } else {
            ipkDisplay.className = 'ipk-display ipk-warning';
            ipkStatus.textContent = 'IPK tidak memenuhi syarat minimum (3.0)';
        }
    }
}

// Fungsi untuk mengaktifkan/menonaktifkan elemen form
function toggleFormElements(enabled) {
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const berkasUpload = document.getElementById('berkas_syarat');
    const submitBtn = document.getElementById('submit-btn');
    
    if (beasiswaSelect) {
        beasiswaSelect.disabled = !enabled;
        if (enabled) {
            beasiswaSelect.focus();
        }
    }
    
    if (berkasUpload) {
        berkasUpload.disabled = !enabled;
    }
    
    if (submitBtn) {
        submitBtn.disabled = !enabled;
    }
}

// Validasi email
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validasi nomor HP
function validatePhone(phone) {
    const phoneRegex = /^[0-9]+$/;
    return phoneRegex.test(phone);
}

// Validasi form lengkap
function validateForm() {
    let isValid = true;
    const errors = [];
    
    // Validasi nama
    const nama = document.getElementById('nama').value.trim();
    if (nama === '') {
        errors.push('Nama harus diisi');
        isValid = false;
    }
    
    // Validasi email
    const email = document.getElementById('email').value.trim();
    if (!validateEmail(email)) {
        errors.push('Format email tidak valid');
        isValid = false;
    }
    
    // Validasi nomor HP
    const noHp = document.getElementById('no_hp').value.trim();
    if (!validatePhone(noHp)) {
        errors.push('Nomor HP hanya boleh berisi angka');
        isValid = false;
    }
    
    // Validasi semester
    const semester = document.getElementById('semester').value;
    if (semester < 1 || semester > 8) {
        errors.push('Semester harus antara 1-8');
        isValid = false;
    }
    
    // Validasi jenis beasiswa
    const jenisBeasiswa = document.getElementById('jenis_beasiswa_id').value;
    if (jenisBeasiswa === '') {
        errors.push('Pilih jenis beasiswa');
        isValid = false;
    }
    
    // Validasi berkas
    const berkas = document.getElementById('berkas_syarat').files[0];
    if (!berkas) {
        errors.push('Upload berkas syarat');
        isValid = false;
    } else {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/zip'];
        if (!allowedTypes.includes(berkas.type)) {
            errors.push('Format berkas tidak didukung. Gunakan PDF, JPG, PNG, atau ZIP');
            isValid = false;
        }
    }
    
    // Tampilkan error jika ada
    if (!isValid) {
        showAlert(errors.join('<br>'), 'error');
    }
    
    return isValid;
}

// Fungsi untuk menampilkan alert
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = message;
    
    const form = document.getElementById('form-beasiswa');
    if (form) {
        form.insertBefore(alertDiv, form.firstChild);
        
        // Hapus alert setelah 5 detik
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Fungsi untuk format status badge
function formatStatusBadge(status) {
    let className = 'status-badge ';
    switch(status) {
        case 'belum di verifikasi':
            className += 'status-pending';
            break;
        case 'diverifikasi':
            className += 'status-verified';
            break;
        case 'ditolak':
            className += 'status-rejected';
            break;
        default:
            className += 'status-pending';
    }
    return className;
}