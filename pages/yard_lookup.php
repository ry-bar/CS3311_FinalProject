<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Our Junk</title>
        <base href="http://localhost/CS3311_FinalProject/">
        <link rel="stylesheet" href="style/styles.css">
        <script src="scripts/open_close_reg.js" defer></script>
    </head>

    <body>
        <?php include __DIR__ . "/../partials/navbar.php"; ?>

        <h1>Look At Our Junk</h1>
        <div class="lookup_form">
        <label for="yard search">Search Our Yard: </label>
        <input id="yard search">
        </div>
        
        <!-- <div id="saved_cars_list">
            <?php if (empty($savedCars)): ?>
                    <p class="saved_cars_empty">You haven't saved any cars yet.</p>
                <?php else: ?>
                    <div class="saved_cars_lines">
                        <?php foreach ($savedCars as $car): ?>
                            <p class="saved_car_line">
                                <span class="saved_car_field"><strong>Type:</strong> <?= htmlspecialchars($car["vehicle_type_name"] ?? $car["vehicle_type"] ?? ""); ?></span>
                                <span class="saved_car_field"><strong>VIN:</strong> <?= htmlspecialchars($car["vin"] ?? ""); ?></span>
                                <?php if (!empty($car["saved_at"])): ?>
                                    <span class="saved_car_field"><strong>Saved:</strong> <?= htmlspecialchars($car["saved_at"] ?? ""); ?></span>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div> -->

        
        <?php include __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
