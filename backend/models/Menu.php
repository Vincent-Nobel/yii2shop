<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '权限ID',
            'url' => '权限路径',
            'sort' => '排序',
        ];
    }
    //二级菜单分类
    public static function getMenu()
    {
//           return ArrayHelper::map(Menu::find()->all(),'parent_id','name');
        $nodes=[0=>'顶级菜单'];
        $node=ArrayHelper::map(Menu::find()->where(['parent_id'=>0])->all(),'id','name');
//        var_dump($node);
        return ArrayHelper::merge($nodes,$node);

    }
    public function getChile()
    {
        return $this->hasMany(Menu::className(),['parent_id'=>'id']);
    }
}
