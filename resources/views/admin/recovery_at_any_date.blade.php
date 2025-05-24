<x-templates.agency :title="'Recouvrement à une date quelconque'" :active="'recovery'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Taux de recouvrement de l'agence</h1>
    </div>
    <br>

    <livewire:recovery-any-date :agency=$agency />

</x-templates.agency>