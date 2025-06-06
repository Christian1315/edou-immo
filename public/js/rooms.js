
const API_BASE_URL = document.querySelector('meta[name="api-base-url"]').content;

function showLocators(id) {
    $("#room_locators").empty();
    axios.get(`${API_BASE_URL}room/${id}/retrieve`)
        .then((response) => {
            const room = response.data;
            const roomNumber = room.number;
            const roomLocators = room.locataires;

            $("#room_number").html(roomNumber);
            $("#room_locators_count").html(roomLocators.length);

            roomLocators.forEach(locator => {
                const locatorFullname = `${locator.name} ${locator.prenom}`;
                $('#room_locators').append(`<li class='list-group-item'>${locatorFullname}</li>`);
            });
        })
        .catch((error) => {
            console.error("Erreur lors de la récupération des locataires:", error);
            alert("Une erreur s'est produite lors de la récupération des locataires");
        });
}

// Fonctions pour la gestion de l'eau
function showWaterInfo() {
    const isChecked = $('#showWaterInfo').prop('checked');
    $('#show_water_info').toggle(!isChecked);

    if (!isChecked) {
        resetWaterFields();
    }
}

function resetWaterFields() {
    $("#water_discounter").prop('checked', false);
    $("#showWaterConventionnalCounterInputs").prop('checked', false);
    $("#forage").prop('checked', false);

    $("#unit_price").val('');
    $("#forait_forage").val(0);
    $("#water_counter_number").val('');
    $("#water_counter_start_index").val('');
    $("#water_conventionnel_counter_start_index").val('');
}

function waterDiscounterInputs() {
    const isChecked = $('#water_discounter').prop('checked');
    $('#water_discounter_inputs').toggle(isChecked);

    if (!isChecked) {
        $("#unit_price").val('');
        $("#water_counter_start_index").val('');
    }
}

function showWaterConventionnalCounterInputs() {
    const isChecked = $('#showWaterConventionnalCounterInputs').prop('checked');
    $('#show_water_conventionnal_counter_inputs').toggle(!isChecked);

    if (!isChecked) {
        $("#water_counter_start_index").val('');
        $("#water_counter_number").val('');
        $("#water_conventionnel_counter_start_index").val('');
    }
}

function showForageInputs() {
    const isChecked = $('#forage').prop('checked');
    $('#show_forage_inputs').toggle(isChecked);

    if (!isChecked) {
        $("#forait_forage").val(0);
    }
}

// Fonctions pour la gestion de l'électricité
function showElectricityInfo() {
    const isChecked = $('#btncheck_electricity').prop('checked');
    $('#showElectricityInfo_block').toggle(isChecked);

    if (!isChecked) {
        resetElectricityFields();
    }
}

function resetElectricityFields() {
    $("#electricity_decounter_flexCheckChecked").prop('checked', false);
    $("#electricity_card_flexCheckDefault").prop('checked', false);
    $("#electricity_card_conven_flexCheckChecked").prop('checked', false);

    $("#electricity_unit_price").val('');
    $("#electricity_counter_number").val('');
    $("#electricity_counter_start_index").val('');
}

function showElectricityDiscountInputs() {
    const isChecked = $('#electricity_decounter_flexCheckChecked').prop('checked');
    $('#show_electricity_discountInputs').toggle(isChecked);

    if (!isChecked) {
        $("#electricity_unit_price").val('');
        $("#electricity_counter_number").val('');
        $("#electricity_counter_start_index").val('');
    }
}

// Fonction pour la mise à jour d'une chambre
function updateRoom(id) {
    axios.get(`${API_BASE_URL}room/${id}/retrieve`)
        .then((response) => {
            const room = response.data;

            // 
            $("#update_showWaterInfo").prop('checked',room.water)
            $("#update_water_discounter").prop('checked',room.water_discounter)
            $("#update_showWaterConventionnalCounterInputs").prop('checked',room.water_conventionnal_counter)
            $("#update_forage").prop('checked',room.forage)
            $("#update_forfait_forage").val(room.forfait_forage)
            $("#update_unit_price").val(Number(room.unit_price))
            $("#update_water_counter_start_index").val(room.water_counter_start_index)
            $("#update_water_counter_number").val(room.water_counter_number)
            $("#update_water_conventionnel_counter_start_index").val(room.water_conventionnel_counter_start_index)

            $("#update_room_fullname").html(room.number);
            $("#loyer").val(room.loyer);
            $("#number").val(room.number);
            $("#gardiennage").val(room.gardiennage);
            $("#rubbish").val(room.rubbish);
            $("#vidange").val(room.vidange);
            $("#cleaning").val(room.cleaning);
            $("#comments").val(room.comments);

            $("#update_btncheck_electricity").prop('checked',room.electricity)
            $("#update_electricity_decounter_flexCheckChecked").prop('checked',room.electricity_discounter)
            $("#update_electricity_card_flexCheckDefault").prop('checked',room.electricity_card_counter)

            $("#update_electricity_card_conven_flexCheckChecked").prop('checked',room.electricity_conventionnal_counter)
            $("#update_electricity_counter_number").val(room.electricity_counter_number);
            $("#update_electricity_unit_price").val(Number(room.electricity_unit_price));
            $("#update_electricity_counter_start_index").val(room.electricity_counter_start_index);

            $("#update_form").attr("action", `/room/${room.id}/update`);
        })
        .catch((error) => {
            console.error("Erreur lors de la récupération des données de la chambre:", error);
            alert("Une erreur s'est produite lors de la récupération des données de la chambre");
        });
}

// Initialisation
$(document).ready(function () {
    $("#showWaterInfo").click();
    $('#show_water_conventionnal_counter_inputs').toggle(false);
    $('#show_forage_inputs').toggle(false);
    $('#water_discounter_inputs').toggle(false);
    $("#forait_forage").val(0);

    $('#showElectricityInfo_block').toggle(false);
    $('#show_electricity_discountInputs').toggle(false);


    // UPDATING 
}); 