<ul class="list-group">
    @foreach (getLessonByPosition($course, null, true) as $item)
        <li class="list-group-item"><a target="_blank" href="{{$item->document->url}}">{{$item->name}} ({{getSize($item->document->size). 'KB'}})</a></li>
    @endforeach
</ul>