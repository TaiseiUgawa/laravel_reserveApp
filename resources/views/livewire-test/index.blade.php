<!DOCTYPE html>
<html lang="en">
<head>
    @livewireStyles
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        @if (session()->has('message'))
            <div class="">
                {{ session('message') }}
            </div>
        @endif
    </div>
    {{-- <livewire:counter /> --}}
    @livewire('counter')
    @livewireScripts
</body>
</html>
