<?php

namespace Phalapi\Auth\Auth\Api;

use PhalApi\Api;
use Phalapi\Auth\Auth\Domain\Rule as Domain_Auth_Rule;

/**
 * Class Api_Auth_Rule 规则接口服务类.
 *
 * @author: hms 2015-6-8
 */
class Rule extends Api
{
    private static $Domain = null;

    public function __construct()
    {
        if (self::$Domain == null) {
            self::$Domain = new Domain_Auth_Rule();
        }
    }

    public function getRules()
    {
        return array(
            'getList' => array(
                'keyWord' => array('name' => 'keyword', 'type' => 'string', 'default' => '', 'desc' => '关键词'),
                'field' => array('name' => 'field', 'type' => 'string', 'default' => '*', 'desc' => '返回字段'),
                'limitPage' => array('name' => 'limit_page', 'type' => 'int', 'default' => '0', 'desc' => '分页页码'),
                'limitCount' => array('name' => 'limit_count', 'type' => 'int', 'default' => '20', 'desc' => '单页记录条数，默认为20'),
                'order' => array('name' => 'order', 'type' => 'string', 'default' => '', 'desc' => '排序参数，如：xx ASC,xx DESC'),
            ),
            'getInfo' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '规则id'),
            ),
            'add' => array(
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '规则标识'),
                'title' => array('name' => 'title', 'type' => 'string', 'default' => '', 'desc' => '规则描述'),
                'status' => array('name' => 'status', 'type' => 'int', 'default' => 1, 'desc' => '状态，1.正常，0.禁用'),
                'add_condition' => array('name' => 'condition', 'type' => 'string', 'default' => '', 'desc' => '附加条件'),
            ),
            'edit' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '修改的规则id'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '规则标识'),
                'title' => array('name' => 'title', 'type' => 'string', 'default' => '', 'desc' => '规则描述'),
                'status' => array('name' => 'status', 'type' => 'int', 'default' => 1, 'desc' => '状态，1.正常，0.禁用'),
                'add_condition' => array('name' => 'condition', 'default' => '', 'type' => 'string', 'desc' => '附加条件'),
            ),
            'del' => array(
                'ids' => array('name' => 'ids', 'type' => 'string', 'require' => true, 'default' => '', 'min' => 1, 'desc' => '规则id，逗号隔开多个'),
            ),
        );
    }

    /**
     * 获取规则列表.
     *
     * @desc <font color="red">[已完成] </font> 获取权限认证规则
     *
     * @return int    err_code 业务代码
     * @return object info 规则信息对象
     * @return object info.items 组数据行
     * @return int    info.count 数据总数，用于分页
     * @return string err_msg 业务消息
     */
    public function getList()
    {
        $rs = array('err_code' => 0, 'info' => array(), 'err_msg' => '');
        $rs['info'] = self::$Domain->getList($this);

        return $rs;
    }

    /**
     * 获取单个规则信息.
     *
     * @desc <font color="red">[已完成] </font> 获取单个权限认证详情
     *
     * @return int    err_code 业务代码：0.获取成功，1.获取失败
     * @return object info 规则信息对象,获取失败为空
     * @return string err_msg 业务消息
     */
    public function getInfo()
    {
        $rs = array('err_code' => 0, 'info' => array(), 'err_msg' => '');
        $r = self::$Domain->getInfo($this->id);
        if (is_array($r)) {
            $rs['info'] = $r;
        } else {
            $rs['err_code'] = 1;
            $rs['err_msg'] = \PhalApi\T('data get failed');
        }

        return $rs;
    }

    /**
     * 创建规则.
     *
     * @desc <font color="red">[已完成] </font> 创建权限认证规则,Name值直接用 ，分隔，不需要使用 "" 为包含规则内容
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败，2.规则标识重复
     * @return string err_msg 业务消息
     */
    public function add()
    {
        $rs = array('err_code' => 0, 'err_msg' => '');
        $r = self::$Domain->addRule($this);
        if ($r == 0) {
            $rs['err_msg'] = \PhalApi\T('success');
        } elseif ($r == 1) {
            $rs['err_msg'] = \PhalApi\T('failed');
        } elseif ($r == 2) {
            $rs['err_msg'] = \PhalApi\T('rule name repeat');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 修改规则.
     *
     * @desc <font color="red">[已完成] </font> 修改权限认证规则，,Name值直接用 ，分隔，不需要使用 "" 为包含规则内容
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败，2.规则重复
     * @return string err_msg 业务消息
     */
    public function edit()
    {
        $rs = array('err_code' => 0, 'err_msg' => '');
        $r = self::$Domain->editRule($this);
        if ($r == 0) {
            $rs['err_msg'] = \PhalApi\T('success');
        } elseif ($r == 1) {
            $rs['err_msg'] = \PhalApi\T('failed');
        } elseif ($r == 2) {
            $rs['err_msg'] = \PhalApi\T('rule name repeat');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 删除规则.
     *
     * @desc <font color="red">[已完成] </font> 删除权限认证规则
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败
     * @return string err_msg 业务消息
     */
    public function del()
    {
        $rs = array('err_code' => 0, 'err_msg' => '');
        $r = self::$Domain->delRule($this->ids);
        if ($r == 0) {
            $rs['err_msg'] = \PhalApi\T('success');
        } else {
            $rs['err_msg'] = \PhalApi\T('failed');
        }
        $rs['err_code'] = $r;

        return $rs;
    }
}
