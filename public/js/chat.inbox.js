document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("chatSearch");
    const clear = document.getElementById("clearSearch");
    const items = [...document.querySelectorAll("#chatList .chat-item")];

    if (!input) return;

    const applyFilter = () => {
        const q = input.value.trim().toLowerCase();
        clear.classList.toggle("d-none", q.length === 0);
        items.forEach((el) => {
            const name = el.getAttribute("data-name") || "";
            el.style.display = name.includes(q) ? "" : "none";
        });
    };

    input.addEventListener("input", applyFilter);
    clear?.addEventListener("click", () => {
        input.value = "";
        applyFilter();
        input.focus();
    });
});
