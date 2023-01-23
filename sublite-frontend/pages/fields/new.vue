<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Fields</a-breadcrumb-item>
      <a-breadcrumb-item>New</a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>Add Field</h4>

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
          label="Title"
          name="title"
          :rules="[
            { required: true, message: 'Please enter the field title!' },
          ]"
        >
          <a-input
            v-model:value="formState.title"
            placeholder="Please enter a field title"
          />
        </a-form-item>

        <a-form-item
          label="Type"
          name="type"
          :rules="[{ required: true, message: 'Please select a field type!' }]"
        >
          <a-select
            v-model:value="formState.type"
            placeholder="Please select a field type"
          >
            <a-select-option value="string">String</a-select-option>
            <a-select-option value="number">Number</a-select-option
            ><a-select-option value="boolean">Boolean</a-select-option
            ><a-select-option value="date">Date</a-select-option>
          </a-select>
        </a-form-item>

        <a-form-item name="required">
          <a-checkbox v-model:checked="formState.required">Required</a-checkbox>
        </a-form-item>

        <a-form-item class="mt-5">
          <a-button type="primary" html-type="submit">Add Field</a-button>
        </a-form-item>
      </a-form>

      <!-- end::field creation form -->
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive } from "vue"
import { useFieldStore } from "~/store/modules/fields"

export default defineComponent({
  setup() {
    const formState = reactive({
      layout: "vertical",
      required: 0,
    })
    const onFinish = (values) => {
      console.log("Success:", values)

      const fieldStore = useFieldStore()
      fieldStore.storeField(values)
    }
    const onFinishFailed = (errorInfo) => {
      console.log("Failed:", errorInfo)
    }
    return {
      formState,
      onFinish,
      onFinishFailed,
    }
  },
})
</script>