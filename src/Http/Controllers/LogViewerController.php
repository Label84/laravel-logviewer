<?php

namespace Label84\LogViewer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Label84\LogViewer\Facades\LogViewer;

class LogViewerController extends Controller
{
    public function index(Request $request): View
    {
        $items = LogViewer::logs()
            ->when($request->has('level'), fn ($collection) => $collection->whereLevel($request->query('level')))
            ->when($request->has('date'), fn ($collection) => $collection->whereDate($request->query('date')))
            ->when($request->has('from'), fn ($collection) => $collection->whereDateFrom($request->query('from')))
            ->when($request->has('till'), fn ($collection) => $collection->whereDateTill($request->query('till')))
            ->when($request->has('logger'), fn ($collection) => $collection->whereLogger($request->query('logger')))
            ->when($request->has('message'), fn ($collection) => $collection->whereMessage($request->query('message')))
            ->paginate(config('logviewer.view.items_per_page'));

        return view('logviewer::index', compact('items'));
    }
}
