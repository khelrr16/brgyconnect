<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Administration
                        </p>

                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                            Admin Dashboard
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Overview of residents and barangay population data.
                        </p>

                    </div>


                    <div
                        class="inline-flex items-center gap-2 self-start rounded-lg
                               border border-gray-200 bg-gray-50 px-3 py-2
                               text-sm text-gray-600 sm:self-auto"
                    >
                        <i class="fa-solid fa-calendar-days text-indigo-600"></i>

                        {{ now()->format('F d, Y') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- STAT CARDS --}}
            {{-- ================================================= --}}

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">


                {{-- Total --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white
                           p-5 shadow-sm"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Total Residents
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($totalResidents) }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-users"></i>
                        </div>

                    </div>

                </div>


                {{-- Male --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white
                           p-5 shadow-sm"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Male
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($maleResidents) }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-blue-50 text-blue-600"
                        >
                            <i class="fa-solid fa-mars"></i>
                        </div>

                    </div>

                </div>


                {{-- Female --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white
                           p-5 shadow-sm"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Female
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($femaleResidents) }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-pink-50 text-pink-600"
                        >
                            <i class="fa-solid fa-venus"></i>
                        </div>

                    </div>

                </div>


                {{-- Minors --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white
                           p-5 shadow-sm"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Minors
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($minorResidents) }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-amber-50 text-amber-600"
                        >
                            <i class="fa-solid fa-child"></i>
                        </div>

                    </div>

                </div>


                {{-- Seniors --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white
                           p-5 shadow-sm"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Senior Citizens
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($seniorResidents) }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-emerald-50 text-emerald-600"
                        >
                            <i class="fa-solid fa-person-cane"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CHART ROW 1 --}}
            {{-- ================================================= --}}

            <div class="mt-6 grid gap-6 lg:grid-cols-3">


                {{-- ================================================= --}}
                {{-- RESIDENT GROWTH --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm lg:col-span-2"
                >

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="font-bold text-gray-900">
                                Resident Registrations
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                New residents registered during the last 12 months.
                            </p>

                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center
                                   rounded-lg bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-chart-line"></i>
                        </div>

                    </div>


                    <div class="mt-6 h-72">
                        <canvas id="residentGrowthChart"></canvas>
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- SEX DISTRIBUTION --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm"
                >

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="font-bold text-gray-900">
                                Sex Distribution
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Resident population by sex.
                            </p>

                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center
                                   rounded-lg bg-pink-50 text-pink-600"
                        >
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>

                    </div>


                    <div class="mt-5 h-64">
                        <canvas id="sexChart"></canvas>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CHART ROW 2 --}}
            {{-- ================================================= --}}

            <div class="mt-6 grid gap-6 lg:grid-cols-2">


                {{-- ================================================= --}}
                {{-- AGE DISTRIBUTION --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm"
                >

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="font-bold text-gray-900">
                                Age Distribution
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Residents grouped by age.
                            </p>

                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center
                                   rounded-lg bg-amber-50 text-amber-600"
                        >
                            <i class="fa-solid fa-chart-column"></i>
                        </div>

                    </div>


                    <div class="mt-6 h-72">
                        <canvas id="ageChart"></canvas>
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CIVIL STATUS --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm"
                >

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="font-bold text-gray-900">
                                Civil Status
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Distribution of residents by civil status.
                            </p>

                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center
                                   rounded-lg bg-emerald-50 text-emerald-600"
                        >
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>

                    </div>


                    <div class="mt-6 h-72">
                        <canvas id="civilStatusChart"></canvas>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- QUICK MANAGEMENT --}}
            {{-- ================================================= --}}

            <div class="mt-6">

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm"
                >

                    <div class="mb-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Management
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-gray-900">
                            Resident Management
                        </h2>

                    </div>


                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

                        <a
                            href="{{ route('admin.residents.index') }}"
                            class="group flex items-center gap-3 rounded-lg
                                   border border-gray-200 p-4 transition
                                   hover:border-indigo-200 hover:bg-indigo-50/50"
                        >

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-indigo-50 text-indigo-600
                                       transition group-hover:bg-indigo-600
                                       group-hover:text-white"
                            >
                                <i class="fa-solid fa-users"></i>
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Residents
                                </p>

                                <p class="text-xs text-gray-500">
                                    Manage resident records
                                </p>

                            </div>

                        </a>


                        <a
                            href="{{ route('admin.verifications.index') }}"
                            class="group flex items-center gap-3 rounded-lg
                                   border border-gray-200 p-4 transition
                                   hover:border-indigo-200 hover:bg-indigo-50/50"
                        >

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-amber-50 text-amber-600
                                       transition group-hover:bg-amber-600
                                       group-hover:text-white"
                            >
                                <i class="fa-solid fa-user-check"></i>
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Verification
                                </p>

                                <p class="text-xs text-gray-500">
                                    Review account requests
                                </p>

                            </div>

                        </a>


                        <a
                            href="{{ route('admin.posts.index') }}"
                            class="group flex items-center gap-3 rounded-lg
                                   border border-gray-200 p-4 transition
                                   hover:border-indigo-200 hover:bg-indigo-50/50"
                        >

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-emerald-50 text-emerald-600
                                       transition group-hover:bg-emerald-600
                                       group-hover:text-white"
                            >
                                <i class="fa-solid fa-newspaper"></i>
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    CMS Posts
                                </p>

                                <p class="text-xs text-gray-500">
                                    Manage news and announcements
                                </p>

                            </div>

                        </a>


                        <a
                            href="{{ route('profile.edit') }}"
                            class="group flex items-center gap-3 rounded-lg
                                   border border-gray-200 p-4 transition
                                   hover:border-indigo-200 hover:bg-indigo-50/50"
                        >

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-gray-100 text-gray-600
                                       transition group-hover:bg-gray-600
                                       group-hover:text-white"
                            >
                                <i class="fa-solid fa-gear"></i>
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Settings
                                </p>

                                <p class="text-xs text-gray-500">
                                    Manage your account
                                </p>

                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- CHART.JS --}}
    {{-- ================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | DATA FROM LARAVEL
        |--------------------------------------------------------------------------
        */

        const sexLabels = @json(
            $sexDistribution->pluck('sex')
        );

        const sexData = @json(
            $sexDistribution->pluck('total')
        );


        const ageLabels = @json(
            array_keys($ageGroups)
        );

        const ageData = @json(
            array_values($ageGroups)
        );


        const civilStatusLabels = @json(
            $civilStatusDistribution->pluck('civil_status')
        );

        const civilStatusData = @json(
            $civilStatusDistribution->pluck('total')
        );


        const registrationLabels = @json(
            $monthlyRegistrations->pluck('month')
        );

        const registrationData = @json(
            $monthlyRegistrations->pluck('total')
        );


        /*
        |--------------------------------------------------------------------------
        | COMMON OPTIONS
        |--------------------------------------------------------------------------
        */

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    labels: {
                        usePointStyle: true,
                        padding: 16
                    }
                }
            }
        };


        /*
        |--------------------------------------------------------------------------
        | RESIDENT GROWTH
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('residentGrowthChart'),
            {
                type: 'line',

                data: {
                    labels: registrationLabels,

                    datasets: [
                        {
                            label: 'New Residents',
                            data: registrationData,

                            fill: true,

                            tension: 0.35,

                            borderWidth: 2,

                            pointRadius: 4,

                            pointHoverRadius: 6
                        }
                    ]
                },

                options: {
                    ...commonOptions,

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SEX
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('sexChart'),
            {
                type: 'doughnut',

                data: {
                    labels: sexLabels,

                    datasets: [
                        {
                            data: sexData,

                            borderWidth: 2
                        }
                    ]
                },

                options: {
                    ...commonOptions,

                    cutout: '65%'
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | AGE
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('ageChart'),
            {
                type: 'bar',

                data: {
                    labels: ageLabels,

                    datasets: [
                        {
                            label: 'Residents',
                            data: ageData,

                            borderRadius: 6,

                            borderWidth: 0
                        }
                    ]
                },

                options: {
                    ...commonOptions,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        y: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        }

                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CIVIL STATUS
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('civilStatusChart'),
            {
                type: 'doughnut',

                data: {
                    labels: civilStatusLabels,

                    datasets: [
                        {
                            data: civilStatusData,

                            borderWidth: 2
                        }
                    ]
                },

                options: {
                    ...commonOptions,

                    cutout: '60%'
                }
            }DD
        );

    </script>

</x-app-layout>