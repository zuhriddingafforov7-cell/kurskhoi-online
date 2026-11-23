// public/assets/js/app.js — интерактивность платформы Biyomuz Academy (ЛР №11)
// Подключается как ES-модуль: <script type="module" src="/assets/js/app.js"></script>

// ── 1. UTILITIES (Утилиты) ────────────────────────────────────────────────────

// Откладывает вызов fn, пока пользователь не сделает паузу delay мс.
function debounce(fn, delay = 300) {
  let timerId;
  return (...args) => {
    clearTimeout(timerId);
    timerId = setTimeout(() => fn(...args), delay);
  };
}

// Преобразует дату 'YYYY-MM-DD' -> 'DD.MM.YYYY' (пустое значение -> '')
function formatDate(isoDate) {
  if (!isoDate || typeof isoDate !== 'string') return '';
  const parts = isoDate.split('-');
  if (parts.length !== 3) return isoDate;
  const [year, month, day] = parts;
  return `${day}.${month}.${year}`;
}

// ── 2. FORM VALIDATION (Валидация формы регистрации) ──────────────────────────

const FIELD_MESSAGES = {
  full_name: {
    valueMissing: 'Укажите ваше полное имя (ФИО).',
    tooShort: 'Имя должно содержать не менее 3 символов.',
  },
  email: {
    valueMissing: 'Укажите адрес электронной почты.',
    typeMismatch: 'Введите корректный адрес электронной почты (например, student@biyomuz.tj).',
  },
  category: {
    valueMissing: 'Выберите интересующее направление обучения.',
  },
  password: {
    valueMissing: 'Придумайте пароль.',
    tooShort: 'Пароль должен содержать не менее 8 символов.',
  },
  password_confirmation: {
    valueMissing: 'Повторите пароль для подтверждения.',
  },
  terms: {
    valueMissing: 'Необходимо подтвердить согласие с правилами платформы.',
  },
};

function messageFor(field) {
  const v = field.validity;
  if (v.valid) return '';
  if (v.customError) return field.validationMessage;

  const custom = FIELD_MESSAGES[field.name] ?? {};
  if (v.valueMissing) return custom.valueMissing ?? 'Заполните это поле.';
  if (v.tooShort) return custom.tooShort ?? `Минимум ${field.minLength} символов.`;
  if (v.tooLong) return `Не более ${field.maxLength} символов.`;
  if (v.typeMismatch) return custom.typeMismatch ?? 'Неверный формат значения.';
  if (v.patternMismatch) return 'Значение не соответствует требуемому формату.';
  if (v.rangeUnderflow) return `Минимальное допустимое значение: ${field.min}.`;
  if (v.rangeOverflow) return `Максимальное допустимое значение: ${field.max}.`;
  if (v.badInput) return 'Введите корректное значение.';

  return 'Проверьте правильность заполнения поля.';
}

function showFieldState(field) {
  const message = messageFor(field);
  const feedback = field.id ? document.getElementById(`${field.id}-error`) : null;

  field.classList.toggle('is-invalid', message !== '');
  if (message) {
    field.setAttribute('aria-invalid', 'true');
  } else {
    field.removeAttribute('aria-invalid');
  }

  if (feedback) {
    feedback.textContent = message;
  }

  return message === '';
}

function validateRegistrationForm(form) {
  const password = form.elements.password;
  const confirm = form.elements.password_confirmation;
  if (!password || !confirm) return;

  // Сбрасываем старую пользовательскую ошибку перед новой проверкой
  confirm.setCustomValidity('');

  if (password.value && confirm.value && password.value !== confirm.value) {
    confirm.setCustomValidity('Пароли не совпадают. Введите одинаковый пароль в оба поля.');
  }
}

function initRegistrationForm() {
  const form = document.getElementById('registration-form');
  if (!form) return;

  const successBox = document.getElementById('registration-success');
  const getFields = () => form.querySelectorAll('input, select, textarea');
  const { password, password_confirmation: confirm } = form.elements;

  const refresh = (field) => {
    validateRegistrationForm(form);
    showFieldState(field);
    if (field === password && confirm && (confirm.value || confirm.classList.contains('is-invalid'))) {
      showFieldState(confirm);
    }
  };

  // focusout всплывает (в отличие от blur) — один обработчик на всю форму
  form.addEventListener('focusout', (event) => {
    if (event.target.matches('input, select, textarea')) {
      refresh(event.target);
    }
  });

  // Снимаем ошибку сразу после исправления ввода
  form.addEventListener('input', (event) => {
    if (successBox) successBox.classList.add('d-none');
    if (event.target.classList.contains('is-invalid') || event.target === password) {
      refresh(event.target);
    }
  });

  form.addEventListener('change', (event) => {
    if (successBox) successBox.classList.add('d-none');
    if (event.target.matches('select, input[type="checkbox"]') || event.target.classList.contains('is-invalid')) {
      refresh(event.target);
    }
  });

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    validateRegistrationForm(form);

    let firstInvalid = null;
    getFields().forEach((field) => {
      if (!showFieldState(field) && !firstInvalid) {
        firstInvalid = field;
      }
    });

    if (firstInvalid) {
      if (successBox) successBox.classList.add('d-none');
      firstInvalid.focus();
      return;
    }

    if (successBox) {
      const fullName = form.elements.full_name?.value.trim() || '';
      successBox.textContent = `Проверка формы пройдена успешно для «${fullName}». Данные не отправляются на сервер и не записываются в БД в рамках ЛР №11.`;
      successBox.classList.remove('d-none');
    }
  });
}

// ── 3. LIVE COURSE SEARCH (Живой поиск курсов) ───────────────────────────────

const STATUS_LABELS = {
  published: 'Нашршуда',
  pending: 'Мунтазир',
  draft: 'Сиёҳнавис',
  rejected: 'Радшуда',
};

function createMetaSpan(label, value) {
  const span = document.createElement('span');
  if (label) {
    const strong = document.createElement('strong');
    strong.textContent = `${label}: `;
    span.append(strong, document.createTextNode(value ?? ''));
  } else {
    span.textContent = value ?? '';
  }
  return span;
}

function createBullet() {
  const span = document.createElement('span');
  span.textContent = '•';
  return span;
}

function createCourseCard(course) {
  const article = document.createElement('article');
  article.className = 'course-card';

  const thumb = document.createElement('div');
  thumb.className = 'course-thumb';
  thumb.setAttribute('aria-hidden', 'true');
  thumb.textContent = course.code ?? 'CRS';

  const body = document.createElement('div');
  body.className = 'course-body';

  const titleHeading = document.createElement('h3');
  titleHeading.className = 'course-title';
  const titleLink = document.createElement('a');
  titleLink.href = course.url || 'course.html';
  // textContent гарантирует защиту от XSS: <img ...> выводится как текст
  titleLink.textContent = course.title ?? '';
  titleHeading.append(titleLink);

  const meta = document.createElement('div');
  meta.className = 'course-meta';
  const lessonsText = `${course.lessons_count ?? 0} дарс`;
  const priceSpan = document.createElement('span');
  priceSpan.className = 'text-success fw-bold';
  priceSpan.textContent = course.price ?? 'Ройгон';

  meta.append(
    createMetaSpan('Муаллиф', course.author),
    createBullet(),
    createMetaSpan('Категория', course.category),
    createBullet(),
    createMetaSpan('', lessonsText),
    createBullet(),
    createMetaSpan('', formatDate(course.created_at)),
    createBullet(),
    priceSpan,
  );

  const desc = document.createElement('p');
  desc.className = 'course-desc';
  desc.textContent = course.description ?? '';

  body.append(titleHeading, meta, desc);

  const actions = document.createElement('div');
  actions.className = 'course-actions';

  const statusKey = course.status in STATUS_LABELS ? course.status : 'draft';
  const statusBadge = document.createElement('span');
  statusBadge.className = `badge-status status-${statusKey}`;
  statusBadge.textContent = STATUS_LABELS[statusKey];

  const viewBtn = document.createElement('a');
  viewBtn.className = 'btn btn-primary btn-sm';
  viewBtn.href = course.url || 'course.html';
  viewBtn.textContent = 'Курсро дидан';

  actions.append(statusBadge, viewBtn);
  article.append(thumb, body, actions);

  return article;
}

function createTableCell(text, label) {
  const td = document.createElement('td');
  if (label) td.setAttribute('data-label', label);
  td.textContent = text ?? '';
  return td;
}

function createCourseTableRow(course, isAuthorMode) {
  const tr = document.createElement('tr');

  const titleTd = document.createElement('td');
  titleTd.setAttribute('data-label', 'Номи курс');
  const titleWrap = document.createElement('div');
  titleWrap.className = 'd-flex align-items-center gap-2';

  const codeBadge = document.createElement('span');
  codeBadge.className = 'badge bg-success-subtle text-success border';
  codeBadge.textContent = course.code ?? 'CRS';

  const link = document.createElement('a');
  link.className = 'fw-semibold text-dark text-decoration-none';
  link.href = course.url || 'course.html';
  link.textContent = course.title ?? '';

  titleWrap.append(codeBadge, link);
  titleTd.append(titleWrap);

  const statusKey = course.status in STATUS_LABELS ? course.status : 'draft';
  const statusTd = document.createElement('td');
  statusTd.setAttribute('data-label', 'Ҳолат');
  const statusSpan = document.createElement('span');
  statusSpan.className = `badge-status status-${statusKey}`;
  statusSpan.textContent = STATUS_LABELS[statusKey];
  statusTd.append(statusSpan);

  const actionsTd = document.createElement('td');
  actionsTd.setAttribute('data-label', 'Амалҳо');
  actionsTd.className = 'text-end cell-actions';

  const openLink = document.createElement('a');
  openLink.className = 'btn btn-sm btn-outline-primary';
  openLink.href = course.url || 'course.html';
  openLink.textContent = 'Кушодан';
  actionsTd.append(openLink);

  if (isAuthorMode) {
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'btn btn-sm btn-outline-danger ms-1';
    deleteBtn.dataset.deleteUrl = `/courses/${encodeURIComponent(course.id)}/delete`;
    deleteBtn.dataset.courseName = course.title ?? '';
    deleteBtn.textContent = 'Нест кардан';
    actionsTd.append(deleteBtn);
  }

  tr.append(
    titleTd,
    createTableCell(course.category, 'Категория'),
    createTableCell(course.author, 'Муаллиф'),
    createTableCell(`${course.lessons_count ?? 0} дарс`, 'Дарсҳо'),
    statusTd,
    actionsTd,
  );

  return tr;
}

function renderCourses(container, courses, tbody) {
  if (container) {
    if (courses.length === 0) {
      const emptyBox = document.createElement('div');
      emptyBox.className = 'alert alert-light border text-muted mb-0';
      emptyBox.textContent = 'Курсҳо ёфт нашуданд.';
      container.replaceChildren(emptyBox);
    } else {
      const cards = courses.map((course) => createCourseCard(course));
      container.replaceChildren(...cards);
    }
  }

  if (tbody) {
    const isAuthorMode = tbody.dataset.manageMode === 'author';
    if (courses.length === 0) {
      const emptyRow = document.createElement('tr');
      const emptyTd = document.createElement('td');
      emptyTd.colSpan = 6;
      emptyTd.className = 'text-center text-muted py-4';
      emptyTd.textContent = 'Курсҳо ёфт нашуданд.';
      emptyRow.append(emptyTd);
      tbody.replaceChildren(emptyRow);
    } else {
      const rows = courses.map((course) => createCourseTableRow(course, isAuthorMode));
      tbody.replaceChildren(...rows);
    }
  }
}

function initCourseSearch() {
  const input = document.getElementById('course-search');
  const listContainer = document.getElementById('courses-list');
  const tbody = document.getElementById('courses-tbody');

  if (!input || (!listContainer && !tbody)) return;

  const statusBox = document.getElementById('course-search-status');
  const setStatus = (text) => {
    if (statusBox) statusBox.textContent = text;
  };

  let controller = null;

  async function search(query) {
    controller?.abort();
    const currentController = new AbortController();
    controller = currentController;

    if (listContainer) listContainer.setAttribute('aria-busy', 'true');
    if (tbody) tbody.setAttribute('aria-busy', 'true');
    setStatus('Ҷустуҷӯи курсҳо идома дорад…');

    try {
      const response = await fetch(`/api/courses?q=${encodeURIComponent(query)}`, {
        headers: { Accept: 'application/json' },
        signal: currentController.signal,
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const courses = await response.json();
      if (currentController.signal.aborted) return;

      if (!Array.isArray(courses)) {
        throw new Error('Некорректный формат ответа API');
      }

      renderCourses(listContainer, courses, tbody);
      setStatus(
        courses.length > 0
          ? `Курсҳои ёфтшуда: ${courses.length}`
          : 'Курсҳо ёфт нашуданд.'
      );
    } catch (error) {
      if (error.name === 'AbortError' || currentController.signal.aborted) return;
      console.error('Ошибка живого поиска курсов:', error);
      setStatus('Ҷустуҷӯ иҷро нашуд. Пайвастшавиро бо сервер санҷед ва дубора кӯшиш кунед.');
    } finally {
      if (controller === currentController) {
        if (listContainer) listContainer.removeAttribute('aria-busy');
        if (tbody) tbody.removeAttribute('aria-busy');
      }
    }
  }

  const debouncedSearch = debounce(search, 300);

  input.addEventListener('input', () => {
    debouncedSearch(input.value.trim());
  });

  // Предотвращаем полную перезагрузку страницы при нажатии Enter в строке живого поиска
  const searchForm = input.closest('form');
  if (searchForm) {
    searchForm.addEventListener('submit', (event) => {
      event.preventDefault();
      search(input.value.trim());
    });
  }
}

// ── 4. DELETE CONFIRMATION MODAL (Модальное окно подтверждения) ───────────────

function initDeleteConfirm() {
  const modalElement = document.getElementById('confirm-delete-modal');
  if (!modalElement || !window.bootstrap) return;

  const form = document.getElementById('confirm-delete-form');
  const nameElement = document.getElementById('confirm-delete-name');
  if (!form || !nameElement) return;

  const submitButton = form.querySelector('[type="submit"]');
  const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
  let lastTrigger = null;

  // Делегирование события клика на document — работает и для строк, добавленных живым поиском
  document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-delete-url]');
    if (!button) return;

    lastTrigger = button;
    form.action = button.dataset.deleteUrl;
    nameElement.textContent = button.dataset.courseName ?? '';
    if (submitButton) submitButton.disabled = false;
    modal.show();
  });

  // Возвращаем фокус на исходную кнопку после закрытия (включая Esc и клик по фону)
  modalElement.addEventListener('hidden.bs.modal', () => {
    if (lastTrigger?.isConnected) {
      lastTrigger.focus();
    }
  });

  // Защита от двойного нажатия при отправке формы
  form.addEventListener('submit', (event) => {
    if (submitButton?.disabled) {
      event.preventDefault();
      return;
    }
    if (submitButton) submitButton.disabled = true;
  });
}

// ── 5. INITIALIZATION (Запуск модулей) ────────────────────────────────────────

initRegistrationForm();
initCourseSearch();
initDeleteConfirm();
