const LOCAL_HOSTS = new Set(['localhost', '127.0.0.1'])

export function isLocalEnvironment(): boolean {
  return LOCAL_HOSTS.has(window.location.hostname)
}

export function getApiBaseUrl(): string {
  return isLocalEnvironment() ? 'http://localhost:8000' : 'https://conviva-labev.onrender.com'
}

export function getStorageBaseUrl(): string {
  return `${getApiBaseUrl()}/storage`
}

export function resolveStorageUrl(
  pathOrUrl?: string | null,
  fallback = ''
): string {
  const raw = (pathOrUrl ?? '').trim()
  if (!raw) return fallback

  if (raw.startsWith('//')) {
    return `${window.location.protocol}${raw}`
  }

  if (/^https?:\/\//i.test(raw)) {
    if (!isLocalEnvironment() && raw.startsWith('http://')) {
      return raw.replace(/^http:\/\//i, 'https://')
    }
    return raw
  }

  const path = raw
    .replace(/^public\//, '')
    .replace(/^\/+/, '')
    .replace(/^storage\//, '')

  return `${getStorageBaseUrl()}/${path}`
}
