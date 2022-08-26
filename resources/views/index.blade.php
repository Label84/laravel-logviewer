<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <title>{{ config('logviewer.view.title', 'LogViewer') }}</title>
</head>

<body class="bg-gray-100">
    <main class="max-w-7xl mx-auto px-4 lg:px-0">
        <div>
            <h1 class="mt-5 mb-3 text-gray-700">
                <a href="{{ route('logviewer.index') }}" class="text-3xl font-bold underline">
                    {{ config('logviewer.view.title', 'LogViewer') }}
                </a>

                @if(config('logviewer.view.show_logger'))
                <span class="float-right">
                    Channel: <strong>{{ config('logviewer.log_channel') }}</strong>
                </span>
                @endif
            </h1>
            <p class="mb-5">
                <a href="{{ config('logviewer.view.back_to_application_link_url') ?? '/' }}" class="text-gray-400 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                    {{ config('logviewer.view.back_to_application_link_title') ?? 'Back to Laravel' }}
                </a>
            </p>
        </div>

        <div class="overflow-x-auto relative rounded-md shadow-md">
            <table class="w-full">
                <thead class="text-xs text-left bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Level</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Environment</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                    <tr class="border-b {{ $item->isNew() ? 'bg-gray-100' : 'bg-white' }}">
                        <th scope="row" class="text-left py-4 px-3 font-medium text-gray-900 whitespace-nowrap">
                            <span class="text-nowrap {{ $item->isNew() ? 'font-semibold' : 'font-normal' }}">
                                <abbr title="{{ $item->date->toDatetimeString() }}">
                                    {{ $item->date->diffForHumans() }}
                                </abbr>
                            </span>
                        </th>
                        <td class="text-gray-700 py-4 px-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold {{ $item->getClassesForLevel() }}">
                                {{ $item->level }}
                            </span>
                        </td>
                        <td class="text-gray-400 py-4 px-3">
                            {{ $item->logger }}
                        </td>
                        <td class="text-gray-700 py-4 px-3 {{ $item->isNew() ? 'font-semibold' : 'font-normal' }}">
                            {{ $item->message }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 mb-12">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </main>
</body>

</html>