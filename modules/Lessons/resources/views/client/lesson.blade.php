@foreach (getModuleByPosition($course) as $key => $module)     
<div class="accordion active title m-0 p-1">     
    <div class="accordion-group pb-0">
        <h4 class="accordion-title {{$module->id == $lesson->parent_id ? 'active' : ''}}">{{$module->name}}</h4>
        <div class="accordion-detail" style="display: {{$module->id == $lesson->parent_id ? 'block' : ''}}">
            @foreach (getLessonByPosition($course, $module->id) as $item)                  
                <div class="card-accordion px-0 {{$item->id == $lesson->id ? 'active' : ''}}">
                    <div class="align-items-start">
                        <i class="fa-brands fa-youtube me-2 ms-2"></i>                      
                        <a class="text-dark" href="{{route('lessons.index', $item->slug)}}">{{"Bài ".(++$index).": ".$item->name}}</a>
                    </div>
                </div>            
            @endforeach
        </div>
    </div> 
</div>
@endforeach