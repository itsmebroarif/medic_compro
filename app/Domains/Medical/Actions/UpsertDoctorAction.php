<?php

namespace App\Domains\Medical\Actions;

use App\Core\Services\MediaUploadService;
use App\Domains\Medical\DTOs\DoctorData;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpsertDoctorAction
{
    public function __construct(
        private readonly MediaUploadService $media,
    ) {}

    public function execute(DoctorData $data, ?Doctor $doctor = null, ?UploadedFile $photo = null): Doctor
    {
        return DB::transaction(function () use ($data, $doctor, $photo): Doctor {
            $attributes = $data->toArray();

            if ($photo) {
                $this->media->delete($doctor?->photo);
                $attributes['photo'] = $this->media->storeAsWebpCropped($photo, 'doctors', 480, 600, quality: 82);
            }

            if ($doctor) {
                $doctor->update($attributes);

                return $doctor->refresh();
            }

            return Doctor::create($attributes);
        });
    }
}
