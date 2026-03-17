<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Filter toggles --}}
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model.live="showProduction"
                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                Production
            </label>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model.live="showOrders"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span>
                Orders
            </label>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model.live="showShipments"
                    class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                <span class="inline-block w-3 h-3 rounded-full bg-orange-500"></span>
                Shipments
            </label>
        </div>

        {{-- Calendar container --}}
        <div id="farm-calendar" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4" wire:ignore></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('farm-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: {!! $this->getEvents() !!},
                eventClick: function (info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
                height: 'auto',
            });
            calendar.render();

            // Re-render on Livewire updates
            Livewire.hook('morph.updated', () => {
                calendar.removeAllEvents();
                const newEvents = {!! $this->getEvents() !!};
                calendar.addEventSource(newEvents);
            });
        });
    </script>
    @endpush
</x-filament-panels::page>
