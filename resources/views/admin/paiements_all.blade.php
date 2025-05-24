<x-templates.base :title="'Paiements'" :active="'paiement'">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des paiements</h1>
    </div>
    <br>

    <livewire:paiement :agency="$agency" />
</x-templates.base>