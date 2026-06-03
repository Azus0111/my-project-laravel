<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reset Password</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
        </style>
    @endif
</head>

<body class="flex items-center justify-center min-h-screen">
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Reset Password</legend>

            <label class="label">Email</label>
            <input type="email" readonly class="input" placeholder="Email" name="email" value="{{ request()->email }}" />

            <label class="label">Password</label>
            <input type="password" class="input" placeholder="Password" name="password" required />

            <label class="label">Confirm Password</label>
            <input type="password" class="input" placeholder="Confirm Password" name="password_confirmation" required />

            <button class="btn btn-neutral mt-3 w-full">Reset Password</button>
        </fieldset>
    </form>
</body>

@if ($errors->any())
    <x-toast :type="'error'" :messages="$errors->all()" />
@endif
@if (@session('error'))
    <x-toast :type="'error'" :messages="[session('error')]" />
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toasts = document.querySelectorAll('.toast-error');
        toasts.forEach(function (toast) {
            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    });
</script>

</html>