<?php

use yii\db\Migration;

/**
 * Handles the creation of table `url`.
 */
class m171113_103726_create_url_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('url', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment('名称'),
            'cmbProvince'=>$this->string()->comment('省份'),
            'cmbCity'=>$this->string()->comment('市州'),
            'cmbArea'=>$this->string()->comment('县区'),
            'url'=>$this->string()->comment('详细地址'),
            'tel'=>$this->string()->comment('电话'),
            'check'=>$this->integer(1)->comment('是否默认地址（1是，0不是）'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('url');
    }
}
