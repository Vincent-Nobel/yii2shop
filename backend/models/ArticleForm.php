<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/21
 * Time: 8:42
 */
namespace backend\models;

use yii\base\Model;
//use yii\db\ActiveRecord;

class ArticleForm  extends Model
{
    public $name;
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
        ];
    }
}