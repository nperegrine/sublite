<template>
  <div>
    <a-breadcrumb style="margin: 16px 0">
      <a-breadcrumb-item>Home</a-breadcrumb-item>
      <a-breadcrumb-item>Fields</a-breadcrumb-item>
      <a-breadcrumb-item>Field - {{ fieldId }} </a-breadcrumb-item>
    </a-breadcrumb>
    <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
      <h4>Edit Field</h4>

      <!-- begin::field update form -->
      <div v-if="Object.keys(field).length > 0">
        <a-form
          :layout="formState.layout"
          :model="field"
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
              v-model:value="field.title"
              placeholder="Please enter a field title"
            />
          </a-form-item>

          <a-form-item
            label="Type"
            name="type"
            :rules="[
              { required: true, message: 'Please select a field type!' },
            ]"
          >
            <a-select
              v-model:value="field.type"
              placeholder="Please select a field type"
            >
              <a-select-option value="string">String</a-select-option>
              <a-select-option value="number">Number</a-select-option
              ><a-select-option value="boolean">Boolean</a-select-option
              ><a-select-option value="date">Date</a-select-option>
            </a-select>
          </a-form-item>

          <a-form-item name="required">
            <a-checkbox v-model:checked="field.required">Required</a-checkbox>
          </a-form-item>

          <a-form-item class="mt-5">
            <a-button type="primary" html-type="submit">Update Field</a-button>
          </a-form-item>
        </a-form>
      </div>
      <!-- end::field update form -->

      <!-- begin::loading -->
      <div v-else class="mt-2">
        <p class="text-muted">Loading field data...</p>
      </div>
      <!-- end::loading -->
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, ref } from "vue"
import { useFieldStore } from "~/store/modules/fields"

export default defineComponent({
  setup() {
    const route = useRoute()
    const fieldId = route.params.id
    let field = ref({})

    const formState = reactive({
      layout: "vertical",
      required: 0,
    })

    const fieldStore = useFieldStore()

    const onFinish = (values) => {
      console.log("Success:", values)
      fieldStore.updateField(values, fieldId)
    }
    const onFinishFailed = (errorInfo) => {
      console.log("Failed:", errorInfo)
    }

    const findField = async () => {
      field.value = await fieldStore.findOrFetchField(fieldId)
    }

    onMounted(findField)

    return {
      formState,
      onFinish,
      onFinishFailed,
      fieldStore,
      fieldId,
      field,
    }
  },
})
</script>