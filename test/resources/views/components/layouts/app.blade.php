<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<style>
    .i4ewOd-pzNkMb-haAclf {
        display: none !important;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Water Level Monitoring System' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/53d16a7ee2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/howler@2.2.3/dist/howler.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
</head>

<body>
    {{ $slot }}

    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKVvI-0R1P3Bd-5L8JH--PRSTSEDWwHo0&libraries=places&callback=initMap&loading=async"
        async defer></script>
    <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>
</body>

</html>
