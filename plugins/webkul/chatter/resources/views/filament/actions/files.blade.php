{{-- resources/views/vendor/chatter/filament/components/file-list.blade.php --}}
<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
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

        <div class="group relative rounded-lg bg-gray-50 p-2 transition duration-150 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex flex-col gap-y-2">
                {{-- File Preview/Icon --}}
                <div class="flex justify-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white dark:bg-gray-700">
                        @if($isImage && $attachment->url)
                            <div class="relative h-10 w-10 overflow-hidden rounded-lg">
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
                </div>

                {{-- File Info --}}
                <div class="flex-grow text-center">
                    <p class="truncate text-xs font-medium text-gray-900 dark:text-white" title="{{ $attachment->original_file_name }}">
                        {{ $attachment->original_file_name }}
                    </p>
                    <div class="mt-0.5 flex items-center justify-center gap-x-1 text-[10px] text-gray-500 dark:text-gray-400">
                        <span>{{ number_format($attachment->file_size / 1024, 1) }} KB</span>
                        <span>•</span>
                        <span>{{ $attachment->created_at->diffForHumans(parts: 1) }}</span>
                    </div>
                    @if($attachment->creator_id)
                        <div class="mt-0.5 truncate text-[10px] text-gray-500 dark:text-gray-400">
                            {{ $attachment->createdBy->name ?? 'Unknown' }}
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex justify-center gap-x-1">
                    @if($attachment->url)
                        @if($isImage || $isPdf)
                            <x-filament::button
                                size="xs"
                                color="gray"
                                icon="heroicon-m-eye"
                                @style([
                                    'gap' => '0 !important',
                                ])
                                icon-only
                                tag="a"
                                :href="Storage::url($attachment->file_path)"
                                target="_blank"
                                :tooltip="__('Preview')"
                            />
                        @endif

                        <x-filament::button
                            size="xs"
                            color="gray"
                            icon="heroicon-m-arrow-down-tray"
                            class="!gap-0"
                            icon-only
                            tag="a"
                            :href="Storage::url($attachment->file_path)"
                            download="{{ $attachment->original_file_name }}"
                            :tooltip="__('Download')"
                            @style([
                                'gap' => '0 !important',
                            ])
                        />
                    @else
                        <x-filament::badge color="danger" size="xs">
                            {{ __('File not found') }}
                        </x-filament::badge>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ __('No files uploaded yet') }}
        </div>
    @endforelse
</div>
