@props(['contest'])

<nav class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-12">
            <div class="flex space-x-8">
                <x-nav-link :href="route('contests.show', $contest)" :active="request()->routeIs('contests.show', $contest)">
                    {{ __('Overview') }}
                </x-nav-link>
                
                <x-nav-link :href="route('contests.problems.index', $contest)" :active="request()->routeIs('contests.problems.*', $contest)">
                    {{ __('Problems') }}
                </x-nav-link>

                <x-nav-link :href="route('contests.submissions', $contest)" :active="request()->routeIs('contests.submissions', $contest)">
                    {{ __('Submissions') }}
                </x-nav-link>

                <x-nav-link :href="route('contests.standings', $contest)" :active="request()->routeIs('contests.standings', $contest)">
                    {{ __('Standings') }}
                </x-nav-link>

                @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('contests.edit', $contest)" :active="request()->routeIs('contests.edit', $contest)">
                        {{ __('Edit Contest') }}
                    </x-nav-link>
                @endif
            </div>
        </div>
    </div>
</nav> 