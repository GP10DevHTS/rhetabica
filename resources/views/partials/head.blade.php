<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
{{-- {{ config('app.pusher.app_key') }} --}}
{{-- add pusher js --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>

var pusher = new Pusher('{{ config('app.pusher.app_key') }}', {
    cluster: '{{ config('app.pusher.app_cluster') }}',
});

// var channel = pusher.subscribe('my-channel');
// channel.bind('my-event', function(data) {
//     alert(JSON.stringify(data));
// });
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
