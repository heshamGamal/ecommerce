<?php
namespace App\Infrastructure\Database;
use App\Domain\Contracts\TransactionManagerInterface;use Illuminate\Support\Facades\DB;
class DatabaseTransactionManager implements TransactionManagerInterface{public function run(\Closure $callback):mixed{return DB::transaction($callback);}}
