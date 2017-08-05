<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_023055_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
//name
        'name'=>$this->string(50)->comment('菜单名称'),
// url
        'url'=>$this->string(100)->comment('权限路径'),
// parent_id
        'parent_id'=>$this->integer()->comment('权限ID'),
// sort
        'sort'=>$this->integer()->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
