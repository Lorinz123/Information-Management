<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Exception;
use Illuminate\Support\Facades\Log;

class StudentService
{
    protected StudentRepositoryInterface $repository;

    public function __construct(StudentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all students with optional filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllStudents(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    /**
     * Get single student by ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     */
    public function getStudentById($id)
    {
        $student = $this->repository->findById($id);

        if (!$student) {
            throw new ModelNotFoundException("Student not found.");
        }

        return $student;
    }

    /**
     * Create a new student.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createStudent(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            Log::error('Failed to create student: ' . $e->getMessage());
            throw new InvalidArgumentException('Unable to create student. Please verify the input data.');
        }
    }

    /**
     * Update an existing student.
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     */
    public function updateStudent($id, array $data)
    {
        $student = $this->repository->update($id, $data);

        if (!$student) {
            throw new ModelNotFoundException("Student not found.");
        }

        return $student;
    }

    /**
     * Delete a student.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteStudent($id)
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            throw new ModelNotFoundException("Student not found.");
        }

        return true;
    }
}
