<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;

class EventStatusService
{
    /**
     * Calculate the automatic status based on event dates
     * 
     * @param Event $event
     * @return string
     */
    public static function calculateStatus(Event $event): string
    {
        $now = Carbon::now();
        $startAt = Carbon::parse($event->start_at);
        $endAt = $event->end_at ? Carbon::parse($event->end_at) : null;

        // If no end date, use start date + 1 day as default
        if (!$endAt) {
            $endAt = $startAt->copy()->addDay();
        }

        // Event has ended
        if ($now->greaterThan($endAt)) {
            return 'completed';
        }

        // Event is currently running
        if ($now->greaterThanOrEqualTo($startAt) && $now->lessThanOrEqualTo($endAt)) {
            return 'active';
        }

        // Event hasn't started yet - return the current status (planning/upcoming)
        return $event->status ?? 'planning';
    }

    /**
     * Update event status automatically based on current date
     * 
     * @param Event $event
     * @return Event
     */
    public static function updateStatus(Event $event): Event
    {
        $calculatedStatus = self::calculateStatus($event);
        
        // Only update if status has changed
        if ($event->status !== $calculatedStatus) {
            $event->update(['status' => $calculatedStatus]);
        }

        return $event->fresh();
    }

    /**
     * Update all events status automatically
     * 
     * @return int Number of events updated
     */
    public static function updateAllStatuses(): int
    {
        $events = Event::all();
        $updated = 0;

        foreach ($events as $event) {
            $originalStatus = $event->status;
            $newStatus = self::calculateStatus($event);
            
            if ($originalStatus !== $newStatus) {
                $event->update(['status' => $newStatus]);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Get events visible to customers based on their planning status
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCustomerVisibleEvents()
    {
        return Event::whereIn('status', ['active', 'upcoming'])
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere(function ($subQuery) {
                        // Show upcoming events but not planning events
                        $subQuery->where('status', 'upcoming');
                    });
            })
            ->orderBy('start_at')
            ->get();
    }

    /**
     * Get events for admin display (includes completed events for management)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAdminEvents()
    {
        return Event::orderBy('start_at')->get();
    }

    /**
     * Check if event should be visible to customers
     * 
     * @param Event $event
     * @return bool
     */
    public static function isVisibleToCustomers(Event $event): bool
    {
        return in_array($event->status, ['active', 'upcoming']);
    }

    /**
     * Get upcoming events (visible to customers but not active yet)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUpcomingEvents()
    {
        return Event::where('status', 'upcoming')
            ->orderBy('start_at')
            ->get();
    }

    /**
     * Get active events (currently running)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveEvents()
    {
        return Event::where('status', 'active')
            ->orderBy('start_at')
            ->get();
    }

    /**
     * Get completed events (for admin management only)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCompletedEvents()
    {
        return Event::where('status', 'completed')
            ->orderBy('start_at', 'desc')
            ->get();
    }
}