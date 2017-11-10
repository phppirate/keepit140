@extends ('layouts.master')

@section('content')
    <div class="title m-b-md">
        Keep It 140
    </div>

    <p>Of your 20 first returned friends, the worst offenders within their last 20 (i think) tweets</p>

    @foreach ($offenders as $offender)
    <div class="my-2 py-2 border-b border-grey">
        <img src="{{ $offender->profile_image_url_https }}" class="rounded-full">
        <a href="{{ $offender->url }}">
            <span class="font-bold">{{ $offender->name }}</span>
            <span class="color-grey">{{ $offender->screen_name }}</span>
        </a>
        <div>
            {{ $offender->description }}
        </div>
        <div class="font-bold text-lg mt-2">
            Offenses in the last 20 tweets: {{ $offender->offenses }}
        </div>
    </div>
    @endforeach
@endsection
