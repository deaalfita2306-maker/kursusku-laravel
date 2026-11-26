<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- BAGIAN KIRI: Logo + Menu -->
            <div class="flex items-center">
                
                <!-- Logo KursusKu -->
                <div class="flex items-center mr-8">
                    <div class="bg-pink-600 text-white font-bold rounded-lg w-8 h-8 flex items-center justify-center mr-2 text-sm">
                        K
                    </div>
                    <span class="font-bold text-lg text-pink-600">KursusKu</span>
                </div>

                <!-- Menu Navigasi -->
                <div class="flex space-x-1">
                    <a href="{{ url('/dashboard') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request()->is('dashboard') ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">
                        Beranda
                    </a>
                    <a href="{{ url('/courses') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request()->is('courses*') ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">
                        Course
                    </a>
                    <a href="{{ url('/participants') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request()->is('participants*') ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">
                        Participant
                    </a>
                    <a href="{{ url('/enrollments') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request()->is('enrollments*') ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">
                        Enrollment
                    </a>
                    <a href="{{ url('/staff') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request()->is('staff*') ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">
                        Akun Staff
                    </a>
                </div>
            </div>

            <!-- BAGIAN KANAN: Admin + Logout -->
            <div class="flex items-center space-x-4">
                
                <!-- Avatar + Nama Admin -->
                <div class="flex items-center">
                    <div class="bg-pink-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">
                        A
                    </div>
                    <span class="text-sm font-medium text-gray-700">Admin KursusKu</span>
                </div>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-pink-600 hover:text-pink-800 font-medium hover:underline">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>