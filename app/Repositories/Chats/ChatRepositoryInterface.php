<?php

namespace App\Services\Chat\Repositories;

use App\Models\Chat;

interface ChatRepositoryInterface
{
    public function find(int $id);

    public function search(array $filters = []);

    public function createFromArray(array $data): Chat;

    public function updateFromArray(Chat $chat, array $data);
}