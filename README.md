信息化公共服务
===============
基于tp5进行扩展

基本使用：

1.配置：使用.env
可能涉及到配置：
;缓存配置（开发环境）
REDIS_HOST = '127.0.0.1';
REDIS_PWD = '';
REDIS_PORT = 6379;
REDIS_SELECT = 10;
REDIS_PREFIX = 'work:';

;公共服务域名地址
COMMON_SERVICE_DOMAIN = 'https://imsapi-dev.xinchao.com/common/api';

2.使用
composer require ims/common-service

use ImsCommonService\CommonService;

$CommonService = new CommonService();
$re= $CommonService->getUser();
p($re);die;


3.基本类库
//公共服务
use ImsCommonService\CommonService;
//基础model（加入缓存封装）
use ImsCommonService\BaseModel;
//基于tp缓存的封装
use ImsCommonService\TpCacheService;