<?php

namespace Phalapi\Auth\Auth\Api;

use PhalApi\Api;
use Phalapi\Auth\Auth\Domain\Group as Domain_Auth_Group;

/**
 * Class Api_Auth_Group 组接口服务类.
 * title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用.
 *
 * @author: symo chan
 */
class Group extends Api
{
    private static $Domain = null;

    public function __construct()
    {
        if (self::$Domain == null) {
            self::$Domain = new Domain_Auth_Group();
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
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '组id'),
            ),
            'add' => array(
                'title' => array('name' => 'title', 'type' => 'string', 'require' => true, 'desc' => '组名称'),
                'status' => array('name' => 'status', 'type' => 'int', 'default' => 1, 'desc' => '状态，1.正常，0.禁用'),
            ),
            'edit' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '需要修改的组id'),
                'title' => array('name' => 'title', 'type' => 'string', 'require' => true, 'desc' => '组名称'),
                'status' => array('name' => 'status', 'type' => 'int', 'title' => '状态，1.正常，0.禁用'),
            ),
            'del' => array(
                'ids' => array('name' => 'ids', 'type' => 'string', 'require' => true, 'min' => 1, 'desc' => '组id，逗号隔开多个'),
            ),
            'setRules' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '组id'),
                'rules' => array('name' => 'rules', 'type' => 'string', 'default' => '', 'desc' => '规则id，逗号隔开多个'),
            ),
            'assUser' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '用户id'),
                'group_id' => array('name' => 'gid', 'type' => 'string', 'default' => '', 'desc' => '组id，逗号隔开多个'),
            ),
        );
    }

    /**
     * 获取组列表.
     *
     * @desc <font color="red">[已完成] </font>获取角色权限组列表，status 状态：为1正常，为0禁用
     *
     * @return int    err_code 业务代码
     * @return object info 组信息对象
     * @return object info.items 组数据行
     * @return int    info.count 数据总数，用于分页
     * @return string err_msg 业务消息
     */
    public function getList()
    {
        $rs = array('err_code' => 0, 'info' => array(), 'err_msg' => '');
        $rs['info'] = self::$Domain->getGroupList($this);

        return $rs;
    }

    /**
     * 获取单个组信息.
     *
     * @desc <font color="red">[已完成] </font>获取单一角色组详情，relus值为权限规则对应的IDS
     *
     * @return int    err_code 业务代码：0.获取成功，1.获取失败
     * @return object info 组信息对象,获取失败为空
     * @return string err_msg 业务消息
     */
    public function getInfo()
    {
        $rs = array('err_code' => 0, 'info' => array(), 'err_msg' => '');
        $r = self::$Domain->getGroupOne($this->id);
        if (is_array($r)) {
            $rs['info'] = $r;
        } else {
            $rs['err_code'] = 1;
            $rs['err_msg'] = \PhalApi\T('data get failed');
        }

        return $rs;
    }

    /**
     * 创建组.
     *
     * @desc <font color="red">[已完成] </font>创建角色组
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败，2.组名重复
     * @return string err_msg 业务消息
     */
    public function add()
    {
        $rs = array('err_code' => 0, 'err_msg' => '');
        $r = self::$Domain->addGroup($this);
        if ($r == 0) {
            $rs['err_msg'] = \PhalApi\T('success');
        } elseif ($r == 1) {
            $rs['err_msg'] = \PhalApi\T('failed');
        } elseif ($r == 2) {
            $rs['err_msg'] = \PhalApi\T('group name repeat');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 修改组.
     *
     * @desc <font color="red">[已完成] </font>修改角色组信息
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败，2.组名重复
     * @return string err_msg 业务消息
     */
    public function edit()
    {
        $rs = array('err_code' => 0, 'err_msg' => '');
        $r = self::$Domain->editGroup($this);
        if ($r == 0) {
            $rs['err_msg'] = \PhalApi\T('success');
        } elseif ($r == 1) {
            $rs['err_msg'] = \PhalApi\T('failed');
        } elseif ($r == 2) {
            $rs['err_msg'] = \PhalApi\T('group name repeat');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 删除组.
     *
     * @desc <font color="red">[已完成] </font>删除角色组，（硬删除）.软删除可通过 edit接口设置status为0
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败
     * @return string msg 业务消息
     */
    public function del()
    {
        $rs = array('err_code' => 0, 'msg' => '');
        $r = self::$Domain->delGroup($this->ids);
        if ($r == 0) {
            $rs['msg'] = \PhalApi\T('success');
        } else {
            $rs['msg'] = \PhalApi\T('failed');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 设置规则.
     *
     * @desc <font color="red">[已完成] </font>设置规则关联，rules里的规则ID，多条用『，』分隔。
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败
     * @return string 业务消息
     */
    public function setRules()
    {
        $rs = array('err_code' => 0, 'msg' => '');
        $r = self::$Domain->setRules($this->id, $this->rules);
        if ($r == 0) {
            $rs['msg'] = \PhalApi\T('success');
        } else {
            $rs['msg'] = \PhalApi\T('failed');
        }
        $rs['err_code'] = $r;

        return $rs;
    }

    /**
     * 组关联用户.
     *
     * @desc <font color="red">[已完成] </font>角色组关联用户ID，多条用『，』分隔。
     *
     * @return int    err_code 业务代码：0.操作成功，1.操作失败
     * @return string 业务消息
     */
    public function assUser()
    {
        $rs = array('err_code' => 0, 'msg' => '');
        $r = self::$Domain->assUser($this);
        if ($r == 0) {
            $rs['msg'] = \PhalApi\T('success');
        } else {
            $rs['msg'] = \PhalApi\T('failed');
        }
        $rs['err_code'] = $r;

        return $rs;
    }
}
