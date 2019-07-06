<?php

namespace Imzhi\JFAdmin\Repositories;

use Imzhi\JFAdmin\Models\AdminUser;

class AdminUserRepository
{
    public function get($id)
    {
        static $data = [];
        if (!array_key_exists($id, $data)) {
            $data[$id] = AdminUser::find($id);
        }

        return $data[$id];
    }

    public function getAll()
    {
        static $list;
        if (is_null($list)) {
            $list = AdminUser::orderBy('id')->get();
        }

        return $list;
    }

    public function pwd($pwd, $id)
    {
        $model = $this->get($id);
        $model->password = bcrypt($pwd);
        $result = $model->save();

        return $result;
    }
}
