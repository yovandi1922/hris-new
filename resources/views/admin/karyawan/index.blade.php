@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6 relative" x-data="karyawanManager()">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">👥 Daftar Karyawan</h1>
        <button type="button"
                @click="openModal()"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-xl shadow transition">
            + Tambah Karyawan
        </button>
    </div>

    <!-- Form Pencarian & Filter -->
    <form method="GET" action="{{ route('admin.karyawan.index') }}" 
          class="flex flex-wrap gap-2 items-center bg-white dark:bg-gray-700 p-4 rounded-xl shadow">
        <input type="text" 
               id="searchInput"
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Cari nama karyawan..." 
               autocomplete="off"
               class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                      flex-1 min-w-[220px] focus:ring-2 focus:ring-blue-500 
                      dark:bg-gray-800 dark:text-gray-100">
        
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-xl shadow transition flex items-center gap-2">
            <i class="fas fa-search"></i> Cari
        </button>

        <button type="button" 
                onclick="openFilterSidebar()"
                class="bg-purple-500 hover:bg-purple-600 text-white px-5 py-2 rounded-xl shadow transition flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request('search') || request('tahun') || request('jabatan') || request('status'))
            <a href="{{ route('admin.karyawan.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-xl shadow transition flex items-center gap-2">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </form>

    <!-- Info Pencarian & Filter -->
    @if(request('search') || request('tahun') || request('jabatan') || request('status'))
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 text-sm text-blue-800 dark:text-blue-200">
            <i class="fas fa-info-circle mr-2"></i>
            <span class="font-semibold">Filter Aktif:</span>
            @if(request('search'))
                <span class="ml-2">Nama: <strong>"{{ request('search') }}"</strong></span>
            @endif
            @if(request('tahun'))
                <span class="ml-2">Tahun: <strong>{{ request('tahun') }}</strong></span>
            @endif
            @if(request('jabatan'))
                <span class="ml-2">Jabatan: <strong>{{ ucfirst(request('jabatan')) }}</strong></span>
            @endif
            @if(request('status'))
                <span class="ml-2">Status: <strong>{{ request('status') }}</strong></span>
            @endif
            <span class="text-gray-600 dark:text-gray-400 ml-3">({{ $employees->total() }} hasil ditemukan)</span>
        </div>
    @endif

    <!-- Tabel Karyawan -->
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full border-collapse bg-white dark:bg-gray-700 rounded-xl overflow-hidden">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-left">
                    <th class="py-3 px-5 font-semibold min-w-[80px]">NIP</th>
                    <th class="py-3 px-5 font-semibold min-w-[200px]">Nama Karyawan</th>
                    <th class="py-3 px-5 font-semibold min-w-[140px]">Tanggal Gabung</th>
                    <th class="py-3 px-5 font-semibold min-w-[120px]">Jabatan</th>
                    <th class="py-3 px-5 font-semibold min-w-[200px]">Email</th>
                    <th class="py-3 px-5 font-semibold min-w-[110px]">Status Kerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                    <tr class="cursor-pointer border-t border-gray-200 dark:border-gray-600 
                               hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                        @click="openDetailModal({{ $employee->id }})">
                        <td class="py-3 px-5 text-gray-800 dark:text-gray-100 font-semibold">
                            {{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="py-3 px-5 text-gray-800 dark:text-gray-100 break-words">{{ $employee->name }}</td>
                        <td class="py-3 px-5 text-gray-600 dark:text-gray-300">
                            {{ $employee->start_date ? \Carbon\Carbon::parse($employee->start_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="py-3 px-5 text-gray-600 dark:text-gray-300">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $employee->role === 'admin' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                {{ $employee->role === 'admin' ? 'Admin' : 'Staf' }}
                            </span>
                        </td>
                        <td class="py-3 px-5 text-gray-600 dark:text-gray-300 break-words">{{ $employee->email }}</td>
                        <td class="py-3 px-5">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ ($employee->work_status ?? 'Aktif') === 'Aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                {{ $employee->work_status ?? 'Aktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            @if(request('search'))
                                <p class="font-semibold">Tidak ada hasil untuk "<strong>{{ request('search') }}</strong>"</p>
                                <p class="text-sm mt-1">Coba cari dengan kata kunci lain atau <a href="{{ route('admin.karyawan.index') }}" class="text-blue-500 hover:underline">reset pencarian</a></p>
                            @else
                                <p class="font-semibold">Tidak ada data karyawan</p>
                                <p class="text-sm mt-1">Klik tombol "+ Tambah Karyawan" untuk menambah data baru</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $employees->appends(['search' => request('search')])->links() }}
    </div>

    <!-- MODAL: Tambah Karyawan -->
    <div x-show="showModal" 
         @click="if ($event.target === $el) closeModal()"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm transition-opacity"
         style="display: none;">
        
        <div @click.stop
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header -->
            <div class="sticky top-0 flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Karyawan Baru</h2>
                <button type="button" @click="closeModal()" 
                        class="text-gray-600 dark:text-gray-300 hover:text-red-500 text-2xl font-bold">×</button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitForm()" class="p-6 space-y-4">
                @csrf
                
                <!-- NIP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        NIP (3 digit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           x-model="form.nip" 
                           required
                           maxlength="3"
                           placeholder="Contoh: 001"
                           @input="form.nip = form.nip.replace(/[^0-9]/g, '')"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">NIP harus 3 digit angka</p>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" x-model="form.name" required
                           placeholder="Masukkan nama lengkap"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select x-model="form.position" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-gray-100">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Staf">Staf</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" x-model="form.email" required
                           placeholder="example@paradise.com"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" x-model="form.password" required
                           placeholder="Minimal 8 karakter"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        No HP <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           x-model="form.phone" 
                           required
                           placeholder="Contoh: 08123456789"
                           @input="form.phone = form.phone.replace(/[^0-9]/g, '')"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hanya angka, min 10 digit</p>
                </div>

                <!-- Tanggal Masuk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Tanggal Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="date" x-model="form.start_date" required
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- Error Message -->
                <template x-if="errorMessage">
                    <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                        <span x-text="errorMessage"></span>
                    </div>
                </template>

                <!-- Success Message -->
                <template x-if="successMessage">
                    <div class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        <span x-text="successMessage"></span>
                    </div>
                </template>
            </form>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <button type="button" @click="closeModal()"
                        class="px-5 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="button" @click="submitForm()" :disabled="loading"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white 
                               shadow transition disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                    <span x-show="!loading">Simpan</span>
                    <span x-show="loading">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: Detail & Edit Karyawan -->
    <div x-show="detailModal" 
         @click="if ($event.target === $el) closeDetailModal()"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm transition-opacity"
         style="display: none;">
        
        <div @click.stop
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header -->
            <div class="sticky top-0 flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Karyawan</h2>
                <button type="button" @click="closeDetailModal()" 
                        class="text-gray-600 dark:text-gray-300 hover:text-red-500 text-2xl font-bold">×</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                
                <!-- NIP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        NIP
                    </label>
                    <input type="text" 
                           x-model="detailForm.nip" 
                           :disabled="!editMode"
                           readonly
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text" 
                           x-model="detailForm.name" 
                           :disabled="!editMode"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                </div>

                <!-- Tanggal Gabung -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Tanggal Gabung
                    </label>
                    <input type="date" 
                           x-model="detailForm.start_date" 
                           :disabled="!editMode"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Jabatan
                    </label>
                    <select x-model="detailForm.role" :disabled="!editMode"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                        <option value="karyawan">Staf</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Email
                    </label>
                    <input type="email" 
                           x-model="detailForm.email" 
                           :disabled="!editMode"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                </div>

                <!-- Status Kerja -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Status Kerja
                    </label>
                    <select x-model="detailForm.work_status" :disabled="!editMode"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                        <option value="Aktif">Aktif</option>
                        <option value="Resign">Resign</option>
                    </select>
                </div>

                <!-- Error Message -->
                <template x-if="detailErrorMessage">
                    <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                        <span x-text="detailErrorMessage"></span>
                    </div>
                </template>

                <!-- Success Message -->
                <template x-if="detailSuccessMessage">
                    <div class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        <span x-text="detailSuccessMessage"></span>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <button type="button" @click="closeDetailModal()"
                        class="px-5 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Tutup
                </button>
                <template x-if="!editMode">
                    <button type="button" @click="toggleEditMode()"
                            class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white 
                                   shadow transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                </template>
                <template x-if="editMode">
                    <div class="flex gap-2">
                        <button type="button" @click="toggleEditMode()"
                                class="px-5 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                       text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Batal
                        </button>
                        <button type="button" @click="saveDetailForm()" :disabled="detailLoading"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white 
                                       shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!detailLoading"><i class="fas fa-save mr-2"></i>Simpan</span>
                            <span x-show="detailLoading"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Filter Sidebar -->
        <div id="filterSidebar" 
            class="fixed top-0 right-0 h-screen w-96 bg-white dark:bg-gray-800 shadow-2xl 
                 transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
        
        <!-- Header -->
        <div class="sticky top-0 p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 z-10">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                <i class="fas fa-filter mr-2"></i>Filter Pencarian
            </h2>
            <button onclick="closeFilterSidebar()" 
                    class="text-gray-600 dark:text-gray-300 hover:text-red-500 text-2xl font-bold">×</button>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.karyawan.index') }}" class="p-6 space-y-6 flex-1 min-h-0 overflow-y-auto">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <!-- Tahun Gabung -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                    Tahun Gabung
                </label>
                
                <div class="space-y-3">
                    <!-- Semua -->
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tahun_filter" value="" 
                               {{ !request('tahun_mulai') && !request('tahun_selesai') && !request('tahun') ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Semua Tahun</span>
                    </label>

                    <!-- Pilih Tahun Spesifik -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mb-2">
                            <input type="radio" name="tahun_filter" value="single" 
                                   {{ request('tahun') && !request('tahun_mulai') ? 'checked' : '' }}
                                   onchange="toggleTahunSingle()"
                                   class="w-4 h-4">
                            <span class="text-gray-700 dark:text-gray-200">Tahun Tertentu</span>
                        </label>
                        <select name="tahun" 
                                id="tahunSingle"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                       dark:bg-gray-700 dark:text-gray-100"
                                {{ request('tahun') && !request('tahun_mulai') ? '' : 'disabled' }}>
                            <option value="">-- Pilih Tahun --</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Rentang Tahun -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mb-2">
                            <input type="radio" name="tahun_filter" value="range" 
                                   {{ request('tahun_mulai') ? 'checked' : '' }}
                                   onchange="toggleTahunRange()"
                                   class="w-4 h-4">
                            <span class="text-gray-700 dark:text-gray-200">Rentang Tahun</span>
                        </label>
                        <div class="space-y-2">
                            <select name="tahun_mulai" 
                                    id="tahunMulai"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                           dark:bg-gray-700 dark:text-gray-100"
                                    {{ request('tahun_mulai') ? '' : 'disabled' }}>
                                <option value="">-- Dari Tahun --</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('tahun_mulai') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            <select name="tahun_selesai" 
                                    id="tahunSelesai"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                           dark:bg-gray-700 dark:text-gray-100"
                                    {{ request('tahun_mulai') ? '' : 'disabled' }}>
                                <option value="">-- Sampai Tahun --</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('tahun_selesai') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                    Jabatan
                </label>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jabatan" value="" 
                               {{ !request('jabatan') ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Semua Jabatan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jabatan" value="karyawan" 
                               {{ request('jabatan') === 'karyawan' ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Staf</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jabatan" value="admin" 
                               {{ request('jabatan') === 'admin' ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Admin</span>
                    </label>
                </div>
            </div>

            <!-- Status Kerja -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                    Status Kerja
                </label>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="" 
                               {{ !request('status') ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Semua Status</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="Aktif" 
                               {{ request('status') === 'Aktif' ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="Resign" 
                               {{ request('status') === 'Resign' ? 'checked' : '' }}
                               class="w-4 h-4">
                        <span class="text-gray-700 dark:text-gray-200">Resign</span>
                    </label>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.karyawan.index') }}" 
                   class="flex-1 text-center bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-100 px-4 py-2.5 rounded-lg font-semibold transition">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i>Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Overlay untuk tutup sidebar saat klik di luar -->
    <div id="filterOverlay" 
         class="fixed inset-0 bg-black bg-opacity-0 z-40 transform transition-all duration-300"
         onclick="closeFilterSidebar()"
         style="pointer-events: none;"></div>
</div>

<!-- Script Filter & Modal -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function karyawanManager() {
    return {
        // Modal Tambah Karyawan
        showModal: false,
        loading: false,
        errorMessage: '',
        successMessage: '',
        form: {
            nip: '',
            name: '',
            position: '',
            email: '',
            password: '',
            phone: '',
            start_date: ''
        },

        // Modal Detail & Edit Karyawan
        detailModal: false,
        editMode: false,
        detailLoading: false,
        detailErrorMessage: '',
        detailSuccessMessage: '',
        currentEmployee: null,
        detailForm: {
            id: null,
            nip: '',
            name: '',
            email: '',
            start_date: '',
            role: '',
            work_status: 'Aktif'
        },

        // ========== MODAL TAMBAH ==========
        async openModal() {
            this.resetForm();
            // Ambil NIP default dari backend
            try {
                const res = await fetch("{{ route('admin.karyawan.create') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (res.ok) {
                    const data = await res.json();
                    this.form.nip = data.nipBaru;
                } else {
                    this.form.nip = '';
                }
            } catch (e) {
                this.form.nip = '';
            }
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                nip: '',
                name: '',
                position: '',
                email: '',
                password: '',
                phone: '',
                start_date: ''
            };
            this.errorMessage = '';
            this.successMessage = '';
        },

        async submitForm() {
            // Validasi frontend
            if (!this.form.nip || !this.form.name || !this.form.position || !this.form.email || !this.form.password || !this.form.phone || !this.form.start_date) {
                this.errorMessage = 'Semua field harus diisi!';
                return;
            }

            if (this.form.nip.length !== 3 || isNaN(this.form.nip)) {
                this.errorMessage = 'NIP harus 3 digit angka!';
                return;
            }

            if (this.form.password.length < 8) {
                this.errorMessage = 'Password minimal 8 karakter!';
                return;
            }

            if (this.form.phone.length < 10) {
                this.errorMessage = 'No HP minimal 10 digit!';
                return;
            }

            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const url = '{{ route("admin.karyawan.store") }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                const payload = {
                    nip: this.form.nip,
                    name: this.form.name,
                    position: this.form.position,
                    email: this.form.email,
                    password: this.form.password,
                    phone: this.form.phone,
                    start_date: this.form.start_date
                };

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.successMessage = 'Karyawan berhasil ditambahkan!';
                    
                    // Reload halaman untuk refresh tabel
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.errorMessage = data.message || 'Terjadi kesalahan saat menyimpan!';
                }
            } catch (error) {
                console.error('Error:', error);
                this.errorMessage = 'Terjadi kesalahan koneksi: ' + error.message;
            } finally {
                this.loading = false;
            }
        },

        // ========== MODAL DETAIL & EDIT ==========
        async openDetailModal(employeeId) {
            this.detailErrorMessage = '';
            this.detailSuccessMessage = '';
            this.editMode = false;
            this.detailLoading = true;

            try {
                const url = `{{ url('/admin/karyawan') }}/${employeeId}`;
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.employee) {
                    this.currentEmployee = data.employee;
                    this.detailForm = {
                        id: data.employee.id,
                        nip: data.employee.nip || '',
                        name: data.employee.name,
                        email: data.employee.email,
                        start_date: data.employee.start_date || '',
                        role: data.employee.role || 'karyawan',
                        work_status: data.employee.work_status || 'Aktif'
                    };
                    this.detailModal = true;
                } else {
                    this.detailErrorMessage = 'Gagal memuat data karyawan!';
                }
            } catch (error) {
                console.error('Error:', error);
                this.detailErrorMessage = 'Terjadi kesalahan: ' + error.message;
            } finally {
                this.detailLoading = false;
            }
        },

        closeDetailModal() {
            this.detailModal = false;
            this.editMode = false;
            this.resetDetailForm();
        },

        resetDetailForm() {
            this.detailForm = {
                id: null,
                nip: '',
                name: '',
                email: '',
                start_date: '',
                role: '',
                work_status: 'Aktif'
            };
            this.detailErrorMessage = '';
            this.detailSuccessMessage = '';
        },

        toggleEditMode() {
            this.editMode = !this.editMode;
            this.detailErrorMessage = '';
        },

        async saveDetailForm() {
            // Validasi
            if (!this.detailForm.name || !this.detailForm.email) {
                this.detailErrorMessage = 'Nama dan email harus diisi!';
                return;
            }

            this.detailLoading = true;
            this.detailErrorMessage = '';
            this.detailSuccessMessage = '';

            try {
                const url = `{{ url('/admin/karyawan') }}/${this.detailForm.id}`;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                const payload = {
                    name: this.detailForm.name,
                    email: this.detailForm.email,
                    start_date: this.detailForm.start_date,
                    role: this.detailForm.role,
                    work_status: this.detailForm.work_status
                };

                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.detailSuccessMessage = 'Data karyawan berhasil diperbarui!';
                    
                    // Reload untuk refresh tabel
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.detailErrorMessage = data.message || 'Gagal memperbarui data!';
                }
            } catch (error) {
                console.error('Error:', error);
                this.detailErrorMessage = 'Terjadi kesalahan: ' + error.message;
            } finally {
                this.detailLoading = false;
            }
        }
    };
}

function openFilterSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('filterOverlay');
    sidebar.classList.remove('translate-x-full');
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    overlay.style.pointerEvents = 'auto';
}

function closeFilterSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('filterOverlay');
    sidebar.classList.add('translate-x-full');
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    overlay.style.pointerEvents = 'none';
}

function toggleTahunSingle() {
    const tahunSingle = document.getElementById('tahunSingle');
    const tahunMulai = document.getElementById('tahunMulai');
    const tahunSelesai = document.getElementById('tahunSelesai');
    tahunSingle.disabled = false;
    tahunMulai.disabled = true;
    tahunSelesai.disabled = true;
}

function toggleTahunRange() {
    const tahunSingle = document.getElementById('tahunSingle');
    const tahunMulai = document.getElementById('tahunMulai');
    const tahunSelesai = document.getElementById('tahunSelesai');
    tahunSingle.disabled = true;
    tahunMulai.disabled = false;
    tahunSelesai.disabled = false;
}
</script>
@endsection
