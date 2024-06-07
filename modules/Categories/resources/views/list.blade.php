@extends('layouts.backend')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách chuyên mục</h6>
    </div>
    @if (session('msg'))
        <div class="alert alert-success text-center">{{session('msg')}}</div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <a href="{{route('admin.categories.add')}}" class="btn btn-primary mb-3">Thêm danh mục</a>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Link</th>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                autoWith: false,
                processing: true,
                serverSide: true,
                pageLength: 2,
                ajax: '{{route('admin.categories.data')}}',
                columns: [
                    { data: 'name' },
                    { data: 'link' },
                    { data: 'created_at' },
                    { data: 'edit' },
                    { data: 'delete' }
                ]
            });
        });
    </script>
@endsection