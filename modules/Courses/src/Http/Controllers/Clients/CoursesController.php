<?php

namespace Modules\Courses\src\Http\Controllers\Clients;

use App\Console\Commands\Controller;
use Modules\Lessons\src\Repositories\LessonsRepository;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;

class CoursesController extends Controller {

    protected $coursesRepository;
    protected $lessonsRepository;

    public function __construct(
        CoursesRepositoryInterface $coursesRepository,
        LessonsRepository $lessonsRepository
       
        ) 
    {
        $this->coursesRepository = $coursesRepository;
        $this->lessonsRepository = $lessonsRepository;
    }
    public function index() {
        $pageTitle = 'Khóa học';
        $pageName = 'Khóa học';
        $courses = $this->coursesRepository->getCourse(config('paginate.limit'));
        return view('courses::clients.index', compact('pageTitle', 'pageName', 'courses'));
    }

    public function detail($slug) {
        $course = $this->coursesRepository->getCourseClient($slug);
        if (!$course) {
            abort(404);
        }
        $pageTitle = $course->name;
        $pageName = $course->name;
        $index = 0;
        return view('courses::clients.detail', compact('pageTitle', 'pageName', 'course', 'index'));
    }

    public function getTrialVideo($lessonId = 0) {
        $lesson = $this->lessonsRepository->find($lessonId);
        if (!$lesson) {
            return [
                'success' => false
            ];
        }
        return [
            'success' => true,
            'data' => $lesson
        ];
    }
}