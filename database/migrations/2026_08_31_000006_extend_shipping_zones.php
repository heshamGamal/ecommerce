<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up():void{Schema::table('shipping_zones',function(Blueprint $t){$t->string('name')->default('Default Zone');$t->string('code')->unique();$t->json('cities')->nullable();});}public function down():void{Schema::table('shipping_zones',function(Blueprint $t){$t->dropUnique(['code']);$t->dropColumn(['name','code','cities']);});}};
