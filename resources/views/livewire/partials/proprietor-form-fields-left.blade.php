<div class="mb-3">
    <label class="form-label">Prénom</label>
    <input type="text" name="firstname" value="{{ old('firstname') }}" class="form-control" required>
    @error('firstname')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nom</label>
    <input type="text" name="lastname" value="{{ old('lastname') }}" class="form-control" required>
    @error('lastname')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Téléphone</label>
    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" required>
    @error('phone')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ old('email') }}" class="form-control" >
    @error('email')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Sexe</label>
    <select class="form-select form-control agency-modal-select2" name="sexe" required>
        <option value="">Sélectionnez le sexe</option>
        <option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
        <option value="Feminin" {{ old('sexe') == 'Feminin' ? 'selected' : '' }}>Feminin</option>
    </select>
    @error('sexe')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Numéro de pièce d'identité</label>
    <input type="text" name="piece_number" value="{{ old('piece_number') }}" class="form-control">
    @error('piece_number')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Télécharger la pièce d'identité</label>
    <input type="file" name="piece_file" class="form-control" >
    @error('piece_file')
    <span class="text-red">{{ $message }}</span>
    @enderror
</div> 