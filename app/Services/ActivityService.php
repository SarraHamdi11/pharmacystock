<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    public static function log(string $action, string $description, ?Model $model = null, array $metadata = []): void
    {
        Activity::create([
            'user_id' => Auth::id() ?? 1, // Fallback to first user if no auth (e.g. CLI)
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->getKey() : null,
            'metadata' => $metadata
        ]);
    }
}
