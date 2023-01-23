import { useMessageStore } from "~~/store/modules/messages";
const messageStore = useMessageStore();

export const useSubscriberStore = defineStore({
  id: "subscribers",

  state: () => ({ subscribers: [], currentSubscriberId: null }),
  getters: {
    getSubscriber: (state) => {
      return state.subscribers.find(
        (s) => s.id === Number(state.currentSubscriberId)
      );
    },
  },
  actions: {
    async getSubscribers() {
      const config = useRuntimeConfig();

      const response = await $fetch(`/subscribers`, {
        baseURL: config.public.baseURL,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });
      this.subscribers = response.items;
    },
    async storeSubscriber(form) {
      const config = useRuntimeConfig();

      const response = await $fetch(`/subscribers`, {
        baseURL: config.public.baseURL,
        method: "POST",
        body: form,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      this.subscribers.push(response.item);
      messageStore.displaySuccessMessage("Subscriber successfully added");
      navigateTo("/subscribers");
    },
    async showSubscriber(subscriberId) {
      const config = useRuntimeConfig();

      const response = await $fetch(`/subscribers/${subscriberId}`, {
        baseURL: config.public.baseURL,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });
      return response.item;
    },
    async updateSubscriber(form, subscriberId) {
      const config = useRuntimeConfig();

      await $fetch(`/subscribers/${subscriberId}`, {
        baseURL: config.public.baseURL,
        method: "PUT",
        body: form,
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      messageStore.displaySuccessMessage("Subscriber successfully updated");
    },
    async deleteSubscriber(subscriberId) {
      const config = useRuntimeConfig();

      await $fetch(`/subscribers/${subscriberId}`, {
        baseURL: config.public.baseURL,
        method: "DELETE",
        onResponseError({ request, response, options }) {
          // Handle the response errors
          messageStore.displayErrorMessage(response._data.message);
        },
      });

      this.subscribers.map((subscriber, index) => {
        if (subscriber.id === subscriberId) {
          this.subscribers.splice(index, 1);
        }
      });

      messageStore.displaySuccessMessage("Subscriber successfully deleted");
    },
    async findOrFetchSubscriber(subscriberId) {
      if (this.subscribers.length !== 0) {
        this.currentSubscriberId = subscriberId;
        return this.getSubscriber;
      } else {
        return await this.showSubscriber(subscriberId);
      }
    },
  },
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useSubscriberStore, import.meta.hot));
}
