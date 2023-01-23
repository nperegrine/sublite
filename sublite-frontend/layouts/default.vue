<template>
  <div>
    <Head>
      <Title>Sublite</Title>
      <Meta name="description" content="My app description" />
    </Head>
    <a-layout style="min-height: 100vh">
      <a-layout-sider v-model:collapsed="collapsed" collapsible theme="light">
        <div v-if="!collapsed" class="logo pl-5 mt-2">
          <nuxt-link href="/">
            <img
              src="/img/sublite_logo.png"
              alt="sublite_logo"
              style="height: 50px"
          /></nuxt-link>
        </div>
        <div v-else class="logo pt-5 pl-5">
          <nuxt-link href="/">
            <b class="text-blue-500">Sub</b><i>lite</i>
          </nuxt-link>
        </div>
        <a-menu
          v-model:selectedKeys="selectedKeys"
          theme="light"
          mode="vertical"
          class="mt-4 px-2"
        >
          <a-sub-menu key="sub1">
            <template #title>
              <span>
                <team-outlined :style="{ fontSize: '20px' }" />
                <span>Subscribers</span>
              </span>
            </template>
            <a-menu-item key="1"
              ><nuxt-link to="/subscribers"
                >All Subscribers</nuxt-link
              ></a-menu-item
            >
            <a-menu-item key="2"
              ><nuxt-link to="/subscribers/new"
                >Add subscriber</nuxt-link
              ></a-menu-item
            >
          </a-sub-menu>
          <a-sub-menu key="sub2">
            <template #title>
              <span>
                <unordered-list-outlined :style="{ fontSize: '20px' }" />
                <span>Fields</span>
              </span>
            </template>
            <a-menu-item key="3"
              ><nuxt-link to="/fields">All fields</nuxt-link></a-menu-item
            >
            <a-menu-item key="4"
              ><nuxt-link to="/fields/new">Add field</nuxt-link></a-menu-item
            >
          </a-sub-menu>
        </a-menu>
      </a-layout-sider>
      <a-layout>
        <a-layout-content style="margin: 0 16px">
          <slot />
        </a-layout-content>
        <a-layout-footer style="text-align: center">
          Sublite © 2023. All rights reserved.
        </a-layout-footer>
      </a-layout>
    </a-layout>
  </div>
</template>
<script>
import { TeamOutlined, UnorderedListOutlined } from "@ant-design/icons-vue"
import { defineComponent, ref } from "vue"

export default defineComponent({
  components: {
    TeamOutlined,
    UnorderedListOutlined,
  },
  data() {
    return {
      collapsed: ref(true),
      selectedKeys: ref(["1"]),
    }
  },
})
</script>