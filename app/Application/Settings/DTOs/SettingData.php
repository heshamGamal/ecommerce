<?php
namespace App\Application\Settings\DTOs;
final class SettingData{public function __construct(public readonly string $key,public readonly string $displayName,public readonly mixed $value,public readonly string $type='text',public readonly string $group='general',public readonly bool $locked=false){}public static function fromArray(array $d):self{return new self((string)($d['key']??''),(string)($d['display_name']??$d['key']??''),$d['value']??null,(string)($d['type']??'text'),(string)($d['group']??'general'),(bool)($d['is_locked']??false));}}
