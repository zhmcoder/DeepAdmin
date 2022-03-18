<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Admin::title() }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! Admin::css() !!}
    @foreach(\Andruby\DeepAdmin\Admin::styles() as $name => $path)
        @if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']))
            <link rel="stylesheet" href="{{ $path }}">
        @else
            <link rel="stylesheet" href="{{route('admin.styles',['style'=>$name])}}">
        @endif

    @endforeach
    @yield('head-js')
</head>
<body>
<div id="app">@yield('content')</div>
<script>
    Admin = {};
    Admin.token = "{{csrf_token()}}";
    window.config = {
        'apiRoot': "{{config('deep_admin.route.api_prefix')}}"
    }
</script>
@yield('js')
<script src="{{ mix('manifest.js', 'vendor/deep-admin') }}"></script>
<script src="{{ mix('vendor.js', 'vendor/deep-admin') }}"></script>
<script src="{{ mix('app.js', 'vendor/deep-admin') }}"></script>

<script>
    window.VueAdmin = new CreateVueAdmin(config)
</script>
@foreach (\Andruby\DeepAdmin\Admin::scripts() as $name => $path)
    @if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']))
        <script src="{!! $path !!}"></script>
    @else
        <script src="{{route('admin.scripts',['script'=>$name])}}"></script>
    @endif
@endforeach
<script>
    VueAdmin.liftOff()
</script>
</body>
</html>
