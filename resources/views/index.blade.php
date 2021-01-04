<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>{{ config('logviewer.view.title', 'LogViewer') }}</title>
</head>

<body>
    <main>
        <section class="py-2 container-fluid">
            <h1>
                <a href="{{ route('logviewer.index') }}" class="link-dark text-decoration-none">
                    {{ config('logviewer.view.title', 'LogViewer') }}
                </a>

                @if(config('logviewer.view.show_logger'))
                <span class="fs-6 float-end text-muted">
                    logger: <strong>{{ config('logviewer.log_channel') }}</strong>
                </span>
                @endif
            </h1>
            <div class="row">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Level</th>
                                <th scope="col">Logger</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <th scope="row">
                                    <span class="text-nowrap">
                                        @if($item->isNew())
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" class="mr-2" style="height: 20px; width: auto;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        @endif
                                        <abbr title="{{ $item->date->toDatetimeString() }}">
                                            {{ $item->date->diffForHumans() }}
                                        </abbr>
                                    </span>
                                </th>
                                <td>
                                    <span class="badge {{ $item->getClassesForLevel() }}">
                                        {{ $item->level }}
                                    </span>
                                </td>
                                <td>{{ $item->logger }}</td>
                                <td>{{ $item->message }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </section>
    </main>
</body>

</html>