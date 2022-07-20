<?= $this->extend('templates/main-template') ?>
<?= $this->section('content') ?>

    <?= form_open('validate/submit') ?>
        <div class="form-outline mb-4">
            <label class="form-label" for="code">Código</label>
            <input type="text" class="form-control" name="code" value="<?= old('code', '') ?>"
                style="text-transform: uppercase"/>
            <?= helperShowErrorIfExists('code', $errors, true) ?>
            <?= helperShowSuccessIfExists('code', $successMsg, true) ?>
        </div>

        <button type="submit" class="btn btn-light btn-block mb-4">Validar</button>
    <?= form_close() ?>

<?= $this->endSection() ?>