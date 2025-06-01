<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <!-- User Profile Card (1/3 of the row) -->
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded h-100">
                        <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="w-75 h-75 bg-light rounded-circle overflow-hidden mb-3 d-flex align-items-center justify-content-center border border-primary border-2" style="width: 96px; height: 96px;">
                                @if (auth()->user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                        alt="{{ auth()->user()->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <svg class="w-50 h-50 text-secondary" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                @endif
                            </div>
                            <h3 class="h5 fw-medium text-dark mb-1">{{ auth()->user()->name }}</h3>
                            <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                            <a href="{{ route('profile.edit') }}"
                                class="btn btn-outline-primary btn-sm d-flex align-items-center mt-auto">
                                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                {{ __('Edit Profile') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Submission Statistics Card (2/3 of the row) -->
                <div class="col-12 col-md-8">
                    <div class="card shadow-sm border-0 rounded h-100">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-medium text-dark mb-4">{{ __('Submission Statistics') }}</h3>
                            <div class="row align-items-start">
                                <div class="col-12 col-lg-6 d-flex justify-content-center mb-4 mb-lg-0">
                                    <div style="width: 300px; height: 300px;">
                                        <canvas id="submissionChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    @php
                                        $submissions = auth()->user()->submissions;
                                        $total = $submissions->count();
                                        $accepted = $submissions->where('status', 'Accepted')->count();
                                        $wrongAnswer = $submissions->where('status', 'Wrong Answer')->count();
                                        $runtimeError = $submissions->where('status', 'Runtime Error')->count();
                                        $compilationError = $submissions->where('status', 'Compilation Error')->count();
                                        $other = $total - $accepted - $wrongAnswer - $runtimeError - $compilationError;
                                    @endphp
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="d-inline-block w-3 h-3 bg-success rounded-circle me-3"></span>
                                            <span class="text-muted small">Accepted: <span
                                                    class="fw-medium text-dark">{{ $accepted }}</span>
                                                ({{ $total > 0 ? round(($accepted / $total) * 100) : 0 }}%)</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="d-inline-block w-3 h-3 bg-danger rounded-circle me-3"></span>
                                            <span class="text-muted small">Wrong Answer: <span
                                                    class="fw-medium text-dark">{{ $wrongAnswer }}</span>
                                                ({{ $total > 0 ? round(($wrongAnswer / $total) * 100) : 0 }}%)</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="d-inline-block w-3 h-3 bg-warning rounded-circle me-3"></span>
                                            <span class="text-muted small">Runtime Error: <span
                                                    class="fw-medium text-dark">{{ $runtimeError }}</span>
                                                ({{ $total > 0 ? round(($runtimeError / $total) * 100) : 0 }}%)</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="d-inline-block w-3 h-3 bg-orange rounded-circle me-3" style="background-color: #f97316;"></span>
                                            <span class="text-muted small">Compilation Error: <span
                                                    class="fw-medium text-dark">{{ $compilationError }}</span>
                                                ({{ $total > 0 ? round(($compilationError / $total) * 100) : 0 }}%)</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="d-inline-block w-3 h-3 bg-secondary rounded-circle me-3"></span>
                                            <span class="text-muted small">Other: <span
                                                    class="fw-medium text-dark">{{ $other }}</span>
                                                ({{ $total > 0 ? round(($other / $total) * 100) : 0 }}%)</span>
                                        </li>
                                        <li class="pt-3 mt-3 border-top">
                                            <span class="text-muted small fw-medium">Total Submissions:
                                                {{ $total }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Submissions Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-medium text-dark mb-4">{{ __('Recent Submissions') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                            <tr class="bg-gray-100">
                                            <th class="w-1/12 py-2 px-4 text-left">#</th>
                                            <th class="w-3/12 py-2 px-4 text-left">Problem</th>
                                            <th class="w-1/12 py-2 px-4 text-left">Language</th>
                                            <th class="w-3/12 py-2 px-4 text-left">Status</th>
                                            <th class="w-2/12 py-2 px-4 text-left">Submitted</th>
                                            <th class="w-1/12 py-2 px-4 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse (auth()->user()->submissions->sortByDesc('created_at')->take(5) as $submission)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-2 px-4">{{ $submission->id }}</td>
                                                <td class="py-2 px-4">
                                                    <a href="{{ route('problems.show', $submission->problem) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                                        {{ $submission->problem->title }}
                                                    </a>
                                                </td>
                                                <td class="py-2 px-4">{{ $submission->getLanguageName() }}</td>
                                                <td class="py-2 px-4">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                            ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            @if(str_starts_with($submission->status, 'Compilation Error'))
                                                                Compilation Error
                                                            @elseif(strlen($submission->status) > 50)
                                                                {{ substr($submission->status, 0, 50) }}...
                                                            @else
                                                                {{ $submission->status }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-4">{{ $submission->created_at->diffForHumans() }}</td>
                                                <td class="py-2 px-4 text-center">
                                                    <a href="{{ route('submissions.show', $submission) }}" 
                                                        class="text-xs text-blue-600 hover:text-blue-900 hover:underline">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                                                    {{ __('No submissions yet.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="mt-4 text-center">
                                    <a href="{{ route('submissions.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View All Submissions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .w-3 { width: 12px; }
            .h-3 { height: 12px; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('submissionChart').getContext('2d');
                
                const data = {
                    labels: ['Accepted', 'Wrong Answer', 'Runtime Error', 'Compilation Error', 'Other'],
                    datasets: [{
                        data: [{{ $accepted }}, {{ $wrongAnswer }}, {{ $runtimeError }}, {{ $compilationError }}, {{ $other }}],
                        backgroundColor: [
                            '#22c55e', // green (Bootstrap success)
                            '#ef4444', // red (Bootstrap danger)
                            '#eab308', // yellow (Bootstrap warning)
                            '#f97316', // orange
                            '#6b7280'  // gray (Bootstrap secondary)
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                };
                
                new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                bodyFont: {
                                    size: 12
                                },
                                padding: 10
                            }
                        },
                        layout: {
                            padding: 0
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>