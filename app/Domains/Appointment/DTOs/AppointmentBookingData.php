<?php

namespace App\Domains\Appointment\DTOs;

readonly class AppointmentBookingData
{
    public function __construct(
        public int $doctorId,
        public string $patientName,
        public string $patientPhone,
        public string $patientDob,
        public string $patientGender,
        public ?string $mrNumber,
        public string $appointmentDate,
        public ?string $complaint,
    ) {}

    /**
     * @return array{doctor_id: int, patient_name: string, patient_phone: string, patient_dob: string, patient_gender: string, mr_number: ?string, appointment_date: string, complaint: ?string}
     */
    public function toArray(): array
    {
        return [
            'doctor_id' => $this->doctorId,
            'patient_name' => $this->patientName,
            'patient_phone' => $this->patientPhone,
            'patient_dob' => $this->patientDob,
            'patient_gender' => $this->patientGender,
            'mr_number' => $this->mrNumber,
            'appointment_date' => $this->appointmentDate,
            'complaint' => $this->complaint,
        ];
    }
}
