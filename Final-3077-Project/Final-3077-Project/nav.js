document.addEventListener("DOMContentLoaded", function () {
    // Fetch the theme from the server 
    fetch('update_theme.php')
        .then(response => response.json())
        .then(data => {
            if (data.theme) {
                // Apply the new theme from the database
                document.body.classList.add(data.theme);
                console.log("Theme applied:", data.theme);
            } else {
                console.warn("No theme found, using default.");
            }
        })
        .catch(error => {
            console.error("Error loading theme:", error);
        });

    // Handle theme change by admin buttons
    const themeButtons = document.querySelectorAll('.admin');
    themeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const newTheme = this.getAttribute('data-theme');
            document.body.classList.remove(...document.body.classList);
            document.body.classList.add(newTheme);
        });
    });
    // Navbar
    fetch("navbar.php")
        .then(res => res.text())
        .then(data => {
            document.getElementById("navbar-container").innerHTML = data;
        })
        .catch(error => console.error("Navbar load error:", error));

    // Footer 
    fetch("footer.html")
        .then(res => res.text())
        .then(data => {
            document.getElementById("footer").innerHTML = data;
        })
        .catch(error => console.error("Footer load error:", error));
});