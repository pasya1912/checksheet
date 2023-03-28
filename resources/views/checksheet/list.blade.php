<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checksheet List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="w-1/2 md:w-1/3  my-5">

                    <form action="{{ route('checksheet.list') }}" method="GET">
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
                                <th>Line</th>
                                <th>Code</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody class="border border-red-200 ">
                            @foreach ($checkList['data'] as $check)
                                <tr class="hover:bg-gray-500 transition duration-300 ease-in-out py-5">

                                    <td class="py-5">{{ $check->line }}</td>
                                    <td class="py-5">{{ $check->code }}</td>
                                    <td class="py-5">
                                        <a
                                            class="hover:text-white"href="{{ route('checksheet.area', $check->id) }}">{{ $check->nama }}</a>
                                    </td>
                                    <td class="py-5">{{ $check->jenis }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-center space-x-4">


                    @foreach ($checkList['links'] as $link)
                        @if($link['url'] == null)
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
</x-app-layout>
