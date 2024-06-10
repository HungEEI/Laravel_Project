<?php
namespace Modules\Lessons\src\Repositories;

use App\Repositories\RepositoryInterface;

interface LessonsRepositoryInterface extends RepositoryInterface {
    public function getPosition($courseId);
    public function getAllLessons($courseId);
    public function getLessons($courseId);
    function getLessonCount($course);
}