<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up():void{Schema::table('shipping_settlements',function(Blueprint $t){$t->foreignUuid('shipment_id')->after('id')->constrained('shipments')->cascadeOnDelete();$t->unique('shipment_id');});}public function down():void{Schema::table('shipping_settlements',function(Blueprint $t){$t->dropUnique(['shipment_id']);$t->dropForeign(['shipment_id']);$t->dropColumn('shipment_id');});}};
