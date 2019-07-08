# jf-admin

jf-admin 是基于 Laravel 的一个基础后台管理模板，前端框架使用 inspinia。

## 安装说明

```
composer require imzhi/jf-admin
```

本扩展包依赖于 laravel-permission、laravel-activitylog，请发布迁移文件：

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
php artisan migrate --step
```

然后发布本扩展包的文件并执行迁移：

```
php artisan vendor:publish --provider="Imzhi\JFAdmin\JFAdminServiceProvider"
php artisan jfadmin:install
```

访问路径为：http://xxx.test/jfadmin
