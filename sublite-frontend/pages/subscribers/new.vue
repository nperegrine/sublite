<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Subscribers</a-breadcrumb-item>
      <a-breadcrumb-item>New</a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>Add Subscriber</h4>

      <h6 class="mt-4">Basic Information</h6>

      <!-- begin::field creation form -->
      <a-form
        :layout="formState.layout"
        :model="formState"
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
          <a-input v-model:value="formState.name" placeholder="Name" />
        </a-form-item>

        <a-form-item
          label="Email address"
          name="email"
          :rules="[
            { required: true, message: 'Please enter subscriber email!' },
          ]"
        >
          <a-input
            v-model:value="formState.email"
            placeholder="Email address"
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
            v-model:value="formState.state"
            placeholder="Subscriber state"
          >
            <a-select-option value="active">Active</a-select-option>
            <a-select-option value="unsubscribed">Unsubscribed</a-select-option
            ><a-select-option value="junk">Junk</a-select-option
            ><a-select-option value="bounced">Bounced</a-select-option>
            ><a-select-option value="unconfirmed">Unconfirmed</a-select-option>
          </a-select>
        </a-form-item>

        <h6 class="mt-5">Additional Data</h6>
        <a-form-item
          v-for="(field, index) in subscriberFields"
          :key="field.id"
          :label="field.title"
          class="mt-4"
        >
          <a-input
            v-if="field.type === 'string'"
            v-model:value="subscriberFields[index].value"
            :placeholder="`${field.title}`"
          />
          <a-input-number
            v-else-if="field.type === 'number'"
            v-model:value="subscriberFields[index].value"
          />
          <a-checkbox
            v-else-if="field.type === 'boolean'"
            v-model:checked="subscriberFields[index].value"
          />
          <a-date-picker
            v-else="field.type === 'date'"
            v-model:value="subscriberFields[index].value"
          />
        </a-form-item>

        <a-form-item class="mt-5">
          <a-button type="primary" html-type="submit">Add Subscriber</a-button>
        </a-form-item>
      </a-form>
      <!-- end::field creation form -->
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive } from "vue"
import { computed } from "vue"
import { useFieldStore } from "~/store/modules/fields"
import { useSubscriberStore } from "~/store/modules/subscribers"

export default defineComponent({
  setup() {
    const formState = reactive({
      layout: "vertical",
    })
    const onFinish = (values) => {
      values.fields = subscriberFields.value
      console.log("Success:", values)

      const subscriberStore = useSubscriberStore()
      subscriberStore.storeSubscriber(values)
    }
    const onFinishFailed = (errorInfo) => {
      console.log("Failed:", errorInfo)
    }

    const fieldStore = useFieldStore()
    const fields = computed(() => fieldStore.fields)
    const subscriberFields = computed(() => fieldStore.fields)

    const getFields = () => {
      fieldStore.getFields()
    }

    onMounted(getFields)

    return {
      formState,
      onFinish,
      onFinishFailed,
      fieldStore,
      fields,
      subscriberFields,
    }
  },
})
</script>