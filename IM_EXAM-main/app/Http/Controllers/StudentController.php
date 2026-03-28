<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     * Use query parameters: 'name', 'course', 'year_level' to filter.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'course', 'year_level']);
        $students = $this->studentService->getAllStudents($filters);

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $student = $this->studentService->createStudent($data);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully.',
                'data' => $student
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentById($id);

            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string|int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $student = $this->studentService->updateStudent($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully.',
                'data' => $student
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {
            $this->studentService->deleteStudent($id);

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }
    }
}
