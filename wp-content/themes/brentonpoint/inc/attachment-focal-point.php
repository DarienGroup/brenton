<?php
/**
 * Per-attachment focal point.
 *
 * Adds a "Focal point" picker to the WordPress attachment edit screen — both
 * the full editor (`upload.php?item=ID`) and the media-modal that opens when
 * editing an image from inside an ACF Gallery field. The editor clicks on the
 * image preview to set the focal point; the X / Y percentages are stored on
 * the attachment as post meta.
 *
 * Templates that crop the image (e.g. the square slider on the single team
 * profile) read these values via brentonpoint_attachment_focal_point() and
 * apply them as `object-position: X% Y%` so the right part of the image stays
 * in view regardless of crop ratio. Cover/poster images that don't crop can
 * ignore the value — it has no effect when `object-fit: contain` is used.
 */
defined('ABSPATH') || exit;

const BRENTONPOINT_FOCAL_META_X = '_bp_focal_x';
const BRENTONPOINT_FOCAL_META_Y = '_bp_focal_y';

/**
 * Read the focal point for an attachment. Defaults to 50/50 (center).
 *
 * @return array{x:int,y:int}
 */
function brentonpoint_attachment_focal_point(int $attachment_id): array {
    $x = get_post_meta($attachment_id, BRENTONPOINT_FOCAL_META_X, true);
    $y = get_post_meta($attachment_id, BRENTONPOINT_FOCAL_META_Y, true);

    $x = is_numeric($x) ? (int) round((float) $x) : 50;
    $y = is_numeric($y) ? (int) round((float) $y) : 50;

    return [
        'x' => max(0, min(100, $x)),
        'y' => max(0, min(100, $y)),
    ];
}

// ── Admin: render the picker in the attachment editor ───────────────────
add_filter('attachment_fields_to_edit', function ($form_fields, $post) {
    if (!isset($post->post_mime_type) || strpos((string) $post->post_mime_type, 'image/') !== 0) {
        return $form_fields;
    }

    $focal = brentonpoint_attachment_focal_point((int) $post->ID);
    $thumb = wp_get_attachment_image_src((int) $post->ID, 'medium_large');
    $src   = $thumb ? $thumb[0] : wp_get_attachment_url((int) $post->ID);

    ob_start();
    ?>
    <div
        class="bp-focal-picker"
        data-bp-focal-picker
        data-id="<?php echo esc_attr((string) $post->ID); ?>"
    >
        <div class="bp-focal-picker__stage" data-bp-focal-stage>
            <img class="bp-focal-picker__image" data-bp-focal-image src="<?php echo esc_url($src); ?>" alt="">
            <div class="bp-focal-picker__square" data-bp-focal-square hidden></div>
        </div>
        <p class="bp-focal-picker__help description">
            <?php esc_html_e('Drag the square to pick the area of the image that stays visible when cropped (e.g. the team grid and single profile gallery). For already-square images the box fills the whole image.', 'brentonpoint'); ?>
        </p>
        <p class="bp-focal-picker__values">
            <strong><?php esc_html_e('Focal point:', 'brentonpoint'); ?></strong>
            <span data-bp-focal-readout><?php echo esc_html(sprintf('%d%% × %d%%', $focal['x'], $focal['y'])); ?></span>
        </p>
        <input
            type="hidden"
            name="attachments[<?php echo esc_attr((string) $post->ID); ?>][bp_focal_x]"
            value="<?php echo esc_attr((string) $focal['x']); ?>"
            data-bp-focal-input-x
        >
        <input
            type="hidden"
            name="attachments[<?php echo esc_attr((string) $post->ID); ?>][bp_focal_y]"
            value="<?php echo esc_attr((string) $focal['y']); ?>"
            data-bp-focal-input-y
        >
    </div>
    <?php
    $html = ob_get_clean();

    $form_fields['bp_focal_point'] = [
        'label'         => __('Focal point', 'brentonpoint'),
        'input'         => 'html',
        'html'          => $html,
        'show_in_edit'  => true,
        'show_in_modal' => true,
    ];

    return $form_fields;
}, 10, 2);

// ── Admin: persist the picker values when the attachment form is saved ──
add_filter('attachment_fields_to_save', function ($post, $attachment) {
    if (!is_array($attachment) || empty($post['ID'])) {
        return $post;
    }

    $id = (int) $post['ID'];
    $write = static function (string $key, string $meta) use ($id, $attachment): void {
        if (!array_key_exists($key, $attachment)) {
            return;
        }
        $v = $attachment[$key];
        if ($v === '' || !is_numeric($v)) {
            delete_post_meta($id, $meta);
            return;
        }
        $clamped = max(0, min(100, (int) round((float) $v)));
        update_post_meta($id, $meta, $clamped);
    };

    $write('bp_focal_x', BRENTONPOINT_FOCAL_META_X);
    $write('bp_focal_y', BRENTONPOINT_FOCAL_META_Y);

    return $post;
}, 10, 2);

// ── Admin: inline CSS + JS for the picker ───────────────────────────────
add_action('admin_print_footer_scripts', function () {
    ?>
    <style id="bp-focal-picker-css">
        .bp-focal-picker { margin-top: 6px; }
        .bp-focal-picker__stage {
            position: relative;
            display: inline-block;
            max-width: 100%;
            line-height: 0;
            background: #f0f0f1;
            border: 1px solid #c3c4c7;
            overflow: hidden;
            user-select: none;
            touch-action: none;
        }
        .bp-focal-picker__image {
            display: block;
            max-width: 100%;
            height: auto;
            pointer-events: none;
        }
        .bp-focal-picker__square {
            position: absolute;
            box-sizing: border-box;
            border: 2px solid #28b4e2;
            background: rgba(40, 180, 226, 0.08);
            /* Dim everything outside the square (the un-cropped area). */
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.45);
            cursor: move;
        }
        .bp-focal-picker__square[hidden] { display: none; }
        .bp-focal-picker__square::before,
        .bp-focal-picker__square::after {
            /* Rule-of-thirds guides — light visual aid for composition. */
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            pointer-events: none;
        }
        .bp-focal-picker__square::before {
            left: 33.333%;
            right: 33.333%;
            top: 0;
            bottom: 0;
            border-left: 1px solid rgba(255, 255, 255, 0.4);
            border-right: 1px solid rgba(255, 255, 255, 0.4);
            background: transparent;
        }
        .bp-focal-picker__square::after {
            top: 33.333%;
            bottom: 33.333%;
            left: 0;
            right: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            background: transparent;
        }
        .bp-focal-picker__help { margin-top: 6px; }
        .bp-focal-picker__values { margin-top: 4px; font-size: 12px; }
    </style>
    <script id="bp-focal-picker-js">
    (function () {
        var DOC = document;

        function pickerState(picker) {
            return {
                stage:   picker.querySelector('[data-bp-focal-stage]'),
                image:   picker.querySelector('[data-bp-focal-image]'),
                square:  picker.querySelector('[data-bp-focal-square]'),
                inputX:  picker.querySelector('[data-bp-focal-input-x]'),
                inputY:  picker.querySelector('[data-bp-focal-input-y]'),
                readout: picker.querySelector('[data-bp-focal-readout]'),
            };
        }

        /**
         * Lay out the square overlay inside the displayed image at the
         * current stored focal point. Re-runs on load + resize because the
         * displayed image size depends on the surrounding container.
         */
        function layout(picker) {
            var s = pickerState(picker);
            if (!s.image || !s.square || !s.stage) return;
            var w = s.image.clientWidth;
            var h = s.image.clientHeight;
            if (w === 0 || h === 0) return;

            var size = Math.min(w, h);
            var maxLeft = Math.max(0, w - size);
            var maxTop  = Math.max(0, h - size);

            var fx = parseFloat(s.inputX && s.inputX.value);
            var fy = parseFloat(s.inputY && s.inputY.value);
            if (isNaN(fx)) fx = 50;
            if (isNaN(fy)) fy = 50;

            var left = (maxLeft * fx) / 100;
            var top  = (maxTop  * fy) / 100;

            s.square.style.width  = size + 'px';
            s.square.style.height = size + 'px';
            s.square.style.left   = left + 'px';
            s.square.style.top    = top  + 'px';
            s.square.hidden = false;
        }

        function persist(picker, fx, fy) {
            var s = pickerState(picker);
            var xi = Math.max(0, Math.min(100, Math.round(fx)));
            var yi = Math.max(0, Math.min(100, Math.round(fy)));
            if (s.inputX) s.inputX.value = xi;
            if (s.inputY) s.inputY.value = yi;
            if (s.readout) s.readout.textContent = xi + '% × ' + yi + '%';
            if (s.inputX) s.inputX.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function moveTo(picker, clientX, clientY, anchorOffsetX, anchorOffsetY) {
            var s = pickerState(picker);
            if (!s.image || !s.square) return;
            var imgRect = s.image.getBoundingClientRect();
            var w = imgRect.width, h = imgRect.height;
            if (w === 0 || h === 0) return;

            var size = Math.min(w, h);
            var maxLeft = Math.max(0, w - size);
            var maxTop  = Math.max(0, h - size);

            // The pointer was offset within the square by (anchorOffsetX, anchorOffsetY)
            // when the drag started; preserve that offset so the square doesn't jump.
            var left = clientX - imgRect.left - anchorOffsetX;
            var top  = clientY - imgRect.top  - anchorOffsetY;

            left = Math.max(0, Math.min(maxLeft, left));
            top  = Math.max(0, Math.min(maxTop,  top));

            s.square.style.left = left + 'px';
            s.square.style.top  = top  + 'px';

            var fx = maxLeft > 0 ? (left / maxLeft) * 100 : 50;
            var fy = maxTop  > 0 ? (top  / maxTop)  * 100 : 50;
            persist(picker, fx, fy);
        }

        function startDrag(picker, clientX, clientY, fromSquare) {
            var s = pickerState(picker);
            if (!s.image || !s.square) return null;
            var imgRect = s.image.getBoundingClientRect();
            var sqRect  = s.square.getBoundingClientRect();
            var size    = sqRect.width;

            var anchorOffsetX, anchorOffsetY;
            if (fromSquare) {
                anchorOffsetX = clientX - sqRect.left;
                anchorOffsetY = clientY - sqRect.top;
            } else {
                // Clicked on the image (not the square) → center the square
                // on the click for that one move, then track from there.
                anchorOffsetX = size / 2;
                anchorOffsetY = size / 2;
                moveTo(picker, clientX, clientY, anchorOffsetX, anchorOffsetY);
            }

            return { anchorOffsetX: anchorOffsetX, anchorOffsetY: anchorOffsetY };
        }

        DOC.addEventListener('mousedown', function (e) {
            var stage = e.target && e.target.closest && e.target.closest('[data-bp-focal-stage]');
            if (!stage) return;
            var picker = stage.closest('[data-bp-focal-picker]');
            if (!picker) return;
            e.preventDefault();

            var fromSquare = !!(e.target.closest && e.target.closest('[data-bp-focal-square]'));
            var ctx = startDrag(picker, e.clientX, e.clientY, fromSquare);
            if (!ctx) return;

            function onMove(ev) {
                moveTo(picker, ev.clientX, ev.clientY, ctx.anchorOffsetX, ctx.anchorOffsetY);
            }
            function onUp() {
                DOC.removeEventListener('mousemove', onMove);
                DOC.removeEventListener('mouseup', onUp);
            }
            DOC.addEventListener('mousemove', onMove);
            DOC.addEventListener('mouseup', onUp);
        });

        DOC.addEventListener('touchstart', function (e) {
            if (!e.touches || e.touches.length === 0) return;
            var stage = e.target && e.target.closest && e.target.closest('[data-bp-focal-stage]');
            if (!stage) return;
            var picker = stage.closest('[data-bp-focal-picker]');
            if (!picker) return;
            var t = e.touches[0];
            var fromSquare = !!(e.target.closest && e.target.closest('[data-bp-focal-square]'));
            var ctx = startDrag(picker, t.clientX, t.clientY, fromSquare);
            picker.__bpFocalCtx = ctx;
        }, { passive: true });

        DOC.addEventListener('touchmove', function (e) {
            if (!e.touches || e.touches.length === 0) return;
            var stage = e.target && e.target.closest && e.target.closest('[data-bp-focal-stage]');
            if (!stage) return;
            var picker = stage.closest('[data-bp-focal-picker]');
            if (!picker || !picker.__bpFocalCtx) return;
            var t = e.touches[0];
            moveTo(picker, t.clientX, t.clientY, picker.__bpFocalCtx.anchorOffsetX, picker.__bpFocalCtx.anchorOffsetY);
        }, { passive: true });

        // ── Lay out each picker once its image has dimensions ─────────────
        function bindPicker(picker) {
            if (picker.__bpFocalBound) return;
            picker.__bpFocalBound = true;
            var img = picker.querySelector('[data-bp-focal-image]');
            if (!img) return;
            if (img.complete && img.naturalWidth) {
                layout(picker);
            } else {
                img.addEventListener('load', function () { layout(picker); }, { once: true });
            }
        }

        function scan(root) {
            (root || DOC).querySelectorAll('[data-bp-focal-picker]').forEach(bindPicker);
        }

        scan(DOC);

        // Pickers in the media modal mount asynchronously — watch the DOM
        // and bind any newly inserted picker. Also relayout on window resize.
        var mo = new MutationObserver(function (records) {
            records.forEach(function (rec) {
                rec.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    if (node.matches && node.matches('[data-bp-focal-picker]')) bindPicker(node);
                    if (node.querySelectorAll) scan(node);
                });
            });
        });
        mo.observe(DOC.body, { childList: true, subtree: true });

        var resizeTimer = null;
        window.addEventListener('resize', function () {
            if (resizeTimer) cancelAnimationFrame(resizeTimer);
            resizeTimer = requestAnimationFrame(function () {
                DOC.querySelectorAll('[data-bp-focal-picker]').forEach(layout);
            });
        });
    })();
    </script>
    <?php
});
