<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">My Applications</h1>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Job Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- This is where your future data will loop --}}
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">
                            You haven't submitted any applications yet. 
                            <a href="{{ route('jobs.index') }}" class="text-green-700 font-medium hover:underline">Start browsing jobs!</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>