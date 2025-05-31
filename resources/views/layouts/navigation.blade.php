<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                       <img src="{{ asset('storage/logo/header_logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" style="height: 142px; width: auto; display: block;">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                        {{ __('Leaderboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('problems.list')" :active="request()->routeIs('problems.list')">
                        {{ __('Problems') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contests.index')" :active="request()->routeIs('contests.*')">
                        {{ __('Contests') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->isAdmin())
                        <!-- Admin Only Links -->
                        <x-nav-link :href="route('admin.problems.index')" :active="request()->routeIs('admin.problems.*')">
                            {{ __('Manage Problems') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.problems.create')" :active="request()->routeIs('admin.problems.create')">
                            {{ __('Upload Problem') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->isContestant())
                        <!-- Contestant Only Links -->
                        <x-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.index')">
                            {{ __('My Submissions') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <div>
                        <button onclick="document.getElementById('userDropdown').style.display = document.getElementById('userDropdown').style.display === 'none' ? 'block' : 'none'" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>

                        </button>
                    </div>

                    <div id="userDropdown" 
                         style="display: none;"
                         class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right">
                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                            <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                {{ __('Edit Profile') }}
                            </a>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                {{ __('Leaderboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('problems.list')" :active="request()->routeIs('problems.list')">
                {{ __('Problems') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contests.index')" :active="request()->routeIs('contests.*')">
                {{ __('Contests') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->isAdmin())
                <!-- Admin Only Links (Mobile) -->
                <x-responsive-nav-link :href="route('admin.problems.index')" :active="request()->routeIs('admin.problems.*')">
                    {{ __('Manage Problems') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.problems.create')" :active="request()->routeIs('admin.problems.create')">
                    {{ __('Upload Problem') }}
                </x-responsive-nav-link>
            @endif
            
            @if(Auth::user()->isContestant())
                <!-- Contestant Only Links (Mobile) -->
                <x-responsive-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.index')">
                    {{ __('My Submissions') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Edit Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
