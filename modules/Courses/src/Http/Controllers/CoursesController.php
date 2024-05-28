<?php
namespace Modules\Courses\src\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Courses\src\Http\Requests\CoursesRequest;
use Modules\Courses\src\Repositories\CoursesRepository;

class CoursesController extends Controller {

    protected $coursesRepository;
    public function __construct(CoursesRepository $coursesRepository) {
        $this->coursesRepository = $coursesRepository;
    }

    public function index() {
        $pageTitle = "Quản lý khóa học";
        return view('courses::list', compact('pageTitle'));
    }

    public function data() {
        $courses = $this->coursesRepository->getAllCourses();

        return DataTables::of($courses)
        ->addColumn('edit', function ($course) {
            return '<a href="'.route('admin.courses.edit', $course).'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function ($course) {
            return '<a href="'.route('admin.courses.delete', $course).'" class="btn btn-danger delete-action">Xóa</a>';
        })
        ->editColumn('created_at', function ($course) {
            return Carbon::parse($course->created_at)->format('d/m/Y H:i:s');
        })
        ->rawColumns(['edit', 'delete'])
        ->toJson();
    }

    public function add() {
        $pageTitle = "Thêm khóa học";
        return view('courses::add', compact('pageTitle'));
    }

    public function store(CoursesRequest $request) {
        

        return redirect()->route('admin.courses.index')->with('msg', __('user::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật khóa học';
        $course = $this->coursesRepository->find($id);
        if (!$course) {
            abort(404);
        }

        return view('courses::edit', compact('user', 'pageTitle'));
    }

    public function update(CoursesRequest $request, $id) {

        return back()->with('msg', __('user::messages.update.success'));     
    }

    public function delete($id) {
        $this->coursesRepository->delete($id);
        return back()->with('msg', __('courses::messages.delete.success'));     
    }
}