<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
            target="_blank">
            <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Zaiko Track</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link  {{ Request::routeIs('Home') ? 'active' : '' }}" href="{{route('Home')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('user.index') ? 'active' : '' }} "
                    href="{{route('user.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('barang.index') ? 'active' : '' }} "
                    href="{{route('barang.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-app text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1"> Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('pembelian.index') ? 'active' : '' }} "
                    href="{{route('pembelian.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-cart text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pembelian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('inventaris.index') ? 'active' : '' }} "
                    href="{{route('inventaris.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-box-2            text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inventaris</span>
                </a>
            </li>
            <li class="nav-item">

                <a class="nav-link {{ Request::routeIs('peminjaman.index') ? 'active' : '' }} "
                    href="{{route('peminjaman.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-people-carry-box text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Peminjaman</span>

                    <a class="nav-link {{ Request::routeIs('pemakaian.index') ? 'active' : '' }} "
                        href="{{route('pemakaian.index')}}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-hands"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pemakaian</span>

                    </a>
            </li>
            @can('isTeknisi')
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Master Data</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('ruangan.index') ? 'active' : '' }} "
                    href="{{route('ruangan.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Ruangan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('jenisbarang.index') ? 'active' : '' }} "
                    href="{{route('jenisbarang.index')}}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-folder-17 text-sm opacity-10" style="color: rgb(219, 149, 99)"></i>
                    </div>
                    <span class="nav-link-text ms-1">Jenis Barang</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</aside>