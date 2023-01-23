<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Fields</a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>
        All Fields
        <span class="float-end">
          <a-button type="primary"
            ><nuxt-link to="/fields/new">Add Field</nuxt-link></a-button
          >
        </span>
      </h4>

      <!-- begin::subscribers list table -->
      <a-table
        :columns="columns"
        :data-source="data"
        :locale="{
          emptyText: `Fields you add will show up here`,
        }"
        class="mt-5"
        rowKey="id"
      >
        <template #headerCell="{ column }">
          <template v-if="column.key === 'name'">
            <span>
              <user-outlined />
              Name
            </span>
          </template>
        </template>

        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'name'">
            <a>
              {{ record.name }}
            </a>
          </template>
          <template v-else-if="column.key === 'required'">
            <span>
              <a-tag :color="record.required === true ? 'green' : 'red'">
                {{ record.required === true ? "YES" : "NO" }}
              </a-tag>
            </span>
          </template>
          <template v-else-if="column.key === 'action'">
            <span>
              <nuxt-link :to="'/fields/' + record.id" class="ant-dropdown-link"
                >Edit</nuxt-link
              >
              <a-divider type="vertical" />
              <a-popconfirm
                title="Are you sure you want to delete this field?"
                ok-text="Yes"
                cancel-text="No"
                @confirm="fieldStore.deleteField(record.id)"
              >
                <a class="text-red-500">Delete</a>
              </a-popconfirm>
            </span>
          </template>
        </template>
      </a-table>
      <!-- end::subscribers list table -->
    </div>
  </div>
</template>


<script>
import { UserOutlined, DownOutlined } from "@ant-design/icons-vue"
import { defineComponent } from "vue"
import { computed } from "vue"
import { useFieldStore } from "~/store/modules/fields"

const columns = [
  {
    title: "Title",
    dataIndex: "title",
    key: "title",
  },
  {
    title: "Type",
    dataIndex: "type",
    key: "type",
  },
  {
    title: "Required",
    dataIndex: "required",
    key: "required",
  },
  {
    title: "Action",
    key: "action",
  },
]

export default defineComponent({
  components: {
    UserOutlined,
    DownOutlined,
  },
  setup() {
    const fieldStore = useFieldStore()
    const data = computed(() => fieldStore.fields)

    const getFields = () => {
      fieldStore.getFields()
    }

    onMounted(getFields)

    return {
      columns,
      fieldStore,
      data,
    }
  },
})
</script>
