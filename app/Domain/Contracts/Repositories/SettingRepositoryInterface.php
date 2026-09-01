<?php
namespace App\Domain\Contracts\Repositories;
use App\Domain\Models\Setting;
interface SettingRepositoryInterface{public function all(?string $group=null):iterable;public function find(string $key):?Setting;public function upsert(array $data):Setting;public function delete(Setting $setting):void;}
