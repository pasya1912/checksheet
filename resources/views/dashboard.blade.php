@section('title', 'Dashboard')
<x-app-layout>
    @section('top-script')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
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
                    <h2>*Status berikut merupakan data dihari ini</h2>
                    <div class="w-full h-max-7xl flex justify-center">
                        <div class="w-11/12 md:w-6/12" id="chart"></div>
                    </div>
                    <div class="w-full h-max-7xl flex lg:justify-between justify-center flex-wrap my-1">

                        @foreach ($daget as $key => $data)
                            <div class="w-6/12 lg:w-3/12 card border my-1 p-2">
                                <div class="header-card text-2xl text-center font-bold">
                                    <span>{{ $data['line'] }}</span>
                                </div>
                                <div class="body-card my-2 w-full">

                                        <div id="chart{{$key}}"></div>

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
                        var options = {
                series: [{
                    name: 'Good',
                    data: {!! json_encode($allArray['status']['ok']) !!},
                    color: '#81C784'
                }, {
                    name: 'NG',
                    data: {!! json_encode($allArray['status']['ng']) !!},
                    color: '#E57373'
                }, {
                    name: 'Revised  ',
                    data: {!! json_encode($allArray['status']['revised']) !!},
                    color: '#E0E0E0'
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: {!! json_encode($allArray['line']) !!},
                },
                yaxis: {
                    title: {
                        text: 'Semua Line'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return  val + " Data"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            @foreach ($daget as $key => $datass)

            var options = {
                series: [{
                    name: 'Good',
                    data: {!! json_encode($daget[$key]['status']['ok']) !!},
                    color: '#81C784'
                }, {
                    name: 'NG',
                    data: {!! json_encode($daget[$key]['status']['ng']) !!},
                    color: '#E57373'
                }, {
                    name: 'Revised  ',
                    data: {!! json_encode($daget[$key]['status']['revised']) !!},
                    color: '#E0E0E0'
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: {!! json_encode($daget[$key]['model']) !!},
                },
                yaxis: {
                    title: {
                        text: '{{$daget[$key]['line']}}'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return  val + " Data"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart{{$key}}"), options);
            chart.render();
            @endforeach
        </script>
    @endsection

</x-app-layout>
