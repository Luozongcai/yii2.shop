<?php

use yii\db\Migration;

class m171103_041629_article extends Migration
{
    public function safeUp()
    {
        $this->createTable('article',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'article_category_id'=>$this->integer()->comment('文件分类id'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态(-1删除 0隐藏 1正常)'),
            'create_time'=>$this->integer(11)->comment('创建时间')

        ]);
    }

    public function safeDown()
    {
        echo "m171103_041629_article cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171103_041629_article cannot be reverted.\n";

        return false;
    }
    */
}
