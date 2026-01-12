<x-filament-panels::page>
    <style>
        /* Improve DateTimePicker (Flatpickr) time UX on this page */
        .flatpickr-calendar .flatpickr-time {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flatpickr-calendar .flatpickr-time input.flatpickr-hour,
        .flatpickr-calendar .flatpickr-time input.flatpickr-minute {
            width: 2.6ch;
            text-align: center;
            font-variant-numeric: tabular-nums;
        }

        .flatpickr-calendar .flatpickr-time .flatpickr-time-separator {
            padding: 0 4px;
        }
    </style>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-4 justify-start">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page>
