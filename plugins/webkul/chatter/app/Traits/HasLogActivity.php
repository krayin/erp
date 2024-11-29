<?php

namespace Webkul\Chatter\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Webkul\Security\Models\User;

trait HasLogActivity
{
    public static function bootHasLogActivity()
    {
        static::created(fn (Model $model) => $model->logModelActivity('created'));

        static::updated(fn (Model $model) => $model->logModelActivity('updated'));

        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::deleted(function (Model $model) {
                if (method_exists($model, 'trashed') && $model->trashed()) {
                    $model->logModelActivity('soft_deleted');
                } else {
                    $model->logModelActivity('hard_deleted');
                }
            });

            static::restored(fn (Model $model) => $model->logModelActivity('restored'));
        } else {
            static::deleting(fn (Model $model) => $model->logModelActivity('deleted'));
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
                'changes'       => $this->determineChanges($event),
            ]);
        } catch (\Exception $e) {
            Log::error('Activity Log Creation Failed: '.$e->getMessage());

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
            ->map(fn ($value, $key) => $this->formatAttributeValue($key, $value))
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

                $newValue = static::decodeValueIfJson($value);

                $oldValue = static::decodeValueIfJson($original[$key] ?? null);

                $changes[$key] = [
                    'type'      => array_key_exists($key, $original) ? 'modified' : 'added',
                    'old_value' => $this->formatAttributeValue($key, $oldValue),
                    'new_value' => $this->formatAttributeValue($key, $newValue),
                ];
            }
        }

        return $changes;
    }

    protected function formatAttributeValue(string $key, $value): mixed
    {
        $userFields = [
            'created_by',
            'assigned_to',
            'user_id',
        ];

        if (
            in_array($key, $userFields)
            && $value !== null
        ) {
            try {
                $user = User::find($value);

                return $user ? $user->name : 'Unassigned';
            } catch (\Exception $e) {
                Log::error("Failed to fetch user for field {$key}: ".$e->getMessage());

                return $value;
            }
        }

        if (in_array($key, ['due_date', 'created_at', 'updated_at'])) {
            return $value ? \Carbon\Carbon::parse($value)->format('F j, Y') : null;
        }

        return $value;
    }

    protected function getExcludedAttributes(): array
    {
        return [
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    protected static function decodeValueIfJson($value)
    {
        if (
            ! is_array($value)
            && json_decode($value, true)
        ) {
            $value = json_decode($value, true);
        }

        if (! is_array($value)) {
            return $value;
        }

        static::ksortRecursive($value);

        return $value;
    }

    protected static function ksortRecursive(&$array)
    {
        if (! is_array($array)) {
            return;
        }

        ksort($array);

        foreach ($array as &$value) {
            if (! is_array($value)) {
                continue;
            }

            static::ksortRecursive($value);
        }
    }
}
