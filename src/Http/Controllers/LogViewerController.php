<?php

namespace Label84\LogViewer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Label84\LogViewer\Facades\LogViewer;

class LogViewerController extends Controller
{
    public function __invoke(Request $request): View
    {
        $items = LogViewer::logs()
            ->when($request->has('level'), fn ($logs) => $logs->whereLevel((int) $request->query('level')))
            ->when($request->has('date'), fn ($logs) => $logs->whereDate($request->query('date')))
            ->when($request->has('from'), fn ($logs) => $logs->whereDateFrom($request->query('from')))
            ->when($request->has('till'), fn ($logs) => $logs->whereDateTill($request->query('till')))
            ->when($request->has('logger'), fn ($logs) => $logs->whereLogger($request->query('logger')))
            ->when($request->has('message'), fn ($logs) => $logs->whereMessage($request->query('message')))
            ->paginate(config('logviewer.view.items_per_page'));

        return view('logviewer::index', compact('items'));
    }
}
