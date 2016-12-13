# [Viease](http://viease.cn/)

微易，一款基于 [Laravel](http://laravel.com) 开发的，使用 [overtrue/wechat](https://github.com/overtrue/wechat) SDK 做为微信接入的微信公众平台管理系统框架。http://viease.cn/

> 此项目为我们多个人业余未完全做完的一个产品，只是开源出来大家去贡献，一起完善它。
> 那么，请不要在群里 @ 我提一些细节问题，我没有时间来帮忙你解决。你要是解决不了，就不要用了，你要是解决了，欢迎贡献 PR。
> 你在玩这个东西之前有必要阅读完这一整篇 README，否则加群问问题也没有人理你。
> 最后，谢谢配合！:pray:

:heart: 请不要加我们开发人员的 QQ 提问，感谢！

> 请不要提 issue! 请不要提问！请不要在产品环境使用！
> 不接受任何形式的提问！谢谢理解！

# 安装

1. Git clone 并安装

 ```shell
 git clone https://github.com/vieasehub/viease
 cd viease
 composer install -v
 chmod -R 777 storage
 chmod -R 777 bootstrap/cache
 ```

2. 创建 `.env` 文件

 ```shell
 $ curl https://raw.githubusercontent.com/laravel/laravel/master/.env.example -o .env
 // 生成key
 $ php artisan key:generate
 ```

 以及修改 `.env` 内对应的项目

3. 配置 nginx 虚拟主机

 ```
 server {
     listen 80;
     server_name viease.app; # 有必要改成你的域名
     root "/path/to/viease"; # 这里改成你的viease所在目录的public

     index index.html index.htm index.php;

     charset utf-8;

     location / {
         try_files $uri $uri/ /index.php?$query_string;
     }

     location = /favicon.ico { access_log off; log_not_found off; }
     location = /robots.txt  { access_log off; log_not_found off; }

     access_log /usr/local/var/log/nginx/viease.app-access.log;
     error_log  /usr/local/var/log/nginx/viease.app-error.log error;

     sendfile off;

     client_max_body_size 100m;

     location ~ \.php$ {
         fastcgi_split_path_info ^(.+\.php)(/.+)$;
         fastcgi_pass 127.0.0.1:9000;
         fastcgi_index index.php;
         include fastcgi_params;
         fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
         fastcgi_intercept_errors off;
         fastcgi_buffer_size 16k;
         fastcgi_buffers 4 16k;
     }

     location ~ /\.ht {
         deny all;
     }
 }
 ```
4. 创建数据库

 确保你的 `.env` 里的数据库配置已经 OK.

 ```shell
 $ php artisan migrate
 $ php artisan db:seed
 ```

5. 访问后台： `yourdomain.com/admin`

 ```
 email: admin
 password: viease
 ```

6. 添加公众号
7. 同步粉丝

 ```shell
 $ php artisan sync:all_fans # 获取全部公众号,同步粉丝
 $ php artisan sync:all_groups # 获取全部公众号,同步粉丝组
 ```
 这一步估计耗时很长...

完事。


# 开发步骤

1. gulp安装：

   首先安装npm,然后执行:

    ```shell
    $ npm install gulp
    ```
2. ruby,sass安装:

   [http://www.w3cplus.com/sassguide/install.html](http://www.w3cplus.com/sassguide/install.html)

3. 修改scss文件,执行gulp
    ```shell
    $ cd /your-project-directory/public
    $ gulp
    ```

# 友情提示

1. 如果你使用 Homestead，环境可能缺少 mcrypt 拓展，那么使用以下命令来更新系统：

    ```shell
    $ sudo apt-get update && sudo apt-get install mcrypt php7.0-mcrypt && sudo apt-get upgrade
    ```

# Docker

1. 运行下面命令启动容器（镜像下载过程可能会很漫长，请尝试国内Docker服务商提供的加速服务）

    ```shell
    $ sudo docker run -d -p 8080:80 -p 2222:22 lee2011/viease
    ```

2. ssh 到你的容器中 （密码：secret）

    ```shell
    $ ssh -p 2222 homestead@localhost
    ```

3. 修改 `/var/www/html/.env` 文件，配置数据库(容器中不包含数据库，请根据需要自己安装或者连接其他主机上的数据库服务器)，然后运行 artisan 命令创建数据库

    ```shell
    $ php artisan migrate
    $ php artisan db:seed
    ```

4. 访问后台： `your-server-ip:8080/admin`


# 说明

1. 公众号没认证玩不了。
1. 你完全有必要会用 [Laravel](http://laravel.com)，否则提一些关于基本框架使用的问题很有可能没有人回答。
1. 不要太过自信，上面的步骤你完全有必要照着做，除非你完全能掌控它。
1. 本项目后台部分功能未完成，只有后台，前台就一个首页，所以不会有 OAuth 之类的演示功能，我们期望有时间的朋友可以补充完善它，谢谢！QQ群：319502940

# License

MIT