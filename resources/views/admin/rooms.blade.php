<x-templates.agency :title="'Chambres'" :active="'room'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des Chambres</h1>
    </div>
    <br>

    <livewire:room :agency="$agency" />

</x-templates.agency>