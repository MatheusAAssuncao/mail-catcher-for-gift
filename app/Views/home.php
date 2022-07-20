<?= $this->extend('templates/main-template') ?>
<?= $this->section('content') ?>

<div class="row mt-4">
    <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
        <h2 class="title">OFERTA DE 1 COPO DE VINHO EA</h2>
        <p>Participa neste passatempo para receberes um copo de vinho EA e aproveita ao máximo a vibe do EALIVE Évora!</p>
        <h3><strong>Como participar?</strong></h3>
        <p>1. Preenche o formulário abaixo com os teus dados;</p>
        <p>2. Vais receber <strong>um código exclusivo</strong> numa SMS que será enviada para o teu telemóvel;</p>
        <p>3. Apresenta <strong>o teu código exclusivo</strong> às promotoras que se encontram junto ao <i>photowall</i>;</p>
        <p>4. Tira uma fotografia no <i>photowall</i> EALIVE e publica no teu Instagram, identificando a <a href="https://www.instagram.com/adegacartuxa/" target="_blank" style="text-decoration:none">@adegacartuxa</a>, e receberás o teu cartão com 1 copo de vinho EA grátis.</p>
    </div>
</div>
    <?= form_open('submit') ?>
        <div class="form-outline mb-4">
            <label class="form-label" for="name">Nome</label>
            <input type="text" class="form-control" name="name" value="<?= old('name', '') ?>"/>
            <?= helperShowErrorIfExists('name', $errors, true) ?>
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="email">E-mail</label>
            <input type="text" class="form-control" name="email" value="<?= old('email', '') ?>"/>
            <?= helperShowErrorIfExists('email', $errors, true) ?>
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="phone">Telemóvel <span class="txt-obs"><br/>(para envio de SMS com o teu código exclusivo)</span></label>
            <input type="text" class="form-control" name="phone" maxlength="9" value="<?= old('phone', '') ?>"/>
            <?= helperShowErrorIfExists('phone', $errors, true) ?>
        </div>

        <div class="form-inline mb-4">
            <input type="checkbox" class="form-check-input" name="term" value="1"/>
            &nbsp;<label for="term" class="txt-obs">Li e aceito a&nbsp;<a href="<?= base_url('/term') ?>" target="_blank">Política de Privacidade e de Tratamento de Dados</a>.</label>
            <?= helperShowErrorIfExists('term', $errors, true) ?>
        </div>

        <button type="submit" class="btn btn-light btn-block mb-4">Enviar SMS</button>
    <?= form_close() ?>

<?= $this->endSection() ?>