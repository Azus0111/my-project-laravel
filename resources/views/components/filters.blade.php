@props([
    'action' => null,
    'fields' => [],
])

<form class="join" method="GET" action="{{ $action }}">

    @foreach ($fields as $field)
        
        @if ($field['type'] === 'search')
            <label class="input join-item">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2.5"></circle>
                    <path d="m21 21-4.3-4.3" fill="none" stroke="currentColor" stroke-width="2.5"></path>
                </svg>

                <input
                    type="search"
                    name="{{ $field['name'] }}"
                    class="w-3xs"
                    maxlength="64"
                    value="{{ request($field['name']) }}"
                    placeholder="{{ $field['placeholder'] ?? 'Search' }}"
                />
            </label>
        @endif


        @if ($field['type'] === 'select')
            <div class="tooltip w-full" data-tip="{{ $field['label'] ?? $field['name'] }}">
                <select class="select join-item" name="{{ $field['name'] }}">

                    <option value="" @selected(request($field['name']) === null || request($field['name']) === '')>
                        All
                    </option>

                    @foreach ($field['options'] as $option)
                        <option value="{{ $option['value'] }}"
                            @selected(request($field['name']) == $option['value'])>
                            {{ $option['label'] }}
                        </option>
                    @endforeach

                </select>
            </div>
        @endif

    @endforeach

    <button class="btn join-item">Search</button>
</form>

