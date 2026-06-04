<?php
/** @var App\Models\Address|null $address */
?>

<div class="address-form">
    <div class="form-row">
        <div class="form-group form-group--full">
            <label for="address_street">Rua / Avenida</label>
            <input type="text" id="address_street" name="address_street" value="<?= htmlspecialchars($address->street ?? '') ?>">
        </div>
    </div>
    <div class="form-row form-row--2">
        <div class="form-group">
            <label for="address_number">Número</label>
            <input type="text" id="address_number" name="address_number" value="<?= htmlspecialchars($address->number ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="address_complement">Complemento</label>
            <input type="text" id="address_complement" name="address_complement" value="<?= htmlspecialchars($address->complement ?? '') ?>" placeholder="Sala 101, Bloco B">
        </div>
    </div>
    <div class="form-row form-row--2">
        <div class="form-group">
            <label for="address_neighborhood">Bairro</label>
            <input type="text" id="address_neighborhood" name="address_neighborhood" value="<?= htmlspecialchars($address->neighborhood ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="address_city">Cidade</label>
            <input type="text" id="address_city" name="address_city" value="<?= htmlspecialchars($address->city ?? '') ?>">
        </div>
    </div>
    <div class="form-row form-row--3">
        <div class="form-group">
            <label for="address_state">Estado</label>
            <input type="text" id="address_state" name="address_state" value="<?= htmlspecialchars($address->state ?? '') ?>" placeholder="SP">
        </div>
        <div class="form-group">
            <label for="address_zip">CEP</label>
            <input type="text" id="address_zip" name="address_zip" value="<?= htmlspecialchars($address->zip ?? '') ?>" placeholder="00000-000">
        </div>
        <div class="form-group">
            <label for="address_country">País</label>
            <input type="text" id="address_country" name="address_country" value="<?= htmlspecialchars($address->country ?? 'Brasil') ?>">
        </div>
    </div>
    <div class="form-row form-row--2">
        <div class="form-group">
            <label for="address_lat">Latitude <span class="label-hint">(opcional)</span></label>
            <input type="text" id="address_lat" name="address_lat" value="<?= htmlspecialchars($address->lat ?? '') ?>" placeholder="-23.5505">
        </div>
        <div class="form-group">
            <label for="address_lng">Longitude <span class="label-hint">(opcional)</span></label>
            <input type="text" id="address_lng" name="address_lng" value="<?= htmlspecialchars($address->lng ?? '') ?>" placeholder="-46.6333">
        </div>
    </div>
    <p class="help-text">O endereço é usado para gerar o schema LocalBusiness e o mapa no Google.</p>
</div>
