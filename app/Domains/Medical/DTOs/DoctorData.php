<?php

namespace App\Domains\Medical\DTOs;

readonly class DoctorData
{
    public function __construct(
        public string $name,
        public int $departmentId,
        public ?string $sipNumber,
        public ?string $bio,
        public bool $isActive,
        public ?string $slug = null,
    ) {}

    /**
     * @return array{name: string, department_id: int, sip_number: ?string, bio: ?string, is_active: bool, slug: ?string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'department_id' => $this->departmentId,
            'sip_number' => $this->sipNumber,
            'bio' => $this->bio,
            'is_active' => $this->isActive,
            'slug' => $this->slug,
        ];
    }
}
