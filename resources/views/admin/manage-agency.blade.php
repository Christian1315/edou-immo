<x-templates.agency :title="'Gérer une agence'" :active="'dashbord'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h5 class="h2">GESTION DE L'AGENCE <strong class="text-red">
                : {{$agency["name"]}}
            </strong> </h5>
    </div>
    
    <livewire:agency-dashbord :agency=$agency />

</x-templates.agency>