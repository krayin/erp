<?php

namespace Webkul\Chatter\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

            static::restored(fn(Model $model) => $model->logModelActivity('restored'));
        } else {
            static::deleting(fn(Model $model) => $model->logModelActivity('deleted'));
        }
    }

    public function logModelActivity(string $event): ?Model
    {
        if (! Auth::check()) {
            return null;
        }

        try {
            return $this->chats()->create([
                'type'          => 'log',
                'activity_type' => $event,
                'user_id'       => Auth::id(),
                'content'       => $this->generateActivityDescription($event),
                'changes'       => $this->determineChanges($event)
            ]);
        } catch (\Exception $e) {
            Log::error('Activity Log Creation Failed: ' . $e->getMessage());

            return null;
        }
    }

    protected function determineChanges(string $event): ?array
    {
        return match ($event) {
            'created' => $this->getModelAttributes(),
            'updated' => $this->getUpdatedAttributes(),
            default   => null
        };
    }

    protected function generateActivityDescription(string $event): string
    {
        $modelName = Str::headline(class_basename(static::class));

        return match ($event) {
            'created'      => "A new {$modelName} was created",
            'updated'      => "The {$modelName} was updated",
            'deleted'      => "The {$modelName} was deleted",
            'soft_deleted' => "The {$modelName} was soft deleted",
            'hard_deleted' => "The {$modelName} was permanently deleted",
            'restored'     => "The {$modelName} was restored",
            default        => $event
        };
    }

    protected function getModelAttributes(): array
    {
        return collect($this->getAttributes())
            ->except($this->getExcludedAttributes())
            ->toArray();
    }

    protected function getUpdatedAttributes(): array
    {
        $original = $this->getOriginal();
        $current = $this->getDirty();
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
                    'type'      => array_key_exists($key, $original) ? 'modified' : 'added',
                    'old_value' => $original[$key] ?? null,
                    'new_value' => $value
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
            'deleted_at'
        ];
    }
}
