<?php
namespace Modules\Teachers\src\Repositories;

use Modules\Teachers\src\Models\Teacher;
use App\Repositories\BaseRepository;
use Modules\Teachers\src\Repositories\TeachersRepositoryInterface;

class TeachersRepository extends BaseRepository implements TeachersRepositoryInterface {

    public function getModel() {
        return Teacher::class;
    }

    public function getTeachers($limit){
        return $this->model->paginate($limit);
    }

    public function getAllTeachers() {
        return $this->model->select(['id', 'name', 'exp', 'image', 'created_at'])->latest();
    }


}

