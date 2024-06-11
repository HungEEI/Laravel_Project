<?php
namespace Modules\Courses\src\Repositories;

use App\Repositories\RepositoryInterface;

interface CoursesRepositoryInterface extends RepositoryInterface {

    public function getAllCourses();
    public function getCourse($limit);
    function getFindCourse($id);
    function deleteCourse($id);
    function updateCourse($id, $data = []);
    function getCourseClient($slug);

}