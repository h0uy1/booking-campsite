<x-layout>
    @foreach($slots as $slot)
        {{ $slot->tent_number }}
    @endforeach
</x-layout>