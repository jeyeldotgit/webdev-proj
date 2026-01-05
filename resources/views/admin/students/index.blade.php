@extends('layouts.app')

@section('title', 'Manage Students - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-neutral-900">Manage Students</h1>
        <a href="{{ route('admin.students.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
            + Add Student
        </a>
    </div>

    @if($students->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Enrollments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($students as $student)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-900">{{ $student->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-600">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-600">{{ $student->enrollments_count }} courses</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-600">{{ $student->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.students.show', $student) }}" class="text-primary-600 hover:text-primary-900 mr-4">View</a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="text-neutral-600 hover:text-neutral-900 mr-4">Edit</a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
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
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $students->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No students yet</h3>
            <p class="text-neutral-600 mb-6">Add your first student to get started</p>
            <a href="{{ route('admin.students.create') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                Add Student
            </a>
        </div>
    @endif
</div>
@endsection

