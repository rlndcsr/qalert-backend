<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QueueEntry;
use App\Models\ReasonCategory;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointments';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'appointment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'schedule_id',
        'appointment_date',
        'appointment_time',
        'reason_category_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'datetime:H:i',
        ];
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function reasonCategory()
    {
        return $this->belongsTo(ReasonCategory::class, 'reason_category_id', 'reason_category_id');
    }

    public function queueEntry()
    {
        return $this->hasOne(QueueEntry::class, 'appointment_id', 'appointment_id');
    }
}
