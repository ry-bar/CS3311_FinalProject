<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <base href="http://localhost/CS3311_FinalProject/">
        <link rel="stylesheet" href="style/styles.css">
        <script src="scripts/open_close_reg.js" defer></script>
    </head>
    <body>
        <?php include __DIR__ . "/../partials/navbar.php"; ?>
        <main class="contact_page">
            <div>
                <h1>Our Information</h1>
                <address>
                    <p>Phone: <a href="tel:2081234567">(208)123-4567</a></p>
                    <p>Email: <a href="mailto:contactus@howdoesemailevenwork.com">contactus@thisisntevenarealemail.com</a></p>
                </address>
            </div>

            <aside class="contact-column contact-info-col">
                <div class="contact-card">
                    <h3>Office Hours</h3>
                    <ul class="office-hours">
                        <li>Mon–Sat: 8am–5pm</li>
                        <li>Sun: 8am-12pm</li>
                    </ul>
                </div>

                <div class="map-wrapper">
                    <iframe src="https://www.google.com/maps?q=42.874301,-112.45512&z=17&output=embed" loading="lazy"></iframe>
                </div>
            </aside>

        </main>

        <?php include __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
