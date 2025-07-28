// Fungsi untuk validasi email
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Fungsi untuk validasi nomor HP (hanya angka)
function validatePhone(phone) {
    const phoneRegex = /^[0-9]+$/;
    return phoneRegex.test(phone);
}

// Fungsi untuk mengaktifkan/menonaktifkan form berdasarkan IPK
function toggleFormElements(ipk) {
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const berkasInput = document.getElementById('berkas_syarat');
    const submitBtn = document.getElementById('submitBtn');
    
    if (ipk < 3.0) {
        // IPK kurang dari 3, disable form elements
        beasiswaSelect.disabled = true;
        berkasInput.disabled = true;
        submitBtn.disabled = true;
        
        // Tampilkan pesan
        showAlert('IPK Anda kurang dari 3.0. Anda tidak dapat mendaftar beasiswa.', 'danger');
    } else {
        // IPK 3.0 atau lebih, enable form elements
        beasiswaSelect.disabled = false;
        berkasInput.disabled = false;
        submitBtn.disabled = false;
        
        // Focus ke pilihan beasiswa
        beasiswaSelect.focus();
        
        showAlert('IPK Anda memenuhi syarat untuk mendaftar beasiswa!', 'success');
    }
}

// Fungsi untuk menampilkan alert
function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    if (alertContainer) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type}">
                ${message}
            </div>
        `;
        
        // Auto hide alert after 5 seconds
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }
}

// Validasi form real-time
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('beasiswaForm');
    
    if (form) {
        // Validasi email
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value && !validateEmail(this.value)) {
                    this.style.borderColor = '#dc3545';
                    showAlert('Format email tidak valid!', 'danger');
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
        }
        
        // Validasi nomor HP
        const phoneInput = document.getElementById('no_hp');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Hanya izinkan angka
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            phoneInput.addEventListener('blur', function() {
                if (this.value && !validatePhone(this.value)) {
                    this.style.borderColor = '#dc3545';
                    showAlert('Nomor HP hanya boleh berisi angka!', 'danger');
                } else if (this.value.length < 10) {
                    this.style.borderColor = '#dc3545';
                    showAlert('Nomor HP minimal 10 digit!', 'danger');
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
        }
        
        // Validasi semester
        const semesterSelect = document.getElementById('semester');
        if (semesterSelect) {
            semesterSelect.addEventListener('change', function() {
                if (this.value < 1 || this.value > 8) {
                    this.style.borderColor = '#dc3545';
                    showAlert('Semester harus antara 1-8!', 'danger');
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
        }
        
        // Validasi file upload
        const fileInput = document.getElementById('berkas_syarat');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/zip'];
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if (!allowedTypes.includes(file.type)) {
                        this.value = '';
                        showAlert('Tipe file tidak diizinkan! Hanya PDF, JPG, PNG, dan ZIP yang diperbolehkan.', 'danger');
                        return;
                    }
                    
                    if (file.size > maxSize) {
                        this.value = '';
                        showAlert('Ukuran file terlalu besar! Maksimal 5MB.', 'danger');
                        return;
                    }
                    
                    showAlert('File berhasil dipilih: ' + file.name, 'success');
                }
            });
        }
        
        // Validasi form sebelum submit
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = ['nama', 'email', 'no_hp', 'semester'];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (input && !input.value.trim()) {
                    input.style.borderColor = '#dc3545';
                    isValid = false;
                }
            });
            
            // Validasi email
            const email = document.getElementById('email').value;
            if (email && !validateEmail(email)) {
                isValid = false;
                showAlert('Format email tidak valid!', 'danger');
            }
            
            // Validasi nomor HP
            const phone = document.getElementById('no_hp').value;
            if (phone && (!validatePhone(phone) || phone.length < 10)) {
                isValid = false;
                showAlert('Nomor HP tidak valid!', 'danger');
            }
            
            // Validasi semester
            const semester = document.getElementById('semester').value;
            if (semester && (semester < 1 || semester > 8)) {
                isValid = false;
                showAlert('Semester harus antara 1-8!', 'danger');
            }
            
            // Validasi pilihan beasiswa
            const beasiswa = document.getElementById('jenis_beasiswa_id').value;
            if (!beasiswa) {
                isValid = false;
                showAlert('Pilih jenis beasiswa!', 'danger');
            }
            
            // Validasi file upload
            const file = document.getElementById('berkas_syarat').files[0];
            if (!file) {
                isValid = false;
                showAlert('Upload berkas syarat!', 'danger');
            }
            
            if (!isValid) {
                e.preventDefault();
                showAlert('Mohon lengkapi semua field dengan benar!', 'danger');
            } else {
                // Konfirmasi sebelum submit
                if (!confirm('Apakah Anda yakin ingin mendaftar beasiswa ini?')) {
                    e.preventDefault();
                }
            }
        });
    }
});

// Fungsi untuk menampilkan IPK dan mengatur form
function displayIPK() {
    // Simulasi mendapatkan IPK dari server
    fetch('get_ipk.php')
        .then(response => response.json())
        .then(data => {
            const ipkDisplay = document.getElementById('ipkDisplay');
            const ipkValue = data.ipk;
            
            if (ipkDisplay) {
                ipkDisplay.innerHTML = `IPK Anda: ${ipkValue}`;
                ipkDisplay.className = ipkValue >= 3.0 ? 'ipk-display' : 'ipk-display ipk-low';
            }
            
            // Set nilai IPK ke hidden input
            const ipkInput = document.getElementById('ipk');
            if (ipkInput) {
                ipkInput.value = ipkValue;
            }
            
            // Toggle form elements berdasarkan IPK
            toggleFormElements(ipkValue);
        })
        .catch(error => {
            console.error('Error fetching IPK:', error);
            // Fallback jika AJAX gagal
            const ipkValue = 3.4; // Default IPK
            const ipkDisplay = document.getElementById('ipkDisplay');
            if (ipkDisplay) {
                ipkDisplay.innerHTML = `IPK Anda: ${ipkValue}`;
                ipkDisplay.className = 'ipk-display';
            }
            
            const ipkInput = document.getElementById('ipk');
            if (ipkInput) {
                ipkInput.value = ipkValue;
            }
            
            toggleFormElements(ipkValue);
        });
}

// Auto load IPK when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('ipkDisplay')) {
        displayIPK();
    }
});

// Fungsi untuk konfirmasi delete (jika ada)
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}