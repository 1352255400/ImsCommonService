# 欢迎使用ims-common-service公共服务

**ims-common-service是基于thinkphp5.1的扩展**

执行命令：`composer require ims/common-service`


| 类库  | 说明 |
| ------------- | ------------- |
| use ImsCommonService\BaseModel;  | 基础model  |
| use ImsCommonService\TpCacheService;  | 缓存封装（redis）  |
| use ImsCommonService\CommonService;  | 公共函数  |
| use ImsCommonService\Code\Code;  | 动态图片验证码  |
| use ImsCommonService\ExcelService;  | phpexcle(导入、导出)  |


### 基础model use ImsCommonService\BaseModel;

> 该类封装了对model的操作，实现查询自动缓存，变化后自动清除缓存。

基本用法：

    <?php
        namespace app\index\model;
		
		use ImsCommonService\BaseModel;

		/**
		 * DemoModel demo 模型
		 * @version 1.0
		 * @author: W_wang
		 * @since: 2019/1/25 9:51
		 */
		class DemoModel extends BaseModel
		{

			public function __construct()
			{
				parent::__construct();
				//初始化表名称
				$this->table = env('DB_DATABASE') . '.wh_demo';
				// 排序字段
				$this->order = 'id desc';
				$this->fields = 'id,name,wh_demo.age';//初始化返回字段
				$this->isShowSql = 1;//初始化返回sql标识0不返回，1返回

				//链表配置(可选)
				$this->joinTable = 'wh_dept';
				$this->joinVal = 'wh_demo.demo_id = wh_dept.id';
				$this->joinType = 'left';
			}
		}
    ?>

```
.env配置说明
;数据库配置（开发环境）
DB_HOST = '127.0.0.1';
DB_USER = 'root';
DB_PWD = '';
DB_DATABASE = 'xinxinst';
DB_PORT = 3306;
DB_PREFIX = '';表前缀
```
----

### 缓存封装（redis） use ImsCommonService\TpCacheService;

> 对redis的封装

基本用法：

    <?php
        namespace app\index\controller;
		
		use think\Controller;
		use ImsCommonService\TpCacheService;

		/**
		 * DemoController demo 控制器
		 * @version 1.0
		 * @author: W_wang
		 * @since: 2019/1/25 9:51
		 */
		class DemoController extends Controller
		{

			public function cache()
			{
				$this->cache = new TpCacheService();
				//缓存key
				$cacheKey = 'demo';
				//写缓存
				$re = $this->cache->set($cacheKey, time(), 100);
				var_dump($re);
				//读缓存
				$re = $this->cache->get($cacheKey);
				var_dump($re);
				//删除缓存
				// $re = $this->cache->delete($cacheKey);
				var_dump($re);

				缓存组用法
				//缓存组key
				$cacheKeyMain = 'demo_main';
				//写缓存（组）
				$re = $this->cache->saveWithKey($cacheKeyMain, $cacheKey . rand(1, 100), array(
					"do" => 1,
					"data" => time()
				), 100);
				var_dump($re);
				//删除缓存（组）
				// $re = $this->cache->delWithKey($cacheKeyMain);
				var_dump($re);
			}
		}
    ?>

```
.env配置说明
;缓存配置（开发环境）
REDIS_HOST = '127.0.0.1';
REDIS_PWD = '';
REDIS_PORT = 6379;
REDIS_SELECT = 1;
REDIS_PREFIX = 'demo:';
```
----
### 公共函数 use ImsCommonService\CommonService;

> 公共服务的封装

基本用法：

    <?php
        namespace app\index\controller;
		
		use think\Controller;
		use ImsCommonService\CommonService;

		/**
		 * DemoController demo 控制器
		 * @version 1.0
		 * @author: W_wang
		 * @since: 2019/1/25 9:51
		 */
		class DemoController extends Controller
		{

			public function CommonService()
			{
				//初始化类
				$CommonService = new CommonService();
				//调用方法
				$data = $CommonService->getUser();
				p($data);
			}
		}
    ?>
----

### 动态图片验证码 use ImsCommonService\Code\Code;

> 动态图片验证码封装

基本用法：

    <?php
        namespace app\index\controller;
		
		use think\Controller;
		use ImsCommonService\Code\Code;

		/**
		 * DemoController demo 控制器
		 * @version 1.0
		 * @author: W_wang
		 * @since: 2019/1/25 9:51
		 */
		class DemoController extends Controller
		{

			public function code()
			{
				$code = trim(input('code'));
				//验证code
				if (!empty($code)) {
					$data = Code::checkCode($code);
					echo json_encode($data);
					die;
				}

				//获取验证码
				$width = '350';
				$height = '50';
				$font_size = '20';
				echo Code::getCode($width, $height, $font_size);
				die;
			}
		}
    ?>
--------

### PHPExcel use ImsCommonService\ExcelService;

> PHPExcel 导入、导出

基本用法：
```
composer require ims/common-service 公共新增了phpExcel（导入和导出）
use ImsCommonService\ExcelService;
$excle = new  ExcelService();

导入：
$file = $_FILES['upfile'];//上传方式
$file = 'file/demo.xlsx';//文件方式
$data = $excle->import($file);

导出：（多个sheet）
//导出Excel
$data = [];
$data [] = [
	'name' => '测试1',//sheet名称
	'title' => ['标题1', '标题2', '标题3'], //表头
	'data' => [['a1', 'b1', 'c1'], ['aa1', 'bb1', 'cc1']] ,//内容
	'color' => 'FFCC0001',//字体颜色
	'color_row' => [['row' => 1, 'col_num' => 1], ['row' => 3, 'col_num' => 2]] //row第几行，col_num列数
];
$data [] = [
	'name' => '测试2',
	'title' => ['标题11', '标题22', '标题33'],
	'data' => [['a2', 'b2', 'c2'], ['aa2', 'bb2', 'cc2']],
];
//1.文件名，2.文件内容，3.保存地址（不填直接下载）
$re = $excle->export('demo', $data , 'file/');
```
    
----

```
.env配置说明（完整）
;当前环境描述
APP_ENV = 'dev';

;调试开关
APP_DEBUG = true;

;数据库配置（开发环境）
DB_HOST = '127.0.0.1';
DB_USER = 'root';
DB_PWD = 'root';
DB_DATABASE = 'demo';
DB_PORT = 3306;
;表前缀
DB_PREFIX = 'demo_';

;缓存配置（开发环境）
REDIS_HOST = '127.0.0.1';
REDIS_PWD = '';
REDIS_PORT = 6379;
;redis库
REDIS_SELECT = 11;
;缓存前缀（：可以分层）
REDIS_PREFIX = 'demo:';

;钉钉配置(开发工作台)
;应用id
DING_AGENT_ID = '';
DING_CORP_ID = '';
DING_CORP_SECRET = '';

;公共公共服务地址
COMMON_SERVICE_DOMAIN = 'http://common-service/';
;授權地址
PLATFORM_SERVICE_DOMAIN = 'https://d-imsapi.xinchao.com/platform/api/';

```

### End