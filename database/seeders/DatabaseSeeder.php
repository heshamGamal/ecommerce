<?php

namespace Database\Seeders;

use App\Domain\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Domain\Models\Setting;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'products.view', 'products.create', 'products.update', 'products.delete', 'products.manage_stock', 'categories.view', 'categories.manage',
            'orders.view_all', 'orders.update_status', 'orders.cancel', 'coupons.manage', 'shipping.settle', 'settings.manage', 'users.manage_roles', 'assistants.manage', 'assistants.track', 'assistants.review',
        ];
        $permissionModels = collect($permissions)->map(fn (string $permission) => Permission::findOrCreate($permission, 'web'));
        Role::findOrCreate('admin', 'web')->syncPermissions($permissionModels);
        Role::findOrCreate('customer', 'web')->syncPermissions($permissionModels->whereIn('name', ['products.view', 'categories.view']));
        Role::findOrCreate('assistant_manager', 'web')->syncPermissions($permissionModels->whereIn('name', ['products.view', 'assistants.track']));
        foreach ([
            ['key'=>'site.name','display_name'=>'اسم المتجر','value'=>'My Store','type'=>'text','group'=>'site','is_locked'=>false],
            ['key'=>'site.logo','display_name'=>'شعار المتجر','value'=>null,'type'=>'file','group'=>'site','is_locked'=>false],
            ['key'=>'site.currency','display_name'=>'العملة','value'=>'EGP','type'=>'text','group'=>'site','is_locked'=>true],
            ['key'=>'store.contact_email','display_name'=>'البريد الإلكتروني','value'=>null,'type'=>'text','group'=>'store','is_locked'=>false],
            ['key'=>'store.contact_phone','display_name'=>'هاتف المتجر','value'=>null,'type'=>'text','group'=>'store','is_locked'=>false],
            ['key'=>'shipping.default_cost','display_name'=>'تكلفة الشحن الافتراضية','value'=>'0','type'=>'number','group'=>'shipping','is_locked'=>false],
            ['key'=>'payment.cod_enabled','display_name'=>'تفعيل الدفع عند الاستلام','value'=>true,'type'=>'boolean','group'=>'payment','is_locked'=>false],
            ['key'=>'theme.primary_color','display_name'=>'اللون الرئيسي','value'=>'#111827','type'=>'color','group'=>'theme','is_locked'=>false],
        ] as $setting) Setting::firstOrCreate(['key'=>$setting['key']],$setting);
    }
}
