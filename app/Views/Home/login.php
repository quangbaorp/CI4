<?php if (session()->get('success')): ?>
          <div class="alert alert-success" role="alert">
            <?= session()->get('success') ?>
          </div>
<?php endif; ?>
<?php if (isset($validation)): ?>
<div class="col-12">
    <div class="alert alert-danger" role="alert">
        <?= $validation->listErrors() ?>
    </div>
    </div>
<?php endif; ?>