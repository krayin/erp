<?php

namespace Webkul\Chatter\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Models\ActivityLog;

trait HasLogActivity
{
    public static function bootHasLogActivity()
    {
        static::created(fn(Model $model) => $model->logModelActivity('created'));

        static::updated(fn(Model $model) => $model->logModelActivity('updated'));

        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::deleted(function (Model $model) {
                if (method_exists($model, 'trashed') && $model->trashed()) {
                    $model->logModelActivity('soft_deleted');
                } else {
                    $model->logModelActivity('hard_deleted');
                }
            });
        } else {
            static::deleting(fn(Model $model) => $model->logModelActivity('deleted'));
        }

        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::restored(fn(Model $model) => $model->logModelActivity('restored'));
        }
    }

    public function logModelActivity(string $event)
    {
        $changes = $this->determineChanges($event);

        return $this->createActivityLog([
            'event' => $event,
            'changes' => $changes
        ]);
    }

    protected function determineChanges(string $event)
    {
        switch ($event) {
            case 'created':
                return $this->getModelAttributes();

            case 'updated':
                return $this->getUpdatedAttributes();

            default:
                return null;
        }
    }

    protected function createActivityLog(array $attributes)
    {
        try {
            return $this->activityLogs()->create([
                ...$attributes,
                'causer_id'   => Auth::id(),
                'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity Log Creation Failed: ' . $e->getMessage());

            return;
        }
    }

    protected function getModelAttributes()
    {
        return collect($this->getAttributes())
            ->except($this->getExcludedAttributes())
            ->toArray();
    }

    protected function getUpdatedAttributes()
    {
        $original = $this->getOriginal();
        $current = $this->getAttributes();

        $changes = [];

        foreach ($current as $key => $value) {
            if (in_array($key, $this->getExcludedAttributes())) {
                continue;
            }

            if (
                ! array_key_exists($key, $original)
                || $original[$key] !== $value
            ) {
                $changes[$key] = [
                    'old' => $original[$key] ?? null,
                    'new' => $value
                ];
            }
        }

        return $changes;
    }

    protected function getExcludedAttributes(): array
    {
        return [
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}
