document.addEventListener("DOMContentLoaded", () => {
    const sel = document.getElementById("unit");
    const cust = document.getElementById("unit_custom");

    function toggleCustom() {
        if (!sel || !cust) return;
        if (sel.value === "custom") {
            cust.classList.remove("d-none");
        } else {
            cust.classList.add("d-none");
            if (!cust.dataset.keep) cust.value = "";
        }
    }
    if (sel) {
        sel.addEventListener("change", toggleCustom);
        toggleCustom();
    }

    // Modal de sucesso
    const modalEl = document.getElementById("successModal");
    if (modalEl) {
        document.body.appendChild(modalEl);

        const shouldShow = (modalEl.dataset.success || "0") === "1";
        const successModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        if (shouldShow) successModal.show();

        modalEl.addEventListener("hidden.bs.modal", () => {
            document
                .querySelectorAll(".modal-backdrop")
                .forEach((b) => b.remove());
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
            const unit = document.getElementById('unit');
            const custom = document.getElementById('unit_custom');
            if (!unit || !custom) return;

            unit.addEventListener('change', function () {
                if (this.value === 'custom') {
                    custom.classList.remove('d-none');
                    custom.focus();
                } else {
                    custom.classList.add('d-none');
                }
            });
        });
