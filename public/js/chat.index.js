document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("chatForm");
    const ta = document.getElementById("chatInput");
    const btn = document.getElementById("sendBtn");

    ta.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            const msg = ta.value.trim();
            if (!msg) return;

            btn?.setAttribute("disabled", "disabled");
            form.submit();
        }
    });

    const autosize = () => {
        ta.style.height = "auto";
        ta.style.height = Math.min(ta.scrollHeight, 120) + "px";
    };
    ta.addEventListener("input", autosize);
    autosize();
});
