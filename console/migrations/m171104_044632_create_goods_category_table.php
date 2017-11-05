<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m171104_044632_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer()->comment('树id'),
            'lft'=>$this->integer()->comment('左值'),
            'rgt'=>$this->integer()->comment('右值'),
            'depth'=>$this->integer()->comment('层级'),
            'parent_id'=>$this->integer()->comment('上级分类id'),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
