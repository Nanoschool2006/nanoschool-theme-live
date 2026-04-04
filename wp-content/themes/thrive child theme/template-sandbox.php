// Shortcode: [island] ... your HTML/CSS/JS ... [/island]
add_shortcode('island', function($atts, $content = null) {
    if (is_null($content)) $content = '';

    // unique id so multiple islands can exist on one page
    $uid = 'island-' . wp_generate_uuid4();

    // we DO NOT run the_content filters on $content; we output it as given
    // still obeys KSES (sanitization) unless user has unfiltered_html
    ob_start(); ?>
    <div id="<?php echo esc_attr($uid); ?>"></div>
    <template id="<?php echo esc_attr($uid); ?>-tpl">
      <?php
      // IMPORTANT: do not escape here so your HTML/CSS/JS stays raw
      // (WP will still enforce capability-based sanitization)
      echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      ?>
    </template>
    <script>
      (function(){
        var mount = document.getElementById('<?php echo esc_js($uid); ?>');
        if(!mount || mount.shadowRoot) return;
        var shadow = mount.attachShadow({mode:'open'});

        // move template html into shadow
        var tpl = document.getElementById('<?php echo esc_js($uid); ?>-tpl');
        if(!tpl) return;
        var frag = tpl.content ? tpl.content.cloneNode(true) : (function(){
          var range = document.createRange();
          range.selectNodeContents(tpl);
          return range.extractContents();
        })();
        shadow.appendChild(frag);

        // re-execute any <script> inside the shadow
        shadow.querySelectorAll('script').forEach(function(old){
          var s = document.createElement('script');
          for (var i=0;i<old.attributes.length;i++){
            var a = old.attributes[i]; s.setAttribute(a.name, a.value);
          }
          if (old.textContent) s.textContent = old.textContent;
          old.replaceWith(s);
        });

        // optional tiny reset ONLY inside the shadow (won’t touch theme)
        var reset = document.createElement('style');
        reset.textContent = ':host, :host * { box-sizing: border-box; } :host { display:block; }';
        shadow.prepend(reset);
      })();
    </script>
    <?php
    return ob_get_clean();
});