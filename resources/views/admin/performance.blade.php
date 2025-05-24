<x-templates.agency :title="'Taux de performance'" :active="'recovery'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Taux de <em class="text-red"> performance </em> </h1>
    </div>
    <br>

    <livewire:performance :agency=$agency />

</x-templates.agency>