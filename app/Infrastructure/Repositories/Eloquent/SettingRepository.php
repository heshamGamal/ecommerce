<?php
namespace App\Infrastructure\Repositories\Eloquent;
use App\Domain\Contracts\Repositories\SettingRepositoryInterface;use App\Domain\Models\Setting;
class SettingRepository implements SettingRepositoryInterface{public function all(?string $group=null):iterable{return Setting::when($group,fn($q)=>$q->where('group',$group))->orderBy('group')->orderBy('key')->get();}public function find(string $key):?Setting{return Setting::where('key',$key)->first();}public function upsert(array $data):Setting{$s=Setting::firstOrNew(['key'=>$data['key']]);$s->fill($data)->save();return $s;}public function delete(Setting $setting):void{$setting->delete();}}
