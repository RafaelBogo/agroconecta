(function () {
    const form = document.getElementById("add-to-cart-form");
    const modalEl = document.getElementById("successModal");
    if (!form) return;

    const cartAddUrl = form.dataset.cartAddUrl || "";
    const loginUrl = form.dataset.loginUrl || "";
    const productId = Number(form.dataset.productId || 0);

    const csrf =
        document.querySelector('meta[name="csrf-token"]')?.content || "";
    const successModal = modalEl
        ? bootstrap.Modal.getOrCreateInstance(modalEl)
        : null;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const qtyEl = document.getElementById("quantity");
        let q = (qtyEl?.value || "").trim().replace(",", ".");
        const quantity = parseFloat(q);
        if (!Number.isFinite(quantity) || quantity <= 0) {
            alert("Quantidade inválida.");
            return;
        }

        try {
            const r = await fetch(cartAddUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({ product_id: productId, quantity }),
            });

            if (r.status === 401) {
                window.location.href = loginUrl;
                return;
            }
            if (!r.ok) throw new Error("Erro ao adicionar ao carrinho");

            try {
                await r.json();
            } catch (_) {}

            document
                .querySelectorAll(".modal-backdrop")
                .forEach((el) => el.remove());
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");

            if (successModal) successModal.show();
        } catch (err) {
            console.error(err);
            alert("Houve um erro ao adicionar o produto ao carrinho.");
        }
    });

    if (modalEl) {
        modalEl.addEventListener("hidden.bs.modal", () => {
            document
                .querySelectorAll(".modal-backdrop")
                .forEach((el) => el.remove());
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");
        });
    }
})();
