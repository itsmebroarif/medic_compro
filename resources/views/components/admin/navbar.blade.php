{{-- Top navbar admin (AdminLTE v4) --}}
@php
    use App\Domains\Content\Models\SiteSetting;
    $hospitalName = SiteSetting::get('hospital_name', config('app.name'));
    $role = auth()->user()?->getRoleNames()->first() ?? 'user';
@endphp
<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar" title="Buka/Tutup sidebar">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="nav-link">
                    <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> Lihat Situs
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-lg-flex align-items-center me-2">
                <span class="badge text-bg-light border">{{ $hospitalName }}</span>
            </li>
            <li class="nav-item dropdown user-menu">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <span class="user-menu__avatar"><i class="bi bi-person-circle fs-5"></i></span>
                    <span class="d-none d-md-inline">{{ auth()->user()?->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-secondary">{{ strtoupper(str_replace('-', ' ', $role)) }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
