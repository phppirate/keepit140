@extends ('layouts.master')

@section('content')
    <div class="content">
        <div class="title m-b-md">
            Keep It 140
        </div>

        Dashboard!

        @foreach ($tweets as $tweet)
            &bull; {{ print_r($tweet) }} <br>
        @endforeach
    </div>
@endsection
