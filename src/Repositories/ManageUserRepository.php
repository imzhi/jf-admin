<?php

namespace Imzhi\JFAdmin\Repositories;

use DB;
use Log;
use Route;
use Exception;
use Carbon\Carbon;
use ReflectionMethod;
use Imzhi\JFAdmin\Models\Role;
use Imzhi\JFAdmin\Models\AdminUser;
use Imzhi\JFAdmin\Models\Permission;
use Imzhi\JFAdmin\Models\PermissionExtra;
use Doctrine\Common\Annotations\AnnotationReader;
use Imzhi\JFAdmin\Annotations\PermissionAnnotation;

class ManageUserRepository
{
    public function list(array $args = [])
    {
        $list = AdminUser::where(function ($query) use ($args) {
            $account = $args['account'] ?? '';
            if ($account) {
                $query->where('name', 'like', "%{$account}%");
            }

            $email = $args['email'] ?? '';
            if ($email) {
                $query->where('email', 'like', "%{$email}%");
            }

            $role = $args['role'] ?? '';
            if (is_numeric($role)) {
                $query->whereHas('roles', function ($hasQuery) use ($role) {
                    $hasQuery->where('id', $role);
                });
            }

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
                    Log::warning('jfadmin::show.manageuser.list daterange err', compact('daterange'));
                }
            }
        })
            ->with('roles')
            ->orderBy('id')
            ->paginate(config('jfadmin.page_num'));

        $status_rels = AdminUser::statusRels();
        $list->transform(function ($item) use ($status_rels) {
            $item->status_text = $status_rels[$item->status];
            $item->status_text_r = $status_rels[$this->statusReverse($item->status)];

            $item->is_init = $this->ifInitAdmin($item->id);

            return $item;
        });

        return $list;
    }

    public function get($id)
    {
        $data = AdminUser::with('roles')->find($id);

        return $data;
    }

    public function create(array $args)
    {
        $model = new AdminUser;
        $model->name = $args['name'];
        $model->email = $args['email'];
        $model->password = bcrypt($args['password']);

        $result = $model->save();
        return $result;
    }

    public function edit(array $args, $id)
    {
        $name = $args['name'] ?? '';
        $email = $args['email'] ?? '';
        $password = $args['password'] ?? '';

        $model = $this->get($id);
        if ($name) {
            $model->name = $name;
        }
        if ($email) {
            $model->email = $email;
        }
        if ($password) {
            $model->password = bcrypt($password);
        }

        $result = $model->save();
        return $result;
    }

    public function checkSame($value, $field, $ignoreId = false)
    {
        $count = AdminUser::where($field, $value)
            ->where(function ($query) use ($ignoreId) {
                if ($ignoreId) {
                    $query->where('id', '<>', $ignoreId);
                }
            })
            ->count();

        return boolval($count);
    }

    public function ifNotAdmin($modifyId, $currentId)
    {
        return $modifyId == 1 && $currentId != 1;
    }

    public function ifDisableAdmin($userId)
    {
        $model = $this->get($userId);

        return $userId == 1 && $model->status === AdminUser::STATUS_ENABLE;
    }

    public function status($userId)
    {
        $model = $this->get($userId);
        $model->status = $this->statusReverse($model->status);
        $result = $model->save();

        return $result;
    }

    public function roles()
    {
        $list = Role::orderBy('id')->paginate(config('jfadmin.page_num'));

        $list->transform(function ($item) {
            $item->is_super = $this->ifSuperRole($item);

            return $item;
        });

        return $list;
    }

    public function ifSuperRole($role)
    {
        return in_array($role->name, (array) config('jfadmin.super_role'));
    }

    public function ifInitAdmin($id)
    {
        return $id === 1;
    }

    public function getRole($id)
    {
        $data = Role::find($id);

        return $data;
    }

    public function rolesCreate(array $args)
    {
        $model = new Role;
        $model->guard_name = 'admin_user';
        $model->name = $args['name'];
        $result = $model->save();

        return $result;
    }

    public function rolesEdit(array $args, $id)
    {
        $model = $this->getRole($id);
        $model->name = $args['name'];
        $result = $model->save();

        return $result;
    }

    public function checkSameRole($name, $ignoreId = false)
    {
        $count = Role::where('name', $name)
            ->where('guard_name', 'admin_user')
            ->where(function ($query) use ($ignoreId) {
                if ($ignoreId) {
                    $query->where('id', '<>', $ignoreId);
                }
            })
            ->count();

        return boolval($count);
    }

    public function allRoles()
    {
        $list = Role::orderBy('id')->get();

        return $list;
    }

    public function distribute($roleIds, $userId)
    {
        $this->get($userId)->syncRoles($roleIds);

        return true;
    }

    public function permissions(array $args = [])
    {
        $list = Permission::where(function ($query) use ($args) {
            $route = $args['route'] ?? '';
            if ($route) {
                $query->where('name', 'like', "%{$route}%");
            }

            $cate = $args['cate'] ?? '';
            $name = $args['name'] ?? '';
            if ($name || $cate) {
                $query->whereHas('permissionExtra', function ($hasQuery) use ($cate, $name) {
                    if ($cate) {
                        $hasQuery->where('extra_cate', $cate);
                    }

                    if ($name) {
                        $hasQuery->where('extra_name', 'like', "%{$name}%");
                    }
                });
            }
        })
            ->with('permissionExtra')
            ->orderBy('id')
            ->paginate(config('jfadmin.page_num'));

        return $list;
    }

    public function permissionsDetect()
    {
        $reader = new AnnotationReader;

        $routes_data = [];
        foreach (Route::getRoutes() as $route) {
            $route_name = $route->getName();
            $middleware = (array) $route->getAction()['middleware'];
            $prefix_len = strlen('jfadmin::');
            if (strlen($route_name) > $prefix_len
                && starts_with($route_name, 'jfadmin::')
                && in_array('jfadmin', $middleware)) {
                $action_name = $route->getActionName();
                $action_arr = explode('@', $action_name);
                if (!method_exists($action_arr[0], $action_arr[1])) {
                    continue;
                }

                try {
                    $reflection_method = new ReflectionMethod($action_arr[0], $action_arr[1]);
                    $permission_annotation = $reader->getMethodAnnotation($reflection_method, PermissionAnnotation::class);
                    $permission_name = $permission_annotation->name;
                } catch (Exception $e) {
                    Log::debug('jfadmin::manageuser.permissions.detect err', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'action_name' => $action_name,
                    ]);
                    return response()->fai(['msg' => "检测出错。控制器方法 {$action_name} 缺少注释。"]);
                }

                $routes_data[$route_name] = $permission_name;
            }
        }
        $routes_key = array_keys($routes_data);
        $permissions = Permission::with('permissionExtra')->orderBy('id')->get()->keyBy('name');
        $routes_all = $permissions->pluck('permissionExtra.extra_name', 'name')->all();
        $routes_exist = $permissions->filter(function ($item) use ($routes_key) {
            return in_array($item->name, $routes_key);
        })
            ->pluck('permissionExtra.extra_name', 'name')
            ->all();

        $routes_add = array_diff_key($routes_data, $routes_exist);
        $routes_del = array_diff_key($routes_all, $routes_data);
        $routes_mod = array_diff_key($routes_exist, array_intersect_assoc($routes_exist, $routes_data));

        // 新增路由
        foreach ($routes_add as $key => $item) {
            $model = new Permission;
            $model->name = $key;
            $model->guard_name = 'admin_user';
            $model->save();
            $model_extra = new PermissionExtra;
            $model_extra->permission_id = $model->id;
            $model_extra->extra_name = $item;
            $model_extra->extra_cate = '';
            $model_extra->save();
        }

        // 更新路由
        foreach ($routes_mod as $key => $item) {
            $model_extra = $permissions[$key]->permissionExtra;
            $model_extra->extra_name = $routes_data[$key];
            $model_extra->save();
        }

        // 删除路由
        foreach ($routes_del as $key => $item) {
            $model = $permissions[$key];
            $model->delete();
        }

        $count_add = count($routes_add);
        $count_del = count($routes_del);
        $count_mod = count($routes_mod);
        return response()->suc([
            'msg' => "操作成功。新增 {$count_add} 条。更新 {$count_mod} 条。删除 {$count_del} 条。",
            'reload' => true,
        ]);
    }

    public function permissionsGroup(array $args)
    {
        $list = Permission::whereIn('id', $args['ids'])->with('permissionExtra')->get();
        foreach ($list as $item) {
            $item->permissionExtra->extra_cate = $args['name'];
            $item->permissionExtra->save();
        }
        return true;
    }

    public function allPermissions()
    {
        $list = Permission::with('permissionExtra')->orderBy('id')->get();

        return $list;
    }

    public function groupSortPermissions($data)
    {
        $data = $data->groupBy('permissionExtra.extra_cate');
        $nogroup = '未分组';

        // 对未分组权限处理
        $list_temp = collect();
        foreach ($data as $key => $item) {
            $group_name = $key ?: $nogroup;
            $list_temp->put($group_name, $item);
        }

        $keys = $list_temp->keys()->all();
        $searched = array_search($nogroup, $keys);
        if ($searched !== false) {
            array_splice($keys, $searched, 1);
        }

        // 先根据字符串排序，再根据指定顺序排序
        sort($keys, SORT_LOCALE_STRING);
        // usort($keys, function ($a, $b) {
        //     $seq = [
        //         1 => '基础操作',
        //         '管理员管理',
        //         '设置',
        //         '示例页面',
        //     ];
        //     $search_a = array_search($a, $seq);
        //     $search_b = array_search($b, $seq);
        //     return $search_a === false && $search_b === false ? 0 : $search_a - $search_b;
        // });

        // 未分组添加到最后
        if ($searched !== false) {
            array_push($keys, $nogroup);
        }

        $list = collect();
        foreach ($keys as $item) {
            $list->put($item, $list_temp[$item]);
        }

        return $list;
    }

    public function permissionExtraCates()
    {
        return PermissionExtra::orderBy('extra_cate')
            ->get([
                DB::raw('distinct(`extra_cate`) as `extra_cate`'),
            ])
            ->pluck('extra_cate');
    }

    public function rolesDistribute($permissionIds, $id)
    {
        $this->getRole($id)->syncPermissions($permissionIds);

        return true;
    }

    protected function statusReverse($status)
    {
        return $status === AdminUser::STATUS_ENABLE ? AdminUser::STATUS_DISABLE : AdminUser::STATUS_ENABLE;
    }
}
