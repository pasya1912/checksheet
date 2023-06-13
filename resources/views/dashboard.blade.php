@section('title', 'Dashboard')
<x-app-layout>
    @section('top-script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endsection
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-4xl font-bold text-center">AIIA</h1>
                    <h2 class="text-xs sm:text-sm lg:text-md">*Data berikut ini merupakan data dari tanggal {{startOfDay()}} sampai {{endOfDay()}}</h2>
                    <div class="w-full h-max-7xl flex flex-col justify-center  my-5">
                        <div class="text-xl text-center font-bold">
                            <span>Resume Chart</span>
                        </div>
                        <div class=" w-full">
                            <div class="w-6/12 h-6/12 m-auto p-0">
                                <canvas id="chart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-max-7xl flex lg:justify-between justify-center flex-wrap my-1">

                        @foreach ($daget as $key => $data)
                            <div class="w-6/12 lg:w-3/12 border card my-1 p-2">
                                <div class="header-card text-xl text-center font-bold">
                                    <span>{{ $data['line'] }}</span>
                                </div>
                                <div class="body-card my-2 w-full">
                                    <canvas class="w-8/12 mx-auto" id="chart{{ $key }}"></canvas>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script>
            var data = {
                labels: ['OK', 'NG', 'Solved'],
                datasets: [{
                    data: [{{ $allArray['status']['ok'] }}, {{ $allArray['status']['ng'] }},
                        {{ $allArray['status']['revised'] }}
                    ],
                    backgroundColor: ['#4CAF50', 'red', 'blue'], // Set the colors of the pie slices
                }]
            };

            // Configure the options for the pie chart

            // Create the pie chart
            var ctx = document.getElementById('chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {

                                labelTextColor: function(context) {
                                    return '#FFFFFF';
                                }
                            }
                        }
                    }
                }
            });
        </script>
        <script>
            @foreach ($daget as $key => $data)
                var data = {
                    labels: ['OK', 'NG', 'Solved'],
                    datasets: [{
                        data: [{{ $data['status']['ok'] }}, {{ $data['status']['ng'] }},
                            {{ $data['status']['revised'] }}
                        ],
                        backgroundColor: ['#4CAF50', 'red', 'blue'], // Set the colors of the pie slices
                    }]
                };

                // Configure the options for the pie chart

                // Create the pie chart
                var ctx = document.getElementById('chart{{$key}}').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {

                                    labelTextColor: function(context) {
                                        return '#FFFFFF';
                                    }
                                }
                            }
                        }
                    }
                });
            @endforeach
        </script>
    @endsection
</x-app-layout>
