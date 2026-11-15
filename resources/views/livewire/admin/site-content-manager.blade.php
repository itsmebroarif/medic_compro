<div>
    @if ($flashMessage)
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle me-2"></i>Semua konten teks &amp; kartu pada halaman website dapat diubah di sini. Data dinamis (daftar poli, dokter, artikel, jadwal, antrean) dikelola melalui menu <strong>Master Data</strong> dan <strong>Artikel/Berita</strong>.
    </div>

    <div class="row g-4">
        @foreach ($sections as $group => $section)
            <div class="col-12">
                <form wire:submit="save('{{ $group }}')" class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="bi bi-layout-text-window-reverse me-2"></i>{{ $section['label'] }}</h3>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Simpan {{ $section['label'] }}</button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($section['keys'] as $key => $field)
                                <div class="{{ $field['type'] === 'textarea' ? 'col-12' : 'col-md-6' }}">
                                    <label class="form-label" for="c-{{ $key }}">{{ $field['label'] }}</label>
                                    @if ($field['type'] === 'textarea')
                                        <textarea id="c-{{ $key }}" class="form-control" rows="3" wire:model="content.{{ $key }}"></textarea>
                                    @elseif ($field['type'] === 'icon')
                                        <select id="c-{{ $key }}" class="form-select" wire:model="content.{{ $key }}">
                                            @foreach ($iconOptions as $class => $label)
                                                <option value="{{ $class }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2"><i class="{{ $content[$key] ?? '' }} fs-4 text-primary"></i></div>
                                    @else
                                        <input id="c-{{ $key }}" type="text" class="form-control" wire:model="content.{{ $key }}">
                                    @endif
                                    @error("content.{$key}") <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
