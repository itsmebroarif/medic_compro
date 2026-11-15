<?php

namespace App\Domains\MasterData\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'category', 'unit', 'stock', 'min_stock', 'price', 'description', 'is_active'])]
class Medicine extends Model
{
    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
