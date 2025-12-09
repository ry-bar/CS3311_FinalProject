<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>We Want Your Junk</title>
        <base href="http://localhost/CS3311_FinalProject/">

        <link rel="stylesheet" href="style/styles.css">
        <script src="scripts/open_close_reg.js" defer></script>
    </head>

    <body>
        <?php include __DIR__ . "/../partials/navbar.php"; ?>
        <h1>Having Junk Issues? We Think Your Junk Is Amazing!</h1>
        <h3> Call Us at (208) 312-3123! </h3>
        <div id="form_column">
            <div class="form_column_flex"><label for="name">Enter your name:</label>
            <input id="name"></div>
            <div class="form_column_flex"><label for="email">Enter your email:</label>
            <input id="email"></div>
            <div class="form_column_flex"><label for="phone_number">Enter your phone</label>
            <input id="phone_number"></div>
            <div class="form_column_flex"><label for="favorite_animal">Enter your favorite animal:</label>
            <input id="favorite_animal"></div>
            <div  class="form_column_flex"><button id="submitButton" class="form_column_flex">We'll tow your car</button></div>

        </div>
        <?php include __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
