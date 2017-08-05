<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/23
 * Time: 23:37
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;

    public $minPrice;
    public $maxPrice;
    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
            ['minPrice','double'],
            ['maxPrice','double'],
        ];
    }
    public function search(ActiveQuery $query)
    {//加载提交表单的数据
        $this->load(\Yii::$app->request->get());
        if ($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if ($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if ($this->maxPrice){
            $query->andWhere(['like','shop_price',$this->maxPrice]);
        }
        if ($this->minPrice){
            $query->andWhere(['like','shop_price',$this->minPrice]);
        }
    }
}