<div>
    @if ($flashMessage)
        <div class="alert alert-{{ $flashType }} alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Jadwal Praktik Mingguan</h3></div>
        <div class="card-body">
            <div class="mb-3" style="max-width: 480px;">
                <label class="form-label" for="sm-doctor">Pilih Dokter *</label>
                <select id="sm-doctor" class="form-select" wire:model.live="doctor_id">
                    <option value="">-- Pilih Dokter --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }} — {{ $doctor->department?->name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($doctor_id)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 160px;">Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Kuota (opsional)</th>
                                <th>Catatan</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $index => $row)
                                <tr wire:key="row-{{ $index }}">
                                    <td>
                                        <select class="form-select form-select-sm" wire:model="rows.{{ $index }}.day_of_week">
                                            @foreach ($days as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="time" class="form-control form-control-sm" wire:model="rows.{{ $index }}.start_time"></td>
                                    <td><input type="time" class="form-control form-control-sm" wire:model="rows.{{ $index }}.end_time"></td>
                                    <td><input type="number" min="0" class="form-control form-control-sm" wire:model="rows.{{ $index }}.quota" placeholder="&infin;"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="rows.{{ $index }}.notes" placeholder="Misal: khusus BPJS"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeRow({{ $index }})" aria-label="Hapus baris"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-outline-secondary" wire:click="addRow"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                    <button type="button" class="btn btn-primary" wire:click="save"><i class="bi bi-save"></i> Simpan Jadwal</button>
                </div>
                <p class="text-muted small mt-2 mb-0">Sistem menolak jadwal yang bentrok pada hari yang sama untuk dokter yang sama.</p>
            @else
                <div class="alert alert-info mb-0">Pilih dokter untuk mengelola jadwal praktiknya.</div>
            @endif
        </div>
    </div>
</div>
