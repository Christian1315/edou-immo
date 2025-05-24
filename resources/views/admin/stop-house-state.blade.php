<x-templates.agency :title="'Arret des états'" :active="'stop_state'" :agency=$agency>
    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel d'arrêt des états de la Maison: <span class="text-red"> {{$house["name"]}}</span> </h1>
    </div>

    <livewire:house-stop-state :agency="$agency" :house="$house" />

</x-templates.agency>