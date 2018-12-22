<input
    type="checkbox"
    id="gdpr_enable_popup"
    name="gdpr_enable_popup"
    value="1"
    <?= checked($enabled, true); ?>
/>
<label for="gdpr_enable_popup">
    <?= _x('Enable Cookie Acceptance Popup', '(Admin)', 'gdpr-framework'); ?>
</label>
