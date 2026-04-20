<nav class="w-auto h-auto border-b border-gray-200 bg-white md:w-[250px] md:h-screen md:border-r md:border-b-0 md:flex md:flex-col">

    {{-- Application logo --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <x-application-logo class="w-auto h-6 mr-2 text-gray-800 fill-current" />
            <span class="text-lg font-semibold text-gray-800">Shaghalni</span>
        </a>

        {{-- Hamburger Button: mobile only --}}
        <button class="md:hidden" onclick="document.getElementById('nav-menu').classList.toggle('hidden')">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Navigation links --}}
    <ul id="nav-menu" class="flex-col flex-1 hidden px-4 py-6 space-y-2 md:flex ">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-nav-link>

        @if (auth()->user()->role === 'admin')
            <x-nav-link :href="route('company.index')" :active="request()->routeIs('company.index')">
                Companies
            </x-nav-link>
        @endif

        @if (auth()->user()->role === 'company_owner')
            <x-nav-link :href="route('my-company.show')" :active="request()->routeIs('my-company.show')">
                My Company
            </x-nav-link>
        @endif

        <x-nav-link :href="route('job_application.index')" :active="request()->routeIs('job_application.index')">
            Job Application
        </x-nav-link>

        @if (auth()->user()->role === 'admin')
            <x-nav-link :href="route('job_category.index')" :active="request()->routeIs('job_category.index')">
                Job Categories
            </x-nav-link>
        @endif

        <x-nav-link :href="route('job_vacancy.index')" :active="request()->routeIs('job_vacancy.index')">
            Job Vacancies
        </x-nav-link>

        @if (auth()->user()->role === 'admin')
            <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                Users
            </x-nav-link>
        @endif


        <hr>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <x-nav-link :href="route('logout')" :active="false" class="text-red-500" style="color: red;" onclick="event.preventDefault(); this.closest('form').submit();">
                Logout
            </x-nav-link>
            {{--
                1- المستخدم يضغط Logout
                2- preventDefault() يمنع فتح الرابط
                3- closest('form') يجيب الفورم الأب
                4- submit() يرسل الفورم
            --}}
        </form>
    </ul>
</nav>
