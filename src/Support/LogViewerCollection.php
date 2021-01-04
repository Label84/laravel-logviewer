<?php

namespace Label84\LogViewer\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /** @param int|string $level */
    public function whereLevel($level): self
    {
        return $this->filter(fn ($item) => LogViewerLevel::get($item->level) == LogViewerLevel::get($level));
    }

    /** @param int|string $level */
    public function whereMinLevel($level): self
    {
        return $this->reject(fn ($item) => LogViewerLevel::get($item->level) < LogViewerLevel::get($level));
    }

    /** @param int|string $level */
    public function whereMaxLevel($level): self
    {
        return $this->reject(fn ($item) => LogViewerLevel::get($item->level) > LogViewerLevel::get($level));
    }

    /** @param Carbon|string $date */
    public function whereDate($date): self
    {
        if ($date instanceof Carbon) {
            return $this->whereDateBetween($date, $date->copy());
        }

        return $this->whereDateBetween(Carbon::parse($date), Carbon::parse($date));
    }

    /**
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     */
    public function whereDateBetween($startDate, $endDate): self
    {
        return $this->whereDateFrom($startDate)->whereDateTill($endDate);
    }

    /** @param Carbon|string $date */
    public function whereDateFrom($date): self
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $this->reject(fn ($item) => $item->date->isBefore($date->startOfDay()));
    }

    /** @param Carbon|string $date */
    public function whereDateTill($date): self
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $this->reject(fn ($item) => $item->date->isAfter($date->endOfDay()));
    }

    /** @param array|string $logger */
    public function whereLogger($logger): self
    {
        if (is_string($logger)) {
            $logger = [$logger];
        }

        return $this->whereIn('logger', $logger);
    }

    /** @param array|string $query */
    public function whereMessage($query): self
    {
        if (is_string($query)) {
            $query = [$query];
        }

        return $this->filter(fn ($item) => Str::contains($item->message, $query));
    }

    /** @param array|string $query */
    public function whereNotMessage($query): self
    {
        if (is_string($query)) {
            $query = [$query];
        }

        return $this->filter(fn ($item) => !Str::contains($item->message, $query));
    }

    public function whereUser(int $userId, string $userIdColumn = 'userId'): self
    {
        return $this->filter(fn ($item) => Str::contains($item->message, "\"{$userIdColumn}\":{$userId}"));
    }
}
