<?php
namespace Modules\Students\src\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Students\src\Http\Requests\StudentRequest;
use Modules\Students\src\Repositories\StudentsRepositoryInterface;

class StudentController extends Controller {

    protected $studentsRepository;
    public function __construct(StudentsRepositoryInterface $studentsRepository) {
        $this->studentsRepository = $studentsRepository;
    }

    public function index() {
        $pageTitle = "Quản lý học viên";
        return view('students::list', compact('pageTitle'));
    }

    public function data() {
        $students = $this->studentsRepository->getAllStudents();

        return DataTables::of($students)
        ->addColumn('edit', function ($student) {
            return '<a href="'.route('admin.students.edit', $student).'" class="btn btn-warning btn-sm">Sửa</a>';
        })
        ->addColumn('delete', function ($student) {
            return '<a href="'.route('admin.students.delete', $student).'" class="btn btn-danger delete-action btn-sm">Xóa</a>';
        })
        ->editColumn('created_at', function ($student) {
            return Carbon::parse($student->created_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('status', function ($student) {
            return $student->status ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class="btn btn-warning btn-sm">Chưa kích hoạt</button>';
        })
        ->rawColumns(['edit', 'delete', 'status'])
        ->toJson();
    }

    public function add() {
        $pageTitle = "Thêm học viên";
        return view('students::add', compact('pageTitle'));
    }

    public function store(StudentRequest $request) {
        $this->studentsRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.students.index')->with('msg', __('students::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật học viên';
        $student = $this->studentsRepository->find($id);
        if (!$student) {
            abort(404);
        }

        return view('students::edit', compact('student', 'pageTitle'));
    }

    public function update(StudentRequest $request, $id) {
        $data = $request->except('__token', 'password');
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $this->studentsRepository->update($id, $data);

        return back()->with('msg', __('students::messages.update.success'));     
    }

    public function delete($id) {
        /*
        Dữ liệu lên quan:
        - Liên kết giữa học viên và khóa học
        - Trung gian: Thống kê học viên, liên kết tài khoản mạng xã hội
        */
        $this->studentsRepository->delete($id);
        return back()->with('msg', __('students::messages.delete.success'));     
    }

}