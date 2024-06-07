<?php
namespace Modules\Teachers\src\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Modules\Teachers\src\Http\Requests\TeachersRequest;
use Modules\Teachers\src\Repositories\TeachersRepository;
use Modules\Teachers\src\Repositories\TeachersRepositoryInterface;

class TeachersController extends Controller {

    protected $teachersRepository;
    public function __construct(TeachersRepositoryInterface $teachersRepository) {
        $this->teachersRepository = $teachersRepository;
    }

    public function index() {
        $pageTitle = "Quản lý Giảng viên";
        return view('teachers::list', compact('pageTitle'));
    }

    public function data() {
        $teachers = $this->teachersRepository->getAllTeachers();

        $data = DataTables::of($teachers)
        ->addColumn('edit', function ($teachers) {
            return '<a href="'.route('admin.teachers.edit', $teachers).'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function ($teachers) {
            return '<a href="'.route('admin.teachers.delete', $teachers).'" class="btn btn-danger delete-action">Xóa</a>';
        })
        ->editColumn('created_at', function ($teachers) {
            return Carbon::parse($teachers->created_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('image', function ($teachers) {
            return $teachers->image ? '<img src="'.$teachers->image.'" style="width: 80px" />' : '';
        })
        ->rawColumns(['edit', 'delete', 'image'])
        ->toJson();

        return $data;
    }

    public function add() {
        $pageTitle = "Thêm giảng viên";
        return view('teachers::add', compact('pageTitle'));
    }

    public function store(TeachersRequest $request) {

        $teacher = $request->except('_token');
        $this->teachersRepository->create($teacher);

        return redirect()->route('admin.teachers.index')->with('msg', __('teachers::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật giảng viên';
        $teacher = $this->teachersRepository->find($id);
        if (!$teacher) {
            abort(404);
        }

        return view('teachers::edit', compact('teacher', 'pageTitle'));
    }

    public function update(TeachersRequest $request, $id) {
        $teacher = $request->except('__token');

        $this->teachersRepository->update($id, $teacher);

        return back()->with('msg', __('teachers::messages.update.success'));     
    }

    public function delete($id) {
        $teacher = $this->teachersRepository->find($id);

        $status = $this->teachersRepository->delete($id);
        if ($status) {
            $image = $teacher->image;
            deleteFileStorage($image);
        }
        return back()->with('msg', __('teachers::messages.delete.success'));     
    }

}