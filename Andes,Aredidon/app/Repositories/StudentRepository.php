<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepository implements StudentRepositoryInterface
{
    protected Student $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['name'])) {
            $name = $filters['name'];
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%")
                  ->orWhere('middle_name', 'like', "%{$name}%");
            });
        }

        if (!empty($filters['course'])) {
            $query->where('course', $filters['course']);
        }

        if (!empty($filters['year_level'])) {
            $query->where('year_level', $filters['year_level']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * @inheritdoc
     */
    public function findById($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @inheritdoc
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @inheritdoc
     */
    public function update($id, array $data)
    {
        $student = $this->findById($id);

        if (!$student) {
            return false;
        }

        $student->update($data);

        return $student;
    }

    /**
     * @inheritdoc
     */
    public function delete($id): bool
    {
        $student = $this->findById($id);

        if (!$student) {
            return false;
        }

        return $student->delete();
    }
}
