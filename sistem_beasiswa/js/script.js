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

// Fungsi untuk validasi IPK dan menampilkan status
function validateIPK(ipk) {
    const ipkStatus = document.getElementById('ipkStatus');
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    
    if (ipk === '' || ipk === null) {
        ipkStatus.style.display = 'none';
        return;
    }
    
    ipkStatus.style.display = 'block';
    
    if (ipk < 3.0) {
        ipkStatus.className = 'ipk-display ipk-low';
        ipkStatus.innerHTML = `IPK Anda: ${ipk} - Tidak memenuhi syarat minimum (IPK < 3.0)`;
        showAlert('IPK kurang dari 3.0. Anda tidak dapat mendaftar beasiswa.', 'danger');
        
        // Disable pilihan beasiswa yang tidak sesuai
        updateBeasiswaOptions(ipk);
    } else {
        ipkStatus.className = 'ipk-display';
        ipkStatus.innerHTML = `IPK Anda: ${ipk} - Memenuhi syarat untuk mendaftar beasiswa`;
        showAlert('IPK Anda memenuhi syarat untuk mendaftar beasiswa!', 'success');
        
        // Update pilihan beasiswa yang tersedia
        updateBeasiswaOptions(ipk);
    }
}

// Fungsi untuk mengupdate pilihan beasiswa berdasarkan IPK
function updateBeasiswaOptions(ipk) {
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const options = beasiswaSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') return; // Skip option pertama
        
        const minIPK = parseFloat(option.getAttribute('data-min-ipk'));
        
        if (ipk < minIPK) {
            option.disabled = true;
            option.style.color = '#999';
            option.text = option.text + ' (IPK tidak mencukupi)';
        } else {
            option.disabled = false;
            option.style.color = '';
            // Remove text tambahan jika ada
            option.text = option.text.replace(' (IPK tidak mencukupi)', '');
        }
    });
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
        
        // Validasi IPK
        const ipkInput = document.getElementById('ipk');
        if (ipkInput) {
            ipkInput.addEventListener('input', function() {
                const ipkValue = parseFloat(this.value);
                
                if (this.value && (isNaN(ipkValue) || ipkValue < 0 || ipkValue > 4)) {
                    this.style.borderColor = '#dc3545';
                    showAlert('IPK harus antara 0.00 - 4.00!', 'danger');
                    return;
                }
                
                if (this.value && ipkValue >= 0 && ipkValue <= 4) {
                    this.style.borderColor = '#28a745';
                    validateIPK(ipkValue);
                } else if (this.value === '') {
                    this.style.borderColor = '#e9ecef';
                    validateIPK('');
                }
            });
            
            ipkInput.addEventListener('blur', function() {
                const ipkValue = parseFloat(this.value);
                
                if (this.value && !isNaN(ipkValue)) {
                    validateIPK(ipkValue);
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
            
            // Validasi IPK
            const ipk = parseFloat(document.getElementById('ipk').value);
            if (!document.getElementById('ipk').value || isNaN(ipk) || ipk < 0 || ipk > 4) {
                isValid = false;
                showAlert('IPK harus diisi dengan nilai antara 0.00 - 4.00!', 'danger');
            } else if (ipk < 3.0) {
                isValid = false;
                showAlert('IPK minimal 3.0 untuk mendaftar beasiswa!', 'danger');
            }
            
            // Validasi pilihan beasiswa
            const beasiswa = document.getElementById('jenis_beasiswa_id').value;
            if (!beasiswa) {
                isValid = false;
                showAlert('Pilih jenis beasiswa!', 'danger');
            }
            
            // Validasi kesesuaian IPK dengan jenis beasiswa
            const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
            const selectedOption = beasiswaSelect.options[beasiswaSelect.selectedIndex];
            if (selectedOption && selectedOption.value && !isNaN(ipk)) {
                const minIPK = parseFloat(selectedOption.getAttribute('data-min-ipk'));
                if (ipk < minIPK) {
                    isValid = false;
                    showAlert(`IPK Anda (${ipk}) tidak memenuhi syarat untuk beasiswa ini (min. ${minIPK})!`, 'danger');
                }
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

// Auto-check IPK pada load jika sudah ada nilai
document.addEventListener('DOMContentLoaded', function() {
    const ipkInput = document.getElementById('ipk');
    if (ipkInput && ipkInput.value) {
        const ipkValue = parseFloat(ipkInput.value);
        if (!isNaN(ipkValue)) {
            validateIPK(ipkValue);
        }
    }
});

// Fungsi untuk konfirmasi delete (jika ada)
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}