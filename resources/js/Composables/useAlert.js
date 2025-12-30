import { ref } from 'vue';

export function useAlert() {
  const alertState = ref({
    show: false,
    type: 'info',
    title: '',
    message: '',
    confirmText: 'OK',
    cancelText: 'Annuler',
    showCancel: false,
    resolve: null,
    reject: null
  });

  const showAlert = (options) => {
    return new Promise((resolve, reject) => {
      alertState.value = {
        show: true,
        type: options.type || 'info',
        title: options.title || '',
        message: options.message || '',
        confirmText: options.confirmText || 'OK',
        cancelText: options.cancelText || 'Annuler',
        showCancel: options.showCancel || false,
        resolve,
        reject
      };
    });
  };

  const showSuccess = (title, message) => {
    return showAlert({
      type: 'success',
      title,
      message
    });
  };

  const showError = (title, message) => {
    return showAlert({
      type: 'error',
      title,
      message
    });
  };

  const showWarning = (title, message) => {
    return showAlert({
      type: 'warning',
      title,
      message
    });
  };

  const showInfo = (title, message) => {
    return showAlert({
      type: 'info',
      title,
      message
    });
  };

  const showConfirm = (title, message, confirmText = 'Confirmer', cancelText = 'Annuler') => {
    return showAlert({
      type: 'warning',
      title,
      message,
      confirmText,
      cancelText,
      showCancel: true
    });
  };

  const confirm = () => {
    if (alertState.value.resolve) {
      alertState.value.resolve(true);
    }
    closeAlert();
  };

  const cancel = () => {
    if (alertState.value.reject) {
      alertState.value.reject(false);
    }
    closeAlert();
  };

  const closeAlert = () => {
    alertState.value.show = false;
    setTimeout(() => {
      alertState.value.resolve = null;
      alertState.value.reject = null;
    }, 300);
  };

  return {
    alertState,
    showAlert,
    showSuccess,
    showError,
    showWarning,
    showInfo,
    showConfirm,
    confirm,
    cancel,
    closeAlert
  };
}
