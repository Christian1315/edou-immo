<x-templates.agency :title="'Paiements'" :active="'paiement'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Paiements des propriétaires</h1>
    </div>
    <br>

    <livewire:paiement :agency="$agency" />
</x-templates.agency>