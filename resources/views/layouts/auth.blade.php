<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    @yield('content')

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".toggle-password").forEach(toggle => {

            const input = toggle.previousElementSibling;
            if (!input) return;

            const show = () => {
                input.type = "text";
                toggle.classList.remove("fa-eye");
                toggle.classList.add("fa-eye-slash");
            };

            const hide = () => {
                input.type = "password";
                toggle.classList.remove("fa-eye-slash");
                toggle.classList.add("fa-eye");
            };

            // Desktop
            toggle.addEventListener("mousedown", show);
            toggle.addEventListener("mouseup", hide);
            toggle.addEventListener("mouseleave", hide);

            // Mobile
            toggle.addEventListener("touchstart", show);
            toggle.addEventListener("touchend", hide);
        });

    });
    </script>


    <script>
    document.addEventListener("mousemove", function (e) {
        const right = document.querySelector(".login-right");
        if (!right || window.innerWidth < 992) return;

        const x = (e.clientX / window.innerWidth - 0.5) * 10;
        const y = (e.clientY / window.innerHeight - 0.5) * 10;

        right.style.backgroundPosition = `${50 + x}% ${50 + y}%`;
    });
    </script>


</body>
</html>
