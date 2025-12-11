const initProfileSavedCars = () => {
  const savedCarForm = document.getElementById("saved_car_form");
  const typeSelect = document.getElementById("vehicle_type_select");
  const vinInput = document.getElementById("vin_input");
  const saveButton = document.getElementById("save_car_btn");
  const clearButton = document.getElementById("clear_saved_cars_btn");
  const statusBox = document.getElementById("save_car_status");
  const savedCarsList = document.getElementById("saved_cars_list");
  const paginationControls = document.querySelector(".saved_cars_pagination");
  const paginationPrev = document.getElementById("saved_cars_prev");
  const paginationNext = document.getElementById("saved_cars_next");
  const paginationStatus = document.getElementById("saved_cars_page_status");

  const pageSize = Number(paginationControls?.dataset.pageSize) || 3;
  let savedCarsData = [];
  let currentPage = 1;

  if (!savedCarForm || !typeSelect || !vinInput || !saveButton || !savedCarsList) {
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

  const resetVehicleForm = ({ keepStatus = false } = {}) => {
    savedCarForm.reset();
    vinInput.classList.remove("input_error");
    vinInput.removeAttribute("aria-invalid");
    if (!keepStatus) {
      setStatus("");
    }
    window.requestAnimationFrame(() => {
      vinInput.dispatchEvent(new Event("input", { bubbles: true }));
    });
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

      resetVehicleForm({ keepStatus: true });
      setStatus("Vehicle saved!");
    } catch (error) {
      setStatus(error.message || "Unable to save vehicle.", true);
      return;
    }

    await loadSavedCars({ showLoading: true });
  };

  const getTotalPages = () => {
    if (!savedCarsData.length) {
      return 0;
    }
    return Math.max(1, Math.ceil(savedCarsData.length / pageSize));
  };

  const updatePaginationControls = () => {
    if (!paginationControls || !paginationStatus) {
      return;
    }

    const totalPages = getTotalPages();
    const hasResults = savedCarsData.length > 0;

    paginationControls.style.display = hasResults ? "flex" : "none";

    if (paginationPrev) {
      paginationPrev.disabled = currentPage <= 1;
    }
    if (paginationNext) {
      paginationNext.disabled = currentPage >= totalPages;
    }

    paginationStatus.textContent = hasResults
      ? `Page ${currentPage} of ${totalPages}`
      : "No saved cars";
  };

  const renderSavedCars = () => {
    savedCarsList.innerHTML = "";

    if (!Array.isArray(savedCarsData) || savedCarsData.length === 0) {
      const emptyState = document.createElement("p");
      emptyState.className = "saved_cars_empty";
      emptyState.textContent = "You haven't saved any cars yet.";
      savedCarsList.appendChild(emptyState);
      updatePaginationControls();
      return;
    }

    const startIndex = (currentPage - 1) * pageSize;
    const paginatedCars = savedCarsData.slice(startIndex, startIndex + pageSize);

    const tableWrapper = document.createElement("div");
    tableWrapper.className = "saved_cars_table_wrapper";

    const table = document.createElement("table");
    table.className = "saved_cars_table";

    const thead = document.createElement("thead");
    const headerRow = document.createElement("tr");
    ["Type", "VIN", "Saved"].forEach((label) => {
      const th = document.createElement("th");
      th.scope = "col";
      th.textContent = label;
      headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    const tbody = document.createElement("tbody");
    paginatedCars.forEach((car) => {
      const row = document.createElement("tr");

      const typeCell = document.createElement("td");
      typeCell.textContent = car.vehicle_type_name || car.vehicle_type || "—";
      row.appendChild(typeCell);

      const vinCell = document.createElement("td");
      vinCell.textContent = car.vin || "—";
      row.appendChild(vinCell);

      const savedCell = document.createElement("td");
      savedCell.textContent = car.saved_at || "—";
      row.appendChild(savedCell);

      tbody.appendChild(row);
    });

    table.appendChild(tbody);
    tableWrapper.appendChild(table);
    savedCarsList.appendChild(tableWrapper);
    updatePaginationControls();
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

      savedCarsData = Array.isArray(payload.cars) ? payload.cars : [];
      currentPage = 1;
      renderSavedCars();
    } catch (error) {
      console.error("Unable to load saved cars:", error);
      savedCarsList.innerHTML = "";
      const errorMessage = document.createElement("p");
      errorMessage.className = "saved_cars_error";
      errorMessage.textContent = error.message || "Unable to load saved cars.";
      savedCarsList.appendChild(errorMessage);
      savedCarsData = [];
      currentPage = 1;
      updatePaginationControls();
    }
  };

  saveButton.addEventListener("click", saveVehicle);

  if (clearButton) {
    clearButton.addEventListener("click", (event) => {
      event.preventDefault();
      resetVehicleForm();
    });
  }

  loadVehicleTypes();
  const shouldShowInitialLoading = savedCarsList.children.length === 0;
  loadSavedCars({ showLoading: shouldShowInitialLoading });

  if (paginationPrev) {
    paginationPrev.addEventListener("click", () => {
      if (currentPage <= 1) {
        return;
      }
      currentPage -= 1;
      renderSavedCars();
    });
  }

  if (paginationNext) {
    paginationNext.addEventListener("click", () => {
      const totalPages = getTotalPages();
      if (currentPage >= totalPages) {
        return;
      }
      currentPage += 1;
      renderSavedCars();
    });
  }
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProfileSavedCars);
} else {
  initProfileSavedCars();
}
