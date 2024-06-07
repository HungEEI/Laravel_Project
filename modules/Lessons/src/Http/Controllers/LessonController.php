<?php
namespace Modules\Lessons\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Lessons\src\Http\Requests\LessonRequest;
use Modules\Video\src\Repositories\VideoRepositoryInterface;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Document\src\Repositories\DocumentRepositoryInterface;

class LessonController extends Controller {

    protected $coursesRepository, $videoRepository, $documentRepository;
    public function __construct(
            CoursesRepositoryInterface $coursesRepository,
            VideoRepositoryInterface $videoRepository,
            DocumentRepositoryInterface $documentRepository
        ) {
        $this->coursesRepository = $coursesRepository;
        $this->videoRepository = $videoRepository;
        $this->documentRepository = $documentRepository;
    }

    public function index($courseId) {
        $course = $this->coursesRepository->find($courseId);
        $pageTitle = "Bài giảng: " . $course->name;
        return view('lessons::list', compact('pageTitle', 'course'));
    }

    public function add($courseId) {
        $pageTitle = "Thêm bài giảng";
        return view('lessons::add', compact('pageTitle', 'courseId'));
    }

    public function store(LessonRequest $request) {
        $video = $request->video;
        $getID3 = new \getID3;
        $path = Storage::disk('public')->path(str_replace('storage', '', $request->video));
        $file = $getID3->analyze($path);
        dd($file);
        $result = $this->videoRepository->createVideo(['url' => $video]);

        // $path = Storage::disk('public')->path(str_replace('storage', '', $request->document));
        // $name = basename($request->document);

        // $size = File::size($path);

        // $result = $this->documentRepository->createDocument(['name' => $name, 'url' => $request->document]);

        // return $result;
    }

}