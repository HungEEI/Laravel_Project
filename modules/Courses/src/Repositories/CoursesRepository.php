<?php
namespace Modules\Courses\src\Repositories;

use App\Models\Scopes\ActiveScope;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Modules\Courses\src\Models\Course;
use Modules\Courses\src\Models\Courses;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;

class CoursesRepository extends BaseRepository implements CoursesRepositoryInterface {

    public function getModel() {
        return Course::class;
    }

    public function getAllCourses() {
        return $this->model->withoutGlobalScope(ActiveScope::class)->select(['id', 'name', 'price', 'sale_price', 'status', 'created_at'])->latest();
    }

    public function getFindCourse($id) {
        return $this->model->withoutGlobalScope(ActiveScope::class)->find($id);
    }

    public function createCourseCategories($course, $data = []) {
        return $course->categories()->attach($data);
    }

    public function updateCourseCategories($course, $data = []) {
        return $course->categories()->sync($data);
    }

    public function getRelatedCategoies($course) {
        $categoryIds = $course->categories()->allRelatedIds()->toArray();
        return $categoryIds;
    }

    public function deleteCourseCategories($course) {
        return $course->categories()->detach();
    }

    public function getCourseClient($slug) {
        return $this->model->whereSlug($slug)->first();
    }

    public function getCourse($limit) {
        return $this->model->limit($limit)->latest()->paginate($limit);
    }

    public function deleteCourse($id)
    {
        return $this->model->withoutGlobalScope(ActiveScope::class)->where('id', $id)->delete();
    }

    public function updateCourse($id, $data = []) {
        $result = $this->getFindCourse($id);
        if ($result) {
            return $result->update($data);
        }
        return false;
    }
}

