<?php
namespace Modules\Teachers\src\Repositories;

use App\Repositories\RepositoryInterface;

interface TeachersRepositoryInterface extends RepositoryInterface {

    public function getAllTeachers();

}