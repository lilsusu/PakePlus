<template>
  <div class="page-container">
    <a-card title="用户首页" :bordered="false">
      <a-descriptions :column="2" bordered>
        <a-descriptions-item label="用户 ID">{{ auth?.id }}</a-descriptions-item>
        <a-descriptions-item label="昵称">{{ auth?.name }}</a-descriptions-item>
        <a-descriptions-item label="手机号">{{ auth?.phone }}</a-descriptions-item>
        <a-descriptions-item label="会员等级">L{{ auth?.member_level || 0 }}</a-descriptions-item>
        <a-descriptions-item label="角色" :span="2">
          <a-tag v-for="role in auth?.roles" :key="role" color="blue">{{ role }}</a-tag>
        </a-descriptions-item>
        <a-descriptions-item label="邀请码" :span="2">{{ auth?.invite_code }}</a-descriptions-item>
      </a-descriptions>

      <div style="margin-top:24px;text-align:center;color:#6b7280">
        <p>M1 基础设施与认证模块已完成 ✅</p>
        <p>后续模块（充值/工作台/画布/提示词中心）将在 M2-M6 逐步开放</p>
      </div>
    </a-card>
  </div>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
const page = usePage()
const auth = page.props.auth as any
</script>
