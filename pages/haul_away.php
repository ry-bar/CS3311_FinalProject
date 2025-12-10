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
            <div class="form_column_flex">
                <label for="name">Enter your name:</label>
                <input id="name">
            <div class="form_column_flex">
                <label for="email">Enter your email:</label>
                <input id="email">
            </div>
            <div class="form_column_flex">
                <label for="phone_number">Enter your phone:</label>
                <input id="phone_number">
            </div>
            <div class="form_column_flex">
                <label for="vehicle_type">Enter your vehicle type:</label>
                
                <select id="vehicle_type" name="selectedVehicle">
                    <option value="car">Car</option>
                    <option value="truck">Truck</option>
                    <option value="motorcycle">Motorcycle</option>

                    <option value="suv">SUV</option>
                    <!--
                    <option value="rv">Option 4</option>
                    <option value="other">Option 5</option>
                    <option value="semi">Option 6</option>
                    <option value="bus">Option 7</option> -->
                    
                </select>
            </div>
            <div class="form_column_flex">
                <label for="service_type">Enter your service type:</label>
                <select id="service_type" name="selectedService">
                    <option value="inspection">Inspection</option>
                    <option value="tow">Vehicle Tow</option>
                    <option value="repair">Repair</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="form_column_flex">
                <label for="comments">Comments:</label>
                <textarea id="comments" rows="5" cols="40" placeholder="Put any comments you would like our team to know"></textarea>
            </div>
            <div  class="form_column_flex"><button id="submitButton" class="form_column_flex">Submit</button></div>

        </div>
                <!-- name, email, phonenumber, comments, vehicle_type, service_type -->

        <!-- button handling -->
        <?php include __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
