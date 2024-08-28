<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'mileage',
        'fuel_cost',
        'driving_hours',
        'total_income',
        'cash_income',
        'nequi_income'
    ];

    // Definir que created_at y updated_at son instancias de Carbon
    protected array $dates = ['created_at', 'updated_at'];

    // Cálculo automático del valor por hora antes de guardar
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($record) {
            // Asegurarse de que el cálculo de hourly_rate se realiza si las horas conducidas no son 0
            if ($record->driving_hours > 0) {
                $record->hourly_rate = $record->total_income / $record->driving_hours;
            } else {
                $record->hourly_rate = 0;
            }
        });
    }
}
