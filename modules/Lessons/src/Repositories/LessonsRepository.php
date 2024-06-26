<?php
namespace Modules\Lessons\src\Repositories;

use App\Repositories\BaseRepository;
use Modules\Lessons\src\Models\Lesson;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;

class LessonsRepository extends BaseRepository implements LessonsRepositoryInterface {

    public function getModel() {
        return Lesson::class;
    }

    public function getPosition($courseId) {
        $result = $this->model->where('course_id', $courseId)->count();
        return $result + 1;
    }

    
    public function getLessons($courseId) {
        return $this->model->with('subLessons')->whereCourseId($courseId)->whereNull('parent_id')->select(['id', 'name', 'is_trial', 'slug', 'view', 'parent_id', 'durations', 'course_id'])->orderBy('position', 'asc');
    }
        
    public function getAllLessons($courseId) {
        return $this->model->where('course_id', $courseId)->get();
    }

    public function getLessonCount($course) {
        return (object) [
            'module' => $course->lessons()->whereNull('parent_id')->count(),
            'lesson' => $course->lessons()->whereNotNull('parent_id')->count()
        ];
    }

    public function getModuleByPosition($course) {
        return $course->lessons()->whereNull('parent_id')->orderBy('position')->get();
    }

    public function getLessonByPosition($course, $moduleId = null, $isDocument = false) {
        $query = $course->lessons();
        if ($moduleId) {
            $query->where('parent_id', $moduleId);
        }else {
            $query->whereNotNull('parent_id');
        }
        if ($isDocument) {
            $query->whereNotNull('document_id');
        }
        return $query->orderBy('position')->get();
    }

    public function getLessonActive($slug) {
        return $this->model->whereSlug($slug)->first();
    }

}

