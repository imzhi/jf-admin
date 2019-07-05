<?php

namespace Imzhi\InspiniaAdmin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Imzhi\InspiniaAdmin\Models\Setting;
use Imzhi\InspiniaAdmin\Repositories\LogRepository;

class SettingController extends Controller
{
    public function __construct(Request $request, LogRepository $logRepository)
    {
        $this->request = $request;
        $this->logRepository = $logRepository;
    }

    /**
     * 设置-操作日志列表页
     */
    protected function showLog()
    {
        $request_data = $this->request->input();

        $list = $this->logRepository->list($request_data);

        $nav_id = 'setting.log';
        return view('admin::setting.log', compact('list', 'request_data', 'nav_id'));
    }
}
