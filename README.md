## jf-admin

jf-admin 是 Laravel扩展包，提供最基本的后台管理，前端主题使用 inspinia。

### 安装说明

```
composer require imzhi/jf-admin "dev-master"
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
