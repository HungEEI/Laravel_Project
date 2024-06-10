<?php

namespace Modules\Courses\src\Http\Controllers\Clients;

use App\Console\Commands\Controller;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;

class CoursesController extends Controller {

    protected $coursesRepository;

    public function __construct(
        CoursesRepositoryInterface $coursesRepository
       
        ) 
    {
        $this->coursesRepository = $coursesRepository;
    }
    public function index() {
        $pageTitle = 'Khóa học';
        $pageName = 'Khóa học';
        $courses = $this->coursesRepository->getCourse(config('paginate.limit'));
        return view('courses::clients.index', compact('pageTitle', 'pageName', 'courses'));
    }

}