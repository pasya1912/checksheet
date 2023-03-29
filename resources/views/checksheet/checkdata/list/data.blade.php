@extends('checksheet.checkdata.layout')
@section('content')

                <div class="w-1/2 md:w-1/3  my-5">
                    <form action="{{ url()->current() }}" method="GET">
                        <label for="default-search"
                            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-3 h-3 text-white fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="search" id="default-search"
                                class="block w-full p-3 pl-10 text-sm bg-gray-400 rounded-xl placeholder-gray-900"
                                name="search" value="{{ request()->query('search') }}" placeholder="Cari...">
                            <input type="submit"
                                class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                value="Cari">
                        </div>
                    </form>

                </div>
                <div class="w-full sm:overflow-x-scroll">
                    <table class="table-auto w-full  border border-red-500 text-center">
                        <thead class="border border-red-400">
                            <tr>
                                <th>Nama</th>
                                <th>Urutan</th>
                                <th>Tanggal</th>
                                <th>Value</th>
                                <th>PIC</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border border-red-200 ">
                            @foreach ($checkdata['data'] as $data)
                                @if ($data->status == 'good')
                                    <tr class="hover:bg-green-500 bg-green-300">
                                    @elseif($data->status == 'notgood')
                                    <tr class="hover:bg-red-500 bg-red-300">
                                @endif
                                <td class="py-5">{{ $data->nama }}</td>
                                <td class="py-5">{{ $data->barang }}</td>

                                <td class="py-5">{{ $data->tanggal }}

                                    @if($data->approval == 'approved')
                                    <span
                                        class="inline-block ml-2 px-2 py-1 text-sm font-semibold text-gray-800 bg-green-200 rounded-md">
                                        Approved
                                    </span>
                                    @elseif($data->approval == 'rejected')
                                    <span
                                        class="inline-block ml-2 px-2 py-1 text-sm font-semibold text-gray-800 bg-red-200 rounded-md">
                                        Rejected
                                    </span>
                                    @else
                                    <span
                                        class="inline-block ml-2 px-2 py-1 text-sm font-semibold text-gray-800 bg-yellow-200 rounded-md">
                                        Waiting
                                    </span>
                                    @endif
                                </td>
                                <td class="py-5">{{ $data->value }}                                     <span
                                    class="inline-block ml-2 px-2 py-1 text-sm font-semibold text-gray-800 bg-gray-200 rounded-md">
                                    {{ $data->status }}
                                </span></td>
                                <td class="py-5">{{ $data->user }}</td>
                                <td class="py-5">Detail</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

@endsection
