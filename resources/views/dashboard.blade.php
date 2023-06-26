@section('title', 'Dashboard')
<x-app-layout>
    @section('top-script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
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
                    <div class="flex w-full max-h-56 justify-between">
                        <div class=" w-8/12 flex items-center">
                            <h2 class="text-xs sm:text-sm lg:text-md">*Data berikut ini merupakan data dari tanggal
                                {{ startOfDay() }} sampai {{ endOfDay() }}</h2>
                        </div>
                        <form class="w-full md:w-5/12 lg:w-2/12" action="{{ url()->current() }}" method="GET"
                            id="formTanggal">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-input block w-full"
                                max="{{ date('Y-m-d') }}" value="{{ request()->get('tanggal') }}">
                        </form>
                    </div>
                    <div class="w-full h-max-7xl flex flex-col justify-center  my-5">
                        <div class="text-xl text-center font-bold">
                            <span>Resume Chart</span>
                        </div>
                        <div class=" w-full">
                            <div class="w-6/12 lg:w-4/12 h-6/12 m-auto p-0">
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
            Chart.register(ChartDataLabels);

            let tggl = document.getElementById('tanggal');
            //onchange submit
            tggl.addEventListener('change', function() {
                document.getElementById('formTanggal').submit();
            });
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
            var allChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        datalabels: {
                                formatter: (value, context) => {
                                    var hiddens = context.chart._hiddenIndices;
                                    var total = 0;
                                    var datapoints = context.dataset.data;
                                    datapoints.forEach((val, i) => {
                                        if (hiddens[i] != undefined) {
                                            if (!hiddens[i]) {
                                                total += val;
                                            }
                                        } else {
                                            total += val;
                                        }
                                    });
                                    var percent = (value / total * 100).toFixed(2);
                                    var percentage = percent + '%';
                                    if (percentage == 'NaN%' || percentage == '0.00%' || percent < 5) {
                                        percentage = '';
                                    }
                                    return percentage;
                                },
                                //set bold
                                font: {
                                    weight: 'bold',
                                    size: 14,
                                },
                                color: '#fff',
                            },
                            tooltip: {

                                callbacks: {
                                    label: function(context) {
                                        var hiddens = context.chart._hiddenIndices;
                                        var total = 0;
                                        var datapoints = context.dataset.data;
                                        datapoints.forEach((val, i) => {
                                            if (hiddens[i] != undefined) {
                                                if (!hiddens[i]) {
                                                    total += val;
                                                }
                                            } else {
                                                total += val;
                                            }
                                        });
                                        var percent = ((context.raw / total) * 100.0).toFixed(2);
                                        var percentage = percent + '%';
                                        return " " + context.raw + " ( " + percentage + " )";
                                    },
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
                var ctx = document.getElementById('chart{{ $key }}').getContext('2d');
                var charter = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        plugins: {
                            datalabels: {
                                formatter: (value, context) => {
                                    var hiddens = context.chart._hiddenIndices;
                                    var total = 0;
                                    var datapoints = context.dataset.data;
                                    datapoints.forEach((val, i) => {
                                        if (hiddens[i] != undefined) {
                                            if (!hiddens[i]) {
                                                total += val;
                                            }
                                        } else {
                                            total += val;
                                        }
                                    });
                                    var percent = (value / total * 100).toFixed(2);
                                    var percentage = percent + '%';
                                    if (percentage == 'NaN%' || percentage == '0.00%' || percent < 5) {
                                        percentage = '';
                                    }
                                    return percentage;
                                },
                                //set bold
                                font: {
                                    weight: 'bold',
                                    size: 14,
                                },
                                color: '#fff',
                            },
                            tooltip: {

                                callbacks: {
                                    label: function(context) {
                                        var hiddens = context.chart._hiddenIndices;
                                        var total = 0;
                                        var datapoints = context.dataset.data;
                                        datapoints.forEach((val, i) => {
                                            if (hiddens[i] != undefined) {
                                                if (!hiddens[i]) {
                                                    total += val;
                                                }
                                            } else {
                                                total += val;
                                            }
                                        });
                                        var percent = ((context.raw / total) * 100.0).toFixed(2);
                                        var percentage = percent + '%';
                                        return " " + context.raw + " ( " + percentage + " )";
                                    },
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
