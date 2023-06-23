@section('title', 'Detail Area')
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
                    <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a
                            href="{{ route('checksheet.setting') }}?{{ request()->getQueryString() }}">&laquo;
                            Back</a></span>
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
                    <h2 class="font-bold">{{ $checksheet->nama }}</h2>
                    <h4 class="text-sm">{{ $area->nama }}</h4>

                </div>
                <div class="h-full w-full">
                    <form class="h-full w-11/12 md:w-6/12 flex flex-col gap-2" action="{{route('checksheet.setting.area.editAction',['idchecksheet'=>$checksheet->id,'idcheckarea'=>$area->id])}}" method="POST">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <div class="mt-1 w-max- ">
                                <input type="text" name="nama" id="nama" autocomplete="given-name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    value="{{ $area->nama }}">
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <div class="mt-1 w-max- ">
                                <textarea id="deskripsi" name="deskripsi" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $area->deskripsi }}</textarea>

                            </div>
                        </div>
                        @include('checksheet.setting.areaEdit.tipe')
                        <div class="w-full text-end">
                            <input type="submit" value="Update"
                                class="cursor-pointer border border-blue-500 hover:border-blue-700 text-gray-700 font-bold py-2 px-4 rounded">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            function getInput(element){
                const tipeInput = document.getElementById('tipeInput');
                if(element.value == 2){

                    tipeInput.innerHTML = `@include('checksheet.setting.areaEdit.dua')`;
                }else{
                    tipeInput.innerHTML = '';
                }
            }
        </script>
    @endsection
</x-app-layout>
