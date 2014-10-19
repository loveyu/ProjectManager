恋羽的PHP个人项目发布平台
======================================

## 使用说明

* 初次使用时先导入数据到mysql数据库
* 修改config目录下的all.config 其中的数据库配置文件
* 详细配置文件可以查看sys/config.php,对目录位置进行调整

## 访问说明

* apache下需要PATH_INFO支持
* 或者使用伪静态规则，nginx下需提供重定向支持，已提供默认配置文件
* 修改系统路径需要对伪静态目录访问进行限制，否则会出现绝对路径泄露的问题

## 默认用户名及密码

* 用户名为:`loveyu`
* 密码为:`123456`

## 演示及说明

* 下载 http://www.loveyu.net

* 反馈 http://www.loveyu.org/2882.html