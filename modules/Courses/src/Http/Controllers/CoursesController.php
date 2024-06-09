<?php
namespace Modules\Courses\src\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Courses\src\Http\Requests\CoursesRequest;
use Modules\Courses\src\Repositories\CoursesRepository;
use Modules\Teachers\src\Repositories\TeachersRepository;
use Modules\Categories\src\Repositories\CategoriesRepository;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Teachers\src\Repositories\TeachersRepositoryInterface;
use Modules\Categories\src\Repositories\CategoriesRepositoryInterface;

class CoursesController extends Controller {

    protected $coursesRepository;
    protected $categoriesRepository;
    protected $teachersRepository;

    public function __construct(
        CoursesRepositoryInterface $coursesRepository, 
        CategoriesRepositoryInterface $categoriesRepository, 
        TeachersRepositoryInterface $teachersRepository
        ) 
    {
        $this->coursesRepository = $coursesRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->teachersRepository = $teachersRepository;
    }

    public function index() {
        $pageTitle = "Quản lý khóa học";
        return view('courses::list', compact('pageTitle'));
    }

    public function data() {
        $courses = $this->coursesRepository->getAllCourses();

        return DataTables::of($courses)
        ->addColumn('lessions', function ($course) {
            return '<a href="'.route('admin.lessons.index', $course).'" class="btn btn-primary btn-sm">Bài giảng</a>';
        })
        ->addColumn('edit', function ($course) {
            return '<a href="'.route('admin.courses.edit', $course).'" class="btn btn-warning btn-sm">Sửa</a>';
        })
        ->addColumn('delete', function ($course) {
            return '<a href="'.route('admin.courses.delete', $course).'" class="btn btn-danger delete-action btn-sm">Xóa</a>';
        })
        ->editColumn('created_at', function ($course) {
            return Carbon::parse($course->created_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('status', function ($course) {
            return $course->status == 1 ? '<button class="btn btn-success btn-sm">Đã ra mắt</button>' : '<button class="btn btn-warning btn-sm">Chưa ra mắt</button>';
        })
        ->editColumn('price', function ($course) {
            if ($course->price) {
                if ($course->sale_price) {
                    $price = number_format($course->sale_price).'đ';
                }else {
                    $price = number_format($course->price).'đ';
                }
            }else {
                $price = 'Miễn phí';
            }
            return $price;
        })
        ->rawColumns(['edit', 'delete', 'status', 'lessions'])
        ->toJson();
    }

    public function add() {
        $pageTitle = "Thêm khóa học";

        $allCategories = $this->categoriesRepository->getAllCategories();

        $teacher = $this->teachersRepository->getAllTeachers()->get();

        return view('courses::add', compact('pageTitle', 'allCategories', 'teacher'));
    }

    public function store(CoursesRequest $request) {
        
        $courses = $request->except(['_token']);
        if (!$courses['sale_price']) {
            $courses['sale_price'] = 0;
        }
        if (!$courses['price']) {
            $courses['price'] = 0;
        }

        $course = $this->coursesRepository->create($courses);
        $categories = $this->getCategories($courses);

        $this->coursesRepository->createCourseCategories($course, $categories);

        return redirect()->route('admin.courses.index')->with('msg', __('courses::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật khóa học';

        $course = $this->coursesRepository->find($id);
        $categoryIds = $this->coursesRepository->getRelatedCategoies($course);
        $allCategories = $this->categoriesRepository->getAllCategories();
        $teacher = $this->teachersRepository->getAllTeachers()->get();

        if (!$course) {
            abort(404);
        }

        return view('courses::edit', compact('course', 'pageTitle', 'allCategories', 'categoryIds', 'teacher'));
    }

    public function update(CoursesRequest $request, $id) {
        $course = $request->except(['_token', '_method']);
        if (!$course['sale_price']) {
            $course['sale_price'] = 0;
        }
        if (!$course['price']) {
            $course['price'] = 0;
        }
        $this->coursesRepository->update($id, $course);
        $categories = $this->getCategories($course);

        $course = $this->coursesRepository->find($id);

        $this->coursesRepository->updateCourseCategories($course, $categories);

        return back()->with('msg', __('courses::messages.update.success'));     
    }

    public function delete($id) {

        $course = $this->coursesRepository->find($id);
        // $this->coursesRepository->deleteCourseCategories($course);
        $this->coursesRepository->delete($id);

        return back()->with('msg', __('courses::messages.delete.success'));     
    }

    public function getCategories($courses) {
        $categories = [];
        foreach ($courses['categories'] as $category) {
            $categories[$category] = ['created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')];
        }

        return $categories;
    }
}