<?php

namespace Phalapi\Auth\Auth\Model;

use PhalApi\Model\NotORMModel;
use PhalApi\Exception\BadRequestException;

/**
 * 快速获取ORM实例，可用于切换数据库
 * 快速切换机构使用数据库.
 *
 * @param $app_key 应用APP_KEY 对应机构id
 */
class BaseModel extends NotORMModel
{
    // public $app_key;

    protected function getORM($id = null)
    {
        $app_key = \PhalApi\DI()->request->get('app_key');

        //根据app_key使用自身的数据库
        $database = $app_key ? 'notorm_'.$app_key : 'notorm';

        //确保APP已经授权，并有对应的配置文件，否则无法正确访问数据库
        if (!file_exists(API_ROOT.'/config/trainings/dbs_'.$app_key.'.php')) {
            throw new BadRequestException('APP_KEY未授权，或是模型文件配置错误 - 请联系管理：Symo.Chan', 99);
        }

        $table = $this->getTableName($id);

        return \PhalApi\DI()->$database->$table;
    }
}
