{{-- Header konten (judul + breadcrumb) --}}
@props(['title' => 'Dashboard'])

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ $title }}</h3>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="float-sm-end">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
