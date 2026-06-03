@props(['items'])

<div class="breadcrumbs text-sm">
    <ul>
        @foreach ($items as $index => $item)
            <li>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                @else
                    <span>{{ $item['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>