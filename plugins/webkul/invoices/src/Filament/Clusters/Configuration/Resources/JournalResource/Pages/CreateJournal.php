<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\JournalResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\JournalResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Models\Journal;

class CreateJournal extends CreateRecord
{
    protected static string $resource = JournalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sort'] = Journal::max('sort') + 1;

        $data['creator_id'] = Auth::user()->id;

        return $data;
    }
}
