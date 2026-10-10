import { createApp, h, type DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ConfigProvider, message } from 'ant-design-vue'
import zhCN from 'ant-design-vue/es/locale/zh_CN'
import dayjs from 'dayjs'
import 'dayjs/locale/zh-cn'
import 'ant-design-vue/dist/reset.css'
import '../css/app.scss'

dayjs.locale('zh-cn')

// 全局 axios 拦截器（错误处理）
import axios from 'axios'

axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      message.error('登录已过期，请重新登录')
      window.location.href = '/login'
    } else if (error.response?.data?.message) {
      message.error(error.response.data.message)
    }
    return Promise.reject(error)
  }
)

createInertiaApp({
  title: (title) => `${title} - AI 短剧平台`,
  resolve: async (name) => {
    const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue')
    return resolvePageComponent(`./Pages/${name}.vue`, pages)
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ConfigProvider as any, { locale: zhCN })
      .mount(el)
  },
  progress: {
    color: '#4f7cff',
    delay: 100,
  },
})
