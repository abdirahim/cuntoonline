<template>
  <nav v-if="meta && meta.last_page > 1" aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
        <a class="page-link" href="#" @click.prevent="changePage(meta.current_page - 1)">
          Previous
        </a>
      </li>

      <li
        v-for="page in visiblePages"
        :key="page"
        class="page-item"
        :class="{ active: page === meta.current_page }"
      >
        <a class="page-link" href="#" @click.prevent="changePage(page)">
          {{ page }}
        </a>
      </li>

      <li class="page-item" :class="{ disabled: meta.current_page === meta.last_page }">
        <a class="page-link" href="#" @click.prevent="changePage(meta.current_page + 1)">
          Next
        </a>
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { PaginationMeta } from '@/types/models'

interface Props {
  meta: PaginationMeta | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
  pageChange: [page: number]
}>()

const visiblePages = computed(() => {
  if (!props.meta) return []
  
  const pages: number[] = []
  const current = props.meta.current_page
  const last = props.meta.last_page
  
  // Show max 5 pages
  let start = Math.max(1, current - 2)
  let end = Math.min(last, current + 2)
  
  if (end - start < 4) {
    if (start === 1) {
      end = Math.min(5, last)
    } else if (end === last) {
      start = Math.max(1, last - 4)
    }
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const changePage = (page: number) => {
  if (props.meta && page >= 1 && page <= props.meta.last_page) {
    emit('pageChange', page)
  }
}
</script>
