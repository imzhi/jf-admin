<?php

namespace App\JFAdmin\Controllers;

use App\Http\Controllers\Controller;
use Imzhi\JFAdmin\Annotations\PermissionAnnotation;

class HomeController extends Controller
{
    /**
     * @PermissionAnnotation(name="公共功能-后台首页")
     */
    protected function showIndex()
    {
        $envs = $this->envs();
        $json = file_get_contents(base_path('composer.json'));
        $dependencies = json_decode($json, true)['require'];
        $nav_id = 'home.index';
        return view('jfadmin::home.index', compact('envs', 'dependencies', 'nav_id'));
    }

    private function envs()
    {
        return [
            ['name' => 'PHP version',       'value' => PHP_VERSION],
            ['name' => 'Laravel version',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => array_get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver',      'value' => config('cache.default')],
            ['name' => 'Session driver',    'value' => config('session.driver')],
            ['name' => 'Queue driver',      'value' => config('queue.default')],

            ['name' => 'Timezone',          'value' => config('app.timezone')],
            ['name' => 'Locale',            'value' => config('app.locale')],
            ['name' => 'Env',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];
    }
}
