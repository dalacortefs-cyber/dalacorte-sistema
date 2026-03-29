import { clsx, type ClassValue } from 'clsx'
import { twMerge } from 'tailwind-merge'
import { format, formatDistanceToNow } from 'date-fns'
import { ptBR } from 'date-fns/locale'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function formatCurrency(value: number): string {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
}

export function formatDate(date: string | Date): string {
  return format(new Date(date), "dd 'de' MMMM 'de' yyyy", { locale: ptBR })
}

export function formatDateShort(date: string | Date): string {
  return format(new Date(date), 'dd/MM/yyyy')
}

export function formatRelative(date: string | Date): string {
  return formatDistanceToNow(new Date(date), { locale: ptBR, addSuffix: true })
}

export function formatCpfCnpj(value: string): string {
  const clean = value.replace(/\D/g, '')
  if (clean.length === 11) {
    return clean.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
  }
  return clean.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
}

export function getInitials(name: string): string {
  return name.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase()
}

export const statusColors: Record<string, string> = {
  ativo: 'bg-green-100 text-green-700',
  inativo: 'bg-gray-100 text-gray-600',
  prospecto: 'bg-blue-100 text-blue-700',
  pendente: 'bg-yellow-100 text-yellow-700',
  processado: 'bg-green-100 text-green-700',
  processando: 'bg-blue-100 text-blue-700',
  erro: 'bg-red-100 text-red-700',
  publicado: 'bg-green-100 text-green-700',
  rascunho: 'bg-gray-100 text-gray-600',
  arquivado: 'bg-orange-100 text-orange-700',
  novo: 'bg-blue-100 text-blue-700',
  ganho: 'bg-green-100 text-green-700',
  perdido: 'bg-red-100 text-red-700',
  concluida: 'bg-green-100 text-green-700',
  cancelada: 'bg-red-100 text-red-700',
  em_andamento: 'bg-blue-100 text-blue-700',
  urgente: 'bg-red-100 text-red-700',
  alta: 'bg-orange-100 text-orange-700',
  media: 'bg-yellow-100 text-yellow-700',
  baixa: 'bg-gray-100 text-gray-500',
}
