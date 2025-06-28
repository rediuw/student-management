<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Students List') }}
            </h2>
            <div>
                <a href="{{ route('students.create') }}" class="px-4 py-2 bg-blue-600 text-gray-800 rounded hover:bg-blue-700">
                    Add New Student
                </a>
                <a href="{{ route('students.export') }}" class="ml-2 px-4 py-2 bg-green-600 text-gray-800 rounded hover:bg-green-700">
                    Export CSV
                </a>
                <!-- CSV Import Form -->
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="inline-block mr-4">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv"
                        class="text-sm text-gray-700 dark:text-gray-200 file:bg-blue-600 file:text-white file:rounded file:px-3 file:py-1">
                    <button type="submit"
                        class="bg-green-600 text-gray-700 px-3 py-2 rounded hover:bg-green-700 mt-2 sm:mt-0">Import CSV</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Search and Filter --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('students.index') }}">
                    <div class="flex space-x-2">
                        <input type="text" name="search" placeholder="Search by name, program, or department"
                            value="{{ $search ?? '' }}"
                            class="flex-grow px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring dark:bg-gray-700 dark:text-white dark:border-gray-600">

                        <select name="department" class="px-8 py-3 rounded border border-gray-300 focus:outline-none focus:ring dark:bg-gray-700 dark:text-white dark:border-gray-600">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department }}" {{ (isset($departmentFilter) && $departmentFilter == $department) ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                            @endforeach
                        </select>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-gray-800 rounded hover:bg-blue-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @if($students->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @php
                            function sortArrow($field, $sortField, $sortDirection) {
                            if ($field !== $sortField) return '';
                            return $sortDirection === 'asc' ? '▲' : '▼';
                            }
                            function newDirection($field, $sortField, $sortDirection) {
                            if ($field !== $sortField) return 'asc';
                            return $sortDirection === 'asc' ? 'desc' : 'asc';
                            }
                            @endphp
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('students.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => newDirection('name', $sortField ?? '', $sortDirection ?? '')])) }}">
                                    Name {!! sortArrow('name', $sortField ?? '', $sortDirection ?? '') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('students.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => newDirection('email', $sortField ?? '', $sortDirection ?? '')])) }}">
                                    Email {!! sortArrow('email', $sortField ?? '', $sortDirection ?? '') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Gender
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('students.index', array_merge(request()->all(), ['sort' => 'program', 'direction' => newDirection('program', $sortField ?? '', $sortDirection ?? '')])) }}">
                                    Program {!! sortArrow('program', $sortField ?? '', $sortDirection ?? '') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('students.index', array_merge(request()->all(), ['sort' => 'department', 'direction' => newDirection('department', $sortField ?? '', $sortDirection ?? '')])) }}">
                                    Department {!! sortArrow('department', $sortField ?? '', $sortDirection ?? '') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('students.index', array_merge(request()->all(), ['sort' => 'enrollment_year', 'direction' => newDirection('enrollment_year', $sortField ?? '', $sortDirection ?? '')])) }}">
                                    Enrollment Year {!! sortArrow('enrollment_year', $sortField ?? '', $sortDirection ?? '') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->phone ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->gender ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->program ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->department ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->enrollment_year ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $students->links() }}
            </div>
            @else
            <p class="text-center text-gray-500 dark:text-gray-400">No students found. Click "Add New Student" to get started.</p>
            @endif
        </div>
    </div>
</x-app-layout>