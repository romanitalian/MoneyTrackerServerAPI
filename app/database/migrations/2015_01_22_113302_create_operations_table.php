<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOperationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('operations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('user_id');
			$table->string('comment');
			$table->float('sum');
			$table->date('tr_date');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('operations');
	}

}
