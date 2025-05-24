<x-templates.agency :title="'Mouvements des caisses'" :active="'caisse'" :agency=$agency :agency_account=$agency_account>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mouvements des caisses</h1>
    </div>
    <br>

    <livewire:caisse-mouvements :agency="$agency" :agency_account="$agency_account" />
</x-templates.agency>