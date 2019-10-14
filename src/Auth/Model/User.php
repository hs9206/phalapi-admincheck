<?php

namespace Phalapi\Auth\Auth\Model;

/*
/**
 * 用户模型
 * @author: hms 2015-8-6
 */

class User extends BaseModel
{
    protected function getTableName($id)
    {
        return \PhalApi\DI()->config->get('app.auth.auth_user');
    }

    public function getUserInfo($uid)
    {
        static $userinfo = array();
        if (!isset($userinfo[$uid])) {
            $userinfo[$uid] = $this->getORM()->where('userid', $uid)->fetchOne();
        }

        return $userinfo[$uid];
    }
}
