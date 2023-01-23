import { useMessageStore } from "~~/store/modules/messages";
const messageStore = useMessageStore();

export const useFieldStore = defineStore({
  id: "fields",

  state: () => ({ fields: [], currentFieldId: null }),
  getters: {
    getField: (state) => {
      return state.fields.find((f) => f.id === Number(state.currentFieldId));
    },
  },
  actions: {
    async getFields() {
      const config = useRuntimeConfig();

      const response = await $fetch(`/fields`, {
        baseURL: config.public.baseURL,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });
      this.fields = response.items;
      return this.fields;
    },
    async storeField(form) {
      const config = useRuntimeConfig();

      const response = await $fetch(`/fields`, {
        baseURL: config.public.baseURL,
        method: "POST",
        body: form,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      this.fields.push(response.item);
      messageStore.displaySuccessMessage("Field successfully added");
      navigateTo("/fields");
    },
    async showField(fieldId) {
      const config = useRuntimeConfig();

      const response = await $fetch(`/fields/${fieldId}`, {
        baseURL: config.public.baseURL,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });
      return response.item;
    },
    async updateField(form, fieldId) {
      const config = useRuntimeConfig();

      await $fetch(`/fields/${fieldId}`, {
        baseURL: config.public.baseURL,
        method: "PUT",
        body: form,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      messageStore.displaySuccessMessage("Field successfully updated");
    },
    async deleteField(fieldId) {
      const config = useRuntimeConfig();

      await $fetch(`/fields/${fieldId}`, {
        baseURL: config.public.baseURL,
        method: "DELETE",
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      this.fields.map((field, index) => {
        if (field.id === fieldId) {
          this.fields.splice(index, 1);
        }
      });

      messageStore.displaySuccessMessage("Field successfully deleted");
    },
    async findOrFetchField(fieldId) {
      if (this.fields.length !== 0) {
        this.currentFieldId = fieldId;
        return this.getField;
      } else {
        return await this.showField(fieldId);
      }
    },
  },
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useFieldStore, import.meta.hot));
}
