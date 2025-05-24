<x-templates.agency :title="'Recouvrement 10'" :active="'recovery'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Taux de recouvrement au <em class="text-red"> 10 </em></h1>
    </div>
    <br>

    <livewire:recovery10 :agency=$agency />

</x-templates.agency>