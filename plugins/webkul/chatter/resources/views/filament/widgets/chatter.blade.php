<div class="flex w-full">
    <livewire:chatter-panel
        :record="$record ?? $this->record"
        :activityPlans="$activityPlans ?? $this->activityPlans"
        lazy
    />
</div>
