<?php

use Darabonba\OpenApi\OpenApiClient;
use AlibabaCloud\Credentials\Credential;
use AlibabaCloud\OpenApiUtil\OpenApiUtilClient;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\Tea\Console\Console;
use Darabonba\OpenApi\Models\Config;
use Darabonba\OpenApi\Models\Params;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\OpenApiRequest;

$path = __DIR__ . '/vendor/autoload.php';
require_once $path;

//
$request       = $_POST + $_GET;
$ifJsonContent = file_get_contents('php://input');
if (!empty($ifJsonContent)) {
    $ifJsonContent = @json_decode($ifJsonContent, true);
    if ($ifJsonContent) $request = $ifJsonContent += $request;
}
$request += [
    'token'    => '',
    'target'   => '',
    'redirect' => '',
];
//
$config = json_decode(
              file_get_contents(__DIR__ . '/config.json'), true
          ) + [
              'endPoint'        => '',
              //
              'RegionId'        => '',
              'NicType'         => 'internet',
              'Direction'       => 'all',
              'SecurityGroupId' => '',
              //
              'credential'      => [
                  'type'              => 'access_key',
                  'access_key_id'     => '',
                  'access_key_secret' => '',
              ],
              'accessToken'     => '',
          ];
//
if (empty($request['target'])) die('invalid target');
if (empty($request['token'])) die('invalid token');
if ($request['token'] != $config['accessToken']) die('invalid token');
//
$client = createClient($config['endPoint'], $config['credential']);
$resp   = makeRequest(
    $client, 'DescribeSecurityGroupAttribute', [
        'SecurityGroupId' => $config['SecurityGroupId'],
        'RegionId'        => $config['RegionId'],
        'NicType'         => $config['NicType'],
        'Direction'       => $config['Direction'],
    ]
);
if (empty($resp['Permissions'])) die('no permissions');
$permissionLs = $resp['Permissions']['Permission'];
$idToDel      = null;
foreach ($permissionLs as $permission) {
    if (empty($permission['Description'])) continue;
    if ($request['target'] != $permission['Description']) continue;
    $idToDel = $permission['SecurityGroupRuleId'];
    break;
}
if (!empty($idToDel)) {
    $resp = makeRequest(
        $client, 'RevokeSecurityGroup', [
            'RegionId'              => $config['RegionId'],
            'SecurityGroupId'       => $config['SecurityGroupId'],
            'SecurityGroupRuleId.1' => $idToDel,
        ]
    );
    if (empty($resp)) die('delete failed');
}
$userIP       = getUserIp();
$isV6         = stripos($userIP, ':') !== false;
$requestParam = [
    'RegionId'                  => $config['RegionId'],
    'SecurityGroupId'           => $config['SecurityGroupId'],
    'SecurityGroupRuleId.1'     => $idToDel,
    'Permissions.1.Policy'      => "accept",
    'Permissions.1.Priority'    => "1",
    'Permissions.1.IpProtocol'  => "ALL",
    'Permissions.1.PortRange'   => "-1/-1",
    'Permissions.1.Description' => $request['target'],
];
if ($isV6) {
    $requestParam['Permissions.1.Ipv6SourceCidrIp'] = $userIP;
} else {
    $requestParam['Permissions.1.SourceCidrIp'] = $userIP;
}
$resp = makeRequest(
    $client, 'AuthorizeSecurityGroup', $requestParam
);
if (empty($resp)) die('add failed');
if (!empty($request['redirect'])) {
    header('Location: ' . $request['redirect']);
}
die('success');

function makeRequest($client, $method, $queries, $version = '2014-05-26') {
    $params = new Params([
        // 接口名称
        "action"      => $method,
        // 接口版本
        "version"     => $version,
        // 接口协议
        "protocol"    => "HTTPS",
        // 接口 HTTP 方法
        "method"      => "POST",
        "authType"    => "AK",
        "style"       => "RPC",
        // 接口 PATH
        "pathname"    => "/",
        // 接口请求体内容格式
        "reqBodyType" => "json",
        // 接口响应体内容格式
        "bodyType"    => "json"
    ]);
// runtime options
    $runtime = new RuntimeOptions([]);
    $request = new OpenApiRequest([
        "query"   => OpenApiUtilClient::query($queries),
        'headers' => [
            'Content-Type' => 'application/json;charset=utf-8',
        ],
    ]);
// 复制代码运行请自行打印 API 的返回值
// 返回值实际为 Map 类型，可从 Map 中获得三类数据：响应体 body、响应头 headers、HTTP 返回的状态码 statusCode。
    $resp = $client->callApi($params, $request, $runtime);
    if (empty($resp['body'])) die('no response');
    return $resp['body'];
}

//
function getUserIp() {
    $defKey = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR',
    ];
    if (isset($_SERVER)) {
        foreach ($defKey as $k) {
            if (!empty($_SERVER[$k])) {
                $v = explode(',', $_SERVER[$k]);
                return $v[0];
            }
        }
    }
    foreach ($defKey as $k) {
        $v = getenv($k);
        if ($v) {
            $v = explode(',', $v);
            return $v[0];
        }
    }
}

/**
 * 使用凭据初始化账号Client
 * @return OpenApiClient Client
 */
function createClient($endPoint, $credentialConfig) {
    // 工程代码建议使用更安全的无AK方式，凭据配置方式请参见：https://help.aliyun.com/document_detail/311677.html。
    $credential = new Credential($credentialConfig);
    $config     = new Config([
        "credential" => $credential
    ]);
    // Endpoint 请参考 https://api.aliyun.com/product/Ecs
    $config->endpoint = $endPoint;
    return new OpenApiClient($config);
}
