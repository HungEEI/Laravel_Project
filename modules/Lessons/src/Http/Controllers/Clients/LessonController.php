<?php
namespace Modules\Lessons\src\Http\Controllers\Clients;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;


class LessonController extends Controller {

    protected $lessonsRepository;
    public function __construct(
            LessonsRepositoryInterface $lessonsRepository
        ) {
        $this->lessonsRepository = $lessonsRepository;
    }

    public function index($slug) {
        $lesson = $this->lessonsRepository->getLessonActive($slug);
        if (!$lesson) {
            abort(404);
        }
        $pageTitle = $lesson->name;
        $pageName = $lesson->name;
        $course = $lesson->course;
        $index = 0;

        $lessons = $this->lessonsRepository->getLessonByPosition($course);
        $currentLessonIndex = null;

        foreach ($lessons as $key => $item) {
            if ($item->id == $lesson->id) {
               $currentLessonIndex = $key;
               break;
            }
        }

        $nextLesson = null;
        $prevLesson = null;
        if (isset($lessons[$currentLessonIndex + 1])) {
            $nextLesson = $lessons[$currentLessonIndex + 1];
        }

        if (isset($lessons[$currentLessonIndex - 1])) {
            $prevLesson = $lessons[$currentLessonIndex - 1];
        }
        
        return view('lessons::client.index', compact('lesson', 'pageTitle', 'pageName', 'lesson', 'course', 'index', 'nextLesson', 'prevLesson'));
    }

}