<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentRepositoryInterface
{
    /**
     * Retrieve a paginated list of students.
     * Optionally filter by name, course, or year_level.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get a single student by ID.
     *
     * @param int|string $id
     * @return Model|null
     */
    public function findById($id): ?Model;

    /**
     * Create a new student record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing student record.
     *
     * @param int|string $id
     * @param array $data
     * @return Model|bool
     */
    public function update($id, array $data);

    /**
     * Delete a student record securely.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;
}
