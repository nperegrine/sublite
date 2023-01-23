<template>
  <a-result
    title="Welcome to Sublite!"
    sub-title="The all-in-one subscriber management platform."
    class="mt-5"
  >
    <template #icon>
      <team-outlined />
    </template>
    <template #extra>
      <a-button type="primary"
        ><nuxt-link to="/subscribers">Add Subscriber</nuxt-link></a-button
      >
      <a-button><nuxt-link to="/fields">Manage Fields</nuxt-link></a-button>
    </template>
  </a-result>
</template>

<script>
import { TeamOutlined } from "@ant-design/icons-vue"
import { defineComponent } from "vue"

export default defineComponent({
  components: {
    TeamOutlined,
  },
})
</script>
