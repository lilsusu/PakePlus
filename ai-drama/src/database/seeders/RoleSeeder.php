<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * 角色 Seeder — 9 种预置角色 + 权限分配
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 必须先跑 PermissionSeeder
        $this->call(PermissionSeeder::class);

        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => '超级管理员',
                'description' => '平台全局管理，所有模块读写',
                'data_scope' => 'all',
            ],
            [
                'name' => 'operation',
                'display_name' => '运营',
                'description' => '日常运营管理：用户/内容/画布/公告/优惠券',
                'data_scope' => 'all',
            ],
            [
                'name' => 'finance',
                'display_name' => '财务',
                'description' => '资金与积分管理：订单/退款/积分调整/财务统计',
                'data_scope' => 'all',
            ],
            [
                'name' => 'support',
                'display_name' => '客服',
                'description' => '用户支持：用户详情只读、消息发送',
                'data_scope' => 'all',
            ],
            [
                'name' => 'auditor',
                'display_name' => '审核员',
                'description' => '内容审核：作品/画布/提示词审核',
                'data_scope' => 'all',
            ],
            [
                'name' => 'prompt_engineer',
                'display_name' => '提示词工程师',
                'description' => '提示词中心全部功能',
                'data_scope' => 'all',
            ],
            [
                'name' => 'user',
                'display_name' => '普通用户',
                'description' => '用户端全部功能，积分消耗',
                'data_scope' => 'self',
            ],
            [
                'name' => 'creator',
                'display_name' => '创作者',
                'description' => '普通用户 + 画布发布/共享/模板',
                'data_scope' => 'self',
            ],
            [
                'name' => 'enterprise',
                'display_name' => '企业用户',
                'description' => '普通用户 + 子账号管理（P2）',
                'data_scope' => 'self',
            ],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(
                ['name' => $r['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $r['display_name'],
                    'description' => $r['description'],
                    'data_scope' => $r['data_scope'],
                ]
            );
        }

        // ============ 权限分配 ============

        // 超级管理员：全部权限
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->syncPermissions(Permission::all());

        // 运营
        Role::where('name', 'operation')->first()?->syncPermissions([
            'dashboard.view',
            'users.read', 'users.write', 'users.ban',
            'payment.packages.read', 'payment.packages.write',
            'payment.orders.read',
            'points.coupons.write', 'points.codes.write',
            'canvas-templates.write', 'canvas-review.review', 'canvas-review.report',
            'content-assets.read', 'content-review.review', 'content-review.takedown',
            'system.config',
        ]);

        // 财务
        Role::where('name', 'finance')->first()?->syncPermissions([
            'dashboard.view',
            'users.read', 'users.points-adjust',
            'payment.orders.read', 'payment.orders.refund', 'payment.callbacks.read', 'payment.reconcile',
            'payment.channels.read', 'payment.channels.write',
            'points.ledger.read', 'points.frozen.read', 'points.expiry.write',
            'ai-cost.read',
            'finance.income.read', 'finance.profit.read', 'finance.orders.read',
        ]);

        // 客服
        Role::where('name', 'support')->first()?->syncPermissions([
            'users.read',
        ]);

        // 审核员
        Role::where('name', 'auditor')->first()?->syncPermissions([
            'canvas-review.review', 'canvas-review.report',
            'content-assets.read', 'content-review.review', 'content-review.takedown',
            'prompt-templates.read', 'prompt-feedback.read',
        ]);

        // 提示词工程师
        Role::where('name', 'prompt_engineer')->first()?->syncPermissions([
            'prompt-templates.read', 'prompt-templates.write',
            'prompt-templates.publish', 'prompt-templates.delete',
            'prompt-bindings.write', 'genre-presets.write', 'style-presets.write',
            'prompt-ab-tests.write', 'prompt-feedback.read',
            'ai-models.read', 'ai-providers.read',
        ]);

        // 普通用户/创作者/企业用户：用户端权限由 Gate 处理，后台权限不分配
    }
}
