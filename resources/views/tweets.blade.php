@extends ('layouts.master')

@section('content')
    <div class="title m-b-md">
        Keep It 140
    </div>

    <p>Dashboard!</p>

    @foreach ($tweets as $tweet)
    <div>
        &bull; {{ print_r($tweet) }} <br>
    </div>
    @endforeach
@endsection
