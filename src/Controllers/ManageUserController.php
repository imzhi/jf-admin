<?php

namespace Imzhi\JFAdmin\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Imzhi\JFAdmin\Models\AdminUser;
use Imzhi\JFAdmin\Requests\ManageUserCreate;
use Imzhi\JFAdmin\Requests\ManageUserDistribute;
use Imzhi\JFAdmin\Requests\ManageUserRolesCreate;
use Imzhi\JFAdmin\Annotations\PermissionAnnotation;
use Imzhi\JFAdmin\Repositories\ManageUserRepository;
use Imzhi\JFAdmin\Requests\ManageUserRolesDistribute;
use Imzhi\JFAdmin\Requests\ManageUserPermissionsGroup;

class ManageUserController extends Controller
{
    protected $request;
    protected $manageUserRepository;

    public function __construct(Request $request, ManageUserRepository $manageUserRepository)
    {
        $this->request = $request;
        $this->manageUserRepository = $manageUserRepository;
    }

    /**
     * @PermissionAnnotation(name="管理员管理-成员列表页")
     */
    protected function showList()
    {
        $request_data = $this->request->input();

        $list = $this->manageUserRepository->list($request_data);

        $status_rels = AdminUser::statusRels();
        $roles = $this->manageUserRepository->allRoles();
        $nav_id = 'manageuser.list';
        return view('jfadmin::manageuser.list', compact(
            'request_data',
            'list',
            'status_rels',
            'roles',
            'nav_id'
        ));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-新增/编辑成员页")
     */
    protected function showCreate($id = null)
    {
        $user = Auth::guard('admin_user')->user();

        $data = null;
        $title = '新增成员';
        if ($id) {
            $data = $this->manageUserRepository->get($id);
            $title = '编辑成员';
            if (!$data) {
                return redirect(url()->previous())->withErrors('参数错误');
            }

            if ($this->manageUserRepository->ifNotAdmin($id, $user->id)) {
                return redirect(url()->previous())->withErrors('无权限修改初始管理员账号');
            }
        }

        $status_rels = AdminUser::statusRels();
        $nav_id = 'manageuser.list';
        return view('jfadmin::manageuser.create', compact('data', 'title', 'status_rels', 'nav_id'));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-新增/编辑成员操作")
     */
    protected function create(ManageUserCreate $request)
    {
        $user = Auth::guard('admin_user')->user();

        $id = $this->request->input('id');
        $name = $this->request->input('name');
        $email = $this->request->input('email');
        $request_data = $this->request->except('id');

        if ($id) {
            $data = $this->manageUserRepository->get($id);
            if (!$data) {
                return response()->fai(['msg' => '参数错误']);
            }

            if ($this->manageUserRepository->ifNotAdmin($id, $user->id)) {
                return response()->fai(['msg' => '无权限修改初始管理员账号']);
            }

            if ($this->manageUserRepository->checkSame($name, 'name', $id)) {
                return response()->fai(['msg' => '用户名已存在']);
            }

            if ($this->manageUserRepository->checkSame($email, 'email', $id)) {
                return response()->fai(['msg' => '邮箱已存在']);
            }

            $result = $this->manageUserRepository->edit($request_data, $id);
            if (!$result) {
                return response()->fai(['msg' => '编辑成员失败']);
            }
            return response()->suc(['msg' => '编辑成员成功', 'redirect' => true]);
        } else {
            if ($this->manageUserRepository->checkSame($name, 'name')) {
                return response()->fai(['msg' => '用户名已存在']);
            }

            if ($this->manageUserRepository->checkSame($email, 'email')) {
                return response()->fai(['msg' => '邮箱已存在']);
            }

            $result = $this->manageUserRepository->create($request_data);
            if (!$result) {
                return response()->fai(['msg' => '新增成员失败']);
            }
            return response()->suc(['msg' => '新增成员成功', 'redirect' => true]);
        }
    }

    /**
     * @PermissionAnnotation(name="管理员管理-成员状态操作")
     */
    protected function status()
    {
        $user = Auth::guard('admin_user')->user();

        $id = $this->request->input('id');

        $data = $this->manageUserRepository->get($id);
        if (!$data) {
            return response()->fai(['msg' => '参数错误']);
        }

        if ($this->manageUserRepository->ifNotAdmin($id, $user->id)) {
            return response()->fai(['msg' => '无权限修改初始管理员账号']);
        }

        if ($this->manageUserRepository->ifDisableAdmin($id)) {
            return response()->fai(['msg' => '不能禁用初始管理员账号']);
        }

        $result = $this->manageUserRepository->status($id);
        if (!$result) {
            return response()->fai(['msg' => '操作失败，请重试']);
        }

        return response()->suc(['msg' => '操作成功', 'reload' => true]);
    }

    /**
     * @PermissionAnnotation(name="管理员管理-角色列表页")
     */
    protected function showRoles()
    {
        $request_data = $this->request->input();

        $list = $this->manageUserRepository->roles();

        $nav_id = 'manageuser.roles';
        return view('jfadmin::manageuser.roles', compact('request_data', 'list', 'nav_id'));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-新增/编辑角色页")
     */
    protected function showRolesCreate($id = null)
    {
        $data = null;
        $title = '新增角色';
        if ($id) {
            $data = $this->manageUserRepository->getRole($id);
            $title = '编辑角色';
            if (!$data) {
                return redirect(url()->previous())->withErrors('参数错误');
            }

            if ($this->manageUserRepository->ifSuperRole($data)) {
                return redirect(url()->previous())->withErrors('禁止编辑超级管理员角色');
            }
        }

        $nav_id = 'manageuser.roles';
        return view('jfadmin::manageuser.roles_create', compact('data', 'title', 'nav_id'));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-新增/编辑角色操作")
     */
    protected function rolesCreate(ManageUserRolesCreate $request)
    {
        $id = $this->request->input('id');
        $name = $this->request->input('name');
        $request_data = $this->request->except('id');

        if ($id) {
            $data = $this->manageUserRepository->getRole($id);
            if (!$data) {
                return response()->fai(['msg' => '参数错误']);
            }

            if ($this->manageUserRepository->ifSuperRole($data)) {
                return response()->fai(['msg' => '禁止编辑超级管理员角色']);
            }

            if ($this->manageUserRepository->checkSameRole($name, $id)) {
                return response()->fai(['msg' => '角色名称已存在']);
            }

            $result = $this->manageUserRepository->rolesEdit($request_data, $id);
            if (!$result) {
                return response()->fai(['msg' => '编辑角色失败']);
            }
            return response()->suc(['msg' => '编辑角色成功', 'redirect' => true]);
        } else {
            if ($this->manageUserRepository->checkSameRole($name)) {
                return response()->fai(['msg' => '角色名称已存在']);
            }

            $result = $this->manageUserRepository->rolesCreate($request_data);
            if (!$result) {
                return response()->fai(['msg' => '新增角色失败']);
            }
            return response()->suc(['msg' => '新增角色成功', 'redirect' => true]);
        }
    }

    /**
     * @PermissionAnnotation(name="管理员管理-成员分配角色页")
     */
    protected function showDistribute($id)
    {
        $data = $this->manageUserRepository->get($id);
        if (!$data) {
            return redirect(url()->previous())->withErrors('参数错误');
        }

        if ($this->manageUserRepository->ifInitAdmin($data->id)) {
            return redirect(url()->previous())->withErrors('禁止修改初始管理员角色');
        }

        $list = $this->manageUserRepository->allRoles();

        $user_roles = $data->roles->pluck('id')->all();
        $nav_id = 'manageuser.list';
        return view('jfadmin::manageuser.distribute', compact('data', 'list', 'user_roles', 'nav_id'));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-成员分配角色操作")
     */
    protected function distribute(ManageUserDistribute $request)
    {
        $id = $this->request->input('id');
        $role_ids = $this->request->input('role_ids', []);

        $data = $this->manageUserRepository->get($id);
        if (!$data) {
            return response()->fai(['msg' => '参数错误']);
        }

        if ($this->manageUserRepository->ifInitAdmin($data->id)) {
            return response()->fai(['msg' => '禁止修改初始管理员角色']);
        }

        $result = $this->manageUserRepository->distribute($role_ids, $id);
        if (!$result) {
            return response()->fai(['msg' => '操作失败']);
        }
        return response()->suc(['msg' => '操作成功', 'redirect' => true]);
    }

    /**
     * @PermissionAnnotation(name="管理员管理-角色分配权限页")
     */
    protected function showRolesDistribute($id)
    {
        $data = $this->manageUserRepository->getRole($id);
        if (!$data) {
            return redirect(url()->previous())->withErrors('参数错误');
        }

        $list = $this->manageUserRepository->groupSortPermissions($this->manageUserRepository->allPermissions());

        $role_permissions = $data->permissions->pluck('id')->all();
        $nav_id = 'manageuser.roles';
        return view('jfadmin::manageuser.roles_distribute', compact(
            'data',
            'list',
            'role_permissions',
            'nav_id'
        ));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-角色分配权限操作")
     */
    protected function rolesDistribute(ManageUserRolesDistribute $request)
    {
        $id = $this->request->input('id');
        $permission_ids = $this->request->input('permission_ids', []);

        $data = $this->manageUserRepository->getRole($id);
        if (!$data) {
            return response()->fai(['msg' => '参数错误']);
        }

        $result = $this->manageUserRepository->rolesDistribute($permission_ids, $id);
        if (!$result) {
            return response()->fai(['msg' => '操作失败']);
        }
        return response()->suc(['msg' => '操作成功', 'redirect' => true]);
    }

    /**
     * @PermissionAnnotation(name="管理员管理-权限列表页")
     */
    protected function showPermissions()
    {
        $request_data = $this->request->input();

        $list = $this->manageUserRepository->permissions($request_data);

        $permission_extra_cates = $this->manageUserRepository->permissionExtraCates();
        $nav_id = 'manageuser.permissions';
        return view('jfadmin::manageuser.permissions', compact(
            'request_data',
            'list',
            'permission_extra_cates',
            'nav_id'
        ));
    }

    /**
     * @PermissionAnnotation(name="管理员管理-权限批量检测操作")
     */
    protected function permissionsDetect()
    {
        $result = $this->manageUserRepository->permissionsDetect();
        return $result;
    }

    /**
     * @PermissionAnnotation(name="管理员管理-权限批量分组操作")
     */
    protected function permissionsGroup(ManageUserPermissionsGroup $request)
    {
        $request_data = $this->request->input();

        $result = $this->manageUserRepository->permissionsGroup($request_data);
        if ($result === false) {
            return response()->fai(['msg' => '分组失败']);
        }

        return response()->suc(['msg' => '分组成功', 'reload' => true]);
    }
}
