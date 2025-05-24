<x-templates.agency :title="'Electricité'" :active="'electricity'" :agency=$agency>
    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Electricité</h1>
    </div>
    <br>

    <livewire:electricity :agency="$agency" />
</x-templates.agency>