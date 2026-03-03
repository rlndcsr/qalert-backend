<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\ReasonCategory;
use App\Models\Schedule;
use Carbon\Carbon;

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
        'session_start_at',
        'session_end_at',
        'session_duration_minutes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'session_start_at' => 'datetime',
        'session_end_at'   => 'datetime',
        'session_duration_minutes' => 'integer',
    ];

    /**
     * Handle session tracking when queue status changes.
     *
     * Call this method before saving when queue_status is updated.
     */
    public function trackSession(): void
    {
        if ($this->isDirty('queue_status')) {
            $newStatus = $this->queue_status;

            if ($newStatus === 'now_serving') {
                $this->session_start_at = Carbon::now();
                $this->session_end_at = null;
                $this->session_duration_minutes = null;
            }

            if ($newStatus === 'completed' && $this->session_start_at) {
                $this->session_end_at = Carbon::now();
                $this->session_duration_minutes = (int) $this->session_start_at->diffInMinutes($this->session_end_at);
            }
        }
    }

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
