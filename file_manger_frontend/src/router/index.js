import api from '@/api'
import { emitter, Notification } from '@/event-bus'
import { createRouter, createWebHistory } from 'vue-router'

const authRoutes = [
  {
    path: '/register',
    name: 'register',
    meta: {
      layout: 'guest',
    },
    component: () => import('../views/auth/register.vue'),
  },
  {
    path: '/login',
    name: 'login',
    meta: {
      layout: 'guest',
    },
    component: () => import('../views/auth/login.vue'),
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    meta: {
      layout: 'guest',
    },
    component: () => import('../views/auth/forgot-password.vue'),
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    meta: {
      layout: 'guest',
    },
    component: () => import('../views/auth/reset-password.vue'),
    props: true
  },
  {
    path: '/verify-email',
    name: 'verify-email',
    meta: {
      layout: 'guest',
    },
    component: () => import('../views/auth/verify-email.vue'),
  },

]

const viewsRoutes = [
  {
    path: '/profile',
    name: 'profile',
    props: true,
    component: () => import('../views/profile/edit.vue')
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    props: true,
    component: () => import('../views/dashboard.vue')
  },
  {
    path: '/trash',
    name: 'trash',
    props: true,
    component: () => import('../views/trash.vue')
  },
  {
    path: '/shared-by-me',
    name: 'shared-by-me',
    props: true,
    component: () => import('../views/shared-by-me.vue')
  },
  {
    path: '/shared-with-me',
    name: 'shared-with-me',
    props: true,
    component: () => import('../views/shared-with-me.vue')
  },
  {
    path: '/my-favorites',
    name: 'my-favorites',
    props: true,
    component: () => import('../views/my-favorites.vue')
  },
  {
    path: '/search',
    name: 'search',
    props: true,
    component: () => import('../views/search.vue')
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('../views/not-found.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...authRoutes,
    ...viewsRoutes
  ],
})

router.beforeEach((to, from) => {
  if(to.name == 'dashboard') {
    if(localStorage.getItem('token')) {
      checkuser(false)
    } else {
      router.push({ name: 'login' })
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }

  if(to.name == 'login' || to.name == 'register' || to.name == 'forgot-password' || to.name == 'reset-password') {
    if(localStorage.getItem('token')) {
      checkuser(true)
    }
  }else{
    checkuser(false)
  }

  if((to.name == 'dashboard') && (from.name == null)) {
    emitter.emit(Notification, {
      success: true,
      message: 'Welcome ' + JSON.parse(localStorage.getItem('user')).name
    })
  }

})

const checkuser = async (authroutes) => {
  await api.get('user', { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}).then(res => {
    // console.log(res)
    if(authroutes){
      router.push({ name: 'dashboard', query: { parent_id: JSON.parse(localStorage.getItem('user')).root_id } })
    }
  }).catch(err => {
    // console.log(err)
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push({ name: 'login' })
  })
}
export default router
