<?php

namespace Label84\LogViewer\Support;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LogViewerCollection extends Collection
{
    public function paginate(int $perPage, int $total = null, int $page = null, string $pageName = 'page'): LengthAwarePaginator
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

        return new LengthAwarePaginator(
            $this->forPage($page, $perPage),
            $total ?: $this->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    public function whereLevel(int|string $level): self
    {
        return $this->filter(fn ($item) => LogViewerLevel::get($item->level) == LogViewerLevel::get($level));
    }

    public function whereMinLevel(int|string $level): self
    {
        return $this->filter(fn ($item) => LogViewerLevel::get($item->level) >= LogViewerLevel::get($level));
    }

    public function whereMaxLevel(int|string $level): self
    {
        return $this->filter(fn ($item) => LogViewerLevel::get($item->level) <= LogViewerLevel::get($level));
    }

    public function whereDate(Carbon|string $date): self
    {
        if ($date instanceof Carbon) {
            return $this->whereDateBetween($date, $date->copy());
        }

        return $this->whereDateBetween(Carbon::parse($date), Carbon::parse($date));
    }

    public function whereDateBetween(Carbon|string $startDate, Carbon|string $endDate): self
    {
        return $this->whereDateFrom($startDate)->whereDateTill($endDate);
    }

    public function whereDateFrom(Carbon|string $date): self
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $this->reject(fn ($item) => $item->date->isBefore($date->startOfDay()));
    }

    public function whereDateTill(Carbon|string $date): self
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $this->reject(fn ($item) => $item->date->isAfter($date->endOfDay()));
    }

    public function whereLogger(array|string $logger): self
    {
        if (is_string($logger)) {
            $logger = [$logger];
        }

        return $this->whereIn('logger', $logger);
    }

    public function whereMessage(array|string $query): self
    {
        if (is_string($query)) {
            $query = [$query];
        }

        return $this->filter(fn ($item) => Str::contains($item->message, $query));
    }

    public function whereNotMessage(array|string $query): self
    {
        if (is_string($query)) {
            $query = [$query];
        }

        return $this->filter(fn ($item) => ! Str::contains($item->message, $query));
    }

    public function whereUser(int $userId, string $userIdColumn = 'userId'): self
    {
        return $this->filter(fn ($item) => Str::contains($item->message, "\"{$userIdColumn}\":{$userId}"));
    }
}
