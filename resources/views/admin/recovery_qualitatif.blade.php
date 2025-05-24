<x-templates.agency :title="'Recouvrement qualitatif'" :active="'recovery'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Taux de recouvrement <em class="text-red"> qualitatif </em></h1>
    </div>
    <br>

    <livewire:recovery-qualitatif :agency=$agency />

</x-templates.agency>