{{-- resources/views/vendor/chatter/filament/components/file-list.blade.php --}}
<div class="space-y-3">
    @forelse($attachments as $attachment)
        @php
            $isImage = str_starts_with($attachment->mime_type, 'image/');
            $isPdf = str_contains($attachment->mime_type, 'pdf');
            $isDoc = str_contains($attachment->mime_type, 'word') || str_contains($attachment->mime_type, 'document');
            $isSpreadsheet = str_contains($attachment->mime_type, 'sheet') || str_contains($attachment->mime_type, 'excel');

            $iconColor = match(true) {
                $isImage => 'text-warning-500',
                $isPdf => 'text-danger-500',
                $isDoc => 'text-primary-500',
                $isSpreadsheet => 'text-success-500',
                default => 'text-gray-500'
            };

            $icon = match(true) {
                $isImage => 'heroicon-o-photo',
                $isPdf => 'heroicon-o-document',
                $isDoc => 'heroicon-o-document-text',
                $isSpreadsheet => 'heroicon-o-table-cells',
                default => 'heroicon-o-paper-clip'
            };
        @endphp

        <div class="group relative rounded-lg bg-gray-50 p-3 transition duration-150 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex items-center gap-x-4">
                {{-- File Preview/Icon --}}
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                    @if($isImage && $attachment->url)
                        <div class="relative h-10 w-10 overflow-hidden rounded">
                            <img
                                src="{{ $attachment->url }}"
                                alt="{{ $attachment->original_file_name }}"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    @else
                        <x-filament::icon
                            :icon="$icon"
                            @class([$iconColor, 'w-6 h-6'])
                        />
                    @endif
                </div>

                {{-- File Info --}}
                <div class="min-w-0 flex-grow">
                    <div class="flex items-center justify-between gap-x-2">
                        <div class="truncate">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                {{ $attachment->original_file_name }}
                            </p>
                            <div class="mt-0.5 flex items-center gap-x-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($attachment->file_size / 1024, 1) }} KB
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $attachment->created_at->diffForHumans() }}
                                </span>
                                @if($attachment->creator_id)
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $attachment->createdBy->name ?? 'Unknown' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-shrink-0 items-center gap-x-2">
                    @if($attachment->url)
                        @if($isImage || $isPdf)
                            <x-filament::button
                                size="sm"
                                color="gray"
                                icon="heroicon-m-eye"
                                icon-only
                                tag="a"
                                :href="Storage::url($attachment->file_path)"
                                target="_blank"
                                :tooltip="__('Preview')"
                            />
                        @endif

                        {{-- Download Button --}}
                        <x-filament::button
                            size="sm"
                            color="gray"
                            icon="heroicon-m-arrow-down-tray"
                            icon-only
                            tag="a"
                            :href="Storage::url($attachment->file_path)"
                            download="{{ $attachment->original_file_name }}"
                            :tooltip="__('Download')"
                        />
                    @else
                        <x-filament::badge color="danger" size="sm">
                            {{ __('File not found') }}
                        </x-filament::badge>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ __('No files uploaded yet') }}
        </div>
    @endforelse
</div>
