@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Post a New Job</h1>
            <p class="text-gray-600">Fill in the details below to post a job opening</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('jobs.store') }}" method="POST" class="p-6">
                @csrf

                <!-- Job Title -->
                <div class="mb-6">
                    <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                    <input type="text" name="job_title" id="job_title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('job_title') border-red-500 @enderror" placeholder="e.g., Senior Developer" required value="{{ old('job_title') }}">
                    @error('job_title')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Description -->
                <div class="mb-6">
                    <label for="job_description" class="block text-sm font-medium text-gray-700 mb-2">Job Description *</label>
                    <textarea name="job_description" id="job_description" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('job_description') border-red-500 @enderror" placeholder="Describe the job position, responsibilities, and what you're looking for..." required>{{ old('job_description') }}</textarea>
                    @error('job_description')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Type -->
                <div class="mb-6">
                    <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">Job Type *</label>
                    <select name="job_type" id="job_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('job_type') border-red-500 @enderror" required>
                        <option value="">Select a job type</option>
                        <option value="full-time" @selected(old('job_type') === 'full-time')>Full-time</option>
                        <option value="part-time" @selected(old('job_type') === 'part-time')>Part-time</option>
                        <option value="contract" @selected(old('job_type') === 'contract')>Contract</option>
                        <option value="temporary" @selected(old('job_type') === 'temporary')>Temporary</option>
                    </select>
                    @error('job_type')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" name="location" id="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror" placeholder="e.g., New York, NY" required value="{{ old('location') }}">
                    @error('location')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salary Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">Minimum Salary</label>
                        <input type="number" name="salary_min" id="salary_min" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('salary_min') border-red-500 @enderror" placeholder="e.g., 50000" value="{{ old('salary_min') }}" min="0" step="1000">
                        @error('salary_min')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-2">Maximum Salary</label>
                        <input type="number" name="salary_max" id="salary_max" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('salary_max') border-red-500 @enderror" placeholder="e.g., 100000" value="{{ old('salary_max') }}" min="0" step="1000">
                        @error('salary_max')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Vacancies -->
                <div class="mb-6">
                    <label for="vacancies" class="block text-sm font-medium text-gray-700 mb-2">Number of Vacancies *</label>
                    <input type="number" name="vacancies" id="vacancies" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vacancies') border-red-500 @enderror" placeholder="e.g., 1" required value="{{ old('vacancies', 1) }}" min="1">
                    @error('vacancies')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requirements -->
                <div class="mb-6">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('requirements') border-red-500 @enderror" placeholder="List the skills and qualifications required for this position...">{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Closing Date -->
                <div class="mb-6">
                    <label for="closing_date" class="block text-sm font-medium text-gray-700 mb-2">Application Closing Date</label>
                    <input type="date" name="closing_date" id="closing_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('closing_date') border-red-500 @enderror" value="{{ old('closing_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('closing_date')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Post Job</button>
                    <a href="{{ route('employer.dashboard') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
