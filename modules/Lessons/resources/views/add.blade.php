@extends('layouts.backend')

@section('content')
@if (session('msg'))
<div class="alert alert-success text-center">{{session('msg')}}</div>
@endif
<form action="" method="post">
    <div class="row">

        <div class="col-6">
            <div class="mb-3">
                <label for="">Tên</label>
                <input type="text" name="name" class="title form-control{{$errors->has('name')?' is-invalid':''}}" placeholder="Tên..." value="{{old('name')}}">
                @error('name')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Slug</label>
                <input type="text" name="slug" class="slug form-control{{$errors->has('slug')?' is-invalid':''}}" placeholder="Slug..." value="{{old('slug')}}">
                @error('slug')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label for="">Nhóm bài giảng</label>
                <select name="parent_id" class="select2 form-control{{$errors->has('parent_id')?' is-invalid':''}}">
                    <option value="0">Trống</option>
                    {{getLessons($lessons, old('parent_id'))}}
                </select>
                @error('parent_id')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label for="">Học thử</label>
                <select name="is_trial" class="form-control{{$errors->has('is_trial')?'is-invalid':''}}">
                    <option value="0" {{old('is_trial') == 0 ? 'selected' : false}}>Không</option>
                    <option value="1" {{old('is_trial') == 1 ? 'selected' : false}}>Có</option>
                </select>
                @error('is_trial')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label for="">Sắp xếp</label>
                <input type="number" class="form-control{{$errors->has('position')?' is-invalid':''}}" name="position" placeholder="Thứ tự bài giảng..." value="{{old('position', $position)}}">
                @error('position')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Video</label>
                <div class="input-group {{$errors->has('video') ? 'is-invalid' : ''}}">
                    <input type="text" class="form-control" name="video" id="video-url" placeholder="Video bài giảng" value="{{old('video')}}">
                    <button type="button" class="btn btn-success" id="lfm-video" data-input="video-url">Chọn</button>
                </div>
                @error('video')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Tài liệu</label>
                <div class="input-group {{$errors->has('document') ? 'is-invalid' : ''}}">
                    <input type="text" class="form-control" name="document" id="document-url" placeholder="Tài liệu bài giảng" value="{{old('document')}}">
                    <button type="button" class="btn btn-success" id="lfm-document" data-input="document-url">Chọn</button>
                </div>
                @error('document')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="">Mô tả</label>
                <textarea name="description" class="form-control ckeditor {{$errors->has('description')?' is-invalid':''}}" placeholder="Nội dung...">{{old('description')}}</textarea>
                @error('description')                 
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Lưu lại</button>
            <a href="{{route('admin.lessons.index', $courseId)}}" class="btn btn-danger">Hủy</a>
        </div>
    </div>
    @csrf
</form>
@endsection