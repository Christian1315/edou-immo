<div>

    <!-- Icons -->
    <!-- <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script> -->
    <script>
        feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.min.js"></script>
    <script>
        // GESTION DU SIDE BAR SUR MOBILE
        var moblie_sidebar = document.getElementById("sidebar_mobile");
        var close_sidebar = document.getElementById("close-moblie-sidebar");
        var open_moblie_sidebar = document.getElementById("open-moblie-sidebar");

        moblie_sidebar.style.display = "none";
        
        close_sidebar.addEventListener("click", function() {
            moblie_sidebar.style.display = "none"
        })

        open_moblie_sidebar.addEventListener("click", function() {
            moblie_sidebar.style.display = "block"
        })


        // var proprietors_count = <?php echo $proprietors_count; ?>
        // var houses_count = <?php echo $houses_count; ?>;
        // var locators_count = <?php echo $locators_count; ?>;
        // var locations_count = <?php echo $locations_count; ?>;
        // var rooms_count = <?php echo $rooms_count; ?>;
        // var paiement_count = <?php echo $paiement_count; ?>;
        // var factures_count = <?php echo $factures_count; ?>;
        // var accountSold_count = <?php echo $accountSold_count; ?>;
        // var initiation_count = <?php echo $initiation_count; ?>;

        // GRAPH
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Proprietaires", "Maisons", "Locataires", "Locations", "Chambres", "Paiements", "Locataires", "Factures", "Comptes", "Initiations de paiements", "Superviseurs", "Agents", "ARRET DES ETATS"],
                datasets: [{
                    data: [50, 2, 3, 100, 9, 30, 50, 63, 500, 64, 66, 100, 200],
                    lineTension: 0,
                    backgroundColor: '#000',
                    borderColor: '#cc3301',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff'
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });
    </script>
</div>