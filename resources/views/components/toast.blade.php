@props(['type' => 'success', 'messages' => [], 'hideToast' => 5000])

@if(count($messages))
    <div class="toast toast-top toast-end toast-{{ $type }}">
        @foreach($messages as $msg)
            <div class="alert alert-{{ $type }} shadow-lg mb-2">
                <span>{{ $msg ?? 'Nothing'}}</span>
            </div>
        @endforeach
    </div>
@endif

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('.toast-{{ $type }}');
            toasts.forEach(function(toast) {
                setTimeout(() => {
                    toast.remove();
                }, {{ $hideToast }});
            });
        });
    </script>
@endsection