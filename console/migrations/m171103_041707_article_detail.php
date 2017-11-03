<?php

use yii\db\Migration;

class m171103_041707_article_detail extends Migration
{
    public function safeUp()
    {
        $this->createTable('article_detail',[
            'article_id'=>$this->primaryKey(),
            'content'=>$this->text()->comment('简介'),

        ]);
    }

    public function safeDown()
    {
        echo "m171103_041707_article_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171103_041707_article_detail cannot be reverted.\n";

        return false;
    }
    */
}
