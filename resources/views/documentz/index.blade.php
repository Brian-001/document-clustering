<!DOCTYPE html>
<html lang="en" x-data="{ isUploading: false, selectedFile: null, isTableHover: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Upload and cluster resumes or CVs in PDF or DOCX format to analyze and group by content similarity.">
    <meta name="keywords" content="document clustering, resume analysis, file upload, Laravel, Tailwind CSS">
    <meta name="author" content="Your Company Name">
    <title>Resume Clustering - Upload & Analyze</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-gray-900">Resume Clustering</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('documentz.index') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Home</a>
                        <a href="#" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">About</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <header class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 sm:p-10">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white text-center tracking-tight">Resume Clustering</h1>
                    <p class="text-blue-100 text-center mt-3 text-base sm:text-lg max-w-2xl mx-auto">Upload PDF or DOCX resumes to analyze and group by content similarity, powered by advanced text analysis.</p>
                </header>

                <!-- Content -->
                <section class="p-6 sm:p-8">
                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-lg flex items-center transition-opacity duration-300 ease-in-out" role="alert" tabindex="0" aria-live="polite" x-data="{ show: true }" x-show="show" x-transition>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                            <button class="ml-auto text-green-700 hover:text-green-900" @click="show = false" aria-label="Close alert">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-lg transition-opacity duration-300 ease-in-out" role="alert" tabindex="0" aria-live="polite" x-data="{ show: true }" x-show="show" x-transition>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button class="ml-auto text-red-700 hover:text-red-900" @click="show = false" aria-label="Close error alert">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('documentz.upload') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-4 space-y-4 sm:space-y-0">
                            <div class="relative flex-1">
                                <label for="document" class="block text-sm font-medium text-gray-700 mb-2 sr-only">Upload Resume or CV</label>
                                <input 
                                    type="file" 
                                    name="document" 
                                    id="document" 
                                    accept=".pdf,.docx" 
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-3 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200"
                                    @change="selectedFile = $event.target.files[0] ? $event.target.files[0].name : null"
                                    aria-describedby="file-help"
                                    required
                                >
                                <p id="file-help" class="mt-2 text-xs text-gray-500">Supported formats: PDF, DOCX (Max 2MB)</p>
                                <div x-show="selectedFile" class="mt-2 text-sm text-gray-600 flex items-center" x-transition>
                                    {{-- <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg> --}}
                                    {{-- <span>Selected: <span x-text="selectedFile" class="font-medium"></span></span> --}}
                                </div>
                            </div>
                            <button 
                                type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-in-out z-10"
                                :disabled="isUploading"
                                x-text="isUploading ? 'Uploading...' : 'Upload Resume/CV'"
                                x-bind:class="{ 'opacity-50 cursor-not-allowed': isUploading }"
                                aria-label="Upload resume or CV"
                            >
                            Submit application
                            </button>
                        </div>
                    </form>

                    <!-- Document Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-4 text-left text-sm font-semibold text-gray-700 sm:min-w-[200px]">Document Name</th>
                                    <th class="p-4 text-left text-sm font-semibold text-gray-700">Match %</th>
                                    <th class="p-4 text-left text-sm font-semibold text-gray-700">Group</th>
                                    <th class="p-4 text-left text-sm font-semibold text-gray-700">Matched Keywords</th>
                                </tr>
                            </thead>
                            <tbody x-data="{ hoveredRow: null }">
                                @forelse ($documents as $index => $document)
                                    <tr 
                                        class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150"
                                        @mouseover="hoveredRow = {{ $index }}"
                                        @mouseleave="hoveredRow = null"
                                    >
                                        <td class="p-4 text-gray-900 text-sm sm:text-base">{{ $document->name }}</td>
                                        <td class="p-4 text-gray-900 text-sm sm:text-base">{{ number_format($document->match_percentage, 2) }}%</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->group === 'Group A' ? 'bg-green-100 text-green-800' : ($document->group === 'Group B' ? 'bg-blue-100 text-blue-800' : ($document->group === 'Group C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }} transition-colors duration-150">
                                                {{ $document->group }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-gray-900">
                                            @if ($document->matched_keywords)
                                                <div class="flex flex-wrap gap-2" x-data="{ tooltip: false }" @mouseover="tooltip = true" @mouseleave="tooltip = false">
                                                    @foreach ($document->matched_keywords as $keyword)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors duration-150">
                                                            {{ $keyword }}
                                                        </span>
                                                    @endforeach
                                                    
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">None</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-gray-500 text-sm sm:text-base">No resumes uploaded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="p-4">
                            {{ $documents->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Resume Clustering. All rights reserved.</p>
                <p class="mt-2">Built with <a href="https://laravel.com" class="underline hover:text-blue-300 transition-colors">Laravel</a> & <a href="https://tailwindcss.com" class="underline hover:text-blue-300 transition-colors">Tailwind CSS</a>.</p>
            </div>
        </footer>
    </div>

    <!-- Accessibility: Focus management for screen readers -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.querySelector('[role="alert"]');
            if (successAlert) {
                successAlert.focus();
            }
        });
    </script>
</body>
</html>