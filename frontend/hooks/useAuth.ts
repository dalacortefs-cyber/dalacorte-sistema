'use client'
import { useState, useEffect } from 'react'
import Cookies from 'js-cookie'
import api from '@/lib/api'
import toast from 'react-hot-toast'

interface User {
  id: number
  name: string
  email: string
  tipo: 'admin' | 'funcionario' | 'cliente'
  avatar?: string
}

export function useAuth() {
  const [user, setUser] = useState<User | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const saved = Cookies.get('user')
    if (saved) {
      try { setUser(JSON.parse(saved)) } catch {}
    }
    setLoading(false)
  }, [])

  const login = async (email: string, password: string) => {
    const { data } = await api.post('/auth/login', { email, password })
    Cookies.set('token', data.access_token, { expires: 1 })
    Cookies.set('user', JSON.stringify(data.user), { expires: 1 })
    setUser(data.user)
    return data.user
  }

  const logout = async () => {
    try { await api.post('/auth/logout') } catch {}
    Cookies.remove('token')
    Cookies.remove('user')
    setUser(null)
    window.location.href = '/login'
  }

  const isAdmin = () => user?.tipo === 'admin'
  const isFuncionario = () => user?.tipo === 'funcionario'
  const isCliente = () => user?.tipo === 'cliente'

  return { user, loading, login, logout, isAdmin, isFuncionario, isCliente }
}
