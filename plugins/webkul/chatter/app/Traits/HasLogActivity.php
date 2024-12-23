<?php

namespace Webkul\Chatter\Traits;

use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

trait HasLogActivity
{
    protected array $oldAttributes = [];

    protected static function bootHasLogActivity(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            if ($eventName === 'updated') {
                static::updating(function (Model $model) {
                    $oldValues = (new static())->setRawAttributes($model->getRawOriginal());
                    $model->oldAttributes = static::logChanges($oldValues);
                });
            }

            static::$eventName(function (Model $model) use ($eventName) {
                if (!$model->shouldLogEvent($eventName)) {
                    return;
                }

                $changes = $model->attributeValuesToBeLogged($eventName);

                if ($model->isLogEmpty($changes)) {
                    return;
                }

                $model->recordActivity($eventName, $changes);
            });
        });
    }

    protected static function eventsToBeRecorded(): Collection
    {
        $events = collect([
            'created',
            'updated',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }

        return $events;
    }

    protected function shouldLogEvent(string $eventName): bool
    {
        if (!Auth::check()) {
            return false;
        }

        if (!in_array($eventName, ['created', 'updated'])) {
            return true;
        }

        if ($this->isRestoring()) {
            return false;
        }

        return (bool) count(Arr::except($this->getDirty(), $this->getExcludedAttributes()));
    }

    protected function isRestoring(): bool
    {
        $deletedAtColumn = method_exists($this, 'getDeletedAtColumn')
            ? $this->getDeletedAtColumn()
            : 'deleted_at';

        return $this->isDirty($deletedAtColumn) && count($this->getDirty()) === 1;
    }

    public function isLogEmpty(array $changes): bool
    {
        return empty($changes['attributes'] ?? []) && empty($changes['old'] ?? []);
    }

    protected function attributeValuesToBeLogged(string $eventName): array
    {
        $properties['new'] = static::logChanges(
            $eventName == 'retrieved'
                ? $this
                : ($this->exists ? $this->fresh() ?? $this : $this)
        );

        if ($eventName === 'updated') {
            $properties['old'] = $this->oldAttributes;
            $this->oldAttributes = [];

            $changedProperties = array_udiff_assoc(
                $properties['new'],
                $properties['old'],
                function ($new, $old) {
                    if ($old === null || $new === null) {
                        return $new === $old ? 0 : 1;
                    }

                    if ($old instanceof DateInterval) {
                        return CarbonInterval::make($old)->equalTo($new) ? 0 : 1;
                    } elseif ($new instanceof DateInterval) {
                        return CarbonInterval::make($new)->equalTo($old) ? 0 : 1;
                    }

                    return $new <=> $old;
                }
            );

            $properties['new'] = $changedProperties;
            $properties['old'] = array_intersect_key($properties['old'], $changedProperties);
        }

        if ($eventName === 'deleted') {
            $properties['old'] = $properties['new'];
            unset($properties['new']);
        }

        return $properties;
    }

    public static function logChanges(Model $model): array
    {
        $changes = [];
        $attributes = $model->getAttributes();

        foreach ($attributes as $attribute => $value) {
            if (in_array($attribute, $model->getExcludedAttributes())) {
                continue;
            }

            if (method_exists($model, Str::camel(str_replace('_id', '', $attribute)))) {
                $relation = Str::camel(str_replace('_id', '', $attribute));
                if (method_exists($model, $relation)) {
                    $relatedModel = $model->$relation()->getRelated();
                    if ($value && $related = $relatedModel->find($value)) {
                        $changes[$attribute] = [
                            'id' => $value,
                            'label' => $related->name ?? $related->title ?? $value
                        ];
                        continue;
                    }
                }
            }

            // Handle JSON attributes
            if (Str::contains($attribute, '->')) {
                Arr::set(
                    $changes,
                    str_replace('->', '.', $attribute),
                    static::getModelAttributeJsonValue($model, $attribute)
                );
                continue;
            }

            // Handle date attributes
            if ($model->isDateAttribute($attribute)) {
                $changes[$attribute] = $model->serializeDate(
                    $model->asDateTime($value)
                );
                continue;
            }

            // Handle enum attributes
            if ($model->hasCast($attribute)) {
                $cast = $model->getCasts()[$attribute];
                if ($model->isEnumCastable($attribute)) {
                    try {
                        $changes[$attribute] = $model->getStorableEnumValue($value);
                    } catch (\ArgumentCountError $e) {
                        $changes[$attribute] = $model->getStorableEnumValue($cast, $value);
                    }
                    continue;
                }
            }

            $changes[$attribute] = $value;
        }

        return $changes;
    }

    protected static function getModelAttributeJsonValue(Model $model, string $attribute): mixed
    {
        $path = explode('->', $attribute);
        $modelAttribute = array_shift($path);
        $modelAttribute = collect($model->getAttribute($modelAttribute));

        return data_get($modelAttribute, implode('.', $path));
    }

    protected function getActivityDescription(string $event): string
    {
        $modelName = Str::headline(class_basename($this));

        return match ($event) {
            'created' => "{$modelName} was created",
            'updated' => "{$modelName} was updated",
            'deleted' => "{$modelName} was deleted",
            'soft_deleted' => "{$modelName} was archived",
            'restored' => "{$modelName} was restored",
            default => $event
        };
    }

    protected function getExcludedAttributes(): array
    {
        return [
            'password',
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    protected function recordActivity(string $event, array $changes = []): ?Model
    {
        try {
            return $this->addMessage([
                'notification' => 'activity',
                'log_name' => 'default',
                'description' => $this->getActivityDescription($event),
                'subject_type' => $this->getMorphClass(),
                'subject_id' => $this->getKey(),
                'causer_type' => Auth::user()?->getMorphClass(),
                'causer_id' => Auth::id(),
                'properties' => [
                    'event' => $event,
                    'user_id' => Auth::id(),
                    'changes' => $changes,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to record activity: ' . $e->getMessage());
            return null;
        }
    }
}
