<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Forgot Password</title>

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
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Forgot Password</legend>

            <label class="label">Email</label>
            <input type="email" class="input" placeholder="Email" name="email" value="{{ old('email') }}" />

            <div class="flex justify-between my-3">
                <label class="label">
                    <input type="checkbox" name="remember" class="checkbox-md" />
                    Remember me
                </label>

                <label class="label">
                    <a class="btn btn-link" href="{{ route('admin.login') }}">Back to Login</a>
                </label>
            </div>

            <button class="btn btn-neutral">Send Reset Link</button>
        </fieldset>
    </form>
</body>

@if ($errors->any())
    <x-toast :type="'error'" :messages="$errors->all()" />
@endif
@if (@session('error'))
    <x-toast :type="'error'" :messages="[session('error')]" />
@endif
@if(session('success'))
    <x-toast :messages="[session('success')]" />
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toasts = document.querySelectorAll('.toast-error', '.toast-success');
        toasts.forEach(function (toast) {
            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    });
</script>

</html>