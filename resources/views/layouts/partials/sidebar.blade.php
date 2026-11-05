<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">

        {{-- Brand --}}
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon">
                <i class="fas fa-boxes-packing"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">Souvenir Jibranime</div>
                <div class="sidebar-brand-subtitle">Monitoring 3 Marketplace</div>
            </div>
        </a>

        {{-- Menu --}}
        <ul class="sidebar-nav">

            @can('dashboard-view')
                <li class="sidebar-header">Menu Utama</li>
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dashboard') }}">
                        <span class="sidebar-link-icon"><i class="fas fa-th-large"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- 1. Pesanan & Produk Jualan --}}
            @canany(['penjualan-list', 'produk-list', 'penerimaan-produk-list'])
                <li class="sidebar-header">Pesanan &amp; Produk</li>

                @can('penjualan-list')
                    <li class="sidebar-item {{ request()->routeIs('transaksi-penjualan.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('transaksi-penjualan.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-boxes-packing"></i></span>
                            <span>Pesanan Marketplace</span>
                        </a>
                    </li>
                @endcan

                @can('produk-list')
                    <li class="sidebar-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('produk.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-gift"></i></span>
                            <span>Katalog Souvenir</span>
                        </a>
                    </li>
                @endcan

                @can('penerimaan-produk-list')
                    <li class="sidebar-item {{ request()->routeIs('penerimaan-produk.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('penerimaan-produk.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-boxes-stacked"></i></span>
                            <span>Hasil Produksi Masuk</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- 2. Gudang & Bahan Baku (Operasional Material Workshop) --}}
            @canany(['bahan-baku-list', 'pemakaian-bahan-list', 'pembelian-list'])
                <li class="sidebar-header">Gudang &amp; Bahan Baku</li>

                @can('bahan-baku-list')
                    <li class="sidebar-item {{ request()->routeIs('bahan-baku.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('bahan-baku.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-layer-group"></i></span>
                            <span>Stok Bahan Baku</span>
                        </a>
                    </li>
                @endcan

                @can('pemakaian-bahan-list')
                    <li class="sidebar-item {{ request()->routeIs('pemakaian-bahan.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pemakaian-bahan.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-dolly"></i></span>
                            <span>Pemakaian Bahan Baku</span>
                        </a>
                    </li>
                @endcan

                @can('pembelian-list')
                    <li class="sidebar-item {{ request()->routeIs('transaksi-pembelian.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('transaksi-pembelian.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-truck-ramp-box"></i></span>
                            <span>Pembelian Bahan Baku</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- 3. Master Data --}}
            @canany(['kategori-list', 'satuan-list'])
                <li class="sidebar-header">Master Data</li>

                @can('kategori-list')
                    <li class="sidebar-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('kategori.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-tags"></i></span>
                            <span>Kategori</span>
                        </a>
                    </li>
                @endcan

                @can('satuan-list')
                    <li class="sidebar-item {{ request()->routeIs('satuan.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('satuan.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-ruler-combined"></i></span>
                            <span>Satuan</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- 4. Keuangan & Kas (Kas Masuk & Pengeluaran Operasional) --}}
            @canany(['kas-masuk-list', 'pengeluaran-list'])
                <li class="sidebar-header">Keuangan &amp; Kas</li>

                @can('kas-masuk-list')
                    <li class="sidebar-item {{ request()->routeIs('kas-masuk.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('kas-masuk.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-hand-holding-dollar"></i></span>
                            <span>Pencairan Saldo &amp; Modal</span>
                        </a>
                    </li>
                @endcan

                @can('pengeluaran-list')
                    <li class="sidebar-item {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pengeluaran.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-credit-card"></i></span>
                            <span>Biaya Operasional &amp; Ads</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- 5. Laporan & Analitik --}}
            @canany([
                'laporan-view',
                'laporan-laba-rugi',
                'laporan-penjualan',
                'laporan-pembelian',
                'laporan-pengeluaran',
                'laporan-produksi',
                'laporan-pribadi',
                'laporan-rekonsiliasi',
                'laporan-kasbon',
            ])
                @php
                    $currentLaporanTab = request()->routeIs('laporan.*') ? request('tab', 'laba-rugi') : null;
                @endphp
                <li class="sidebar-header">Laporan &amp; Analitik</li>

                @can('laporan-laba-rugi')
                    <li class="sidebar-item {{ $currentLaporanTab === 'laba-rugi' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'laba-rugi']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-chart-line"></i></span>
                            <span>Laba/Rugi Bisnis</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-penjualan')
                    <li class="sidebar-item {{ $currentLaporanTab === 'penjualan' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'penjualan']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-bag-shopping"></i></span>
                            <span>Laporan Penjualan MP</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-pembelian')
                    <li class="sidebar-item {{ $currentLaporanTab === 'pembelian' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'pembelian']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-cart-flatbed"></i></span>
                            <span>Laporan Pembelian Bahan</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-pengeluaran')
                    <li class="sidebar-item {{ $currentLaporanTab === 'pengeluaran' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'pengeluaran']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-bullhorn"></i></span>
                            <span>Laporan Operasional &amp; Ads</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-produksi')
                    <li class="sidebar-item {{ $currentLaporanTab === 'produksi' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'produksi']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-industry"></i></span>
                            <span>Laporan Produksi &amp; QC</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-pribadi')
                    <li class="sidebar-item {{ $currentLaporanTab === 'pribadi' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'pribadi']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-wallet"></i></span>
                            <span>Laporan Prive Pemilik</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-rekonsiliasi')
                    <li class="sidebar-item {{ $currentLaporanTab === 'rekonsiliasi' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'rekonsiliasi']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-scale-balanced"></i></span>
                            <span>Rekonsiliasi Kas &amp; Modal</span>
                        </a>
                    </li>
                @endcan

                @can('laporan-kasbon')
                    <li class="sidebar-item {{ $currentLaporanTab === 'kasbon' ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan', ['tab' => 'kasbon']) }}">
                            <span class="sidebar-link-icon"><i class="fas fa-hand-holding-dollar"></i></span>
                            <span>Laporan Kasbon Tim</span>
                        </a>
                    </li>
                @endcan
            @endcanany

            {{-- 6. Pengaturan Akses --}}
            @canany(['user-list', 'role-list', 'permission-list'])
                <li class="sidebar-header">Pengaturan Akses</li>

                @can('user-list')
                    <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('users.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-users"></i></span>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                @endcan

                @can('role-list')
                    <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('roles.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-user-shield"></i></span>
                            <span>Role</span>
                        </a>
                    </li>
                @endcan

                @can('permission-list')
                    <li class="sidebar-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('permissions.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-key"></i></span>
                            <span>Permission</span>
                        </a>
                    </li>
                @endcan
            @endcanany

        </ul>
    </div>
</nav>
