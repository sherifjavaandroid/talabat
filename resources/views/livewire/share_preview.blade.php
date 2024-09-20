<!DOCTYPE html>
<html lang="{{ setting('localeCode', 'en') }}" dir="{{ isRTL() ? 'rtl' : 'ltr' }}">

<head>
    @php
        $siteName = setting('websiteName', env('APP_NAME'));
        $title = $model->title ?? ($model->name ?? setting('websiteName', env('APP_NAME')));
        $description = $model->description ?? '';
        $imageUrl = $model->feature_image ?? ($model->logo ?? ($model->photo ?? ''));
        $url = request()->url('') ?? '';
        //get image type from image url
        $imageType = pathinfo($imageUrl, PATHINFO_EXTENSION);
        //generate keywords from title  explode by space and comma
        $splittedTitle = explode(' ', $title);
        $keywords = implode(',', $splittedTitle);
        //
        $androidPackageName = env('dynamic_link.android', null);
        $showAndroid = $androidPackageName ? true : false;
        $androidLink = "https://play.google.com/store/apps/details?id=$androidPackageName";
        $iosAppId = env('dynamic_link.ios_id', null);
        $showIos = $iosAppId ? true : false;
        $iosLink = "https://apps.apple.com/app/$iosAppId";

    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ setting('favicon') }}" />
    <title>{{ $title }}</title>
    {{-- push social media tags for better seo --}}
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }},{{ $title }}">
    <link rel="canonical" href="{{ $url }}">
    <link rel="icon" href="{{ $imageUrl }}" type="image/{{ $imageType }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
    {{-- <meta name="twitter:site" content="@YourTwitterHandle"> --}}
    {{-- <meta name="twitter:creator" content="@AuthorTwitterHandle"> --}}
    @include('layouts.partials.styles')
</head>

<body>
    <div class="flex items-center justify-center py-20">
        {{-- <p>{{ __('Link sharing failed --') }}</p> --}}
        <div class="shadow-lg rounded border w-10/12 md:w-6/12 lg:w-4/12 overflow-hidden">
            {{-- image --}}
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-64 object-cover" />
            {{-- title --}}
            <h1 class="text-2xl font-bold mt-4 mx-4">{{ $title }}</h1>
            {{-- breif description --}}
            <div class="mt-2 mx-4">
                {!! $description !!}
            </div>
            <hr class="my-4" />
            <div class="space-y-1">
                {{-- download app --}}
                <p class="text-center text-lg font-bold">{{ __('Download our app') }}</p>
                {{-- buttons --}}
                <div class="flex space-x-4 pb-4 justify-center items-center">
                    {{-- android --}}
                    @if ($showAndroid)
                        <a href="{{ $androidLink }}" class="bg-green-500 text-white px-4 py-2 rounded hover:shadow">
                            <i class="fab fa-android"></i>
                            <span>{{ __('Download Android') }}</span>
                        </a>
                    @endif
                    {{-- ios --}}
                    @if ($showIos)
                        <a href="{{ $iosLink }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:shadow">
                            <i class="fab fa-apple"></i>
                            <span>{{ __('Download iOS') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- footer --}}
    @include('layouts.partials.scripts')
</body>

</html>
