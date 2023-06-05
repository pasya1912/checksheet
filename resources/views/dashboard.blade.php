@section('title', 'Dashboard')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <x-slot name="daget">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-4xl font-bold text-center">AIIA</h1>
                        <h2>*Status berikut merupakan data dihari ini</h2>
                        <div class="w-full h-max-7xl flex lg:justify-between justify-center flex-wrap my-1">
                            @foreach ($daget as $data)
                                <div class="w-6/12 lg:w-3/12 card border my-1 p-2">
                                    <div class="header-card text-2xl text-center font-bold">
                                        <span>{{ $data['line'] }}</span>
                                    </div>
                                    <div class="body-card my-2 w-full">
                                        @foreach ($data['model'] as $model)
                                            <div class="my-1 w-full flex justify-between"
                                                id="status-{{ $model['model'] }}"><span>{{ $model['model'] }}</span>
                                                <div class="flex w-full gap-2 justify-end"><span
                                                        class=" bg-green-300 px-2 py-0.5 rounded-md"
                                                        id="good-{{ $model['model'] }}">{{ $model['status']['good'] }}</span><span
                                                        class=" bg-red-300 px-2 py-0.5 rounded-md"
                                                        id="ng-{{ $model['model'] }}">{{ $model['status']['notgood'] }}</span><span
                                                        class=" bg bg-gray-300 px-2 py-0.5 rounded-md"
                                                        id="revisi-{{ $model['model'] }}">{{ $model['status']['revisi'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-app-layout>
