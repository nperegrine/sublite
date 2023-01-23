<?php

namespace App\Repository;

interface SubscriberRepositoryInterface
{
    public function all();
    public function find($id);
    public function save($subscriber, array $data);
    public function delete($id);
}