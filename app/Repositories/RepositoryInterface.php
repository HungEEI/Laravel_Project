<?php

namespace App\Repositories;

interface RepositoryInterface {

    public function getAll();

    public function find($id);

    public function create($attributes = []);

    public function update($id, $arrtributes = []);

    public function delete($id);

}