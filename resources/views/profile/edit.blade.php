@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola profil dan preferensi akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden sticky top-20">
                <nav class="p-2">
                    <a href="#profile" class="settings-nav block px-4 py-3 text-emerald-600 bg-emerald-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <a href="#password" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-lock mr-2"></i> Kata Sandi
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
            @if(session('status') === 'profile-updated')
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Profil berhasil diperbarui
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Kata sandi berhasil diubah
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Section -->
            <div id="profile" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-emerald-600"></i>
                    Informasi Profil
                </h3>
                
                <!-- Avatar Upload -->
                <div class="flex flex-col sm:flex-row items-center gap-6 mb-6 pb-6 border-b">
                    <div class="relative">
                        @if(auth()->user()->avatar)
                            <img id="avatar_preview" src="{{ asset('storage/'.auth()->user()->avatar) }}" 
                                 alt="Profile" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-emerald-200">
                        @else
                            <img id="avatar_preview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff&size=200" 
                                 alt="Profile" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-emerald-200">
                        @endif
                        
                        <button type="button" onclick="openUploadModal()" 
                                class="absolute bottom-0 right-0 w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-lg" 
                                title="Ubah Foto Profil">
                            <i class="fas fa-camera text-sm"></i>
                        </button>
                        
                        <input type="file" id="avatar_upload" name="avatar" class="hidden" accept="image/*" onchange="handleFileSelect(this)">
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <p class="font-semibold text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        @if(auth()->user()->avatar)
                            <button type="button" onclick="deleteAvatar()" class="mt-2 text-xs text-red-600 hover:text-red-800 font-medium">
                                <i class="fas fa-trash mr-1"></i>Hapus Foto
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Profile Form -->
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="dob" value="{{ old('dob', auth()->user()->dob) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">{{ old('address', auth()->user()->address) }}</textarea>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div id="password" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock mr-2 text-emerald-600"></i>
                    Ubah Kata Sandi
                </h3>
                
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('put')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition" 
                                   required>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-key mr-2"></i>Ubah Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-camera mr-2"></i>Ubah Foto Profil
            </h3>
            <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <button type="button" onclick="openCamera()" 
                    class="w-full p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition flex items-center gap-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-video text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="font-semibold">Ambil Foto dari Kamera</p>
                    <p class="text-sm text-purple-100">Foto langsung menggunakan kamera</p>
                </div>
            </button>
            
            <button type="button" onclick="selectFromFile()" 
                    class="w-full p-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition flex items-center gap-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-image text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="font-semibold">Pilih dari Galeri</p>
                    <p class="text-sm text-emerald-100">Upload foto dari perangkat</p>
                </div>
            </button>
        </div>
        
        <div class="mt-6 pt-6 border-t">
            <button type="button" onclick="closeUploadModal()" 
                    class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div id="cameraModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-camera mr-2"></i>Ambil Foto Profil
            </h3>
            <button type="button" onclick="closeCamera()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-4">
            <div class="relative bg-gray-900 rounded-lg overflow-hidden mb-4" style="aspect-ratio: 16/9;">
                <video id="cameraVideo" autoplay playsinline class="w-full h-full object-cover"></video>
                <canvas id="cameraCanvas" style="display: none;"></canvas>
            </div>
            
            <div id="cameraControls" class="flex justify-center gap-3">
                <button type="button" onclick="capturePhoto()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center gap-2">
                    <i class="fas fa-camera"></i>
                    <span>Ambil Foto</span>
                </button>
                <button type="button" onclick="closeCamera()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
            
            <div id="capturedPhotoPreview" style="display: none;" class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview Foto:</p>
                <img id="capturedImage" class="w-full rounded-lg border-2 border-emerald-200 mb-3">
                <div class="flex gap-3">
                    <button type="button" onclick="useCapturedPhoto()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-check mr-2"></i>Gunakan Foto Ini
                    </button>
                    <button type="button" onclick="retakePhoto()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-redo mr-2"></i>Ambil Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Avatar Modal -->
<div id="deleteAvatarModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-2xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Foto Profil?</h3>
            <p class="text-gray-600 mb-6">Foto profil Anda akan dihapus dan kembali ke avatar default.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteAvatarModal()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition">
                    Batal
                </button>
                <form action="{{ route('profile.delete-avatar') }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Smooth scroll untuk navigation
document.querySelectorAll('.settings-nav').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        document.querySelectorAll('.settings-nav').forEach(l => {
            l.classList.remove('text-emerald-600', 'bg-emerald-50');
            l.classList.add('text-gray-600');
        });
        this.classList.add('text-emerald-600', 'bg-emerald-50');
        this.classList.remove('text-gray-600');
    });
});

// Toast Notification
function showToast(message, type = 'success', duration = 3000) {
    const existing = document.querySelector('.custom-toast');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-6 right-6 z-[9999] px-6 py-4 rounded-xl shadow-2xl text-white flex items-center gap-3 max-w-md ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-blue-600'
    }`;
    
    const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
    const titles = { success: 'Berhasil!', error: 'Error!', info: 'Informasi' };
    
    toast.innerHTML = `
        <i class="fas fa-${icons[type]} text-xl"></i>
        <div class="flex-1">
            <p class="font-semibold text-sm">${titles[type]}</p>
            <p class="text-sm opacity-90">${message}</p>
        </div>
        <button onclick="this.closest('.custom-toast').remove()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Modal Functions
function openUploadModal() { document.getElementById('uploadModal').style.display = 'flex'; }
function closeUploadModal() { document.getElementById('uploadModal').style.display = 'none'; }
function selectFromFile() { 
    closeUploadModal(); 
    document.getElementById('avatar_upload').click(); 
}

// Handle File Selection
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (file.size > 5 * 1024 * 1024) {
            showToast('Ukuran file terlalu besar! Maksimal 5MB.', 'error');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar_preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        uploadAvatar(file);
    }
}

// Upload Avatar via AJAX
function uploadAvatar(file) {
    const formData = new FormData();
    formData.append('avatar', file);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showToast('CSRF token tidak ditemukan', 'error');
        return;
    }
    
    formData.append('_token', csrfToken.getAttribute('content'));
    
    showToast('Mengupload foto...', 'info', 0);
    
    fetch('{{ route("profile.upload-avatar") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message + ' Halaman akan di-refresh...', 'success', 2000);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showToast(data.message || 'Gagal menyimpan foto', 'error');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        showToast('Terjadi kesalahan saat upload foto', 'error');
        location.reload();
    });
}

// Camera Functions
let cameraStream = null;
let capturedBlob = null;

async function openCamera() {
    closeUploadModal();
    
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }, 
            audio: false 
        });
        
        document.getElementById('cameraVideo').srcObject = cameraStream;
        document.getElementById('cameraModal').style.display = 'flex';
        document.getElementById('capturedPhotoPreview').style.display = 'none';
        document.getElementById('cameraControls').style.display = 'flex';
    } catch (error) {
        console.error('Camera error:', error);
        showToast('Tidak dapat mengakses kamera.', 'error');
    }
}

function closeCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    document.getElementById('cameraModal').style.display = 'none';
    capturedBlob = null;
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const context = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);
    
    canvas.toBlob(function(blob) {
        capturedBlob = blob;
        const imageUrl = URL.createObjectURL(blob);
        document.getElementById('capturedImage').src = imageUrl;
        document.getElementById('capturedPhotoPreview').style.display = 'block';
        document.getElementById('cameraControls').style.display = 'none';
    }, 'image/jpeg', 0.9);
}

function retakePhoto() {
    document.getElementById('capturedPhotoPreview').style.display = 'none';
    document.getElementById('cameraControls').style.display = 'flex';
    capturedBlob = null;
}

function useCapturedPhoto() {
    if (!capturedBlob) {
        showToast('Tidak ada foto yang diambil', 'error');
        return;
    }
    
    const file = new File([capturedBlob], 'camera-photo-' + Date.now() + '.jpg', { type: 'image/jpeg' });
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('avatar_preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
    
    closeCamera();
    
    uploadAvatar(file);
}

// Delete Avatar
function deleteAvatar() {
    document.getElementById('deleteAvatarModal').style.display = 'flex';
}

function closeDeleteAvatarModal() {
    document.getElementById('deleteAvatarModal').style.display = 'none';
}

// Modal click outside to close
['uploadModal', 'cameraModal', 'deleteAvatarModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            if (id === 'uploadModal') closeUploadModal();
            if (id === 'cameraModal') closeCamera();
            if (id === 'deleteAvatarModal') closeDeleteAvatarModal();
        }
    });
});

// ESC key to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
        closeCamera();
        closeDeleteAvatarModal();
    }
});
</script>
@endsection