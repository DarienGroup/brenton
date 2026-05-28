<?php
/**
 * Shared "Thank you" dialog opened after any Gravity Form on the page submits
 * successfully. Rendered once in the footer; opened by `parts/form-success.js`
 * on the `gform_confirmation_loaded` event.
 */
defined('ABSPATH') || exit;

$heading = __('Thank you!', 'brentonpoint');
$message = __("We've received your request and will get back to you soon.", 'brentonpoint');
$cta     = __('Got It', 'brentonpoint');
?>
<dialog class="form-success-popup" id="form-success-popup" aria-labelledby="form-success-popup-title">
    <div class="form-success-popup__panel">

        <button type="button" class="form-success-popup__close" aria-label="<?php esc_attr_e('Close', 'brentonpoint'); ?>" data-modal-close>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
            </svg>
        </button>

        <div class="form-success-popup__icon" aria-hidden="true">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                <path d="M8 18.5l6.5 6.5L28 11.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <h2 id="form-success-popup-title" class="form-success-popup__title text-h2 text-weight-700 text-color-black">
            <?php echo esc_html($heading); ?>
        </h2>

        <p class="form-success-popup__message text-body-L text-color-black">
            <?php echo esc_html($message); ?>
        </p>

        <div class="form-success-popup__cta">
            <button type="button" class="form-success-popup__button" data-modal-close>
                <?php echo esc_html($cta); ?>
            </button>
        </div>

    </div>
</dialog>
