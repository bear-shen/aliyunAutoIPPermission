# 查询安全组和组内规则信息完整工程示例

该项目为DescribeSecurityGroupAttribute的完整工程示例。

该示例**无法在线调试**，如需调试可下载到本地后替换 [AK](https://usercenter.console.aliyun.com/#/manage/ak) 以及参数后进行调试。

## 运行条件

- 下载并解压需要语言的代码;


- 在阿里云帐户中获取您的 [凭证](https://usercenter.console.aliyun.com/#/manage/ak) 并通过它替换下载后代码中的 ACCESS_KEY_ID 以及 ACCESS_KEY_SECRET;

- 执行对应语言的构建及运行语句

## 执行步骤

下载的代码包，在根据自己需要更改代码中的参数和 AK 以后，可以在**解压代码所在目录下**按如下的步骤执行：

- *最低要求 PHP 5.6*
- *必须在系统上[全局安装 Composer](https://getcomposer.org/doc/00-intro.md?spm=api-workbench.SDK%20Document.0.0.206f726ceIMZ36#globally)*
- *注意：执行 composer 安装 SDK 的 PHP 版本要小于或等于实际运行时的 PHP 版本。 例如，在 PHP7.2 环境下安装 SDK 后生成 vendor 目录，只能在 PHP7.2 以上版本使用，如果拷贝到 PHP5.6 环境下使用，会出现依赖版本不兼容问题。*
>*一些用户可能由于网络问题无法安装，可以通过以下命令切换为阿里云 Composer 全量镜像。*
```sh
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
- *执行命令*
```sh
composer install && php src/Sample.php
```
## 使用的 API

-  DescribeSecurityGroupAttribute：本接口主要用于查询一个指定安全组的详细信息，并关联查询安全组规则详细信息列表。 更多信息可参考：[文档](https://next.api.aliyun.com/document/Ecs/2014-05-26/DescribeSecurityGroupAttribute)

## API 返回示例

*实际输出结构可能稍有不同，属于正常返回；下列输出值仅作为参考，以实际调用为准*


- JSON 格式 
```js
{
  "VpcId": "vpc-bp1opxu1zkhn00gzv****",
  "RequestId": "473469C7-AA6F-4DC5-B3DB-A3DC0DE3C83E",
  "InnerAccessPolicy": "Accept",
  "Description": "This is description.",
  "SecurityGroupId": "sg-bp1gxw6bznjjvhu3****",
  "SecurityGroupName": "SecurityGroupName Sample",
  "RegionId": "cn-hangzhou",
  "Permissions": {
    "Permission": [
      {
        "SecurityGroupRuleId": "sgr-bp12kewq32dfwrdi****",
        "Direction": "ingress",
        "SourceGroupId": "sg-bp12kc4rqohaf2js****",
        "DestGroupOwnerAccount": "1234567890",
        "DestPrefixListId": "pl-x1j1k5ykzqlixabc****",
        "DestPrefixListName": "DestPrefixListName Sample",
        "SourceCidrIp": "0.0.0.0/0",
        "Ipv6DestCidrIp": "2001:db8:1233:1a00::***",
        "CreateTime": "2018-12-12T07:28:38Z",
        "Ipv6SourceCidrIp": "2001:db8:1234:1a00::***",
        "DestGroupId": "sg-bp1czdx84jd88i7v****",
        "DestCidrIp": "0.0.0.0/0",
        "IpProtocol": "TCP",
        "Priority": "1",
        "DestGroupName": "testDestGroupName",
        "NicType": "intranet",
        "Policy": "Accept",
        "Description": "Description Sample 01",
        "PortRange": "80/80",
        "SourcePrefixListName": "SourcePrefixListName Sample",
        "SourcePrefixListId": "pl-x1j1k5ykzqlixdcy****",
        "SourceGroupOwnerAccount": "1234567890",
        "SourceGroupName": "testSourceGroupName1",
        "SourcePortRange": "80/80",
        "PortRangeListId": "prl-2ze9743****",
        "PortRangeListName": "PortRangeListNameSample"
      }
    ]
  },
  "NextToken": "AAAAAdDWBF2****"
}
```

