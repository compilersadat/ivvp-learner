import '../bootstrap';

const TOKEN_KEY = 'ivvp_institute_token';
const INSTITUTE_KEY = 'ivvp_institute_profile';

const loginForm = document.getElementById('institute-login-form');
const usbForm = document.getElementById('usb-login-form');
const errorBox = document.querySelector('[data-error-box]');

const redirectIfAuthenticated = () => {
    const redirectUrl = loginForm?.dataset.redirect ?? '/institutes/home';
    if (localStorage.getItem(TOKEN_KEY)) {
        window.location.replace(redirectUrl);
    }
};

const toggleButtonState = (button, isLoading) => {
    if (!button) {
        return;
    }

    button.classList.toggle('is-loading', isLoading);
    button.disabled = isLoading;
};

const showError = (message) => {
    if (!errorBox) {
        return;
    }

    errorBox.textContent = message;
    errorBox.classList.toggle('d-none', !message);
};

const persistSession = (token, institute) => {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(INSTITUTE_KEY, JSON.stringify(institute ?? {}));
};

const handleLoginSuccess = (response, redirectUrl) => {
    const { token, institute } = response ?? {};
    if (!token) {
        showError('Unable to login. Please try again.');
        return;
    }

    persistSession(token, institute);
    window.location.href = redirectUrl;
};

const extractError = (error) => {
    if (error.response?.data?.message) {
        return error.response.data.message;
    }

    return 'Something went wrong. Please try again.';
};

const handleCredentialsLogin = async (event) => {
    event.preventDefault();
    showError('');

    const submitButton = document.getElementById('login-submit');
    toggleButtonState(submitButton, true);

    const redirectUrl = event.currentTarget.dataset.redirect ?? '/institutes/home';

    try {
        const payload = {
            email: event.currentTarget.email.value.trim(),
            password: event.currentTarget.password.value,
        };

        const { data } = await window.axios.post('/api/institutes/login', payload);
        handleLoginSuccess(data, redirectUrl);
    } catch (error) {
        showError(extractError(error));
    } finally {
        toggleButtonState(submitButton, false);
    }
};

const handleUsbLogin = async (event) => {
    event.preventDefault();
    showError('');

    const submitButton = document.getElementById('usb-submit');
    toggleButtonState(submitButton, true);

    const redirectUrl = event.currentTarget.dataset.redirect ?? '/institutes/home';

    try {
        const payload = {
            usb_identifier: event.currentTarget.usb_identifier.value.trim(),
        };

        const { data } = await window.axios.post('/api/institutes/usb-login', payload);
        handleLoginSuccess(data, redirectUrl);
    } catch (error) {
        showError(extractError(error));
    } finally {
        toggleButtonState(submitButton, false);
    }
};

if (loginForm) {
    loginForm.addEventListener('submit', handleCredentialsLogin);
    redirectIfAuthenticated();
}

if (usbForm) {
    usbForm.addEventListener('submit', handleUsbLogin);
}
