@section('title', 'Approve Change')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approval change #' . $data->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="py-5 text-lg">
                    <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a
                            href="{{ route('checksheet.setting.approval') }}?{{ request()->getQueryString() }}">&laquo;
                            Back</a></span>
                </div>
                <div class="flex justify-between">
                    <div>
                        Model : {{ $data->code }}
                    </div>
                    <div>
                        Line : {{ $data->line }}
                    </div>
                </div>
                <div class="text-center text-xl">
                    <h2 class="font-bold">{{ $data->nama_checksheet }}</h2>
                    <h4 class="text-sm">{{ $data->nama_checkarea }}</h4>

                </div>
                <div class="h-full w-full flex flex-col md:flex-row justify-between mb-4">
                    <form class="h-full w-10/12 md:w-5/12 flex flex-col gap-2">
                        <h3 class="font-bold">Data Lama</h3>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <div class="mt-1 w-max- ">
                                <input disabled type="text" name="nama" id="nama" autocomplete="given-name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    value="{{ $data->old_nama }}">
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <div class="mt-1 w-max- ">
                                <textarea disabled id="deskripsi" name="deskripsi" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $data->old_deskripsi }}</textarea>

                            </div>
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                            <div class="mt-1 w-max-full">
                                <select disabled id="tipe" name="tipe" autocomplete="tipe"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">

                                    <option value="1" {{ $data->old_tipe == 1 ? 'selected' : '' }}>OK / NG</option>
                                    <option value="2" {{ $data->old_tipe == 2 ? 'selected' : '' }}>Standard
                                        Min/Max
                                    </option>
                                    <option value="3" {{ $data->old_tipe == 3 ? 'selected' : '' }}>Normal Input
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div id="tipeInput">
                            @if ($data->old_tipe == 1)
                            @elseif($data->old_tipe == 2)
                                <div>
                                    <label for="min" class="block text-sm font-medium text-gray-700">Min</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="min" id="min"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->old_min ?? '' }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="tengah" class="block text-sm font-medium text-gray-700">Nilai
                                        Tengah</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="tengah" id="tengah"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->old_tengah ?? '' }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="max" class="block text-sm font-medium text-gray-700">Max</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="max" id="max"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->old_max ?? '' }}">
                                    </div>
                                </div>
                            @elseif($data->old_tipe == 3)
                            @endif
                        </div>
                    </form>
                    <hr class="inline md:hidden m-4">
                    <form class="h-full w-10/12 md:w-5/12 flex flex-col gap-2 mt-4 md:mt-0">
                        <h3 class="font-bold">Data Baru</h3>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <div class="mt-1 w-max- ">
                                <input disabled type="text" name="nama" id="nama" autocomplete="given-name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    value="{{ $data->new_nama }}">
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <div class="mt-1 w-max- ">
                                <textarea disabled id="deskripsi" name="deskripsi" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $data->new_deskripsi }}</textarea>

                            </div>
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                            <div class="mt-1 w-max-full">
                                <select disabled id="tipe" name="tipe" autocomplete="tipe"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">

                                    <option value="1" {{ $data->new_tipe == 1 ? 'selected' : '' }}>OK / NG
                                    </option>
                                    <option value="2" {{ $data->new_tipe == 2 ? 'selected' : '' }}>Standard
                                        Min/Max
                                    </option>
                                    <option value="3" {{ $data->new_tipe == 3 ? 'selected' : '' }}>Normal Input
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div id="tipeInput">
                            @if ($data->new_tipe == 1)
                            @elseif($data->new_tipe == 2)
                                <div>
                                    <label for="min" class="block text-sm font-medium text-gray-700">Min</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="min" id="min"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->new_min ?? '' }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="tengah" class="block text-sm font-medium text-gray-700">Nilai
                                        Tengah</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="tengah" id="tengah"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->new_tengah ?? '' }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="max" class="block text-sm font-medium text-gray-700">Max</label>
                                    <div class="mt-1 w-max-full">
                                        <input disabled type="number" name="max" id="max"
                                            autocomplete="given-name" step="any"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $data->new_max ?? '' }}">
                                    </div>
                                </div>
                            @elseif($data->new_tipe == 3)
                            @endif
                        </div>
                    </form>
                </div>
                <hr>
                <div class="mt-4">
                    @if($data->status == 'pending')
                    <form class="w-full flex flex-col gap-1 justify-start md:justify-end" method="POST" action="{{route('checksheet.setting.approval.action',['id'=>$data->id])}}">
                        @csrf
                        <div class="w-full sm:w-3/12">
                            <label for="status">Terima atau Reject perubahan data ?</label>
                            <select id="status" name="status" autocomplete="status"
                                class="active:outline-none outline-none  bg-gray-200 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled selected>Setujui/Tolak</option>
                                <option value="1">Setujui</option>
                                <option value="0">Tolak</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-3/12">
                            <input type="submit" value="Update"
                                class="bg-gray-200  hover:bg-gray-300 cursor-pointer py-2 px-4 rounded">
                        </div>
                    </form>
                    @else
                    <div class="w-full flex flex-col gap-1 justify-start md:justify-end">
                        <div class="w-full sm:w-3/12">
                            <label for="status">Status</label>
                            <select id="status" name="status" disabled autocomplete="status"
                                class="active:outline-none outline-none  bg-gray-200 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled selected>Setujui/Tolak</option>
                                <option value="1" {{ $data->status == 'approved' ? 'selected' : '' }}>Setujui</option>
                                <option value="0" {{ $data->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            function getInput(element) {
                const tipeInput = document.getElementById('tipeInput');
                if (element.value == 2) {

                    tipeInput.innerHTML = `@include('checksheet.setting.areaEdit.dua')`;
                } else {
                    tipeInput.innerHTML = '';
                }
            }
        </script>
    @endsection
</x-app-layout>
