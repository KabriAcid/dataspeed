<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Tabs</h5>
        </header>

        <div>

            <!-- TABS -->
            <div class="tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="pending">Pending</button>
                    <button class="tab-btn" data-tab="completed">Completed</button>
                </div>

                <div class="tab-content" id="pending">
                    <p>This is the <strong>Pending</strong> content.</p>
                </div>

                <div class="tab-content hidden" id="completed">
                    <p>This is the <strong>Completed</strong> content.</p>
                </div>
            </div>


            <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".tabs").forEach(tabsContainer => {
                const buttons = tabsContainer.querySelectorAll(".tab-btn");
                const contents = tabsContainer.querySelectorAll(".tab-content");

                buttons.forEach(button => {
                    button.addEventListener("click", () => {
                        const tabId = button.getAttribute("data-tab");

                        // Remove active from all buttons in this container
                        buttons.forEach(btn => btn.classList.remove("active"));

                        // Hide all contents in this container
                        contents.forEach(content => content.classList.add("hidden"));

                        // Add active to clicked button
                        button.classList.add("active");

                        // Show the matching tab content
                        tabsContainer.querySelector(`#${tabId}`).classList.remove("hidden");
                    });
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>