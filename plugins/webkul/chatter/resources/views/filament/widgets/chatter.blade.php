<div class="flex w-full">
    <livewire:chatter-panel
        :record="$record ?? $this->record"
        :activityPlans="$activityPlans ?? $this->activityPlans"
        :modelName="$modelName ?? $this->modelName"
        lazy
    />
</div>
