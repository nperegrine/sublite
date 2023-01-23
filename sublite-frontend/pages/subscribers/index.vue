<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Subscribers</a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>
        All Subscribers
        <span class="float-end">
          <a-button type="primary"
            ><nuxt-link to="/subscribers/new"
              >Add Subscriber</nuxt-link
            ></a-button
          >
        </span>
      </h4>

      <!-- begin::subscribers list table -->
      <a-table
        :columns="columns"
        :data-source="subscribers"
        :locale="{
          emptyText: `Subscribers you add will show up here`,
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
          <template v-else-if="column.key === 'state'">
            <span>
              <a-tag
                :color="
                  record.state === 'active'
                    ? 'green'
                    : record.state === 'unsubscribed'
                    ? 'red'
                    : record.state === 'junk'
                    ? 'purple'
                    : record.state === 'unconfirmed'
                    ? ''
                    : record.state === 'bounced'
                    ? 'yellow'
                    : 'blue'
                "
              >
                {{ record.state.toUpperCase() }}
              </a-tag>
            </span>
          </template>
          <template v-else-if="column.key.includes('boolean')">
            <span v-if="Object.keys(record).length > 0 && record[column.title]">
              <a-tag
                :color="record[column.title].value === true ? 'green' : 'red'"
              >
                {{ record[column.title].value === true ? "YES" : "NO" }}
              </a-tag>
            </span>
          </template>
          <template v-else-if="column.key === 'action'">
            <span>
              <nuxt-link
                :to="'/subscribers/' + record.id"
                class="ant-dropdown-link"
                >Edit</nuxt-link
              >
              <a-divider type="vertical" />
              <a-popconfirm
                title="Are you sure you want to delete this subscriber?"
                ok-text="Yes"
                cancel-text="No"
                @confirm="subscriberStore.deleteSubscriber(record.id)"
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
import { computed, ref } from "vue"
import { useSubscriberStore } from "~/store/modules/subscribers"
import { useFieldStore } from "~/store/modules/fields"

let columns = ref([
  {
    name: "Name",
    dataIndex: "name",
    key: "name",
  },
  {
    title: "Email",
    dataIndex: "email",
    key: "email",
  },
  {
    title: "State",
    key: "state",
    dataIndex: "state",
  },
])

export default defineComponent({
  components: {
    UserOutlined,
    DownOutlined,
  },
  setup() {
    const subscriberStore = useSubscriberStore()
    const fieldStore = useFieldStore()
    const subscribers = computed(() => subscriberStore.subscribers)
    const fields = computed(() => fieldStore.fields)
    let innerColumns = ref([])
    let innerData = ref([])

    const getSubscribers = () => {
      subscriberStore.getSubscribers()
      fieldStore.getFields()
    }

    watch(fields, (newFields) => {
      // filter table columns to include subscriber fields
      newFields.forEach(function (value, i) {
        columns.value.push({
          title: value.title,
          key:
            value.type === "boolean"
              ? "boolean" + value.id
              : "value" + value.id,
          dataIndex: [value.title, "value"],
        })
      })

      columns.value.push({
        title: "Action",
        key: "action",
      })
    })

    watch(subscribers, (newSubscribers) => {
      // filter table columns to include subscriber fields
      newSubscribers.forEach(function (subscriber, si) {
        subscriber.fields.forEach(function (field, fi) {
          subscriber[field.title] = {
            value: field.value,
            type: field.type,
          }
        })
      })
    })

    onMounted(getSubscribers)

    return {
      subscribers,
      columns,
      subscriberStore,
      innerColumns,
      innerData,
    }
  },
})
</script>
