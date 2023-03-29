<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="py-5 text-lg">
                <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a href="{{route('checksheet.area',['id'=>$data->id_checksheet])}}">&laquo; Back</a></span>
                </div>
                <div class="flex justify-between">
                    <div class="text-sm">
                        Model : {{$data->code}}
                    </div>
                    <div class="text-sm">
                        {{$data->nama_checksheet}}
                    </div>
                    <div class="text-sm">
                        Line : {{$data->line}}
                    </div>
                </div>
                <div class="text-center text-xl m-5">
                    <h2>{{$data->nama}}</h2>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</x-app-layout>
