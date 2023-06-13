@section('title', 'Input')
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
                    <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a href="{{ route('checksheet.list') }}?{{ request()->getQueryString() }}">&laquo;
                            Back</a></span>
                </div>
                <div class="flex justify-between">
                    <div>
                        Model : {{ $checksheet->code }}
                    </div>
                    <div>
                        Line : {{ $checksheet->line }}
                    </div>
                    <div>
                        Cell : {{ request()->get('cell') }}
                    </div>
                    <div>
                        Shift : {{ request()->get('shift') }}
                    </div>
                    <div>
                        Barang : {{ request()->get('barang') }}
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
                                @if ($area->tipe == 2)
                                <td class="py-5 w-8/12">
                                    <div>{{ $area->max ? 'Max: ' . $area->max : '' }}</div>
                                    <div><span class="w-10/12 md:w-11/12 bg-gray-200 p-5 text-black inline-block text-sm md:text-base">{{ $area->nama }}</span></div>
                                    <div>{{ $area->min ? 'Min: ' . $area->min : '' }}</div>
                                    <div>{{ $area->deskripsi ?? '' }}</div>
                                </td>
                                @elseif($area->tipe == 1)
                                <td class="py-5 w-8/12">

                                    <div><span class="w-10/12 md:w-11/12 bg-gray-200 p-5 text-black inline-block text-sm md:text-base">{{ $area->nama }}</span></div>
                                    <div class="text-xs">
                                        {{ $area->deskripsi ?? '' }}
                                    </div>
                                </td>
                                @else
                                <td class="py-5 w-8/12">
                                    <div class="w-10/12 md:w-11/12 bg-gray-200 p-5 text-black inline-block text-sm md:text-base">
                                        {{ $area->nama }}
                                    </div>
                                    <div class="text-xs">
                                        {{ $area->deskripsi ?? '' }}
                                    </div>
                                </td>
                                @endif
                                <td class="py-5 w-4/12">

                                    <div class="w-full">
                                        @csrf
                                        @include('checksheet.checkdata.input')
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-5 text-lg">
                    <span class="hover:bg-blue-200 border border-blue-400 px-3 py-2"><a href="{{ route('checksheet.list') }}?{{ request()->getQueryString() }}">&laquo;
                            Back</a></span>
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
    <!-- Main modal -->
    <div id="staticModal" class="fixed top-14 z-50 w-full hidden p-4 overflow-x-hidden overflow-y-hidden md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full  max-w-2xl max-h-full m-auto">
            <!-- Modal content -->
            <div class="relative bg-gray-300 rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Add Notes
                    </h3>
                    <button type="button" id="closeStaticModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="staticModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('checksheet.data.updateNotes', ['idchecksheet' => ':idchecksheet', 'idcheckarea' => ':idcheckarea']) }}" method="POST" id="form_notes">

                    <div class="p-6 space-y-6">
                        <input type="hidden" id="id_checkdata" name="id_checkdata">
                        <div class="mb-4">
                            <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                            <textarea name="notes" id="textarea_notes" cols="30" rows="5" max="144" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tambahkan notes.."></textarea>
                        </div>
                        <div class=" mb-4">
                            <div class="">
                                <div class="flex items-center">
                                    <input id="mark_checkbox" name="marked" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Solved / Sudah
                                        Dihandle</label>
                                </div>
                                <div class="mt-2 hidden" id="inputRevisi">

                                </div>

                            </div>

                            <div class=" text-sm font-sm text-gray-800   dark:text-gray-300">
                                <p class="text-red-700 dark:text-yellow-300">Notes tidak dapat diedit jika sudah dihandle</p>
                                <p>Jangan dicentang bila belum belum diselesaikan / dihandle</p>
                            </div>

                        </div>



                    </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" data-modal-hide="staticModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('script')
    <script>
        document.querySelector('#mark_checkbox').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('#inputRevisi').classList.remove('hidden');
                document.querySelector('#inputRevisi').classList.add('block');
            } else {
                document.querySelector('#inputRevisi').classList.add('hidden');

                document.querySelector('#inputRevisi').value = null;

            }
        });

        function inputData(checksheetid, areaid, tipe, element) {
            element.disabled = true;
            var value;
            //get element value attribute
            if (tipe == 1) {

                value = element.getAttribute('value');
                console.log(value);
                //set value to input
                document.getElementById("value-" + areaid).value = value;
            }
            value = document.getElementById("value-" + areaid).value;

            //get root url
            var root = window.location.origin;
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);
            //call route checksheet.data.store using xhr
            var xhr = new XMLHttpRequest();
            xhr.open('POST', root + '/checksheet/' + checksheetid + '/checkarea/' + areaid);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            //csrf
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = function() {
                var res = JSON.parse(xhr.responseText);
                if (xhr.status === 200) {
                    //change border to red
                    element.classList.remove('bg-gray-300');
                    console.log(res);
                    //change text to "success"
                    //change ring to green

                    if (res.is_good) {
                        if (tipe != 1) {
                            document.getElementById("value-" + areaid).classList.add('ring-green-200');
                            element.innerHTML = "Good";
                            element.classList.add('bg-green-300');
                            document.getElementById("value-" + areaid).disabled = true;
                        } else {
                            element.innerHTML = "OK";
                            element.classList.add('bg-green-300');
                            document.getElementById("ngbutton-" + areaid).remove();
                        }

                    } else {
                        if (tipe != 1) {
                            document.getElementById("value-" + areaid).classList.add('ring-red-200');
                            element.innerHTML = "NG!";
                            element.classList.add('bg-red-300');
                            document.getElementById("value-" + areaid).disabled = true;
                            //refresh

                        } else {
                            //remove ngbutton
                            element.innerHTML = "NG!";
                            element.classList.add('bg-red-300');
                            document.getElementById("okbutton-" + areaid).remove();
                        }
                        location.reload();

                    }
                } else {
                    element.enabled = false;

                    element.classList.remove('border-gray-300');
                    element.classList.add('border-red-300');
                    element.innerHTML = "Failed";

                    //if existd, remove green ring
                    if (document.getElementById("value-" + areaid).classList.contains('ring-green-200')) {
                        document.getElementById("value-" + areaid).classList.remove('ring-green-200');
                    }
                    document.getElementById("value-" + areaid).classList.add('ring-red-300');
                }


            };
            xhr.send('value=' + value + "&" + params.toString());
        }
    </script>
    <script>
        var modal = document.getElementById("staticModal");
        var closeBtn = document.getElementById("closeStaticModal");
        var form_notes = document.getElementById("form_notes");

        function getData(areaid, dataid) {
            return new Promise((resolve, reject) => {
                var xhr = new XMLHttpRequest();
                var url =
                    "{{ route('checksheet.data.get', ['idchecksheet' => $checksheet->id, 'idcheckarea' => ':idcheckarea']) }}";
                url = url.replace(':idcheckarea', areaid);

                console.log(url);
                xhr.open('GET', url + "?id=" + dataid);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'));
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var res = JSON.parse(xhr.responseText);
                            if (res && res.data) {
                                resolve(res.data);
                            } else {
                                reject(new Error("Invalid response data"));
                            }
                        } catch (err) {
                            reject(err);
                        }
                    } else {
                        reject(new Error("Request failed with status " + xhr.status));
                    }
                };
                xhr.onerror = function() {
                    reject(new Error("Network error"));
                };
                xhr.send();
            });

        }

        async function openModal(btn, id_checksheet, id_checkarea, tipe) {
            //get attribute area-id value
            var dataid = btn.getAttribute("data-id");
            var data = await getData(id_checkarea, dataid);
            //regex replace data.notes
            if (data.notes == null) {
                data.notes = "";
            }

            data.notes = data.notes.replace(/<br>(.*?)\)/, '');

            var notes = form_notes.getAttribute("action");
            //change textarea_notes value with data
            document.getElementById("textarea_notes").value = data.notes;
            //replace :id_checksheet with id_checksheet and :id_checkdata with id_checkdata
            var url = notes.replace(':idchecksheet', id_checksheet).replace(':idcheckarea', id_checkarea);
            //assign to form_notes action
            form_notes.setAttribute("action", url);
            //assign to form_notes input
            document.getElementById("id_checkdata").value = dataid;
            modal.classList.remove("hidden");

            var inputRevised = document.getElementById("inputRevisi");
            inputRevised.innerHTML = "";

                            //add child input type number append
            var labelInput = document.createElement("label");
            labelInput.setAttribute("for", "revised_value");
            labelInput.setAttribute("class", "block mb-2 text-sm font-medium text-gray-900 dark:text-white");
            labelInput.innerHTML = "Revised Value";
            inputRevised.appendChild(labelInput);
            if (tipe == 2) {
                var input = document.createElement("input");
                input.setAttribute("type", "number");
                input.setAttribute("name", "revised_value");
                input.setAttribute("id", "revised_value");
                input.setAttribute("step","0.01");
                input.setAttribute("class", "bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500");
                input.setAttribute("placeholder", "Revised Value");
                inputRevised.appendChild(input);
            }
            else if (tipe == 3) {
                var input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("name", "revised_value");
                input.setAttribute("id", "revised_value");
                input.setAttribute("class", "bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500");
                input.setAttribute("placeholder", "Revised Value");
                inputRevised.appendChild(input);
            }else if(tipe == 1)
            {
                //make a two radio box input with name value and value ok and ng
                var input = document.createElement("div");
                input.setAttribute("class", "flex flex-row");
                var input1 = document.createElement("input");
                input1.setAttribute("type", "radio");
                input1.setAttribute("name", "revised_value");
                input1.setAttribute("id", "revised_value");
                input1.setAttribute("value", "ok");
                var label1 = document.createElement("label");
                label1.setAttribute("for", "revised_value");
                label1.setAttribute("class", "block mb-2 text-sm font-medium text-gray-900 dark:text-white");
                label1.innerHTML = "OK";
                var input2 = document.createElement("input");
                input2.setAttribute("type", "radio");
                input2.setAttribute("name", "revised_value");
                input2.setAttribute("id", "revised_value");
                input2.setAttribute("value", "ng");
                var label2 = document.createElement("label");
                label2.setAttribute("for", "revised_value");
                label2.setAttribute("class", "block mb-2 text-sm font-medium text-gray-900 dark:text-white");
                label2.innerHTML = "NG";
                inputRevised.appendChild(input);
                input.appendChild(input1);
                input.appendChild(label1);
                input.appendChild(input2);
                input.appendChild(label2);



            }
        }
        closeBtn.addEventListener("click", function() {
            modal.classList.add("hidden");
        });
    </script>
    @endsection
</x-app-layout>
