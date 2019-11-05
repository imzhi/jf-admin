jf-admin 是基于 Laravel 5.5+ 的扩展包，助你构建自己的后台管理系统。

:star2: __演示站点：__

<https://jfadmin.imzhi.me/jfadmin/login>，用户名：admin，密码：admin。

:star2: __演示截屏：__

![](http://upcdn.imzhi.me/jfadmin/2.gif)

__内容：__

- [简介](#简介)
- [文档](#文档)
- [环境](#环境)
- [安装](#安装)
- [许可证](#许可证)

简介
----

jf-admin 提供后台登录、权限控制和操作日志查看等功能。权限控制采用 RBAC，包括成员管理、角色管理、权限管理。

前端主题使用 inspinia。

文档
----

### 目录结构

```
├── config
├── database
│   └── migrations
├── resources
│   ├── assets
│   │   ├── inspinia
│   │   └── jfadmin
│   ├── lang
│   │   ├── en
│   │   └── zh-CN
│   └── views
│       ├── auth
│       ├── home
│       ├── layouts
│       ├── manageuser
│       ├── profile
│       └── setting
└── src
    ├── Console
    │   └── stubs
    ├── Controllers
    ├── Facades
    ├── Middleware
    ├── Models
    ├── Repositories
    ├── Requests
    └── Seeds
```

### 依赖包

包名|约束版本|说明
---|---|---
[mews/captcha](https://github.com/mewebstudio/captcha)|^2.2|图形验证码
[spatie/laravel-activitylog](https://github.com/spatie/laravel-activitylog)|^2.8|操作日志记录
[spatie/laravel-permission](https://github.com/spatie/laravel-permission)|^2.37|权限控制
[doctrine/annotations](https://github.com/doctrine/annotations)|^1.8|注解解析

### 安装说明

__发布命令__

```bash
php artisan vendor:publish --provider="Imzhi\JFAdmin\JFAdminServiceProvider"
```

发布必要文件到项目中：

```
├── config          => config_path()
├── database
│   └── migrations  => database_path('migrations')
├── resources
│   ├── assets      => public_path('vendor/jfadmin')
│   │   ├── inspinia
│   │   └── jfadmin
│   ├── lang        => resource_path('lang/vendor/jfadmin')
│   │   ├── en
│   │   └── zh-CN
│   └── views
│       ├── auth
│       ├── home    => resource_path('views/vendor/jfadmin/home')
│       ├── layouts => resource_path('views/vendor/jfadmin/layouts')
│       ├── manageuser
│       ├── profile
│       └── setting
└── src
    ├── Console
    │   └── stubs
    ├── Controllers
    ├── Facades
    ├── Middleware
    ├── Models
    ├── Repositories
    ├── Requests
    └── Seeds
```

__安装命令__

```bash
php artisan jfadmin:install
```

首先会检查配置文件 ``config/jfadmin.php`` 是否存在，安装时需要用到。

执行迁移文件，并且生成初始的管理员用户（默认用户名：admin，密码：admin）。

新建目录（默认为 app/JFAdmin），并生成后台首页控制器类文件（HomeController）和路由文件（routes.php）：

```
app
└── JFAdmin
    ├── Controllers
    │   └── HomeController.php
    └── routes.php
```

### 卸载说明

```bash
php artisan jfadmin:uninstall
```

输入上面命令后会弹出确认提示，键入 yes 后，会开始卸载操作，将会删除：

- ``jfadmin::install`` 新建的目录（默认为 app/JFAdmin）
- ``config_path('jfadmin.php')``
- ``public_path('vendor/jfadmin')``
- ``resource_path('lang/vendor/jfadmin')``
- ``resource_path('views/vendor/jfadmin')``

请注意：卸载命令不会去更改数据表。卸载成功后如需重新安装请先执行发布命令再执行安装命令。

### 配置项

__title__

> 站点标题

__caption__

> 站点标题缩写（小屏浏览时用到）

__welcome__

> 欢迎语句（后台首页右上角的欢迎语句）

__wallpaper__

> 登录页面的背景图（支持 URL 和项目可访问的路径）

__wallpaper_class__

> 登录页面标题文件的 CSS class

__directory__

> 安装目录（jfadmin::install 命令生成文件将要安装的目录）

### 操作日志

操作日志记录使用的 laravel-activitylog 扩展包，数据表是 activity_log。

jf-admin 新增两个响应宏：suc 和 fai，分别对应成功响应宏和失败响应宏，当调用成功响应宏时会进行操作日志的记录。

请注意：这两个响应宏只适用于 Ajax 操作返回响应数据。

### 超级管理员角色

默认的超级管理员角色名称是 Super Admin，也可以在配置文件中设置多个超级管理员角色名称。

初始管理员账号就是超级管理员，可以进行任何操作。

### 中间件

jf-admin 有两个重要的中间件：jfadmin.auth，jfadmin.permission。

jfadmin.auth 检测用户会话登录状态和用户账号的状态。

jfadmin.permission 根据路由名称检测用户的操作权限。

### 其它命令

__重置密码__

```bash
php artisan jfadmin:reset-password
```

输入要修改的管理员用户名，并输入密码、重复密码。

环境
----

- PHP >= 7.1.3
- Laravel >= 5.5

安装
----

__第一步__

使用 Composer 安装 jf-admin 扩展包：

```
composer require imzhi/jf-admin ^1.1
```

__第二步__

发布 [laravel-permission](https://github.com/spatie/laravel-permission) 和 [laravel-activitylog](https://github.com/spatie/laravel-activitylog) 扩展包的迁移文件，并执行迁移命令：

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
php artisan migrate --step
```

发布 [mews/captcha](https://github.com/mewebstudio/captcha) 扩展包的配置文件：

```
php artisan vendor:publish --provider="Mews\Captcha\CaptchaServiceProvider"
```

修改配置文件 ``config/captcha.php`` 的 length，修改成 4：

```php
return [
    // 省略
    'default' => [
        'length' => 4,
```

__第三步__

发布 jf-admin 扩展包的文件，并执行安装命令：

```
php artisan vendor:publish --provider="Imzhi\JFAdmin\JFAdminServiceProvider"
php artisan jfadmin:install
```

经过以上三步，扩展包安装成功。

访问 URL 为：<http://xxx.xxx/jfadmin/login>，默认用户名：admin，密码：admin。

许可证
---

jf-admin 扩展包使用 [MIT](/imzhi/jf-admin/blob/master/LICENSE) 许可证。
