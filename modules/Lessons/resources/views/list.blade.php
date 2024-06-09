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
                        <th>Thời lượng</th>
                        <th>Thêm</th>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                autoWith: false,
                processing: true,
                serverSide: true,
                pageLength: 2,
                ajax: '{{route('admin.lessons.data', $course->id)}}',
                columns: [
                    { data: 'name' },
                    { data: 'is_trial' },
                    { data: 'view' },
                    { data: 'durations' },
                    { data: 'add' },
                    { data: 'edit' },
                    { data: 'delete' }
                ]
            });
        });
    </script>
@endsection
