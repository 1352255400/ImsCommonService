<?php
/**
 *
 * @version 1.0
 * @author: TianChao <191818715@qq.com>
 * @since: 2020/4/8 13:55
 *
 */

namespace ImsCommonService;


class CommonFunction
{

    /**
     * 组装返回值数组
     * @author TianChao
     * @since 2020/4/8
     * @param string $code
     * @param string $msg
     * @param array $data
     * @return mixed
     */
    public static function returnResult($code = '000', $msg = '', $data = [])
    {
        $arr['code'] = $code;
        $arr['msg'] = $msg;
        $arr['data'] = $data;
        return $arr;
    }

    /**
     * 字段验证函数
     * @author TianChao
     * @since 2020/4/8
     * @param string $val
     * @param string $rules
     * @param string $title
     * @return array
     */
    public static function customValidate($val = '', $rules = '', $title = '')
    {
        if (empty($rules) || empty($title)) {
            return array('code' => '1000', 'data' => '', 'msg' => '验证参数不正确！');
        }

        //拆分验证规则
        $rules_arr = explode('|', $rules);
        //初始化错误信息
        $msg = '';
        foreach ($rules_arr as $rule) {
            //拆分单个验证规则
            $rule_arr = explode(':', $rule);
            // 验证类型
            $checkType = isset($rule_arr[0]) ? $rule_arr[0] : '';
            // 验证长度
            $checkNum = isset($rule_arr[1]) ? $rule_arr[1] : 0;
            switch ($checkType) {
                case 'require':
                    // 必须
                    $re = !empty($val) || '0' == $val;
                    $msg = $title . '不能为空';
                    break;
                case 'min':
                    //最小长度
                    if (is_array($val)) {
                        $length = count($val);
                    } else {
                        $length = mb_strlen((string)$val);
                    }

                    $re = $length >= $checkNum;
                    $msg = $title . '长度不能小于' . $checkNum;
                    break;
                case 'max':
                    //最大长度
                    if (is_array($val)) {
                        $length = count($val);
                    } else {
                        $length = mb_strlen((string)$val);
                    }

                    $re = $length <= $checkNum;
                    $msg = $title . '长度不能大于' . $checkNum;
                    break;
                case 'number':
                    //数字
                    $re = is_numeric($val);
                    $msg = $title . '必须为数字类型';
                    break;
                case 'email':
                    //邮箱
                    $re = false;
                    if (is_string($val)) {
                        $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
                        $re = preg_match($preg_email, $val);
                    }
                    $msg = $title . '必须为邮箱类型';
                    break;
                case 'mobile':
                    // 手机
                    $re = false;
                    if (is_numeric($val)) {
                        $preg_phone = '/^1[34578]\d{9}$/ims';
                        $re = preg_match($preg_phone, $val);
                    }
                    $msg = $title . '必须为手机类型';
                    break;
                case 'url':
                    //链接
                    $re = false;
                    if (is_string($val)) {
                        $preg_url = '/^http[s]?:\/\/' .
                            '(([0-9]{1,3}\.){3}[0-9]{1,3}' .             // IP形式的URL- 199.194.52.184
                            '|' .                                        // 允许IP和DOMAIN（域名）
                            '([0-9a-z_!~*\'()-]+\.)*' .                  // 三级域验证- www.
                            '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.' .     // 二级域验证
                            '[a-z]{2,6})' .                              // 顶级域验证.com or .museum
                            '(:[0-9]{1,4})?' .                           // 端口- :80
                            '((\/\?)|' .                                 // 如果含有文件对文件部分进行校验
                            '(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/';
                        $re = preg_match($preg_url, $val);
                    }
                    $msg = $title . '必须为url类型';
                    break;
                default:
                    $re = true;
                    break;
            }

            if (!$re) {
                return array('code' => '1000', 'data' => '', 'msg' => $msg);
            }
        }

        return array('code' => '000', 'data' => '', 'msg' => 'ok');

    }

    /**
     * curl函数请求
     * @author TianChao
     * @since 2020/4/8
     * @param $url
     * @param string $type
     * @param string $data
     * @param array $headers
     * @return mixed
     */
    public static function curlRequest($url, $type = 'get', $data = '', $headers = array('Content-Type: application/json; charset=utf-8'))
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($type == 'post') {
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }
}