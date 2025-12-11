document.addEventListener("DOMContentLoaded", () => {
  const vinInput = document.getElementById("vin_input");
  const saveButton = document.getElementById("save_car_btn");

  if (!vinInput) {
    return;
  }

  const validationMessage = document.createElement("div");
  validationMessage.id = "vin_duplicate_message";
  validationMessage.className = "form_message vin_validation_message";
  validationMessage.setAttribute("role", "status");
  validationMessage.setAttribute("aria-live", "polite");
  validationMessage.style.display = "none";
  vinInput.insertAdjacentElement("afterend", validationMessage);

  const MIN_VIN_LENGTH = 17;
  let currentRequestController = null;
  let lastVinChecked = "";

  const setStatusMessage = (text = "", variant = "") => {
    validationMessage.textContent = text;
    validationMessage.style.display = text ? "block" : "none";
    validationMessage.classList.remove("error", "success");
    if (variant) {
      validationMessage.classList.add(variant);
    }
  };

  const setSaveButtonDisabled = (disabled) => {
    if (saveButton) {
      saveButton.disabled = disabled;
    }
  };

  const cancelPendingRequest = () => {
    if (currentRequestController) {
      currentRequestController.abort();
      currentRequestController = null;
    }
  };

  const resetValidationState = () => {
    cancelPendingRequest();
    lastVinChecked = "";
    setStatusMessage();
    vinInput.classList.remove("input_error");
    vinInput.removeAttribute("aria-invalid");
    setSaveButtonDisabled(false);
  };

  const checkForDuplicateVin = async (vin) => {
    cancelPendingRequest();
    const controller = new AbortController();
    currentRequestController = controller;

    setStatusMessage("Checking VIN...", "");
    setSaveButtonDisabled(true);

    try {
      const response = await fetch("api/user_saved_cars.php", {
        signal: controller.signal,
      });
      const payload = await response.json().catch(() => ({}));

      if (!response.ok || payload.success === false) {
        throw new Error(payload.error || "Unable to check VIN right now.");
      }

      const cars = Array.isArray(payload.cars) ? payload.cars : [];
      const vinUpper = vin.toUpperCase();
      const duplicateFound = cars.some(
        (car) => typeof car.vin === "string" && car.vin.toUpperCase() === vinUpper,
      );

      if (controller.signal.aborted) {
        return;
      }

      if (duplicateFound) {
        vinInput.classList.add("input_error");
        vinInput.setAttribute("aria-invalid", "true");
        setStatusMessage("You've already saved this VIN. Enter a different VIN.", "error");
        setSaveButtonDisabled(true);
      } else {
        vinInput.classList.remove("input_error");
        vinInput.removeAttribute("aria-invalid");
        setStatusMessage("VIN looks good.", "success");
        setSaveButtonDisabled(false);
      }

      lastVinChecked = vin;
    } catch (error) {
      if (controller.signal.aborted) {
        return;
      }

      vinInput.classList.add("input_error");
      vinInput.setAttribute("aria-invalid", "true");
      setStatusMessage(error.message || "Unable to check VIN right now.", "error");
      setSaveButtonDisabled(true);
      lastVinChecked = "";
    } finally {
      if (currentRequestController === controller) {
        currentRequestController = null;
      }
    }
  };

  const handleVinInput = () => {
    const vin = vinInput.value.trim();

    if (vin.length !== MIN_VIN_LENGTH) {
      resetValidationState();
      return;
    }

    if (vin === lastVinChecked) {
      return;
    }

    checkForDuplicateVin(vin);
  };

  vinInput.addEventListener("input", handleVinInput);
});
