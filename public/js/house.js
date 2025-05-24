// House management functionality
const API_BASE_URL = document.querySelector('meta[name="api-base-url"]').content;

// Show rooms function
function show_rooms_fun(id) {
    $('#house_rooms').empty();

    axios.get(`${API_BASE_URL}house/${id}/retrieve`)
        .then((response) => {
            const house = response.data;
            const house_fullname = house.name;
            const house_rooms = house.rooms;

            $("#house_fullname").html(house_fullname);
            $("#house_rooms_count").html(house_rooms.length);

            house_rooms.forEach(room => {
                $('#house_rooms').append(`
                    <li class='list-group-item'>
                        <strong>N° :</strong>${room.number},
                        <strong>Loyer :</strong>${room.loyer},
                        <strong>Montant total :</strong>${room.total_amount}
                    </li>
                `);
            });
        })
        .catch((error) => {
            console.error('Error fetching house rooms:', error);
            alert("Une erreur s'est produite lors de la récupération des chambres");
        });
}

// Update modal function
function updateModal_fun(id) {
    axios.get(`${API_BASE_URL}house/${id}/retrieve`)
        .then((response) => {
            const house = response.data;
            
            $("#update_house_fullname").html(house.name);
            $("#update-name").val(house.name);
            $("#update-longitude").val(house.longitude);
            $("#update-latitude").val(house.latitude);
            $("#update-geolocalisation").val(house.geolocalisation);
            $("#update-proprio_payement_echeance_date").val(house.proprio_payement_echeance_date);
            $("#update-commission_percent").val(house.commission_percent);
            $("#update-locative_commission").val(house.locative_commission);
            $("#update-update_form").attr("action", `/house/${house.id}/update`);

            // Handle pre_paid & post_paid
            $(".paid_blocked").removeClass("d-none");
            const pre_paid = document.getElementById("update_pre_paid");
            const post_paid = document.getElementById("update_post_paid");

            pre_paid.checked = house.pre_paid;
            post_paid.checked = house.post_paid;
        })
        .catch((error) => {
            console.error('Error fetching house details:', error);
            alert("Une erreur s'est produite lors de la récupération des détails de la maison");
        });
}

// Caution modal function
function cautionModal_fun(id) {
    axios.get(`${API_BASE_URL}house/${id}/retrieve`)
        .then((response) => {
            const house = response.data;
            
            $("#caution_house_fullname").html(house.name);
            $("#caution_form")
                .attr("action", `/house/${id}/generate_cautions_for_house_by_period`)
                .attr("method", "POST");
        })
        .catch((error) => {
            console.error('Error fetching house details:', error);
            alert("Une erreur s'est produite lors de la récupération des détails de la maison");
        });
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}); 