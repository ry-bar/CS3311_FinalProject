document.addEventListener("DOMContentLoaded", () => {
  const typeSelect = document.getElementById("vehicle_type_select");
  const vinInput = document.getElementById("vin_input");
  const saveButton = document.getElementById("save_car_btn");
  const statusBox = document.getElementById("save_car_status");
  const savedCarsList = document.getElementById("saved_cars_list");

  if (!typeSelect || !vinInput || !saveButton || !savedCarsList) {
    return;
  }

  const setStatus = (message, isError = false) => {
    if (!statusBox) {
      if (message) {
        const log = isError ? console.error : console.log;
        log(message);
      }
      return;
    }

    statusBox.textContent = message;
    statusBox.classList.toggle("error", Boolean(message) && isError);
    statusBox.classList.toggle("success", Boolean(message) && !isError);
  };

  const loadVehicleTypes = async () => {
    try {
      const response = await fetch("api/vehicles.php");
      if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
      }

      const payload = await response.json();
      const types = Array.isArray(payload.results) ? payload.results : [];

      // clear previous options before adding new ones.
      typeSelect.querySelectorAll("option[data-dynamic='true']").forEach((option) => {
        option.remove();
      });

      types.forEach((type) => {
        if (!type.slug) {
          return;
        }

        const option = document.createElement("option");
        option.value = type.slug;
        option.textContent = type.slug;
        option.dataset.dynamic = "true";
        if (type.id) {
          option.dataset.typeId = String(type.id);
        }
        typeSelect.appendChild(option);
      });
    } catch (error) {
      console.error("Unable to load vehicle types:", error);
    }
  };

  const getSelectedVehicleTypeId = () => {
    const selectedOption = typeSelect.selectedOptions[0];
    return selectedOption?.dataset.typeId || "";
  };

  const saveVehicle = async () => {
    const vin = vinInput.value.trim();
    const vehicleTypeId = getSelectedVehicleTypeId();

    if (!vin) {
      setStatus("Please enter a VIN before saving.", true);
      return;
    }

    if (vin.length > 17) {
      setStatus("VIN must be 17 characters or fewer.", true);
      return;
    }

    if (!vehicleTypeId) {
      setStatus("Please select a vehicle type.", true);
      return;
    }

    setStatus("Saving vehicle...");

    try {
      const response = await fetch("api/save_user_saved_car.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          vin,
          vehicle_type_id: Number(vehicleTypeId),
        }),
      });

      const payload = await response.json().catch(() => ({}));

      if (!response.ok || !payload.success) {
        const message = payload.error || "Unable to save vehicle.";
        throw new Error(message);
      }

      vinInput.value = "";
      typeSelect.value = "";
      setStatus("Vehicle saved!");
    } catch (error) {
      setStatus(error.message || "Unable to save vehicle.", true);
      return;
    }

    await loadSavedCars({ showLoading: true });
  };

  const createField = (label, value) => {
    const span = document.createElement("span");
    span.className = "saved_car_field";
    const labelElement = document.createElement("strong");
    labelElement.textContent = label;
    span.append(labelElement, " ", value || "Unknown");
    return span;
  };

  const renderSavedCars = (cars) => {
    savedCarsList.innerHTML = "";

    if (!Array.isArray(cars) || cars.length === 0) {
      const emptyState = document.createElement("p");
      emptyState.className = "saved_cars_empty";
      emptyState.textContent = "You haven't saved any cars yet.";
      savedCarsList.appendChild(emptyState);
      return;
    }

    const lines = document.createElement("div");
    lines.className = "saved_cars_lines";

    cars.forEach((car) => {
      const line = document.createElement("p");
      line.className = "saved_car_line";

      const typeLabel = car.vehicle_type_name || car.vehicle_type || "";
      line.appendChild(createField("Type:", typeLabel));
      line.appendChild(document.createTextNode(" "));

      line.appendChild(createField("VIN:", car.vin || ""));

      if (car.saved_at) {
        line.appendChild(document.createTextNode(" "));
        line.appendChild(createField("Saved:", car.saved_at));
      }

      lines.appendChild(line);
    });

    savedCarsList.appendChild(lines);
  };

  const loadSavedCars = async ({ showLoading = false } = {}) => {
    if (showLoading) {
      savedCarsList.textContent = "Loading saved cars...";
    }

    try {
      const response = await fetch("api/user_saved_cars.php");
      const payload = await response.json().catch(() => ({}));

      if (!response.ok || payload.success === false) {
        const message = payload.error || "Unable to load saved cars.";
        throw new Error(message);
      }

      renderSavedCars(payload.cars || []);
    } catch (error) {
      console.error("Unable to load saved cars:", error);
      savedCarsList.innerHTML = "";
      const errorMessage = document.createElement("p");
      errorMessage.className = "saved_cars_error";
      errorMessage.textContent = error.message || "Unable to load saved cars.";
      savedCarsList.appendChild(errorMessage);
    }
  };

  saveButton.addEventListener("click", saveVehicle);
  loadVehicleTypes();
  const shouldShowInitialLoading = savedCarsList.children.length === 0;
  loadSavedCars({ showLoading: shouldShowInitialLoading });
});
