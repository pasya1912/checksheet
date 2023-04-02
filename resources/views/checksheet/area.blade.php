<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check Area Input') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="py-5 text-lg">
                    <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a href="{{route('checksheet.list')}}?{{request()->getQueryString()}}">&laquo; Back</a></span>
                </div>
                <div class="flex justify-between">
                    <div>
                        Model : {{ $checksheet->code }}
                    </div>
                    <div>
                        Line : {{ $checksheet->line }}
                    </div>
                </div>
                <div class="text-center text-xl">
                    <h2>{{ $checksheet->nama }}</h2>
                </div>
                <div class="w-full sm:overflow-x-scroll">
                    <table class="table-auto w-full  border border-red-500 text-center">
                        <thead class="border border-red-400">
                            <tr>
                                <th>Nama Area</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border border-red-400 ">
                            @foreach ($areaList['data'] as $area)

                            <tr class="border border-y-gray-200 border-x-red-300">
                                @if($area->tipe == 2)
                                <td class="py-5 w-8/12">{{$area->min ? "Min: ".$area->min : ""}} <span class="bg-gray-200 p-5 text-black">{{ $area->nama }}</span> {{$area->max ? "Max: ".$area->max : ""}} </td>
                                @else
                                <td class="py-5 w-8/12"><span class="bg-gray-200 p-5 text-black">{{ $area->nama }}</span> </td>
                                @endif
                                <td class="py-5 w-4/12">

                                    <form class="w-full flex gap-3" action="" method="POST">    
                                        @csrf
                                        @include('checksheet.checkdata.input')

                                        <button type="submit" class="w-4/12 border border-gray-300 p-2 mr-3">Input</button>

                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-center space-x-4">
                    @foreach ($areaList['links'] as $link)
                    @if ($link['url'] == null)
                    @continue
                    @endif
                    <a href="{{ $link['url'] }}" class="px-3 py-2 text-gray-700 hover:text-white hover:bg-gray-700 rounded {{ $link['active'] ? 'bg-orange-400' : '' }}">
                        <?= $link['label'] ?>
                    </a>
                    @endforeach
                </div>



            </div>
        </div>
    </div>
</x-app-layout>