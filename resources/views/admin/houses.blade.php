<x-templates.agency :title="'Maisons'" :active="'house'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des Maisons</h1>
    </div>
    <br>

    <livewire:house :agency="$agency"/>
</x-templates.agency>