import '../bootstrap';

const TOKEN_KEY = 'ivvp_institute_token';
const INSTITUTE_KEY = 'ivvp_institute_profile';
const LOGIN_URL = '/institutes/login';
const MEDIA_VIDEO = 'video';
const MEDIA_PDF = 'pdf';

const root = document.getElementById('institute-home-root');
const logoutButton = document.querySelector('[data-logout]');
const nameEl = document.querySelector('[data-institute-name]');
const emailEl = document.querySelector('[data-institute-email]');
const phoneEl = document.querySelector('[data-institute-phone]');
const viewer = document.getElementById('portal-viewer');
const viewerBody = document.querySelector('[data-viewer-body]');
const viewerCloseTriggers = document.querySelectorAll('[data-viewer-close]');

const token = localStorage.getItem(TOKEN_KEY);
let activeObjectUrl = null;

const redirectToLogin = () => window.location.replace(LOGIN_URL);

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

const closeViewer = () => {
    if (!viewer) {
        return;
    }

    viewer.classList.remove('is-open');
    viewer.setAttribute('aria-hidden', 'true');
    viewerBody.innerHTML = '';

    if (activeObjectUrl) {
        window.URL.revokeObjectURL(activeObjectUrl);
        activeObjectUrl = null;
    }
};

viewerCloseTriggers.forEach((trigger) => {
    trigger.addEventListener('click', closeViewer);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && viewer?.classList.contains('is-open')) {
        closeViewer();
    }
});

const openVideoViewer = (src, title) => {
    if (!viewer || !viewerBody) {
        return;
    }

    viewerBody.innerHTML = `
        <div class="portal-viewer__media">
            <video controls autoplay src="${src}"></video>
            <p class="portal-text-sm">${title ?? 'Video resource'}</p>
        </div>
    `;

    viewer.classList.add('is-open');
    viewer.setAttribute('aria-hidden', 'false');
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

const fetchContentBlob = async (downloadUrl) => {
    const response = await window.axios.get(downloadUrl, {
        responseType: 'blob',
    });

    const filename = extractFilename(
        response.headers['content-disposition'],
        'ivvp-resource'
    );

    return {
        blob: response.data,
        filename,
    };
};

const handleContentActivation = async (event) => {
    const card = event.currentTarget;
    const downloadUrl = card.dataset.downloadUrl;
    const streamUrl = card.dataset.streamUrl;
    const mediaCategory = card.dataset.mediaCategory ?? 'file';
    const title = card.dataset.title ?? 'Resource';

    card.classList.add('is-loading');

    try {
        if (mediaCategory === MEDIA_VIDEO && streamUrl) {
            openVideoViewer(streamUrl, title);
            return;
        }

        if (mediaCategory === MEDIA_PDF && streamUrl) {
            const pdfWindow = window.open(streamUrl, '_blank', 'noopener');
            if (!pdfWindow) {
                alert('Please allow pop-ups to view this PDF.');
            }
            return;
        }

        if (!downloadUrl) {
            alert('Preview is not available for this file.');
            return;
        }

        const { blob, filename } = await fetchContentBlob(downloadUrl);

        if (mediaCategory === MEDIA_VIDEO) {
            const blobUrl = window.URL.createObjectURL(blob);
            activeObjectUrl = blobUrl;
            openVideoViewer(blobUrl, title);
            return;
        }

        const objectUrl = window.URL.createObjectURL(blob);

        if (mediaCategory === MEDIA_PDF) {
            const pdfWindow = window.open(objectUrl, '_blank', 'noopener');
            if (!pdfWindow) {
                alert('Please allow pop-ups to view this PDF.');
            }

            setTimeout(() => window.URL.revokeObjectURL(objectUrl), 60 * 1000);
            return;
        }

        window.URL.revokeObjectURL(objectUrl);
        alert('This content type cannot be previewed yet.');
    } catch (error) {
        if (error.response?.status === 401) {
            clearSession();
            redirectToLogin();
            return;
        }

        alert(error.response?.data?.message ?? 'Unable to open this content right now.');
    } finally {
        card.classList.remove('is-loading');
    }
};

const createContentCard = (content) => {
    const card = document.createElement('article');
    card.className = 'portal-content-card portal-content-card--interactive';
    card.dataset.downloadUrl = content.download_url ?? '';
    card.dataset.mediaCategory = content.media_category ?? 'file';
    card.dataset.title = content.title ?? 'Resource';
    card.dataset.streamUrl = content.stream_url ?? '';

    const hasDirectStream = Boolean(content.stream_url);
    const hasFallbackDownload = Boolean(content.download_url);

    if (!hasDirectStream && !hasFallbackDownload) {
        card.classList.add('is-disabled');
    }

    const thumbnail = document.createElement('div');
    thumbnail.className = 'portal-thumb';

    if (content.thumbnail_url) {
        const img = document.createElement('img');
        img.src = content.thumbnail_url;
        img.alt = `${content.title ?? 'Resource'} thumbnail`;
        thumbnail.appendChild(img);
    } else {
        const placeholder = document.createElement('div');
        placeholder.className = 'portal-thumb__placeholder';
        placeholder.textContent = (content.type_label ?? 'Rsc').slice(0, 1);
        thumbnail.appendChild(placeholder);
    }

    const tag = document.createElement('span');
    tag.className = 'portal-thumb__tag';
    tag.textContent = content.type_label ?? 'Resource';
    thumbnail.appendChild(tag);

    const body = document.createElement('div');
    body.className = 'portal-content-card__body';

    const title = document.createElement('h3');
    title.textContent = content.title ?? 'Untitled resource';

    const description = document.createElement('p');
    description.className = 'portal-content-card__description';
    description.textContent = content.description ?? 'No description provided.';

    body.appendChild(title);
    body.appendChild(description);

    card.appendChild(thumbnail);
    card.appendChild(body);

    if (hasDirectStream || hasFallbackDownload) {
        card.addEventListener('click', handleContentActivation);
    }

    return card;
};

const renderMonths = (container, year) => {
    container.innerHTML = '';

    if (!year?.months?.length) {
        container.innerHTML = '<p class="portal-text-sm">No months published for this year yet.</p>';
        return;
    }

    year.months.forEach((month, index) => {
        const wrapper = document.createElement('article');
        wrapper.className = 'portal-month';

        const header = document.createElement('button');
        header.className = 'portal-month__header';
        header.type = 'button';
        header.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
        header.innerHTML = `
            <div>
                <p class="portal-kicker">Month</p>
                <h2 class="portal-accordion__title">${month.label ?? 'Unscheduled'}</h2>
            </div>
            <span>${month.contents?.length ?? 0} items</span>
        `;

        const body = document.createElement('div');
        body.className = 'portal-month__body';
        if (index !== 0) {
            body.setAttribute('hidden', 'true');
        }

        if (!month.contents?.length) {
            body.innerHTML = '<p class="portal-text-sm">No content yet.</p>';
        } else {
            const list = document.createElement('div');
            list.className = 'portal-content-list';
            month.contents.forEach((content) => list.appendChild(createContentCard(content)));
            body.appendChild(list);
        }

        header.addEventListener('click', () => {
            const isHidden = body.hasAttribute('hidden');
            body.toggleAttribute('hidden', !isHidden);
            header.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });

        wrapper.appendChild(header);
        wrapper.appendChild(body);
        container.appendChild(wrapper);
    });
};

const buildBranchAccordion = (branch, branchIndex) => {
    const container = document.createElement('article');
    container.className = 'portal-accordion';

    const header = document.createElement('button');
    header.className = 'portal-accordion__header';
    header.type = 'button';
    header.setAttribute('aria-expanded', branchIndex === 0 ? 'true' : 'false');
    header.innerHTML = `
        <div>
            <p class="portal-kicker">Branch</p>
            <h2 class="portal-accordion__title">${branch.branch_name ?? 'Unnamed branch'}</h2>
        </div>
        <span>${branch.years?.length ?? 0} years</span>
    `;

    const body = document.createElement('div');
    body.className = 'portal-accordion__body';
    if (branchIndex !== 0) {
        body.setAttribute('hidden', 'true');
    }

    const monthsWrapper = document.createElement('div');
    monthsWrapper.className = 'portal-months';

    if (!branch.years?.length) {
        monthsWrapper.innerHTML = '<p class="portal-text-sm">No academic years published yet.</p>';
    } else {
        const yearPicker = document.createElement('div');
        yearPicker.className = 'portal-year-list';

        branch.years.forEach((year, yearIndex) => {
            const pill = document.createElement('button');
            pill.type = 'button';
            pill.className = 'portal-pill';
            pill.textContent = year.year;

            pill.addEventListener('click', () => {
                yearPicker.querySelectorAll('.portal-pill').forEach((btn) => btn.classList.remove('is-active'));
                pill.classList.add('is-active');
                renderMonths(monthsWrapper, year);
            });

            yearPicker.appendChild(pill);
        });

        yearPicker.firstChild?.classList.add('is-active');
        renderMonths(monthsWrapper, branch.years[0]);

        body.appendChild(yearPicker);
    }

    body.appendChild(monthsWrapper);

    header.addEventListener('click', () => {
        const isHidden = body.hasAttribute('hidden');
        body.toggleAttribute('hidden', !isHidden);
        header.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
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
        renderState('empty', 'No institute content yet. Please check back soon.');
        return;
    }

    root.innerHTML = '';
    branches.forEach((branch, index) => {
        root.appendChild(buildBranchAccordion(branch, index));
    });
};

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
