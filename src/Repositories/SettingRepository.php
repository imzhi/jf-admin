<?php

namespace Imzhi\JFAdmin\Repositories;

use Log;
use Cache;
use Exception;
use Carbon\Carbon;
use Imzhi\JFAdmin\Models\Setting;

class SettingRepository
{
    protected $cache_key = 'setting';

    public function getAll()
    {
        $result = Cache::remember($this->cache_key, 360, function () {
            $list = Setting::orderBy('id')->get();

            return $list;
        });

        return $result;
    }

    public function dataList()
    {
        $data = [];

        $list = $this->getAll();
        foreach ($list as $item) {
            $name = $item->name;
            $title = $item->title;
            $value = $item->value;
            $remark = $item->remark;
            if (!$item->name) {
                continue;
            }
            $data[$name] = $value;
        }

        return $data;
    }

    /**
     * 只新增、更新配置项，不删除配置项
     */
    public function saveData(array $args, $adminId)
    {
        $now = Carbon::now();
        $name_rels = Setting::nameRels();
        $list = $this->getAll()->keyBy('name');
        $names = array_column($list->all(), 'name');
        $args_names = array_keys($args);

        $add = array_values(array_diff($args_names, $names));
        $update = array_values(array_intersect($args_names, $names));
        $insert_data = [];
        foreach ($add as $item) {
            $insert_data[] = [
                'name' => $item,
                'value' => $args[$item],
                'title' => $name_rels[$item],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Setting::insert($insert_data);

        foreach ($update as $item) {
            $list_item = $list[$item];
            if ($list_item->value != $args[$item]) {
                $list_item->value = $args[$item];
                $list_item->title = $name_rels[$item];
                $list_item->save();
            }
        }

        // 清除缓存
        $this->flushCache();
    }

    public function getValue($item)
    {
        $list = $this->getAll()->keyBy('name');

        return isset($list[$item]) ? $list[$item]->value : null;
    }

    protected function flushCache()
    {
        Cache::forget($this->cache_key);
    }
}
