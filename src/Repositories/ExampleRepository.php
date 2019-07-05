<?php

namespace Imzhi\InspiniaAdmin\Repositories;

use Carbon\Carbon;
use App\Models\User;

class ExampleRepository
{
    public function list(array $args = [])
    {
        $list = User::where(function ($query) use ($args) {
            $name = $args['name'] ?? '';
            if ($name) {
                $query->where('name', 'like', "%{$name}%");
            }

            $email = $args['email'] ?? '';
            if ($email) {
                $query->where('email', 'like', "%{$email}%");
            }

            $status = $args['status'] ?? '';
            if (strlen($status)) {
                $query->where('status', $status);
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
                    Log::warning('admin::show.example.index daterange err', compact('daterange'));
                }
            }
        })
            ->orderBy('id', 'desc')
            ->paginate(config('admin.pagination.num'));

        return $list;
    }

    public function get($id)
    {
        static $data = [];
        if (!array_key_exists($id, $data)) {
            $data[$id] = User::find($id);
        }

        return $data[$id];
    }

    public function getAll()
    {
        static $list;
        if (is_null($list)) {
            $list = User::orderBy('id')->get();
        }

        return $list;
    }

    public function create(array $args)
    {
        $model = new User;
        $model->name = $args['name'];
        $model->email = $args['email'];
        $model->password = bcrypt($args['password']);
        $model->status = $args['status'];

        $result = $model->save();
        return $result;

    }

    public function edit(array $args, $id)
    {
        $name = $args['name'];
        $email = $args['email'];
        $password = $args['password'] ?? '';
        $status = $args['status'];

        $model = $this->get($id);
        $model->name = $name;
        $model->email = $email;
        if ($password) {
            $model->password = bcrypt($password);
        }
        $model->status = $status;

        $result = $model->save();
        return $result;
    }

    public function del($id)
    {
        $result = User::destroy($id);

        return $result;
    }

    public function checkSame($value, $field, $ignoreId = false)
    {
        $count = User::where($field, $value)
            ->where(function ($query) use ($ignoreId) {
                if ($ignoreId) {
                    $query->where('id', '<>', $ignoreId);
                }
            })
            ->count();

        return boolval($count);
    }
}
