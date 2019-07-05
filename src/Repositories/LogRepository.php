<?php

namespace Imzhi\InspiniaAdmin\Repositories;

use Carbon\Carbon;
use App\Models\Log as LogModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class LogRepository
{
    public function list(array $args = [])
    {
        $list = Activity::where(function ($query) use ($args) {
            $daterange = $args['daterange'] ?? '';
            $daterange_arr = explode(' - ', $daterange);
            if (count($daterange_arr) === 2) {
                try {
                    $date_from = Carbon::parse($daterange_arr[0]);
                    $date_to = Carbon::parse($daterange_arr[1]);
                    $query->whereBetween('created_at', [
                        $date_from->startOfDay(),
                        $date_to->endOfDay(),
                    ]);
                } catch (Exception $e) {
                    Log::warning('admin::show.setting.log daterange err', compact('daterange'));
                }
            }
        })
            ->orderBy('id', 'desc')
            ->paginate(config('admin.pagination.num'));

        return $list;
    }
}
