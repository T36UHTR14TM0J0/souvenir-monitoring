<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">

        {{-- Brand --}}
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">Souvenir Jibranime</div>
                <div class="sidebar-brand-subtitle">Toko Souvenir</div>
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

            @canany(['produk-list', 'kategori-list', 'satuan-list'])
                <li class="sidebar-header">Master Data</li>

                @can('produk-list')
                    @if(Route::has('produk.index'))
                        <li class="sidebar-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('produk.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-box"></i></span>
                                <span>Produk</span>
                            </a>
                        </li>
                    @endif
                @endcan

                @can('kategori-list')
                    @if(Route::has('kategori.index'))
                        <li class="sidebar-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('kategori.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-tags"></i></span>
                                <span>Kategori</span>
                            </a>
                        </li>
                    @endif
                @endcan

                @can('satuan-list')
                    @if(Route::has('satuan.index'))
                        <li class="sidebar-item {{ request()->routeIs('satuan.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('satuan.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-ruler-combined"></i></span>
                                <span>Satuan</span>
                            </a>
                        </li>
                    @endif
                @endcan
            @endcanany

            @canany(['penjualan-list', 'pembelian-list', 'pengeluaran-list'])
                <li class="sidebar-header">Transaksi</li>

                @can('penjualan-list')
                    @if(Route::has('transaksi-penjualan.index'))
                        <li class="sidebar-item {{ request()->routeIs('transaksi-penjualan.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('transaksi-penjualan.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-shopping-cart"></i></span>
                                <span>Penjualan</span>
                            </a>
                        </li>
                    @endif
                @endcan

                @can('pembelian-list')
                    @if(Route::has('transaksi-pembelian.index'))
                        <li class="sidebar-item {{ request()->routeIs('transaksi-pembelian.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('transaksi-pembelian.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-truck"></i></span>
                                <span>Pembelian</span>
                            </a>
                        </li>
                    @endif
                @endcan

                @can('pengeluaran-list')
                    @if(Route::has('pengeluaran.index'))
                        <li class="sidebar-item {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('pengeluaran.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-credit-card"></i></span>
                                <span>Pengeluaran</span>
                            </a>
                        </li>
                    @endif
                    @if(Route::has('kas-masuk.index'))
                        <li class="sidebar-item {{ request()->routeIs('kas-masuk.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('kas-masuk.index') }}">
                                <span class="sidebar-link-icon"><i class="fas fa-hand-holding-dollar"></i></span>
                                <span>Kas Masuk & Modal</span>
                            </a>
                        </li>
                    @endif
                @endcan
            @endcanany

            @can('laporan-view')
                @if(Route::has('laporan.keuangan'))
                    <li class="sidebar-header">Laporan</li>
                    <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('laporan.keuangan') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-chart-line"></i></span>
                            <span>Keuangan</span>
                        </a>
                    </li>
                @endif
            @endcan

            @canany(['user-list', 'role-list', 'permission-list'])
                <li class="sidebar-header">Pengaturan Akses</li>

                @can('user-list')
                    <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('users.index') }}">
                            <span class="sidebar-link-icon"><i class="fas fa-users"></i></span>
                            <span>User</span>
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