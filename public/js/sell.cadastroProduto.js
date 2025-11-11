document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById("sellTipsModal");
    if (!modalEl) return;

    document.body.appendChild(modalEl);

    const shouldShow = !localStorage.getItem("sellTipsSeen");
    if (shouldShow) {
        const tipsModal = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true,
        });
        tipsModal.show();

        modalEl.addEventListener("hidden.bs.modal", () => {
            localStorage.setItem("sellTipsSeen", "1");
            const first = document.querySelector(
                "form input, form textarea, form select"
            );
            if (first) first.focus();
        });
    }
});
