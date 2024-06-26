@foreach (getModuleByPosition($course) as $key => $module)          
    <div class="accordion-group">
        <h4 class="accordion-title {{$key == 0 ? 'active' : ''}}">{{$module->name}}</h4>
        <div class="accordion-detail" style="display: {{$key == 0 ? 'block' : ''}}">
            @foreach (getLessonByPosition($course, $module->id) as $lesson)                  
                <div class="card-accordion">
                    <div>
                        <i class="fa-brands fa-youtube"></i>                      
                        <a class="text-dark" href="{{route('lessons.index', $lesson->slug)}}">{{"Bài ".(++$index).": ".$lesson->name}}</a>
                        {!! $lesson->is_trial ? '<p class="trial-btn" data-id="'.$lesson->id.'">Học thử</p>' : '' !!}
                        <span>{{getTime($lesson->durations)}}</span>
                    </div>
                </div>            
            @endforeach
        </div>
    </div> 
@endforeach

@section('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            
            const modalEl = document.querySelector('#modal');
            const trialBtnList = document.querySelectorAll('.trial-btn');
            if (trialBtnList.length) {
                trialBtnList.forEach(trialBtn => {
                    trialBtn.addEventListener('click', (e) => {
                        const initialBtn = e.target.innerText;
                        const id = e.target.dataset.id;
                        if (!id) {
                            alert('Không mở được video học thử');
                            return;
                        }
                        const modal = new bootstrap.Modal(modalEl);                  
    
                        // Call tới Server để trả về video (csrf token)
    
                        const url = "{{route('courses.data.trial')}}/" + id;
                        e.target.innerText = 'Đang mở...';
                        fetch(url).then(response => {
                            return response.json();
                        }).then(({success, data}) => {    
                            if (!success) {
                                alert('Bài giảng không tồn tại');
                                return;
                            }
                            if (data.is_trial != 1) {
                                alert('Không được phép học thử');
                                return;
                            }      
                            
                            const name = data.name;
                            const videoUrl = data.video.url;
                            modal.show();
                            modalEl.querySelector('.modal-title').innerText = name;
                            modalEl.querySelector('.modal-body').innerHTML = `<video 
                            id="my-video"
                            class="video-js"
                            preload="auto"
                            controls
                            data-setup="{}">
                                <source src="/data/stream?video=${videoUrl}" />
                                <p class="vjs-no-js">
                                To view this video please enable JavaScript
                                <a href="https://videojs.com/html5-video-support/" target="_blank"
                                    >supports HTML5 video</a
                                >
                                </p>
                            </video>`;
                        }).finally(() => {
                            e.target.innerText = initialBtn;
                            const myVideoEl = modal.querySelector('.modal-body').querySelector('#my-video');
                            videojs(myVideoEl);
                        });
    
                    });
                });
            }
            modalEl.addEventListener('hidden.bs.modal', () => {
                modalEl.querySelector('.modal-title').innerText = '';
                modalEl.querySelector('.modal-body').innerText = '';
            })
        });
    </script>
@endsection
