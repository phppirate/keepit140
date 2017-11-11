@extends ('layouts.master')

@section('content')
    <p>Of your, the worst offenders within their last 20 tweets</p>

    @foreach ($offenders as $offender)
    <div class="my-2 py-2 border-b border-grey flex content-between">
        <div class="mr-2 flex-no-shrink">
            <img src="{{ $offender->profile_image_url_https }}" class="rounded-full mr-2 w-16 h-16">
        </div>
        <div class="flex-1">
            <a href="{{ $offender->url }}">
                <span class="font-bold">{{ $offender->name }}</span>
                <span class="color-grey">{{ $offender->screen_name }}</span>
            </a>
            <div>
                {{ $offender->description }}
            </div>
        </div>
        <div class="font-bold mt-2 flex-shrink w-32">
            Offenses in the last 20 tweets: {{ $offender->offenses }}
        </div>
    </div>
    @endforeach
@endsection
