<template>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>AI 短剧一站式服务平台</h1>
        <p>统一托管 AI Key · 积分消耗驱动 · 全自动短剧生产</p>
      </div>

      <a-tabs v-model:activeKey="activeTab" centered>
        <a-tab-pane key="phone" tab="手机号登录">
          <a-form :model="phoneForm" @finish="handlePhoneLogin" layout="vertical">
            <a-form-item name="phone" :rules="phoneRules">
              <a-input v-model:value="phoneForm.phone" placeholder="请输入手机号" size="large" prefix="📱" />
            </a-form-item>
            <a-form-item name="code" :rules="[{ required: true, message: '请输入验证码' }]">
              <div style="display:flex;gap:8px">
                <a-input v-model:value="phoneForm.code" placeholder="验证码" size="large" style="flex:1" />
                <a-button size="large" :disabled="countdown > 0 || sending" :loading="sending" @click="sendSms">
                  {{ countdown > 0 ? `${countdown}s` : '获取验证码' }}
                </a-button>
              </div>
            </a-form-item>
            <a-button type="primary" html-type="submit" size="large" block :loading="logging" @click="handlePhoneLogin">登录</a-button>
          </a-form>
        </a-tab-pane>

        <a-tab-pane key="register" tab="注册">
          <a-form :model="registerForm" @finish="handleRegister" layout="vertical">
            <a-form-item name="phone" :rules="phoneRules">
              <a-input v-model:value="registerForm.phone" placeholder="请输入手机号" size="large" />
            </a-form-item>
            <a-form-item name="code" :rules="[{ required: true, message: '请输入验证码' }]">
              <div style="display:flex;gap:8px">
                <a-input v-model:value="registerForm.code" placeholder="验证码" size="large" style="flex:1" />
                <a-button size="large" :disabled="regCountdown > 0 || regSending" :loading="regSending" @click="sendRegisterSms">
                  {{ regCountdown > 0 ? `${regCountdown}s` : '获取验证码' }}
                </a-button>
              </div>
            </a-form-item>
            <a-form-item name="invite_code">
              <a-input v-model:value="registerForm.invite_code" placeholder="邀请码（选填）" size="large" />
            </a-form-item>
            <a-button type="primary" html-type="submit" size="large" block :loading="registering" @click="handleRegister">注册</a-button>
          </a-form>
        </a-tab-pane>

        <a-tab-pane key="email" tab="邮箱登录">
          <a-form :model="emailForm" @finish="handleEmailLogin" layout="vertical">
            <a-form-item name="email" :rules="emailRules">
              <a-input v-model:value="emailForm.email" placeholder="请输入邮箱" size="large" />
            </a-form-item>
            <a-form-item name="password" :rules="[{ required: true, message: '请输入密码' }]">
              <a-input-password v-model:value="emailForm.password" placeholder="密码" size="large" />
            </a-form-item>
            <a-button type="primary" html-type="submit" size="large" block :loading="emailLogging" @click="handleEmailLogin">登录</a-button>
          </a-form>
        </a-tab-pane>
      </a-tabs>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onUnmounted } from 'vue'
import { useRouter } from '@inertiajs/vue3'
import { message } from 'ant-design-vue'
import axios from 'axios'

const router = useRouter()
const activeTab = ref('phone')

const phoneRules = [
  { required: true, message: '请输入手机号' },
  { pattern: /^1[3-9]\d{9}$/, message: '手机号格式不正确' },
]

const emailRules = [
  { required: true, message: '请输入邮箱' },
  { type: 'email' as const, message: '邮箱格式不正确' },
]

// 手机登录
const phoneForm = ref({ phone: '', code: '' })
const countdown = ref(0)
const sending = ref(false)
const logging = ref(false)

let timer: any = null
const startCountdown = () => {
  countdown.value = 60
  timer = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) clearInterval(timer)
  }, 1000)
}
onUnmounted(() => timer && clearInterval(timer))

const sendSms = async () => {
  if (!/^1[3-9]\d{9}$/.test(phoneForm.value.phone)) {
    message.warning('请输入正确的手机号')
    return
  }
  sending.value = true
  try {
    await axios.post('/api/auth/sms/send', { phone: phoneForm.value.phone, scene: 'login' })
    message.success('验证码已发送')
    startCountdown()
  } catch (e: any) {
    message.error(e.response?.data?.message || '发送失败')
  } finally {
    sending.value = false
  }
}

const handlePhoneLogin = async () => {
  logging.value = true
  try {
    const res = await axios.post('/api/auth/login/phone', phoneForm.value)
    localStorage.setItem('token', res.data.data.token)
    message.success('登录成功')
    router.visit('/dashboard')
  } catch (e: any) {
    message.error(e.response?.data?.message || '登录失败')
  } finally {
    logging.value = false
  }
}

// 注册
const registerForm = ref({ phone: '', code: '', invite_code: '' })
const regCountdown = ref(0)
const regSending = ref(false)
const registering = ref(false)
let regTimer: any = null

const startRegCountdown = () => {
  regCountdown.value = 60
  regTimer = setInterval(() => {
    regCountdown.value--
    if (regCountdown.value <= 0) clearInterval(regTimer)
  }, 1000)
}
onUnmounted(() => regTimer && clearInterval(regTimer))

const sendRegisterSms = async () => {
  if (!/^1[3-9]\d{9}$/.test(registerForm.value.phone)) {
    message.warning('请输入正确的手机号')
    return
  }
  regSending.value = true
  try {
    await axios.post('/api/auth/sms/send', { phone: registerForm.value.phone, scene: 'register' })
    message.success('验证码已发送')
    startRegCountdown()
  } catch (e: any) {
    message.error(e.response?.data?.message || '发送失败')
  } finally {
    regSending.value = false
  }
}

const handleRegister = async () => {
  registering.value = true
  try {
    const res = await axios.post('/api/auth/register', registerForm.value)
    localStorage.setItem('token', res.data.data.token)
    message.success('注册成功')
    router.visit('/dashboard')
  } catch (e: any) {
    message.error(e.response?.data?.message || '注册失败')
  } finally {
    registering.value = false
  }
}

// 邮箱登录
const emailForm = ref({ email: '', password: '' })
const emailLogging = ref(false)

const handleEmailLogin = async () => {
  emailLogging.value = true
  try {
    const res = await axios.post('/api/auth/login/email', emailForm.value)
    localStorage.setItem('token', res.data.data.token)
    message.success('登录成功')
    router.visit('/dashboard')
  } catch (e: any) {
    message.error(e.response?.data?.message || '登录失败')
  } finally {
    emailLogging.value = false
  }
}
</script>

<style scoped>
.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #1a1c28, #1e2030);
  padding: 24px;
}
.auth-card {
  width: 100%;
  max-width: 420px;
  background: #fff;
  border-radius: 16px;
  padding: 40px 32px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}
.auth-header {
  text-align: center;
  margin-bottom: 24px;
}
.auth-header h1 {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 4px;
}
.auth-header p {
  font-size: 12px;
  color: #6b7280;
}
</style>
