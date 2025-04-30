## 给阿里云的 VPS 安全组自动添加当前 IP 白名单的脚本（ php 版）

最近阿里云的安全组好像不能自动显示当前访问的 IP 了，没法很方便的给自己所在的 IP 开放端口，头疼。。。

没办法，自己写一个吧

安全是肯定不安全，但是个人用用就这样吧

调用的是安全组 API 的 [原生方法](https://help.aliyun.com/zh/ecs/user-guide/overview-44)

配置项写在 config.json ，请求通过静态的 accessToken 验证

请求方式如 /index.php?token={accessToken}&target={目标备注}&redirect={重定向地址，可选}

根据 OpenAPI 门户的 [DEMO](https://api.aliyun.com/api/Ecs/2014-05-26/AuthorizeSecurityGroup?useCommon=true) 改的

php 的 sdk 有各种会导致不能运行的问题，如 [这种](https://github.com/aliyun/openapi-sdk-php/issues/208) ，本地反正 8.1.22 跑不起来。。。

改了改勉强能跑，所以带 vender 提交

放在服务器上就行，访问后自动根据请求 IP 和标题进行修改
