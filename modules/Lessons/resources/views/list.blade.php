@extends('layouts.backend')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách bài giảng </h6>
    </div>
    @if (session('msg'))
        <div class="alert alert-success text-center">{{session('msg')}}</div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <a href="{{route('admin.courses.index')}}" class="btn btn-info mb-3">Quay lại khóa học</a>
            <a href="{{route('admin.lessons.add', $course)}}" class="btn btn-primary mb-3">Thêm mới</a>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Học thử</th>
                        <th>Lượt xem</th>
                        <th>Thứ tự</th>
                        <th>Thời gian</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
            </table>
            @include('parts.backend.delete')
        </div>
    </div>
</div>
@endsection
