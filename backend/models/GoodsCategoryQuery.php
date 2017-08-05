<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/21
 * Time: 16:43
 */

namespace backend\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class GoodsCategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}