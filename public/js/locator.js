document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeEventListeners();
});

function initializeEventListeners() {
    // Display locators options toggle
    const optionsDiv = document.getElementById('display_locators_options_block');
    optionsDiv.style.display = 'none'
    
    const displayLocatorsOptions = document.getElementById('displayLocatorsOptions');
    if (displayLocatorsOptions) {
        displayLocatorsOptions.addEventListener('change', function() {
            optionsDiv.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Avalisor checkbox
    const avalisorCheckbox = document.getElementById('avalisor');
    if (avalisorCheckbox) {
        avalisorCheckbox.addEventListener('change', function() {
            const avalisorInfo = document.getElementById('show_avalisor_info');
            avalisorInfo.classList.toggle('d-none', !this.checked);
        });
    }

    // Prorata checkbox
    const prorataCheckbox = document.getElementById('prorata');
    if (prorataCheckbox) {
        prorataCheckbox.addEventListener('change', function() {
            const prorataInfo = document.getElementById('show_prorata_info');
            prorataInfo.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                document.getElementById('prorata_date').value = '';
            }
        });
    }

    // Modal event listeners
    initializeModalEventListeners();
}

function initializeModalEventListeners() {
    // Show Avalisor Modal
    document.querySelectorAll('[data-bs-target="#showAvalisor"]').forEach(button => {
        button.addEventListener('click', function() {
            const locatorId = this.dataset.locatorId;
            showAvalisorModal(locatorId);
        });
    });

    // Show Houses Modal
    document.querySelectorAll('[data-bs-target="#showHouses"]').forEach(button => {
        button.addEventListener('click', function() {
            const locatorId = this.dataset.locatorId;
            showHousesModal(locatorId);
        });
    });

    // Show Rooms Modal
    document.querySelectorAll('[data-bs-target="#showRooms"]').forEach(button => {
        button.addEventListener('click', function() {
            const locatorId = this.dataset.locatorId;
            showRoomsModal(locatorId);
        });
    });

    // Update Modal
    document.querySelectorAll('[data-bs-target="#updateModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const locatorId = this.dataset.locatorId;
            showUpdateModal(locatorId);
        });
    });
}

// API Calls
async function fetchLocatorData(id) {
    try {
        const API_BASE_URL = document.querySelector('meta[name="api-base-url"]').content;
        const response = await axios.get(`${API_BASE_URL}locator/${id}/retrieve`);
        return response.data;
    } catch (error) {
        console.error('Error fetching locator data:', error);
        alert("Une erreur s'est produite lors de la récupération des données");
        throw error;
    }
}

// Modal Functions
async function showAvalisorModal(id) {
    try {
        const locator = await fetchLocatorData(id);
        const avalisor = locator.avaliseur;

        document.getElementById('ava_locator_fullmneme').textContent = `${locator.name} ${locator.prenom}`;
        document.getElementById('avar_nom').textContent = avalisor.ava_name;
        document.getElementById('avar_prenom').textContent = avalisor.ava_prenom;
        document.getElementById('avar_link').textContent = avalisor.ava_parent_link;
        document.getElementById('avar_phone').textContent = avalisor.ava_phone;
    } catch (error) {
        console.error('Error in showAvalisorModal:', error);
    }
}

async function showHousesModal(id) {
    try {
        const locator = await fetchLocatorData(id);
        const housesList = document.getElementById('locator_houses');
        housesList.innerHTML = '';

        document.getElementById('locator_fullname').textContent = `${locator.name} ${locator.prenom}`;
        document.getElementById('locator_houses_count').textContent = locator.houses.length;

        locator.houses.forEach(house => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.textContent = house.name;
            housesList.appendChild(li);
        });
    } catch (error) {
        console.error('Error in showHousesModal:', error);
    }
}

async function showRoomsModal(id) {
    try {
        const locator = await fetchLocatorData(id);
        const roomsList = document.getElementById('locator_rooms');
        roomsList.innerHTML = '';

        document.getElementById('room_locator_fullname').textContent = `${locator.name} ${locator.prenom}`;
        document.getElementById('locator_rooms_count').textContent = locator.rooms.length;

        locator.rooms.forEach(room => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerHTML = `Numero: <strong><em class="text-red">${room.number}</em></strong>; Loyer: <strong><em class="text-red">${room.loyer}</em></strong>`;
            roomsList.appendChild(li);
        });
    } catch (error) {
        console.error('Error in showRoomsModal:', error);
    }
}

async function showUpdateModal(id) {
    try {
        const locator = await fetchLocatorData(id);
        const form = document.getElementById('update_form');

        document.getElementById('update_locator_fullname').textContent = `${locator.name} ${locator.prenom}`;
        
        // Fill form fields
        document.getElementById('update-name').value = locator.name;
        document.getElementById('update-prenom').value = locator.prenom;
        document.getElementById('update-email').value = locator.email;
        document.getElementById('update-sexe').value = locator.sexe;
        document.getElementById('update-phone').value = locator.phone;
        document.getElementById('update-card_id').value = locator.card_id;
        document.getElementById('update-adresse').value = locator.adresse;
        document.getElementById('update-card_type').value = locator.card_type;
        document.getElementById('update-country').value = locator.country;
        document.getElementById('update-departement').value = locator.departement;
        document.getElementById('update-comments').value = locator.comments;

        form.action = `/locataire/${locator.id}/update`;
    } catch (error) {
        console.error('Error in showUpdateModal:', error);
    }
} 