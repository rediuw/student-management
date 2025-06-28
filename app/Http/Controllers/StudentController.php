<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $departmentFilter = $request->input('department');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $query = Student::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%");
            });
        }

        if ($departmentFilter) {
            $query->where('department', $departmentFilter);
        }

        if (
            in_array($sortField, ['name', 'email', 'program', 'department', 'enrollment_year', 'created_at']) &&
            in_array($sortDirection, ['asc', 'desc'])
        ) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $students = $query->paginate(5)->appends($request->all());

        // Get distinct departments for filter dropdown
        $departments = Student::select('department')->distinct()->pluck('department');

        return view('students.index', compact('students', 'search', 'departmentFilter', 'sortField', 'sortDirection', 'departments'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'program' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'enrollment_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        Student::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'program' => $request->program,
            'department' => $request->department,
            'enrollment_year' => $request->enrollment_year,
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_id' => 'nullable|unique:students,student_id,' . $student->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'program' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'enrollment_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'program' => $request->program,
            'department' => $request->department,
            'enrollment_year' => $request->enrollment_year,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted.');
    }

    public function export(): StreamedResponse
    {
        $students = Student::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students.csv"',
        ];

        $callback = function () use ($students) {
            $handle = fopen('php://output', 'w');
            // CSV headers updated to new columns
            fputcsv($handle, ['Student ID', 'Name', 'Email', 'Phone', 'Gender', 'Date of Birth', 'Program', 'Department', 'Enrollment Year']);

            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->student_id,
                    $student->name,
                    $student->email,
                    $student->phone,
                    $student->gender,
                    $student->date_of_birth,
                    $student->program,
                    $student->department,
                    $student->enrollment_year,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $header = fgetcsv($handle); // First row = headers

        while (($row = fgetcsv($handle)) !== false) {
            if (count($header) !== count($row)) {
                continue; // skip invalid rows
            }

            $data = array_combine($header, $row);

            // Adjust keys to lowercase to avoid case mismatch issues
            $data = array_change_key_case($data, CASE_LOWER);

            // Skip if required fields are missing
            if (!isset($data['name'], $data['email'], $data['program'], $data['department'])) {
                continue;
            }

            Student::updateOrCreate(
                ['email' => $data['email']],
                [
                    'student_id' => $data['student_id'] ?? null,
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'program' => $data['program'],
                    'department' => $data['department'],
                    'enrollment_year' => $data['enrollment_year'] ?? null,
                ]
            );
        }

        fclose($handle);

        return redirect()->route('students.index')->with('success', 'Students imported successfully!');
    }
}
