<?php
namespace Modules\Lessons\src\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
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
        $course = $this->coursesRepository->getFindCourse($courseId);
        $pageTitle = "Bài giảng: " . $course->name;
        $this->updateDurations($courseId);
        return view('lessons::list', compact('pageTitle', 'course'));
    }

    public function data($courseId) {
        $lessons = $this->lessonsRepository->getLessons($courseId);
        $lessons = DataTables::of($lessons)->toArray();
        $lessons['data'] = $this->getlessonsTable($lessons['data']);

        return $lessons;
    }

    public function getlessonsTable($lessons, $char = '', &$result = []) {
        if (!empty($lessons)) {
            foreach ($lessons as $key => $lesson) {
                $row = $lesson;
                if ($row['parent_id'] == null) {
                    $row['name'] = '<strong class="text-dark !important>">' . $char . $row['name'] . '</strong>';
                    $row['is_trial'] = '';
                    $row['view'] = '';
                    $row['durations'] = '';  
                    $row['add'] = '<a href="'.route('admin.lessons.add', $row['course_id']).'?module='.$row['id'].'" class="btn btn-primary btn-sm">Thêm bài</a>';
                    $row['edit'] = '<a href="'.route('admin.lessons.edit', $row['id']).'" class="btn btn-warning btn-sm">Sửa</a>';
                    $row['delete'] = '<a href="'.route('admin.lessons.delete', $row['id']).'" class="btn btn-danger delete-action btn-sm">Xóa</a>';
                    } else {
                        $row['name'] = $char.$row['name'];
                        $row['is_trial'] = ($row['is_trial'] == 1 ? 'Có' : 'Không');
                        $row['view'] = $row['view'];
                        $row['durations'] = getTime($row['durations']);
                        $row['add'] = '';
                        $row['edit'] = '<a href="'.route('admin.lessons.edit', $row['id']).'" class="btn btn-warning btn-sm">Sửa</a>';
                        $row['delete'] = '<a href="'.route('admin.lessons.delete', $row['id']).'" class="btn btn-danger delete-action btn-sm">Xóa</a>';
                    }
                unset($row['sub_lessons']);
                unset($row['course_id']);
                $result[] = $row;
                if (!empty($lesson['sub_lessons'])) {                  
                    $this->getlessonsTable($lesson['sub_lessons'], $char.'|-- ', $result);
                }
            }
        }

        return $result;
    }

    public function add(Request $request, $courseId) {
        $pageTitle = "Thêm bài giảng";
        $position = $this->lessonsRepository->getPosition($courseId);
        $lessons = $this->lessonsRepository->getAllLessons($courseId)->toArray();
        return view('lessons::add', compact('pageTitle', 'courseId', 'position', 'lessons'));
    }

    public function store($courseId, LessonRequest $request) {
        $name = $request->name;
        $slug = $request->slug;
        $video = $request->video;
        $document = $request->document;
        $parentId = $request->parent_id == 0 ? null : $request->parent_id;
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
        if ($video) {
            $videoInfo = getVideoInfo($video);
            $video = $this->videoRepository->createVideo(['url' => $video, 'name' => $videoInfo['filename'], 'size' => $videoInfo['playtime_seconds']], $video);
            $videoId = $video ? $video->id : null;
        }

        $this->lessonsRepository->create([
            'name' => $name, 
            'slug' => $slug, 
            'video_id' => $videoId, 
            'course_id' => $courseId, 
            'document_id' => $documentId, 
            'parent_id' => $parentId, 
            'is_trial' => $isTrial,
            'position' => $position, 
            'durations' => $videoInfo['playtime_seconds'] ?? 0,
            'description' => $description
        ]);

        $this->updateDurations($courseId);
        return redirect()->route('admin.lessons.index', $courseId)->with('msg' , __('lessons::messages.add.success'));
    
    }

    public function edit(Request $request, $lessonId) {
        $pageTitle = "Sửa bài giảng";
        $lesson = $this->lessonsRepository->find($lessonId);
        $lessons = $this->lessonsRepository->getAllLessons($lesson->course_id);
        $lesson->video = $lesson->video?->url;
        $lesson->document = $lesson->document?->url;
        if (!$lesson) {
            return abort(404);
        }
        
        $courseId = $lesson['course_id'];
        return view('lessons::edit', compact('pageTitle', 'lessonId', 'courseId', 'lessons', 'lesson'));
    }

    public function update($lessonId, Request $request) {
        $name = $request->name;
        $slug = $request->slug;
        $video = $request->video;
        $document = $request->document;
        $parentId = $request->parent_id == 0 ? null : $request->parent_id;
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
        if ($video) {
            $videoInfo = getVideoInfo($video);
            $video = $this->videoRepository->createVideo(['url' => $video, 'name' => $videoInfo['filename'], 'size' => $videoInfo['playtime_seconds']], $video);
            $videoId = $video ? $video->id : null;
        }

        $this->lessonsRepository->update($lessonId, [
            'name' => $name, 
            'slug' => $slug, 
            'video_id' => $videoId, 
            'document_id' => $documentId, 
            'parent_id' => $parentId, 
            'is_trial' => $isTrial,
            'position' => $position, 
            'durations' => $videoInfo['playtime_seconds'] ?? 0,
            'description' => $description
        ]);
        $lesson = $this->lessonsRepository->find($lessonId);
        $this->updateDurations($lesson->course_id);
        return redirect()->route('admin.lessons.edit', $lessonId)->with('msg' , __('lessons::messages.update.success'));
    
    }

    public function delete(Request $request, $lessonId) {
        $lesson = $this->lessonsRepository->find($lessonId);
        $this->lessonsRepository->delete($lessonId);
        $this->updateDurations($lesson->course_id);
        return back()->with('msg', __('lessons::messages.delete.success')); 
    }

    public function sort(Request $request, $courseId) {
        $pageTitle = "Sắp xếp bài giảng";
        $modules = $this->lessonsRepository->getLessons($courseId)->with('children')->get();
        return view('lessons::sort', compact('pageTitle', 'courseId', 'modules'));
    }

    public function hanldeSort(Request $request, $courseId) {
        $lesson = $request->lesson;
        if ($lesson) {
            foreach ($lesson as $index => $lessonId) {
                $this->lessonsRepository->update($lessonId, [
                    'position' => $index
                ]);
            }   
            return back()->with('msg', __('lessons::messages.update.success')); 
        }
    }

    private function updateDurations($courseId) {
        // Lấy tất cả bài học trong 1 khóa học
        $lessons = $this->lessonsRepository->getAllLessons($courseId);
        // Tính tổng durations của các bài học trong 1 khóa học
        $durations = $lessons->reduce(function ($prev, $item) {
            return $prev + $item->durations;
        }, 0);

        // Cập nhật bảng courses
        $this->coursesRepository->updateCourse($courseId, [
            'durations' => $durations
        ]);
    }

}