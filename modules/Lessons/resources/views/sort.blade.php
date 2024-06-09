@extends('layouts.backend')

@section('content')
@if (session('msg'))
<div class="alert alert-success text-center">{{session('msg')}}</div>
@endif
<form action="" method="post">
    <div id="sortable-list" class="list-group col">
        @foreach ($modules as $module)            
            <div id="items-{{$module->id}}" data-id="{{$module->id}}" class="list-group-item text text-dark">
                {{$module->name}}
                <input type="hidden" name="lesson[]" value="{{$module->id}}">
            </div>      
            @if ($module->children)
                @php
                    $lessons = $module->children()->orderBy('position', 'asc')->get();
                @endphp
                @foreach ($lessons as $lesson)
                    <div id="items-{{$lesson->id}}" data-id="{{$lesson->id}}" class="list-group-item children">
                        {{$lesson->name}}
                        <input type="hidden" name="lesson[]" value="{{$lesson->id}}">
                    </div>                       
                @endforeach
            @endif  
        @endforeach
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Lưu lại</button>
        <a href="{{route('admin.lessons.index', $courseId)}}" class="btn btn-danger">Hủy</a>
    </div>
    @csrf
</form>
@endsection

@section('stylesheets')
<style>
    .ghost {
        opacity: 0.4;
    }
    .list-group {
        margin-bottom: 20px;
    }
    .children {
        padding-left: 44px; 
    }
    .text {
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('#sortable-list').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost',
                onSort: () => {
                    console.log('Success');
                },
            });
        });
    </script>
@endsection