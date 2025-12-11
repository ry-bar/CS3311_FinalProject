<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Our Junk</title>
        <base href="http://localhost/CS3311_FinalProject/">
        <link rel="stylesheet" href="style/styles.css">
        <script src="scripts/open_close_reg.js" defer></script>
        <script src="scripts/yardLookup.js" defer></script>
    </head>

    <body>
        <?php include __DIR__ . "/../partials/navbar.php"; ?>

        <h1>Look At Our Junk</h1>
        <div class="lookup_form">
            <div class="lookup_form_block">
                <label for="yard_search">Search Our Yard: </label>
                <input id="yard_search" placeholder="Search Parts">
            </div>
            <div class="lookup_form_block">
            <label for="vehicle_type">Enter your vehicle type:</label>        
                <select id="vehicle_type" name="selectedVehicle">
                    <option value="0">Select a vehicle</option>
                    <option value="1">Car</option>
                    <option value="2">Truck</option>
                    <option value="3">Motorcycle</option>
                    <option value="4">SUV</option>            
                </select>
            </div>

        </div>
        <div id="results_container" class="lookup_form_stack">
            <span class="lookup_form_block">Enter a part name!</span>

        </div>
        
        <?php include __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
