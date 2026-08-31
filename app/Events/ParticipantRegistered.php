<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventRegistration;

class ParticipantRegistered implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $registration->loadMissing(['user', 'event', 'answers.formField', 'attendances']);

        // SECURITY: Sanitized non-sensitive payload only
        $this->payload = [
            'id' => $registration->id,
            'user_id' => $registration->user_id,
            'participant_name' => $registration->user->name ?? 'Peserta Baru',
            'nip_nik' => $registration->user->nip_nik ?? '-',
            'instansi' => $registration->user->instansi ?? 'Umum',
            'jabatan' => $registration->user->jabatan ?? 'Peserta BIMTEK',
            'bimtek_id' => $registration->bimtek_event_id,
            'bimtek_name' => $registration->event->title ?? 'Kegiatan BIMTEK',
            'registration_code' => $registration->registration_code,
            'registration_status' => strtoupper($registration->status ?? 'APPROVED'),
            'registered_at' => now()->format('H:i') . ' WIB',
            'attendances_count' => $registration->attendances->count() ?: 1,
            'answers' => $registration->answers->map(function ($ans) {
                return [
                    'id' => $ans->id,
                    'label' => $ans->formField->field_label ?? 'Field',
                    'value' => $ans->answer_value,
                ];
            })->values()->all(),
            'timestamp' => now()->toIso8601String(),
        ];

        // Also record to the RealtimeStreamService buffer
        \App\Services\RealtimeStreamService::pushEvent('ParticipantRegistered', $this->payload);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.participants'),
            new PrivateChannel('admin.bimtek.' . $this->payload['bimtek_id'] . '.participants'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ParticipantRegistered';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
