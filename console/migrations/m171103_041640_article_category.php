<?php

use yii\db\Migration;

class m171103_041640_article_category extends Migration
{
    public function safeUp()
    {
        $this->createTable('article_category',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态(-1删除 0隐藏 1正常)')

        ]);
    }

    public function safeDown()
    {
        echo "m171103_041640_article_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171103_041640_article_category cannot be reverted.\n";

        return false;
    }
    */
}
