<div class="mb-3">
    <label class="form-label">Télécharger le contrat de mandat</label>
    <input type="file" name="mandate_contrat" class="form-control" >
    @error('mandate_contrat')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Adresse</label>
    <input type="text" name="adresse" value="{{ old('adresse') }}" class="form-control" required>
    @error('adresse')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Pays</label>
    <select class="form-select form-control agency-modal-select2" name="country" required>
        <option value="">Sélectionnez le pays</option>
        @foreach($countries as $country)
            @if($country['id'] == 4)
                <option value="{{ $country['id'] }}" {{ old('country') == $country['id'] ? 'selected' : '' }}>
                    {{ $country['name'] }}
                </option>
            @endif
        @endforeach
    </select>
    @error('country')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Ville/Commune</label>
    <select class="form-select form-control agency-modal-select2" style="width: 100%!important;" name="city" required>
        <option value="">Sélectionnez la ville</option>
        @foreach($cities as $city)
            @if($city['_country']['id'] == 4)
                <option value="{{ $city['id'] }}" {{ old('city') == $city['id'] ? 'selected' : '' }}>
                    {{ $city['name'] }}
                </option>
            @endif
        @endforeach
    </select>
    @error('city')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Type de carte ID</label>
    <select class="form-select form-control agency-modal-select2" name="card_type" required>
        <option value="">Sélectionnez le type de carte</option>
        @foreach($card_types as $type)
            <option value="{{ $type['id'] }}" {{ old('card_type') == $type['id'] ? 'selected' : '' }}>
                {{ $type['name'] }}
            </option>
        @endforeach
    </select>
    @error('card_type')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Commentaire</label>
    <textarea name="comments" class="form-control" rows="3" placeholder="Laissez un commentaire ici">{{ old('comments') }}</textarea>
    @error('comments')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div> 