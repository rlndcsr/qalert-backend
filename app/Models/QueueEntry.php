<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\ReasonCategory;
use App\Models\Schedule;

class QueueEntry extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'queue_entries';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'queue_entry_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'queue_number',
        'reason',
        'reason_category_id',
        'schedule_id',
        'appointment_id',
        'queue_status',
        'estimated_time_wait',
        'date',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function reasonCategory()
    {
        return $this->belongsTo(ReasonCategory::class, 'reason_category_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }
}
