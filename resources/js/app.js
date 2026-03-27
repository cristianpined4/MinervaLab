import "./bootstrap";
import Swal from "sweetalert2";
import Chart from "chart.js/auto";
import moment from "moment";
import Alpine from "alpinejs";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es.js";
import "flatpickr/dist/flatpickr.min.css";

if (!window.Alpine) {
    window.Alpine = Alpine;
    window.Alpine.start();
}

window.moment = moment;
window.months =
    "_Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre".split(
        "_",
    );

const Alert = (title, text, icon) => {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: "Ok",
    });
};

window.Alert = Alert;

const Confirm = async (
    title,
    text,
    icon,
    confirmButtonText,
    cancelButtonText,
) => {
    const result = await Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
    });

    return result.isConfirmed; // Devuelve true si se confirmó, false si se canceló
};

window.Confirm = Confirm;

const hiddenLoader = () => {
    let el = document.getElementById("loader");
    if (el != null) {
        el.classList.remove("show");
    }
};

window.hiddenLoader = hiddenLoader;

const showLoader = () => {
    let el = document.getElementById("loader");
    if (el != null) {
        el.classList.add("show");
    }
};

window.showLoader = showLoader;

function generarGraficoBarras(selector, etiquetas, datos) {
    // Generar colores aleatorios
    const coloresAleatorios = datos.map(() => {
        const randomColor = () => Math.floor(Math.random() * 256);
        return `rgba(${randomColor()}, ${randomColor()}, ${randomColor()}, 1)`;
    });

    // Crear el gráfico
    const ctx = document.querySelector(selector).getContext("2d");
    const myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: etiquetas,
            datasets: [
                {
                    label: "Total",
                    data: datos,
                    backgroundColor: coloresAleatorios.map((color) =>
                        color.replace("1)", "0.3)"),
                    ),
                    borderColor: coloresAleatorios,
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    return myChart;
}

window.chartJs = generarGraficoBarras;

const openModal = (modal) => {
    modal.style.display = "flex";
    if (window.refreshDatePickersInModal) {
        window.refreshDatePickersInModal(modal);
    }
};

window.openModal = openModal;

const closeModal = (modal) => {
    const videos = modal.querySelectorAll("video");
    videos.forEach((video) => {
        video.pause();
    });

    modal.style.display = "none";
    clearForm(modal);
};

window.closeModal = closeModal;

const clearForm = (modal) => {
    const inputs = modal.querySelectorAll("input, textarea, select");
    inputs.forEach((input) => {
        if (input._flatpickr) {
            input._flatpickr.clear(false);
        }
        if (input.type === "checkbox" || input.type === "radio") {
            input.checked = false;
        } else {
            input.value = "";
        }
        hideError(input);
    });
};

window.clearForm = clearForm;

const validateInput = (input) => {
    if (input.type === "email") {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    } else if (input.type === "checkbox") {
        return input.checked;
    } else if (input.type === "radio") {
        const radios = document.getElementsByName(input.name);
        return Array.from(radios).some((r) => r.checked);
    } else if (input.type === "file") {
        return input.files.length > 0;
    } else {
        return input.value.trim() !== "";
    }
};

window.validateInput = validateInput;

const showError = (input, message) => {
    input.classList.add("is-invalid");
    if (input.nextElementSibling) {
        input.nextElementSibling.innerText = message;
        input.nextElementSibling.style.display = "block";
    }
};

window.showError = showError;

const hideError = (input) => {
    input.classList.remove("is-invalid");
    if (input.nextElementSibling)
        input.nextElementSibling.style.display = "none";
};

window.hideError = hideError;

const waitingButtons = new Set();

const markButtonWaiting = (btn) => {
    if (!btn || btn.dataset.noWait === "1") return;
    if (btn.dataset.wasDisabled === "1") return;

    btn.dataset.wasDisabled = btn.disabled ? "1" : "0";
    btn.disabled = true;
    btn.classList.add("is-waiting");
    waitingButtons.add(btn);
};

const releaseWaitingButtons = () => {
    waitingButtons.forEach((btn) => {
        if (!btn) return;
        if (btn.dataset.wasDisabled !== "1") {
            btn.disabled = false;
        }
        btn.classList.remove("is-waiting");
        delete btn.dataset.wasDisabled;
    });
    waitingButtons.clear();
};

document.addEventListener(
    "click",
    (event) => {
        const btn = event.target.closest("button[wire\\:click]");
        if (!btn) return;
        markButtonWaiting(btn);
    },
    true,
);

const setupLivewireWaitingHooks = () => {
    if (!window.Livewire || typeof window.Livewire.hook !== "function") return;

    window.Livewire.hook("request", ({ succeed, fail }) => {
        succeed(() => releaseWaitingButtons());
        fail(() => releaseWaitingButtons());
    });
};

document.addEventListener("livewire:initialized", setupLivewireWaitingHooks);
document.addEventListener("Livewire:initialized", setupLivewireWaitingHooks);

document.addEventListener("Livewire:initialized", () => {
    // Abrir modal
    const modalButtons = document.querySelectorAll("[data-modal-target]");
    modalButtons.forEach((btn) => {
        const target = document.querySelector(btn.dataset.modalTarget);
        if (!target) return;
        btn.addEventListener("click", () => openModal(target));
    });

    // Cerrar modal
    const closeButtons = document.querySelectorAll(
        ".modal .btn-close, .modal .btn-secondary",
    );

    closeButtons.forEach((btn) => {
        const modal = btn.closest(".modal");
        btn.addEventListener("click", () => closeModal(modal));
    });

    // Global synchronization para modal abrir - sincroniza pickers después que Livewire actualiza
    if (window.Livewire && typeof window.Livewire.on === "function") {
        window.Livewire.on("abrir-modal", () => {
            // Pequeño delay para asegurarse que Livewire ha actualizado el DOM completamente
            setTimeout(() => {
                const openedModal = Array.from(
                    document.querySelectorAll(".modal"),
                ).find((m) => m.style.display === "flex");
                if (openedModal && window.refreshDatePickersInModal) {
                    window.refreshDatePickersInModal(openedModal);
                }
            }, 50);
        });
    }

    // Vincular cambios de inputs de fecha para revalidar rango
    document.addEventListener(
        "input",
        (event) => {
            const input = event.target;
            if (
                input.classList.contains("js-picker-date") ||
                input.classList.contains("js-picker-datetime")
            ) {
                const modal = input.closest(".modal");
                if (modal) {
                    setTimeout(() => {
                        revalidateDateRange(input, modal);
                    }, 10);
                }
            }
        },
        true,
    );

    // Monitorear cambios de Livewire en campos de fecha
    if (window.Livewire && typeof window.Livewire.hook === "function") {
        window.Livewire.hook("effect", () => {
            // Después de cada update de Livewire, revalidar rangos de fechas en modales
            setTimeout(() => {
                const modals = document.querySelectorAll(
                    ".modal[style*='display: flex']",
                );
                modals.forEach((modal) => {
                    const dateInputs = modal.querySelectorAll(
                        ".js-picker-date, .js-picker-datetime",
                    );
                    dateInputs.forEach((input) => {
                        revalidateDateRange(input, modal);
                    });
                });
            }, 50);

            // Reinicializar pickers en vistas normales que fueron limpiados
            setTimeout(() => {
                initDatePickers();
            }, 100);
        });
    }

    // Listener para cambios en selects de Livewire que afecten pickers
    document.addEventListener("change", (event) => {
        const select = event.target;
        if (
            select.tagName === "SELECT" &&
            (select.name === "room_id" || select.getAttribute("wire:model"))
        ) {
            setTimeout(() => {
                initDatePickers();
            }, 150);
        }
    });

    // No cerrar al hacer clic fuera del modal
});

function buildPickerConfig(isDateTime) {
    const config = {
        allowInput: true,
        disableMobile: true,
        altInput: true,
        clickOpens: true,
        locale: Spanish,
        monthSelectorType: "dropdown",
        static: true,
        prevArrow: '<span class="text-white">&#10094;</span>',
        nextArrow: '<span class="text-white">&#10095;</span>',
        onReady: function (_, __, instance) {
            if (instance.altInput) {
                instance.altInput.style.width = "100%";
                instance.altInput.style.boxSizing = "border-box";
                instance.altInput.classList.add("bg-white/5");
                instance.altInput.classList.add("text-white");
                instance.altInput.classList.add("border-2");
                instance.altInput.classList.add("border-white/20");
                instance.altInput.classList.add("rounded-xl");
                instance.altInput.classList.add("px-4");
                instance.altInput.classList.add("py-3");
                instance.altInput.classList.add("focus:ring-2");
                instance.altInput.classList.add("focus:ring-cyan-400");
                instance.altInput.classList.add("focus:border-cyan-400");
                instance.altInput.classList.add("transition");
                // Agregar placeholder
                instance.altInput.placeholder = isDateTime
                    ? "dd/mm/yyyy HH:MM"
                    : "dd/mm/yyyy";
            }

            if (instance.calendarContainer) {
                instance.calendarContainer.classList.add("ml-flatpickr-theme");
            }
        },
        onOpen: function (_, __, instance) {
            if (instance.calendarContainer) {
                instance.calendarContainer.classList.add("ml-flatpickr-theme");
            }
        },
        onChange: function (_, __, instance) {
            instance.input.dispatchEvent(new Event("input", { bubbles: true }));
            instance.input.dispatchEvent(
                new Event("change", { bubbles: true }),
            );
        },
        onClose: function (_, __, instance) {
            instance.input.dispatchEvent(new Event("input", { bubbles: true }));
            instance.input.dispatchEvent(
                new Event("change", { bubbles: true }),
            );
        },
    };

    if (isDateTime) {
        return {
            ...config,
            enableTime: true,
            time_24hr: false,
            dateFormat: "Y-m-d\\TH:i",
            altFormat: "d/m/Y h:i K",
        };
    }

    return {
        ...config,
        enableTime: false,
        dateFormat: "Y-m-d",
        altFormat: "d/m/Y",
    };
}

window.flatpickr = flatpickr;
window.buildPickerConfig = buildPickerConfig;

// Función para revalidar restricciones de rango (reutilizable)
window.revalidateDateRange = function revalidateDateRange(input, scope) {
    const inputId = input.id;
    const inputName = input.name;
    const dateFormat = input.classList.contains("js-picker-datetime")
        ? "Y-m-d\\TH:i"
        : "Y-m-d";

    // Encontrar los inputs compañero (inicio/fin)
    const startInput =
        scope.querySelector("#starts_at") ||
        scope.querySelector('[id*="starts_at"]') ||
        scope.querySelector('[name*="starts_at"]');

    const endInput =
        scope.querySelector("#ends_at") ||
        scope.querySelector('[id*="ends_at"]') ||
        scope.querySelector('[name*="ends_at"]');

    if (!startInput || !endInput) return;

    const startValue = startInput.value;
    const endValue = endInput.value;

    // Siempre validar: si fin existe y es anterior a inicio, corregir
    if (
        startValue &&
        endValue &&
        startInput._flatpickr &&
        endInput._flatpickr
    ) {
        const startDate = new Date(startValue);
        const endDate = new Date(endValue);

        // Si fin es anterior a inicio, auto-corregir
        if (endDate < startDate) {
            endInput.value = startInput.value;
            endInput._flatpickr.setDate(startDate, true, dateFormat);
            // Disparar cambio en Livewire
            endInput.dispatchEvent(new Event("input", { bubbles: true }));
            endInput.dispatchEvent(new Event("change", { bubbles: true }));
        }
    }

    // Si cambió el de inicio, aplicar minDate a fin
    const isStarts =
        inputId === "starts_at" ||
        inputId.includes("starts") ||
        inputName.includes("starts");

    if (
        isStarts &&
        startValue &&
        startInput._flatpickr &&
        endInput._flatpickr
    ) {
        const startDate = new Date(startValue);
        // Establecer fecha mínima en el input fin
        endInput._flatpickr.set("minDate", startDate);
    }
};

let flatpickrThemeInjected = false;
function ensureFlatpickrTheme() {
    if (flatpickrThemeInjected) return;

    const style = document.createElement("style");
    style.id = "ml-flatpickr-theme";
    style.textContent = `
        .flatpickr-wrapper {
            width: 100%;
            display: block;
        }
        .flatpickr-calendar.ml-flatpickr-theme {
            background: #0f172a;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow: 0 12px 30px rgba(0,0,0,.45);
            color: #fff;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-months,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-weekdays {
            background: #111827;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-current-month,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-weekday,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day {
            color: #fff;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day:hover,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day:focus {
            background: rgba(59,130,246,.25);
            border-color: rgba(59,130,246,.4);
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day.selected,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day.startRange,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day.endRange {
            background: #2563eb;
            border-color: #2563eb;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-day.today {
            border-color: #22d3ee;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-monthDropdown-months,
        .flatpickr-calendar.ml-flatpickr-theme .numInputWrapper input {
            background: #0f172a !important;
            color: #fff !important;
            border: 1px solid rgba(255,255,255,.2) !important;
            border-radius: 6px;
        }
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-time input,
        .flatpickr-calendar.ml-flatpickr-theme .flatpickr-time .flatpickr-am-pm {
            color: #fff;
            background: #0f172a;
        }
        .flatpickr-calendar.static.open {
            position: relative;
        }
        .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
            background: transparent !important;
            border-color: rgba(255,255,255,.1) !important;
            color: rgba(255,255,255,.3) !important;
            cursor: not-allowed !important;
        }
    `;

    document.head.appendChild(style);
    flatpickrThemeInjected = true;
}

function initDatePickers(scope = document) {
    ensureFlatpickrTheme();

    const dateInputs = scope.querySelectorAll("input.js-picker-date");
    const datetimeInputs = scope.querySelectorAll("input.js-picker-datetime");

    const ensurePicker = (input, isDateTime) => {
        const dateFormat = isDateTime ? "Y-m-d\\TH:i" : "Y-m-d";

        // Si el picker ya existe, guardar minDate antes de destruir
        if (input._flatpickr) {
            try {
                if (
                    input._flatpickr.config &&
                    input._flatpickr.config.minDate
                ) {
                    input._prevMinDate = input._flatpickr.config.minDate;
                }
                input._flatpickr.destroy();
            } catch (e) {}
            delete input._flatpickr;
        }

        // CREAR NUEVO PICKER
        let config = buildPickerConfig(isDateTime);

        // Detectar si es inicio o fin
        const isStart = (inp) => {
            const id = (inp.id || "").toLowerCase();
            const name = (inp.name || "").toLowerCase();
            return id.includes("starts") || name.includes("starts");
        };

        const isEnd = (inp) => {
            const id = (inp.id || "").toLowerCase();
            const name = (inp.name || "").toLowerCase();
            return id.includes("ends") || name.includes("ends");
        };

        // BUSCAR INPUTS COMPAÑEROS
        const allInputs = Array.from(
            scope.querySelectorAll(
                "input.js-picker-date, input.js-picker-datetime",
            ),
        );

        const startInput = allInputs.find(isStart);
        const endInput = allInputs.find(isEnd);

        // SI ES FIN: APLICAR minDate
        if (isEnd(input) && startInput && startInput.value) {
            try {
                const startDate = new Date(startInput.value);
                if (!isNaN(startDate.getTime())) {
                    config.minDate = startDate;
                }
            } catch (e) {}
        }

        // CALLBACK AL CERRAR - VALIDACIÓN SUAVE
        const originalOnClose = config.onClose;

        config.onClose = function (selectedDates, dateStr, instance) {
            if (originalOnClose) {
                originalOnClose.call(this, selectedDates, dateStr, instance);
            }

            if (!selectedDates || !selectedDates[0]) return;

            const selectedDate = selectedDates[0];

            // SI ES PICKER DE FIN: VALIDAR CONTRA INICIO
            if (isEnd(instance.input) && startInput && startInput.value) {
                const startDate = new Date(startInput.value);
                if (selectedDate < startDate) {
                    instance.setDate(startDate, true, dateFormat);
                    return;
                }
            }

            // SI ES PICKER DE INICIO: ACTUALIZAR minDate DE FIN
            if (isStart(instance.input) && endInput && endInput._flatpickr) {
                endInput._flatpickr.set("minDate", selectedDate);

                if (endInput.value) {
                    const endDate = new Date(endInput.value);
                    if (endDate < selectedDate) {
                        endInput._flatpickr.setDate(
                            selectedDate,
                            true,
                            dateFormat,
                        );
                        endInput.dispatchEvent(
                            new Event("input", { bubbles: true }),
                        );
                        endInput.dispatchEvent(
                            new Event("change", { bubbles: true }),
                        );
                    }
                }
            }

            instance.input.dispatchEvent(new Event("input", { bubbles: true }));
            instance.input.dispatchEvent(
                new Event("change", { bubbles: true }),
            );
        };

        flatpickr(input, config);
    };

    dateInputs.forEach((input) => {
        ensurePicker(input, false);
    });

    datetimeInputs.forEach((input) => {
        ensurePicker(input, true);
    });
}

window.initDatePickers = initDatePickers;

function refreshDatePickersInModal(modal) {
    if (!modal) return;

    // Destruir todos los pickers existentes en el modal para evitar que queden con valores viejos
    const allDateInputs = modal.querySelectorAll(
        "input.js-picker-date, input.js-picker-datetime",
    );
    allDateInputs.forEach((input) => {
        if (input._flatpickr) {
            try {
                input._flatpickr.destroy();
            } catch (e) {}
            delete input._flatpickr;
        }
    });

    // Esperar a que Livewire actualice completamente el DOM
    setTimeout(() => {
        // Ahora inicializar pickers frescos con los nuevos valores de Livewire
        initDatePickers(modal);

        // Después de crear los pickers, validar el rango
        setTimeout(() => {
            const inputs = Array.from(
                modal.querySelectorAll(
                    "input.js-picker-date, input.js-picker-datetime",
                ),
            );

            const isStart = (inp) => {
                const id = (inp.id || "").toLowerCase();
                const name = (inp.name || "").toLowerCase();
                return id.includes("starts") || name.includes("starts");
            };

            const isEnd = (inp) => {
                const id = (inp.id || "").toLowerCase();
                const name = (inp.name || "").toLowerCase();
                return id.includes("ends") || name.includes("ends");
            };

            const startInput = inputs.find(isStart);
            const endInput = inputs.find(isEnd);

            // Validar y corregir si es necesario
            if (
                startInput &&
                endInput &&
                startInput._flatpickr &&
                endInput._flatpickr &&
                startInput.value &&
                endInput.value
            ) {
                try {
                    const startDate = new Date(startInput.value);
                    const endDate = new Date(endInput.value);
                    if (
                        !isNaN(startDate.getTime()) &&
                        !isNaN(endDate.getTime())
                    ) {
                        if (endDate < startDate) {
                            const dateFormat = endInput.classList.contains(
                                "js-picker-datetime",
                            )
                                ? "Y-m-d\\TH:i"
                                : "Y-m-d";
                            endInput._flatpickr.setDate(
                                startDate,
                                true,
                                dateFormat,
                            );
                            endInput.value = startInput.value;
                            endInput.dispatchEvent(
                                new Event("input", { bubbles: true }),
                            );
                            endInput.dispatchEvent(
                                new Event("change", { bubbles: true }),
                            );
                        }
                    }
                } catch (e) {}
            }
        }, 50);
    }, 150);
}

window.refreshDatePickersInModal = refreshDatePickersInModal;

let datePickerRaf = null;
const scheduleInitDatePickers = (scope = document) => {
    if (datePickerRaf) {
        cancelAnimationFrame(datePickerRaf);
    }

    datePickerRaf = requestAnimationFrame(() => {
        initDatePickers(scope);
        datePickerRaf = null;
    });
};

document.addEventListener("DOMContentLoaded", () => scheduleInitDatePickers());
document.addEventListener("livewire:initialized", () =>
    scheduleInitDatePickers(),
);
document.addEventListener("livewire:navigated", () =>
    scheduleInitDatePickers(),
);
