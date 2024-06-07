<?php
namespace Modules\Lessons\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lessons\src\Http\Requests\LessonRequest;
use Modules\Video\src\Repositories\VideoRepositoryInterface;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;
use Modules\Document\src\Repositories\DocumentRepositoryInterface;

class LessonController extends Controller {

    protected $coursesRepository, $videoRepository, $documentRepository, $lessonsRepository;
    public function __construct(
            CoursesRepositoryInterface $coursesRepository,
            VideoRepositoryInterface $videoRepository,
            DocumentRepositoryInterface $documentRepository,
            LessonsRepositoryInterface $lessonsRepository
        ) {
        $this->coursesRepository = $coursesRepository;
        $this->videoRepository = $videoRepository;
        $this->documentRepository = $documentRepository;
        $this->lessonsRepository = $lessonsRepository;
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

    public function store($courseId, LessonRequest $request) {

        $name = $request->name;
        $slug = $request->slug;
        $video = $request->video;
        $document = $request->document;
        $patentId = $request->patent_id == 0 ? null : $request->patent_id;
        $isTrial = $request->is_trial;
        $position = $request->position;
        $description = $request->description;
        $videoInfo = getVideoInfo($video);
        $documentId = null;
        $videoId = null;

        if ($document) {
            $documentInfo = getFileInfo($document);
            $document = $this->documentRepository->createDocument([
                'url' => $document, 
                'name' => $documentInfo['name'], 
                'size' => $documentInfo['size']
            ], $document);
            $documentId = $document ? $document->id : null;
        }

        $video = $this->videoRepository->createVideo(['url' => $video, 'name' => $videoInfo['filename'], 'size' => $videoInfo['playtime_seconds']], $video);
        $videoId = $video ? $video->id : null;

        $this->lessonsRepository->create([
            'name' => $name, 
            'slug' => $slug, 
            'video_id' => $videoId, 
            'document_id' => $documentId, 
            'patent_id' => $patentId, 
            'is_trial' => $isTrial,
            'position' => $position, 
            'durations' => $videoInfo['playtime_seconds'] ?? 0,
            'description' => $description
        ]);
        return redirect()->route('admin.lessons.add', $courseId)->with('msg' , __('lessons::messages.add.success'));
    
    }

}