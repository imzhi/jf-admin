<?php

namespace Imzhi\JFAdmin\Controllers;

use Illuminate\Http\Request;
use Imzhi\JFAdmin\Models\Setting;
use App\Http\Controllers\Controller;
use Imzhi\JFAdmin\Repositories\LogRepository;
use Imzhi\JFAdmin\Annotations\PermissionAnnotation;

class SettingController extends Controller
{
    public function __construct(Request $request, LogRepository $logRepository)
    {
        $this->request = $request;
        $this->logRepository = $logRepository;
    }

    /**
     * @PermissionAnnotation(name="设置-操作日志列表页")
     */
    protected function showLog()
    {
        $request_data = $this->request->input();

        $list = $this->logRepository->list($request_data);

        $nav_id = 'setting.log';
        return view('jfadmin::setting.log', compact('list', 'request_data', 'nav_id'));
    }
}
