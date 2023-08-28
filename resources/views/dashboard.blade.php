<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div id="alert-wrapper">
        @if (session()->has('message'))
            <div class="alert alert-success mb-10 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                {{ session('message') }}
            </div>
        @endif
        </div>
        

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- btn for fake data generation --}}
                <a href="{{ route('dashboard.generate-fake-data') }}" class="button" style="background-color: blue; color: white; padding: 15px 25px;text-decoration: none;">Generate fake data</a>
                {{-- render streamer stats here: summary + notifications table --}}
                <x-streamer-stats />
            </div>
        </div>
    </div>
        <script type="text/javascript">
            $(document).ready(function(){
                setTimeout(function(){ 
                    $("#alert-wrapper").fadeOut('slow');
                }, 1500); // 3 secs
            });
        </script>
</x-app-layout>


