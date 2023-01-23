<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Subscribers</a-breadcrumb-item>
      <a-breadcrumb-item>Subscriber - {{ subscriberId }}</a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>Edit Subscriber</h4>

      <h6 class="mt-4">Basic Information</h6>

      <!-- begin::field creation form -->
      <div v-if="Object.keys(subscriber).length > 0">
        <a-form
          :layout="formState.layout"
          :model="subscriber"
          name="basic"
          autocomplete="off"
          class="mt-4"
          @finish="onFinish"
          @finishFailed="onFinishFailed"
        >
          <a-form-item
            label="Name"
            name="name"
            :rules="[
              { required: true, message: 'Please enter subscriber name!' },
            ]"
          >
            <a-input
              v-model:value="subscriber.name"
              placeholder="Please enter subscriber name"
            />
          </a-form-item>

          <a-form-item
            label="Email address"
            name="email"
            :rules="[
              { required: true, message: 'Please enter subscriber email!' },
            ]"
          >
            <a-input
              v-model:value="subscriber.email"
              placeholder="Please enter subscriber email"
            />
          </a-form-item>

          <a-form-item
            label="Subscriber state"
            name="state"
            :rules="[
              { required: true, message: 'Please select subscriber state!' },
            ]"
          >
            <a-select
              v-model:value="subscriber.state"
              placeholder="Please select subscriber state"
            >
              <a-select-option value="active">Active</a-select-option>
              <a-select-option value="unsubscribed"
                >Unsubscribed</a-select-option
              ><a-select-option value="junk">Junk</a-select-option
              ><a-select-option value="bounced">Bounced</a-select-option>
              ><a-select-option value="unconfirmed"
                >Unconfirmed</a-select-option
              >
            </a-select>
          </a-form-item>

          <h6 class="mt-5">Additional Data</h6>

          <a-form-item
            v-for="(field, index) in subscriber.fields"
            :key="field.id"
            :label="field.title"
            class="mt-4"
          >
            <a-input
              v-if="field.type === 'string'"
              v-model:value="subscriber.fields[index].value"
              :placeholder="`${field.title}`"
            />
            <a-input-number
              v-else-if="field.type === 'number'"
              v-model:value="subscriber.fields[index].value"
            />
            <a-checkbox
              v-else-if="field.type === 'boolean'"
              v-model:checked="subscriber.fields[index].value"
            />
            <!-- todo::improve date handling -->
            <!-- <input
              type="date"
              class="form-control"
              v-else-if="field.type === 'date'"
              :value="subscriber.fields[index].value"
            /> -->
          </a-form-item>

          <a-form-item class="mt-5">
            <a-button type="primary" html-type="submit"
              >Update Subscriber</a-button
            >
          </a-form-item>
        </a-form>
      </div>
      <!-- end::field creation form -->

      <!-- begin::loading -->
      <div v-else class="mt-2">
        <p class="text-muted">Loading subscriber data...</p>
      </div>
      <!-- end::loading -->
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive } from "vue"
import { useSubscriberStore } from "~/store/modules/subscribers"
import dayjs from "dayjs"

export default defineComponent({
  setup() {
    const route = useRoute()
    const subscriberId = route.params.id
    let subscriber = ref({})
    const dateFormat = "YYYY-MM-DD"

    const formState = reactive({
      layout: "vertical",
    })

    const subscriberStore = useSubscriberStore()

    const onFinish = (values) => {
      console.log("Success:", values)
      subscriberStore.updateSubscriber(subscriber.value, subscriberId)
    }

    const onFinishFailed = (errorInfo) => {
      console.log("Failed:", errorInfo)
    }

    const findSubscriber = async () => {
      subscriber.value = await subscriberStore.findOrFetchSubscriber(
        subscriberId
      )
    }

    onMounted(findSubscriber)

    return {
      formState,
      onFinish,
      onFinishFailed,
      subscriberStore,
      subscriberId,
      subscriber,
      dateFormat,
    }
  },
})
</script>