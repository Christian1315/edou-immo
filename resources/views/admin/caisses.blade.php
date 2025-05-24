<x-templates.agency :title="'Caisses'" :active="'caisse'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gestion des caisses</h1>
    </div>
    <br>

    <livewire:caisses :agency="$agency" />
</x-templates.agency>