<h2><?= (gdpr('options')->get('gdpr_delete_text') != '') ? gdpr('options')->get('gdpr_delete_text') : __('Delete my user and data', 'gdpr-framework') ?></h2>
<br/>
<p class="description">
    <?= __('Delete all data we have gathered about you.', 'gdpr-framework') ?> <br/>
    <?= __('If you have a user account on our site, it will also be deleted.', 'gdpr-framework') ?> <br/>
    <?= __('Be careful - this action is permanent and CANNOT be undone.', 'gdpr-framework') ?>
</p>
<br/>
<div class="gdpr-delete-button">
<?php add_thickbox(); ?>

<a href="#TB_inline?width=600&height=239&inlineId=gdprmodal-window-id" class="thickbox button button-primary"><?= __('Delete my data', 'gdpr-framework') ?></a>

<div id="gdprmodal-window-id" style="display:none;">
    <center><form method="GET">
        <p class="description">
            <?= __('Delete all data we have gathered about you.', 'gdpr-framework') ?> <br/>
            <?= __('If you have a user account on our site, it will also be deleted.', 'gdpr-framework') ?> <br/>
            <?= __('Be careful - this action is permanent and CANNOT be undone.', 'gdpr-framework') ?>
        </p>
            <input type="hidden" name="gdpr_nonce" value="<?= $nonce; ?>"/>
            <input type="hidden" name="gdpr_action" value="<?= $action; ?>"/>
            <input type="submit" class="button button-primary" value="<?= __('Delete my data', 'gdpr-framework') ?>"/>
    </form>
    <center>
</div>

   
</div>


<hr>
