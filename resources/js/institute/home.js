import '../bootstrap';

const TOKEN_KEY = 'ivvp_institute_token';
const INSTITUTE_KEY = 'ivvp_institute_profile';
const LOGIN_URL = '/institutes/login';

const root = document.getElementById('institute-home-root');
const logoutButton = document.querySelector('[data-logout]');
const nameEl = document.querySelector('[data-institute-name]');
const emailEl = document.querySelector('[data-institute-email]');
const phoneEl = document.querySelector('[data-institute-phone]');

const token = localStorage.getItem(TOKEN_KEY);

const redirectToLogin = () => {
    window.location.replace(LOGIN_URL);
};

if (!token) {
    redirectToLogin();
} else {
    window.axios.defaults.headers.common.Authorization = `Bearer ${token}`;
}

const parseProfile = () => {
    try {
        return JSON.parse(localStorage.getItem(INSTITUTE_KEY) ?? '{}');
    } catch (error) {
        return {};
    }
};

const populateProfile = () => {
    const profile = parseProfile();
    if (nameEl) {
        nameEl.textContent = profile.name ?? 'Institute';
    }
    if (emailEl) {
        emailEl.textContent = profile.email ?? '';
    }
    if (phoneEl) {
        phoneEl.textContent = profile.phone ?? '';
    }
};

const clearSession = () => {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(INSTITUTE_KEY);
};

logoutButton?.addEventListener('click', () => {
    clearSession();
    redirectToLogin();
});

populateProfile();

const renderState = (type, message) => {
    if (!root) {
        return;
    }

    root.innerHTML = `
        <div class="portal-state" data-state="${type}">
            ${type === 'loading' ? '<span class="spinner"></span>' : ''}
            <p>${message}</p>
        </div>
    `;
};

const fetchHomeData = async () => {
    try {
        const { data } = await window.axios.get('/api/institutes/home-data');
        return data?.data?.branches ?? [];
    } catch (error) {
        if (error.response?.status === 401) {
            clearSession();
            redirectToLogin();
            return [];
        }

        throw new Error(error.response?.data?.message ?? 'Unable to load institute data.');
    }
};

const createContentCard = (content) => {
    const card = document.createElement('article');
    card.className = 'portal-content-card';

    const type = content.type?.replace(/_/g, ' ') ?? 'resource';
    const month = content.month ?? '';
    const description = content.description ?? '';

    card.innerHTML = `
        <div class="portal-content-card__meta">
            <span class="portal-tag">${type}</span>
            <span>${month}</span>
        </div>
        <h3>${content.title ?? 'Untitled resource'}</h3>
        <p>${description}</p>
        ${
            content.download_url
                ? `<button class="portal-btn portal-btn--primary portal-btn--compact" data-download-url="${content.download_url}" data-filename="${encodeURIComponent(content.title ?? 'resource')}">Download securely</button>`
                : ''
        }
    `;

    return card;
};

const createYearBlock = (year) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'portal-year';

    const heading = document.createElement('p');
    heading.className = 'portal-year__title';
    heading.textContent = `${year.year}`;

    const list = document.createElement('div');
    list.className = 'portal-content-list';

    year.contents.forEach((content) => {
        list.appendChild(createContentCard(content));
    });

    wrapper.appendChild(heading);
    wrapper.appendChild(list);

    return wrapper;
};

const buildAccordion = (branch) => {
    const container = document.createElement('article');
    container.className = 'portal-accordion';

    const header = document.createElement('button');
    header.className = 'portal-accordion__header';
    header.type = 'button';
    header.innerHTML = `
        <div>
            <p class="portal-kicker">Branch</p>
            <h2 class="portal-accordion__title">${branch.branch_name ?? 'Unnamed Branch'}</h2>
        </div>
        <span>${branch.years?.length ?? 0} years</span>
    `;

    const body = document.createElement('div');
    body.className = 'portal-accordion__body';
    body.hidden = true;

    (branch.years ?? []).forEach((year) => {
        body.appendChild(createYearBlock(year));
    });

    header.addEventListener('click', () => {
        const isHidden = body.hasAttribute('hidden');
        body.toggleAttribute('hidden', !isHidden);
    });

    container.appendChild(header);
    container.appendChild(body);
    return container;
};

const renderBranches = (branches) => {
    if (!root) {
        return;
    }

    if (!branches.length) {
        renderState('empty', 'No content yet. Please check back later.');
        return;
    }

    root.innerHTML = '';
    const fragment = document.createDocumentFragment();
    branches.forEach((branch, index) => {
        const accordion = buildAccordion(branch);
        if (index === 0) {
            accordion.querySelector('.portal-accordion__body')?.removeAttribute('hidden');
        }
        fragment.appendChild(accordion);
    });
    root.appendChild(fragment);
};

const extractFilename = (headerValue, fallback) => {
    if (!headerValue) {
        return fallback;
    }

    const match = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(headerValue);
    if (match?.[1]) {
        return match[1].replace(/['"]/g, '') || fallback;
    }

    return fallback;
};

const downloadContent = async (downloadUrl, providedName, trigger) => {
    if (!downloadUrl) {
        return;
    }

    if (trigger) {
        trigger.classList.add('is-loading');
        trigger.disabled = true;
    }

    try {
        const response = await window.axios.get(downloadUrl, {
            responseType: 'blob',
        });

        const filename = extractFilename(
            response.headers['content-disposition'],
            decodeURIComponent(providedName ?? 'resource')
        );

        const blobUrl = window.URL.createObjectURL(response.data);
        const anchor = document.createElement('a');
        anchor.href = blobUrl;
        anchor.download = filename;
        document.body.appendChild(anchor);
        anchor.click();
        anchor.remove();
        window.URL.revokeObjectURL(blobUrl);
    } catch (error) {
        if (error.response?.status === 401) {
            clearSession();
            redirectToLogin();
            return;
        }

        alert(error.response?.data?.message ?? 'Unable to download file right now.');
    } finally {
        if (trigger) {
            trigger.classList.remove('is-loading');
            trigger.disabled = false;
        }
    }
};

root?.addEventListener('click', (event) => {
    const target = event.target.closest('[data-download-url]');
    if (!target) {
        return;
    }

    event.preventDefault();
    downloadContent(target.dataset.downloadUrl, target.dataset.filename, target);
});

const boot = async () => {
    if (!token) {
        return;
    }

    renderState('loading', 'Gathering your institute data…');
    try {
        const branches = await fetchHomeData();
        renderBranches(branches);
    } catch (error) {
        renderState('error', error.message);
    }
};

boot();
