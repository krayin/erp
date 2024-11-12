<div class="bg-white rounded-lg shadow p-6">
    {{ $this->form }}
    
    <x-filament::tabs>
        <x-filament::tabs.item
            :active="$activeTab === 'send'"
            wire:click="$set('activeTab', 'send')"
        >
            Send
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'log'"
            wire:click="$set('activeTab', 'log')"
        >
            Log
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'activity'"
            wire:click="$set('activeTab', 'activity')"
        >
            Activity
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'file'"
            wire:click="$set('activeTab', 'file')"
        >
            File
        </x-filament::tabs.item>
    </x-filament::tabs>

    {{-- Send Form --}}
    @if($activeTab === 'send')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Message</label>

                <x-filament::input.wrapper>
                    <x-filament::input
                        type="text"
                        wire:model="message"
                    />
                </x-filament::input.wrapper>
                
                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Select Followers</label>

                <div class="mt-2 space-y-2">
                    @foreach($users as $user)
                        <label class="inline-flex items-center">
                            <input 
                                type="checkbox" 
                                wire:model="selectedFollowers" 
                                value="{{ $user->id }}"
                                class="rounded border-gray-300 text-blue-600"
                            >
                            <span class="ml-2">{{ $user->name }} ({{ $user->email }})</span>
                        </label>
                    @endforeach
                </div>

                @error('selectedFollowers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <x-filament::button type="submit" wire:click="send" class="mt-3">
                Send Message
            </x-filament::button>
        </div>
    @endif

    {{-- Log Form --}}
    @if($activeTab === 'log')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Log Entry</label>
                <textarea 
                    wire:model="logEntry" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    rows="4"
                ></textarea>
                @error('logEntry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button 
                wire:click="saveLog" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Save Log
            </button>
        </div>
    @endif

    {{-- Activity Form --}}
    @if($activeTab === 'activity')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Activity Details</label>
                <textarea 
                    wire:model="activity" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    rows="4"
                ></textarea>
                @error('activity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button 
                wire:click="saveActivity" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Record Activity
            </button>
        </div>
    @endif

    {{-- File Upload Form --}}
    @if($activeTab === 'file')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Files</label>
                <input 
                    type="file" 
                    wire:model="files" 
                    class="mt-1 block w-full"
                    multiple
                >
                @error('files.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button 
                wire:click="uploadFiles" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Upload Files
            </button>
        </div>
    @endif
</div>