<?php

namespace App\Traits;

use App\Models\CardLayout;

trait HasCardLayout
{
    /**
     * Get the active card layout for this model's event
     */
    public function getActiveCardLayout()
    {
        if (!isset($this->event_id)) {
            return null;
        }

        return CardLayout::where('event_id', $this->event_id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get layout JSON for rendering
     * Prioritizes card's layout_id, then event's active layout, then default
     */
    public function getLayoutForRender()
    {
        // If card has layout snapshot, use it
        if (isset($this->layout_id) && $this->layout_id) {
            $layout = CardLayout::find($this->layout_id);
            if ($layout) {
                return $layout->layout_json;
            }
        }

        // Otherwise use event's active layout
        $activeLayout = $this->getActiveCardLayout();
        if ($activeLayout) {
            return $activeLayout->layout_json;
        }

        // Fallback to default
        return CardLayout::getDefaultLayout();
    }
}
