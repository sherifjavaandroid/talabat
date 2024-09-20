<?php

namespace App\Traits;

use Spatie\ModelStatus\HasStatuses;
use Spatie\ModelStatus\Events\StatusUpdated;
use Spatie\ModelStatus\Exceptions\InvalidStatus;

trait CustomHasStatuses
{
    use HasStatuses;

    public function setStatus(string $name, ?string $reason = null): self
    {
        if (!$this->isValidStatus($name, $reason)) {
            throw InvalidStatus::create($name);
        }
        return $this->forceSetStatus($name, $reason);
    }



    public function forceSetStatus(string $name, ?string $reason = null): self
    {
        $oldStatus = $this->latestStatus();
        //MAKE SURE STATUS IS ALLOWED TO CHANGE
        //prevent changing status after delivered,failed,cancelled
        $unAllowedStatuses = ["delivered", "completed", "failed", "fail", "cancelled", "cancel", "success", "successful"];
        $allowedAction = !in_array($oldStatus, $unAllowedStatuses);
        if (!$allowedAction) {
            return $this;
        }

        $newStatus = $this->statuses()->create([
            'name' => $name,
            'reason' => $reason,
        ]);

        event(new StatusUpdated($oldStatus, $newStatus, $this));

        return $this;
    }
}