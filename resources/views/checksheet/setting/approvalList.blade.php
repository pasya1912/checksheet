@section('title', 'Setting')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Change History ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="w-full  my-5 flex flex-wrap md:flex-nowrap">
                    <form action="{{ route('checksheet.setting.approval') }}" method="GET" id="search-checksheet"
                        class="flex gap-3 w-full md:w-6/12">
                        <div class="w-full">
                            <label for="line">Line</label>
                            <select id="line" name="line" class=" w-full border-gray-400 p-2 rounded-lg"
                                required>
                                <option value="" selected>Line</option>
                                @foreach ($lineList as $line)
                                    <option value="{{ $line->line }}"
                                        {{ request()->get('line') == $line->line ? 'selected' : '' }}>
                                        {{ $line->line }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="code">Model</label>
                            <select id="code" name="code" class="w-full border-gray-400 p-2 rounded-lg"
                                required>
                                <option value="" selected>Model</option>
                                @foreach ($codeList as $code)
                                    <option value="{{ $code->code }}"
                                        {{ request()->get('code') == $code->code ? 'selected' : '' }}>
                                        {{ $code->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200 text-center">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Line
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Model
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Checksheet
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Area
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Request By
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list['data'] as $data)

                            <tr class="odd:bg-white even:bg-gray-50 border-b text-center ">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                    {{$data->line}}
                                </th>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{$data->code}}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{$data->nama_checksheet}}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{$data->old_nama}}
                                </td>
                                @if($data->status == 'approved')
                                <td class="px-6 py-4">
                                    <div>
                                    <span class="px-3 py-2 text-gray-900 font-bold bg-green-300 rounded-md whitespace-nowrap uppercase">{{$data->status}}</span>
                                    </div>
                                    <div class="mt-1 overflow-x-auto">
                                        {{$data->nama_approver}}
                                    </div>
                                </td>
                                @elseif($data->status == 'rejected')
                                <td class="px-6 py-4  ">
                                    <span class="px-3 py-2 text-gray-900 font-bold bg-red-300 rounded-md whitespace-nowrap uppercase">{{$data->status}}</span>
                                </td>
                                @else
                                <td class="px-6 py-4 ">
                                    <span class="px-3 py-2 text-gray-900 font-bold bg-gray-100 rounded-md whitespace-nowrap uppercase">{{$data->status}}</span>
                                </td>
                                @endif
                                <td>
                                    {{$data->nama_user}}
                                </td>
                                <td>
                                    <a href="{{route('checksheet.setting.approval.detail',['id'=>$data->id])}}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-center space-x-4 mt-3">
                    @foreach ($list['links'] as $link)
                        @if ($link['url'] == null)
                            @continue
                        @endif
                        <a href="{{ $link['url'] }}"
                            class="px-3 py-2 text-gray-700 hover:text-white hover:bg-gray-700 rounded {{ $link['active'] ? 'bg-orange-400' : '' }}">
                            <?= $link['label'] ?>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script>
            var formes = document.getElementById('search-checksheet');
            //get selected value from selected option form then build a query string
            var line = document.getElementById('line');
            var code = document.getElementById('code');
            var cell = document.getElementById('cell');
            var shift = document.getElementById('shift');
            var barang = document.getElementById('barang');

            //if code cell shift barang change then submit form
            line.addEventListener('change', function(value) {
                console.log('line change');
                //call xhr request to checksheet.getCode
                if (line.value == '' || line.value == null) {
                    return
                }
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '{{ route('checksheet.getCode') }}?line=' + line.value);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var res = JSON.parse(xhr.responseText);
                        var option = '<option value="" selected disabled>Model</option>';
                        for (var i = 0; i < res.data.length; i++) {
                            option += '<option value="' + res.data[i].code + '">' + res.data[i].code +
                                '</option>';
                        }
                        code.innerHTML = option;
                    } else {
                        alert('Request failed.  Returned status of ' + xhr.status);
                    }
                };
                xhr.send();

            });
            code.addEventListener('change', function() {
                formes.submit();
            });
            cell.addEventListener('change', function() {
                formes.submit();
            });
            shift.addEventListener('change', function() {
                formes.submit();
            });
            barang.addEventListener('change', function() {
                formes.submit();
            });
        </script>
    @endsection
</x-app-layout>
