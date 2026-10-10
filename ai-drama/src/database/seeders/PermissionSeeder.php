<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * 权限点 Seeder
 * 权限点格式：module.action
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // 仪表盘
            ['name' => 'dashboard.view', 'display_name' => '查看仪表盘', 'module' => 'dashboard'],

            // 用户管理
            ['name' => 'users.read', 'display_name' => '查看用户', 'module' => 'users'],
            ['name' => 'users.write', 'display_name' => '编辑用户', 'module' => 'users'],
            ['name' => 'users.ban', 'display_name' => '封禁用户', 'module' => 'users'],
            ['name' => 'users.points-adjust', 'display_name' => '调整积分', 'module' => 'users'],

            // RBAC
            ['name' => 'rbac.read', 'display_name' => '查看权限', 'module' => 'rbac'],
            ['name' => 'rbac.write', 'display_name' => '编辑权限', 'module' => 'rbac'],

            // 支付
            ['name' => 'payment.channels.read', 'display_name' => '查看支付渠道', 'module' => 'payment'],
            ['name' => 'payment.channels.write', 'display_name' => '编辑支付渠道', 'module' => 'payment'],
            ['name' => 'payment.packages.read', 'display_name' => '查看套餐', 'module' => 'payment'],
            ['name' => 'payment.packages.write', 'display_name' => '编辑套餐', 'module' => 'payment'],
            ['name' => 'payment.orders.read', 'display_name' => '查看订单', 'module' => 'payment'],
            ['name' => 'payment.orders.refund', 'display_name' => '退款', 'module' => 'payment'],
            ['name' => 'payment.callbacks.read', 'display_name' => '查看回调日志', 'module' => 'payment'],
            ['name' => 'payment.reconcile', 'display_name' => '对账', 'module' => 'payment'],

            // 积分
            ['name' => 'points.ledger.read', 'display_name' => '查看积分流水', 'module' => 'points'],
            ['name' => 'points.frozen.read', 'display_name' => '查看冻结积分', 'module' => 'points'],
            ['name' => 'points.coupons.write', 'display_name' => '优惠券管理', 'module' => 'points'],
            ['name' => 'points.codes.write', 'display_name' => '兑换码管理', 'module' => 'points'],
            ['name' => 'points.expiry.write', 'display_name' => '过期规则配置', 'module' => 'points'],

            // AI 平台
            ['name' => 'ai-providers.read', 'display_name' => '查看供应商', 'module' => 'ai'],
            ['name' => 'ai-providers.write', 'display_name' => '编辑供应商', 'module' => 'ai'],
            ['name' => 'ai-models.read', 'display_name' => '查看模型', 'module' => 'ai'],
            ['name' => 'ai-models.write', 'display_name' => '编辑模型', 'module' => 'ai'],
            ['name' => 'ai-keys.read', 'display_name' => '查看 Key', 'module' => 'ai'],
            ['name' => 'ai-keys.write', 'display_name' => '编辑 Key', 'module' => 'ai'],
            ['name' => 'ai-cost.read', 'display_name' => '查看成本统计', 'module' => 'ai'],

            // 提示词中心
            ['name' => 'prompt-templates.read', 'display_name' => '查看提示词', 'module' => 'prompt'],
            ['name' => 'prompt-templates.write', 'display_name' => '编辑提示词', 'module' => 'prompt'],
            ['name' => 'prompt-templates.publish', 'display_name' => '发布/回滚', 'module' => 'prompt'],
            ['name' => 'prompt-templates.delete', 'display_name' => '删除提示词', 'module' => 'prompt'],
            ['name' => 'prompt-bindings.write', 'display_name' => '节点绑定', 'module' => 'prompt'],
            ['name' => 'genre-presets.write', 'display_name' => '类型预设', 'module' => 'prompt'],
            ['name' => 'style-presets.write', 'display_name' => '风格预设', 'module' => 'prompt'],
            ['name' => 'prompt-ab-tests.write', 'display_name' => 'A/B 测试', 'module' => 'prompt'],
            ['name' => 'prompt-feedback.read', 'display_name' => '反馈分析', 'module' => 'prompt'],

            // 工作流
            ['name' => 'workflow.config', 'display_name' => '工作流配置', 'module' => 'workflow'],

            // 画布
            ['name' => 'canvas-templates.write', 'display_name' => '画布模板', 'module' => 'canvas'],
            ['name' => 'canvas-review.review', 'display_name' => '画布审核', 'module' => 'canvas'],
            ['name' => 'canvas-review.report', 'display_name' => '举报处理', 'module' => 'canvas'],

            // 内容
            ['name' => 'content-assets.read', 'display_name' => '素材管理', 'module' => 'content'],
            ['name' => 'content-review.review', 'display_name' => '内容审核', 'module' => 'content'],
            ['name' => 'content-review.takedown', 'display_name' => '内容下架', 'module' => 'content'],

            // 财务
            ['name' => 'finance.income.read', 'display_name' => '收入统计', 'module' => 'finance'],
            ['name' => 'finance.profit.read', 'display_name' => '毛利统计', 'module' => 'finance'],
            ['name' => 'finance.orders.read', 'display_name' => '订单流水', 'module' => 'finance'],

            // 系统
            ['name' => 'system.config', 'display_name' => '系统配置', 'module' => 'system'],
            ['name' => 'system.audit.read', 'display_name' => '审计日志', 'module' => 'system'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(
                ['name' => $p['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $p['display_name'],
                    'module' => $p['module'],
                ]
            );
        }
    }
}
