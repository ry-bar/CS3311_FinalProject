document.addEventListener("DOMContentLoaded", function(){

    const searchBox = document.getElementById("yard_search");
    const result = document.getElementById("results_container");
    const vehicleType = document.getElementById("vehicle_type");
    
    searchBox.addEventListener("keyup", async function (){
        await performSearch();
    })
    vehicleType.addEventListener("change", async function (){
        await performSearch();
    })    
       
    async function performSearch(){
        let vehicleTypeValue = vehicleType.value;
        let search = searchBox.value.trim();

        let url = `api/yardSearch.php?search=${encodeURIComponent(search)}&vehicle_type=${encodeURIComponent(vehicleTypeValue)}`;
        try{
            let response = await fetch(url);
            result.innerHTML = "";
            let objectReturned = await response.json(); 
            if (objectReturned.errors !== undefined && objectReturned.errors.length > 0) {
                throw new Error(objectReturned.errors.join(", "));
            }else{
                let data = objectReturned.results;
                if(data.length === 0){
                    result.innerHTML = "<span>No parts found</span>";
                }else{
                    for (let part of data){
                        let partDiv = document.createElement("div");
                        partDiv.classList.add("lookup_form_block");
                        partDiv.innerHTML = `
                            <span>${part.name} || (SKU: ${part.sku}) ||
                            Condition: ${part.condition} ||
                             ${part.vehicle_type_name} ||
                            Notes: ${part.notes} </span>
                        `;
                        result.appendChild(partDiv);
                    }

                }
            }
        }
        catch (error) {
            console.error("Fetch error:", error);
            result.innerHTML = "Network error.";
            return;
    }

}
});