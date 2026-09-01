<?php
namespace Tests\Architecture;
use PHPUnit\Framework\TestCase;
class LayerDependencyTest extends TestCase
{
 private function root(): string { return dirname(__DIR__, 2); }
 public function test_domain_does_not_depend_on_http_or_controllers():void{$iterator=new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root().'/app/Domain'));foreach($iterator as $file){if(!$file->isFile())continue;$content=file_get_contents($file->getPathname());$this->assertStringNotContainsString('App\\Http\\',$content,$file->getPathname());$this->assertStringNotContainsString('App\\Application\\',$content,$file->getPathname());}}
 public function test_layers_exist_and_no_empty_application_files_remain():void{$this->assertDirectoryExists($this->root().'/app/Domain');$this->assertDirectoryExists($this->root().'/app/Application');$this->assertDirectoryExists($this->root().'/app/Infrastructure');$iterator=new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root().'/app'));foreach($iterator as $file)if($file->isFile())$this->assertGreaterThan(0,$file->getSize(),$file->getPathname());}
}
