<?php

namespace App\Livewire\Web;

use App\Domains\Appointment\Actions\CreateAppointmentAction;
use App\Domains\Appointment\DTOs\AppointmentBookingData;
use App\Domains\Appointment\Services\ScheduleValidationService;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical', ['title' => 'Janji Temu Online'])]
class AppointmentBooking extends Component
{
    public ?int $department_id = null;

    public ?int $doctor_id = null;

    public string $appointment_date = '';

    public string $patient_name = '';

    public string $patient_phone = '';

    public string $patient_dob = '';

    public string $patient_gender = '';

    public ?string $mr_number = null;

    public string $complaint = '';

    public ?string $bookingCode = null;

    /** @var array<string, mixed>|null */
    public ?array $bookingSummary = null;

    public function mount(): void
    {
        $doctorId = request()->integer('dokter');

        if ($doctorId && ($doctor = Doctor::find($doctorId))) {
            $this->department_id = $doctor->department_id;
            $this->doctor_id = $doctor->id;
        }
    }

    #[Computed]
    public function departments()
    {
        return Department::query()->where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function doctors()
    {
        if (! $this->department_id) {
            return collect();
        }

        return Doctor::query()
            ->where('department_id', $this->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedDoctor(): ?Doctor
    {
        return $this->doctor_id ? Doctor::query()->with('schedules')->find($this->doctor_id) : null;
    }

    #[Computed]
    public function availableSlots()
    {
        if (! $this->selectedDoctor || blank($this->appointment_date)) {
            return collect();
        }

        try {
            return app(ScheduleValidationService::class)->availableSlots($this->selectedDoctor, $this->appointment_date);
        } catch (\Throwable) {
            return collect();
        }
    }

    public function updatedDepartmentId(): void
    {
        $this->doctor_id = null;
        $this->appointment_date = '';
    }

    public function updatedDoctorId(): void
    {
        $this->appointment_date = '';
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'patient_name' => ['required', 'string', 'min:3', 'max:150'],
            'patient_phone' => ['required', 'regex:/^(\+62|62|08)[0-9]{7,13}$/'],
            'patient_dob' => ['required', 'date', 'before:today'],
            'patient_gender' => ['required', 'in:L,P'],
            'mr_number' => ['nullable', 'string', 'max:50'],
            'complaint' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'department_id.required' => 'Silakan pilih poli.',
            'doctor_id.required' => 'Silakan pilih dokter.',
            'appointment_date.required' => 'Silakan pilih tanggal kunjungan.',
            'appointment_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'patient_name.required' => 'Nama lengkap wajib diisi.',
            'patient_name.min' => 'Nama minimal 3 karakter.',
            'patient_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'patient_phone.regex' => 'Format WhatsApp harus diawali 08, 62, atau +62 diikuti 7-13 digit angka.',
            'patient_dob.required' => 'Tanggal lahir wajib diisi.',
            'patient_dob.before' => 'Tanggal lahir harus sebelum hari ini.',
            'patient_gender.required' => 'Silakan pilih jenis kelamin.',
        ];
    }

    public function book(CreateAppointmentAction $action): void
    {
        $validated = $this->validate();

        $appointment = $action->execute(new AppointmentBookingData(
            doctorId: (int) $validated['doctor_id'],
            patientName: $validated['patient_name'],
            patientPhone: $validated['patient_phone'],
            patientDob: $validated['patient_dob'],
            patientGender: $validated['patient_gender'],
            mrNumber: $validated['mr_number'] ?: null,
            appointmentDate: $validated['appointment_date'],
            complaint: $validated['complaint'] ?: null,
        ));

        $appointment->load('doctor.department');

        $this->bookingCode = $appointment->booking_code;
        $this->bookingSummary = [
            'kode' => $appointment->booking_code,
            'nama' => $appointment->patient_name,
            'telepon' => $appointment->patient_phone,
            'dokter' => $appointment->doctor->name,
            'poli' => $appointment->doctor->department->name,
            'tanggal' => $appointment->appointment_date->translatedFormat('l, d F Y'),
        ];

        $this->reset(['patient_name', 'patient_phone', 'patient_dob', 'patient_gender', 'mr_number', 'complaint']);
    }

    public function bookAnother(): void
    {
        $this->reset([
            'department_id', 'doctor_id', 'appointment_date',
            'patient_name', 'patient_phone', 'patient_dob', 'patient_gender', 'mr_number', 'complaint',
            'bookingCode', 'bookingSummary',
        ]);
    }

    public function render()
    {
        return view('livewire.web.appointment-booking', [
            'whatsappAdmin' => preg_replace('/\D/', '', (string) SiteSetting::get('whatsapp', '6281234567890')),
        ]);
    }
}
