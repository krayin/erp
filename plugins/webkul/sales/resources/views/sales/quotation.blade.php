<x-filament::section>
    <div>
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:gap-8 md:gap-16 lg:gap-48 xl:gap-60">
            <div class="w-full">
                <div>
                    <img src="{{ asset('storage/'.$record->company?->partner?->avatar) ?? '' }}" alt="{{ $record->company->name }}" class="w-16">
                </div>
                <div class="flex flex-col">
                    <div class="mt-3 text-sm">
                        Bill From:
                    </div>
                    <div class="text-lg font-bold">
                        {{ $record->company->name }}
                    </div>
                    <div class="text-sm">
                        {{ $record->company->phone }}
                    </div>
                    <div class="text-sm">
                        {{ sprintf(
                            "%s\n%s%s\n%s, %s %s\n%s",
                            $record->company->address->name ?? '',
                            $record->company->address->street1 ?? '',
                            $record->company->address->street2 ? ', ' . $record->company->address->street2 : '',
                            $record->company->address->city ?? '',
                            $record->company->address->state ? $record->company->address->state->name : '',
                            $record->company->address->zip ?? '',
                            $record->company->address->country ? $record->company->address->country->name : ''
                        ) }}
                    </div>
                    <div class="text-sm">
                        {{ $record->company->city }}
                    </div>
                    <div class="text-sm">
                        {{ $record->company->country }}
                    </div>
                </div>
                <div class="mt-6">
                    <div class="mt-4">
                        <div class="text-sm">
                            Bill To:
                        </div>
                        <div class="text-lg font-bold">
                            {{ $record->partner->name }}
                        </div>
                        <div class="text-sm">
                            {{ $record->partner->email }}
                        </div>
                        <div class="text-sm">
                            {{ $record->partner->phone }}
                        </div>
                        <div class="text-sm">
                            {{ sprintf(
                                "%s\n%s%s\n%s %s %s\n%s",
                                $record->partner->address?->name ?? '',
                                $record->partner->address?->street1 ?? '',
                                $record->partner->address?->street2 ? ', ' . $record->partner->address?->street2 : '',
                                $record->partner->address?->city ?? '',
                                $record->partner->address?->state ? $record->partner->address?->state->name : '',
                                $record->partner->address?->zip ?? '',
                                $record->partner->address?->country ? $record->partner->address?->country->name : ''
                            ) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full">
                <div class="flex justify-end font-bold">
                    <div>
                        <div>
                            <h1 class="text-3xl uppercase">Quotation</h1>
                        </div>
                        <div>
                            #{{ $record->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="px-2 my-4 border border-gray-200 rounded-lg dark:border-gray-700">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="font-bold border-b border-gray-200 text-start dark:border-gray-700">
                            <th class="w-1/4 px-4 py-2 text-center">Item</th>
                            <th class="w-1/4 px-4 py-2 text-center">Quantity</th>
                            <th class="w-1/4 px-4 py-2 text-center">Price ({{ $record->currency->symbol }})</th>
                            <th class="w-1/4 px-4 py-2 text-center">Tax (%)</th>
                            <th class="w-1/4 px-4 py-2 text-center">Total ({{ $record->currency->symbol }})</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($record->orderSales as $item)
                            <tr>
                                <td class="px-4 py-2 text-center">
                                    <div class="text-lg font-bold">{{ $item->name }}</div>
                                    <div class="text-gray-400">{{ $item->description }}</div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center">
                                        <span class="font-bold">
                                            {{ $item->product_uom_qty }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center">
                                        <span class="font-bold">
                                            {{ number_format($item->price_unit, 2) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center">
                                        <span class="font-bold">
                                            {{ implode(', ', $item->product?->productTaxes->pluck('name')->toArray() ?? []) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center">
                                        <span class="font-bold">
                                            {{ number_format($item->price_total, 2) }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-6">
                <div class="flex flex-col justify-end w-full gap-4">
                    <div>
                        <div class="mb-2 text-xl">
                            Signature
                        </div>
                        <div class="text-sm">
                            <div>
                                {{ $record->company->name }}
                            </div>
                            <div>
                                {{ $record->company->email }}
                            </div>
                            <div>
                                {{ $record->company->phone }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col w-full gap-2 mt-4">
                    <div class="flex justify-between">
                        <div class="font-bold">
                            Subtotal
                        </div>
                        <div>
                            {{ number_format($record?->amount_untaxed, 2) }}<small class="font-normal text-md">({{ $record->currency->symbol }})</small>
                        </div>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="font-bold">
                            Tax
                        </div>
                        <div>
                            {{ number_format($record?->amount_tax, 2) }}<small class="font-normal text-md">({{ $record->currency->symbol }})</small>
                        </div>
                    </div>
                    <div class="flex justify-between text-xl font-bold">
                        <div>
                            Grand Total
                        </div>
                        <div>
                            {{ number_format($record?->amount_total, 2) }}<small class="font-normal text-md">({{ $record->currency->symbol }})</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-4 border-b border-gray-200 dark:border-gray-700"></div>
            <div>
                <div class="mb-2 text-xl">
                    Payment Terms
                </div>
                <div class="text-sm">
                    {{ $record->paymentTerm->name }}
                </div>
            </div>
        </div>
    </div>
</x-filament::section>
