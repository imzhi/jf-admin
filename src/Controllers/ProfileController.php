<?php

namespace Imzhi\JFAdmin\Controllers;

use Illuminate\Http\Request;
use Imzhi\JFAdmin\Requests\Pwd;
use App\Http\Controllers\Controller;
use Imzhi\JFAdmin\Annotations\PermissionAnnotation;
use Imzhi\JFAdmin\Repositories\AdminUserRepository;

class ProfileController extends Controller
{
    protected $request;
    protected $adminUserRepository;

    public function __construct(Request $request, AdminUserRepository $adminUserRepository)
    {
        $this->request = $request;
        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * @PermissionAnnotation(name="公共功能-修改密码页面")
     */
    protected function showPwd()
    {
        $nav_id = 'profile.pwd';
        return view('jfadmin::profile.pwd', compact('nav_id'));
    }

    /**
     * @PermissionAnnotation(name="公共功能-修改密码操作")
     */
    protected function pwd(Pwd $request)
    {
        $password = $this->request->input('password');
        $admin_user = $this->request->user('admin_user');

        $result = $this->adminUserRepository->pwd($password, $admin_user->id);
        if (!$result) {
            return response()->fai(['msg' => '修改密码失败']);
        }

        return response()->suc(['msg' => '修改密码成功', 'reload' => true]);
    }
}
