import { ref, computed, onMounted, onUnmounted } from 'vue'

export function useBreakpoint() {
  const width = ref(typeof window !== 'undefined' ? window.innerWidth : 1200)

  const onResize = () => {
    width.value = window.innerWidth
  }

  onMounted(() => {
    window.addEventListener('resize', onResize, { passive: true })
    onResize()
  })

  onUnmounted(() => {
    window.removeEventListener('resize', onResize)
  })

  const isMobile = computed(() => width.value < 768)
  const isTablet = computed(() => width.value >= 768 && width.value < 992)
  const isDesktop = computed(() => width.value >= 992)

  const carouselGroupSize = computed(() => {
    if (width.value < 768) return 1
    if (width.value < 992) return 2
    return 3
  })

  return { width, isMobile, isTablet, isDesktop, carouselGroupSize }
}
