(function() {
  "use strict";
  const icons = {
    "kpn-icon": '<path d="M13.3414 4C13.1203 4.62556 13 5.29873 13 6H5V20H19V12C19.7013 12 20.3744 11.8797 21 11.6586V21C21 21.5523 20.5523 22 20 22H4C3.44772 22 3 21.5523 3 21V5C3 4.44772 3.44772 4 4 4H13.3414ZM19 8C20.1046 8 21 7.10457 21 6C21 4.89543 20.1046 4 19 4C17.8954 4 17 4.89543 17 6C17 7.10457 17.8954 8 19 8ZM19 10C16.7909 10 15 8.20914 15 6C15 3.79086 16.7909 2 19 2C21.2091 2 23 3.79086 23 6C23 8.20914 21.2091 10 19 10Z"></path>',
    "kpn-send": '<path d="M21 7C22.6569 7 24 5.65685 24 4C24 2.34315 22.6569 1 21 1C19.3431 1 18 2.34315 18 4C18 5.65685 19.3431 7 21 7ZM22 18V8.89998C21.6769 8.96557 21.3425 9 21 9C20.6575 9 20.3231 8.96557 20 8.89998V17H5.76282L4 18.3851V5H16.1C16.0344 4.67689 16 4.34247 16 4C16 3.65753 16.0344 3.32311 16.1 3H3C2.44772 3 2 3.44772 2 4V22.5L6.45455 19H21C21.5523 19 22 18.5523 22 18Z"></path>'
  };
  const globalVue = window.Vue;
  function usePanel() {
    return window.panel;
  }
  globalVue.computed;
  globalVue.customRef;
  globalVue.defineAsyncComponent;
  globalVue.defineComponent;
  globalVue.effectScope;
  globalVue.getCurrentInstance;
  globalVue.getCurrentScope;
  globalVue.h;
  globalVue.inject;
  globalVue.isProxy;
  globalVue.isReactive;
  globalVue.isReadonly;
  globalVue.isRef;
  globalVue.isShallow;
  globalVue.markRaw;
  globalVue.nextTick;
  globalVue.onActivated;
  globalVue.onBeforeMount;
  globalVue.onBeforeUnmount;
  globalVue.onBeforeUpdate;
  globalVue.onDeactivated;
  globalVue.onErrorCaptured;
  globalVue.onMounted;
  globalVue.onRenderTracked;
  globalVue.onRenderTriggered;
  globalVue.onScopeDispose;
  globalVue.onServerPrefetch;
  globalVue.onUnmounted;
  globalVue.onUpdated;
  globalVue.provide;
  globalVue.proxyRefs;
  globalVue.reactive;
  globalVue.readonly;
  globalVue.ref;
  globalVue.shallowReactive;
  globalVue.shallowReadonly;
  globalVue.shallowRef;
  globalVue.toRaw;
  globalVue.toRef;
  globalVue.toRefs;
  globalVue.triggerRef;
  globalVue.unref;
  globalVue.useAttrs;
  globalVue.useCssModule;
  globalVue.useCssVars;
  globalVue.useListeners;
  globalVue.useSlots;
  globalVue.watch;
  globalVue.watchEffect;
  globalVue.watchPostEffect;
  globalVue.watchSyncEffect;
  const _sfc_main$2 = /* @__PURE__ */ Vue.defineComponent({
    __name: "NotificationButton",
    props: {
      icon: { type: [String, null], required: false },
      text: { type: [String, null], required: false },
      variant: { type: [String, null], required: false },
      size: { type: [String, null], required: false },
      title: { type: [String, null], required: false }
    },
    setup(__props) {
      const panel = usePanel();
      function openSendNotificationDialog() {
        var _a, _b, _c;
        const uuid = ((_c = (_b = (_a = panel.view) == null ? void 0 : _a.props) == null ? void 0 : _b.model) == null ? void 0 : _c.uuid) ?? null;
        panel.dialog.open("philippoehrlein/push-notifications/send-notification", {
          query: {
            uuid: uuid ?? null
          }
        });
      }
      return { __sfc: true, panel, openSendNotificationDialog };
    }
  });
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    return {
      exports: scriptExports,
      options
    };
  }
  var _sfc_render$2 = function render() {
    var _vm = this, _c = _vm._self._c, _setup = _vm._self._setupProxy;
    return _c("k-button", { attrs: { "icon": _vm.icon ?? "kpn-send", "text": _vm.text, "variant": _vm.variant ?? "filled", "size": _vm.size ?? "sm", "title": _vm.title ?? "Send Push Notification" }, on: { "click": _setup.openSendNotificationDialog } });
  };
  var _sfc_staticRenderFns$2 = [];
  _sfc_render$2._withStripped = true;
  var __component__$2 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$2,
    _sfc_render$2,
    _sfc_staticRenderFns$2,
    false,
    null,
    null
  );
  __component__$2.options.__file = "/Users/philipp/Documents/02_Offen/Kirby Plugins/kirby-push-notifications/site/plugins/kirby-push-notifications/src/components/NotificationButton.vue";
  const NotificationButton = __component__$2.exports;
  const _sfc_main$1 = /* @__PURE__ */ Vue.defineComponent({
    __name: "SubscribeButton",
    props: {
      channels: { type: [Array, null], required: false },
      icon: { type: [String, null], required: false },
      text: { type: [String, null], required: false },
      variant: { type: [String, null], required: false },
      size: { type: [String, null], required: false },
      title: { type: [String, null], required: false },
      theme: { type: [String, null], required: false },
      disabled: { type: [Boolean, null], required: false }
    },
    setup(__props) {
      const props = __props;
      const panel = usePanel();
      function openSubscribeDialog() {
        panel.dialog.open({
          component: "kpn-subscribe-dialog",
          props: {
            channels: props.channels
          }
        });
      }
      return { __sfc: true, props, panel, openSubscribeDialog };
    }
  });
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c, _setup = _vm._self._setupProxy;
    return _c("k-button", { attrs: { "icon": _vm.icon ?? "kpn-icon", "text": _vm.text, "variant": _vm.variant ?? "filled", "size": _vm.size ?? "sm", "theme": _vm.theme, "disabled": _vm.disabled ?? false, "title": _vm.title ?? "Toggle Notifications" }, on: { "click": _setup.openSubscribeDialog } });
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1,
    false,
    null,
    null
  );
  __component__$1.options.__file = "/Users/philipp/Documents/02_Offen/Kirby Plugins/kirby-push-notifications/site/plugins/kirby-push-notifications/src/components/SubscribeButton.vue";
  const SubscribeButton = __component__$1.exports;
  const API = "philippoehrlein/push-notifications";
  function base64UrlToArrayBuffer(base64Url) {
    const padding = "=".repeat((4 - base64Url.length % 4) % 4);
    const base64 = (base64Url + padding).replace(/-/g, "+").replace(/_/g, "/");
    const raw = window.atob(base64);
    const out = new Uint8Array(raw.length);
    for (let i = 0; i < raw.length; i++) out[i] = raw.charCodeAt(i);
    return out.buffer;
  }
  function usePushNotifications() {
    const panel = usePanel();
    function checkSupport() {
      if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
        return { supported: false, reason: "Browser does not support service worker or push API." };
      }
      if (!("Notification" in window)) {
        return { supported: false, reason: "Browser does not support web push notifications." };
      }
      if (Notification.permission === "denied") {
        return { supported: false, reason: "Notifications were blocked by the browser." };
      }
      return { supported: true };
    }
    async function requestPermission() {
      if (Notification.permission === "default") {
        const permission = await Notification.requestPermission();
        if (permission !== "granted") {
          throw new Error("Notifications were not allowed.");
        }
      }
    }
    async function requestPermissionEarly() {
      if (!checkSupport().supported) return false;
      try {
        await requestPermission();
        return true;
      } catch {
        return false;
      }
    }
    async function getExistingSubscription() {
      if (!checkSupport().supported) return null;
      const reg = await navigator.serviceWorker.ready;
      return reg.pushManager.getSubscription();
    }
    async function ensureSubscription() {
      const support = checkSupport();
      if (!support.supported) throw new Error(support.reason);
      await requestPermission();
      const reg = await navigator.serviceWorker.ready;
      let sub = await reg.pushManager.getSubscription();
      if (!sub) {
        const keys = await panel.api.get(`${API}/get-keys`);
        if (keys.status !== "success") throw new Error(keys.message ?? "VAPID keys failed.");
        sub = await reg.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: base64UrlToArrayBuffer(keys.keys.publicKey)
        });
      }
      if (!sub) throw new Error("Push-Subscription could not be created.");
      return sub;
    }
    function toJson(subscription) {
      const j = subscription.toJSON();
      return { endpoint: j.endpoint ?? subscription.endpoint ?? void 0, keys: j.keys };
    }
    async function isSubscribedForCurrentUser(channel, endpoint) {
      if (!endpoint) return false;
      const res = await panel.api.get(`${API}/status`, { channel, endpoint });
      return res.status === "success" && Boolean(res.subscribed);
    }
    async function subscribeChannel(endpoint, keys, channel) {
      const res = await panel.api.post(`${API}/subscribe`, { endpoint, keys, channel });
      if ((res == null ? void 0 : res.status) !== "success") throw new Error((res == null ? void 0 : res.message) ?? "Subscribe failed.");
    }
    async function unsubscribeEndpoint(endpoint) {
      const res = await panel.api.post(`${API}/unsubscribe`, { endpoint });
      if ((res == null ? void 0 : res.status) !== "success") throw new Error((res == null ? void 0 : res.message) ?? "Unsubscribe failed.");
    }
    return {
      checkSupport,
      requestPermission,
      requestPermissionEarly,
      getExistingSubscription,
      ensureSubscription,
      toJson,
      isSubscribedForCurrentUser,
      subscribeChannel,
      unsubscribeEndpoint
    };
  }
  const _sfc_main = /* @__PURE__ */ Vue.defineComponent({
    __name: "SubscribeDialog",
    props: {
      channels: { type: Array, required: true }
    },
    setup(__props, { emit }) {
      const props = __props;
      const panel = usePanel();
      const push = usePushNotifications();
      const loading = Vue.ref(false);
      const error = Vue.ref(null);
      const selectedChannels = Vue.ref([]);
      const uiChannels = Vue.ref(props.channels ?? []);
      const defaultChannels = Vue.ref(null);
      const availableChannels = Vue.computed(() => {
        if (uiChannels.value && uiChannels.value.length > 0) {
          return uiChannels.value;
        }
        if (defaultChannels.value) {
          return [
            ...defaultChannels.value.panel || [],
            ...defaultChannels.value.website || []
          ];
        }
        return [];
      });
      const support = Vue.computed(() => push.checkSupport());
      const supported = Vue.computed(() => support.value.supported);
      const supportReason = Vue.computed(
        () => support.value.supported ? "" : support.value.reason
      );
      async function loadInitialState() {
        loading.value = true;
        error.value = null;
        selectedChannels.value = [];
        try {
          if (!push.checkSupport().supported) {
            loading.value = false;
            return;
          }
          const subscription = await push.getExistingSubscription();
          if (!subscription) {
            loading.value = false;
            return;
          }
          const json = push.toJson(subscription);
          const endpoint = json.endpoint;
          if (!endpoint) {
            loading.value = false;
            return;
          }
          const subscribed = [];
          for (const ch of availableChannels.value) {
            const isSub = await push.isSubscribedForCurrentUser(ch.value, endpoint);
            if (isSub) subscribed.push(ch.value);
          }
          selectedChannels.value = subscribed;
        } catch (e) {
          console.error("[kpn] Error loading subscriptions:", e);
          error.value = e instanceof Error ? e.message : "Unknown error";
        } finally {
          loading.value = false;
        }
      }
      async function submit() {
        if (!supported.value) {
          emit("cancel");
          return;
        }
        loading.value = true;
        error.value = null;
        try {
          const subscription = await push.ensureSubscription();
          const json = push.toJson(subscription);
          const endpoint = json.endpoint;
          const keys = json.keys;
          if (!endpoint || !keys) {
            error.value = panel.t(
              "philippoehrlein.push-notifications.panel.notifications.error.no_endpoint_or_keys"
            );
            loading.value = false;
            return;
          }
          await push.unsubscribeEndpoint(endpoint);
          for (const channelValue of selectedChannels.value) {
            await push.subscribeChannel(endpoint, keys, channelValue);
          }
          emit("submit");
        } catch (e) {
          console.error("[kpn] Error saving:", e);
          error.value = e instanceof Error ? e.message : "Unknown error";
        } finally {
          loading.value = false;
        }
      }
      async function loadDefaultChannels() {
        const response = await panel.api.get("philippoehrlein/push-notifications/get-channels");
        if (response.status === "success") {
          const ch = response.channels;
          if (Array.isArray(ch)) {
            uiChannels.value = ch;
          } else {
            defaultChannels.value = ch;
          }
        }
      }
      Vue.onMounted(async () => {
        if (!props.channels || props.channels.length === 0) await loadDefaultChannels();
        await loadInitialState();
      });
      return { __sfc: true, props, emit, panel, push, loading, error, selectedChannels, uiChannels, defaultChannels, availableChannels, support, supported, supportReason, loadInitialState, submit, loadDefaultChannels };
    }
  });
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c, _setup = _vm._self._setupProxy;
    return _c("k-dialog", { attrs: { "visible": true, "size": "medium", "submit-button": !_setup.loading, "cancel-button": !_setup.loading }, on: { "cancel": function($event) {
      return _setup.emit("cancel");
    }, "submit": function($event) {
      return _setup.submit();
    } }, scopedSlots: _vm._u([{ key: "default", fn: function() {
      return [_setup.loading ? _c("div", [_c("k-icon-frame", { attrs: { "icon": "loader", "ratio": "2/1" } })], 1) : _c("div", [_setup.error ? _c("k-box", { attrs: { "text": _setup.error, "theme": "negative" } }) : !_setup.supported ? _c("k-box", { attrs: { "text": _setup.supportReason, "theme": "warning" } }) : _c("div", [_setup.uiChannels.length > 0 ? _c("k-checkboxes-field", { attrs: { "name": "channels", "label": _setup.panel.t("philippoehrlein.push-notifications.panel.notifications.subscribe.label"), "options": _setup.uiChannels, "value": _setup.selectedChannels, "disabled": _setup.loading, "counter": false }, on: { "input": function($event) {
        _setup.selectedChannels = $event;
      } } }) : _setup.defaultChannels ? _c("div", { staticClass: "kpn-dialog-channels" }, [_c("k-checkboxes-field", { attrs: { "name": "channels", "label": _setup.panel.t("philippoehrlein.push-notifications.panel.notifications.subscribe.panel.label"), "options": _setup.defaultChannels.panel, "value": _setup.selectedChannels, "disabled": _setup.loading, "counter": false }, on: { "input": function($event) {
        _setup.selectedChannels = $event;
      } } }), _c("k-checkboxes-field", { attrs: { "name": "channels", "label": _setup.panel.t("philippoehrlein.push-notifications.panel.notifications.subscribe.website.label"), "options": _setup.defaultChannels.website, "value": _setup.selectedChannels, "disabled": _setup.loading, "counter": false }, on: { "input": function($event) {
        _setup.selectedChannels = $event;
      } } })], 1) : _vm._e()], 1)], 1)];
    }, proxy: true }]) });
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    "f6027ae2"
  );
  __component__.options.__file = "/Users/philipp/Documents/02_Offen/Kirby Plugins/kirby-push-notifications/site/plugins/kirby-push-notifications/src/components/SubscribeDialog.vue";
  const SubscribeDialog = __component__.exports;
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register(
      "/push-notifications-sw.js",
      { scope: "/" }
    );
  }
  window.panel.plugin("philippoehrlein/push-notifications", {
    icons,
    components: {
      "kpn-subscribe-button": SubscribeButton,
      "kpn-subscribe-dialog": SubscribeDialog,
      "kpn-notification-button": NotificationButton
    },
    viewButtons: {
      "kpn-subscribe": SubscribeButton,
      "kpn-notification": NotificationButton
    }
  });
})();
