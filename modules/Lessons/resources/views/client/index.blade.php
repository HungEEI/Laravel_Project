@extends('layouts.client')

@section('content')
@include('parts.client.page_title')
<section class="video">
    <div class="container">
        <h3>{{$lesson->name}}</h3>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="video-detail">
                    <video 
                        id="my-video"
                        class="video-js"
                        preload="auto"
                        controls
                        data-setup="{}">
                        <source src="/data/stream?video={{$lesson->video->url}}" />
                        <p class="vjs-no-js">
                                To view this video please enable JavaScript
                            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                         </p>
                    </video>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        @if ($prevLesson)                      
                            <a href="{{route('lessons.index', $prevLesson->slug)}}" class="prev text-white">Quay lại</a>
                        @endif
                    </div>
                    <div>
                        @if ($nextLesson)                     
                            <a href="{{route('lessons.index', $nextLesson->slug)}}" class="next text-white">Tiếp theo</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="nav flex">
                    <p class="lesson active">Bài học</p>
                    <p class="document">Tài liệu</p>
                </div>
                <div class="group mt-2">
                    @include('lessons::client.lesson')
                    <div class="document-title title">
                        <p>tài liệu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        const myVideoEl = document.querySelector('#my-video');
        videojs(myVideoEl);
    </script>
@endsection