
    window.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById("loader");
        loader.style.display = "none";

        document.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", function (e) {
                const href = this.getAttribute("href");
                if (href && !href.startsWith("#") && !href.startsWith("javascript:")) {
                    e.preventDefault();
                    loader.style.display = "flex";
                    setTimeout(() => {
                        window.location.href = href;
                    }, 800); // pode aumentar esse tempo se quiser que o efeito dure mais
                }
            });
        });
    });

