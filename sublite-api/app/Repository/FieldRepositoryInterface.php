<?php

namespace App\Repository;

interface FieldRepositoryInterface
{
    public function all();
    public function find($id);
    public function store(array $data);
    public function update(object $data, $id);
    public function delete($id);
}