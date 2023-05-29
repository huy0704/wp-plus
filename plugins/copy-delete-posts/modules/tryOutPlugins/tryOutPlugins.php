<?php

  /**
   * File for our cool review ask in the header
   *
   * @category Child Plugin
   * @version v0.1.0
   * @since v0.1.0
   * @author iClyde <kontakt@iclyde.pl>
   */

  // Namespace
  namespace Inisev\Subs;

  // Disallow direct access
  if (defined('ABSPATH')) {

    /**
     * Main class for handling the Review
     */
    if (!class_exists('Inisev\Subs\Inisev_Try_Out_Plugins')) {
      class Inisev_Try_Out_Plugins {

        function __construct($plugin_file, $plugin_dir, $plugin_name, $plugin_menu_page) {

          if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            add_action('wp_ajax_tifm_notice_actions', [&$this, 'noticeAjax']);
          }

          global $pagenow;
          if (!($pagenow == 'plugin-install.php' || $pagenow == 'admin-ajax.php')) return;
          if (!is_admin() || !current_user_can('install_plugins')) return;
          if (get_option('_tifm_disable_feature_forever', false) != false) return;

          $this->pluginDir = $plugin_dir;
          $this->pluginFile = $plugin_file;
          $this->pluginName = $plugin_name;
          $this->pluginPageURL = admin_url($plugin_menu_page) . '&scrollToSection=testPlugins';

          $this->showInformativeNotice();
          $this->insertActionButton();

        }

        public function showInformativeNotice() {

          add_action('in_admin_footer', [&$this, 'tryItOutScript']);

          if (get_option('_tifm_hide_notice_forever', false) != false) return;

          add_action('admin_notices', [&$this, 'informativeAdminNoticeHandler']);
          add_action('admin_head', [&$this, 'noticeStyles']);
          add_action('in_admin_footer', [&$this, 'noticeScripts']);

        }

        public function noticeStyles() {
          ?>

          <style media="screen">
            #tifm_paragraph_notice {
              display: flex;
              flex-direction: row;
              justify-content: space-between;
            }

            #tifm_paragraph_notice span {
              line-height: 30px;
            }

            #tifm_paragraph_notice .tifm_darker_a {
              color: #555;
            }

            #tifm_paragraph_notice .tifm_darker_a_muted {
              color: #999;
            }

            #tifm_paragraph_notice .tifm_brought_url {
              color: #00a32a;
              text-decoration: none;
            }

            #tifm_paragraph_notice .tifm-grow-1 {
              flex-grow: 1;
            }

            #tifm_paragraph_notice .tifm-grow-5 {
              flex-grow: 5;
            }

            @media screen and (max-width: 1400px) {
              #tifm_paragraph_notice {
                flex-direction: column;
              }
            }
          </style>

          <?php
        }

        public function noticeScripts() {
          ?>
          <script type="text/javascript">
            jQuery(document).ready(function($) {

              $('#tifm_new_feature_notice').on('click', '.notice-dismiss', hideAndDismissNotice);

              $('#tifm_new_feature_notice').on('click', '.tifm_darker_a', hideAndDismissNotice);

              $('#tifm_new_feature_notice').on('click', '.tifm_darker_a_muted', disableFeatureAndDismiss);

              let nonce = "<?php echo wp_create_nonce('tifm_notice_nonce') ?>";

              function hideAndDismissNotice(e) {

                let dismiss = false;
                if (typeof e != 'string') {
                  e.preventDefault();
                } else if (e = 'dismiss') {
                  dismiss = true;
                }

                $('#tifm_new_feature_notice').hide(300);
                setTimeout(function () {
                  $('#tifm_new_feature_notice').remove();
                }, 350);

                let method = 'dismiss_notice';
                if (dismiss == true) {
                  method = 'dismiss_notice_and_disable';
                }

                $.post(ajaxurl, { action: 'tifm_notice_actions', nonce: nonce, method: method }).done(function () {
                  if (method == 'dismiss_notice_and_disable') {
                    window.location.reload();
                  }
                }).fail(function (e) {
                  alert('Error occurred, please refresh and try again.' + JSON.stringify(e));
                });

              }

              function disableFeatureAndDismiss(e) {

                e.preventDefault();
                hideAndDismissNotice('dismiss');

              }

            });
          </script>
          <?php
        }

        public function tryItOutScript() {
          ?>
          <script type="text/javascript">
            jQuery(document).ready(function($) {
              function makeButton(slug) {

                let a = document.createElement('A');
                    a.classList.add('button');
                    a.classList.add('button-primary');
                    a.classList.add('tifm-btn-iframe');
                    a.classList.add('right');
                    a.style.color = '#fff';
                    a.style.background = '#2d9418';
                    a.style.borderColor = '#2d9418';
                    a.style.boxShadow = 'none';
                    a.style.marginRight = '15px';
                    a.setAttribute('href', 'https://tastewp.com/plugins/' + slug);
                    a.setAttribute('target', '_blank');
                    a.innerText = 'Try it first';

                return a;

              }

              const observer = new MutationObserver(function (mutations_list) {
                mutations_list.forEach(function (mutation) {
                  mutation.addedNodes.forEach(function (added_node) {
                    if (added_node.id == 'TB_window') {
                      $('#TB_window #TB_iframeContent')[0].onload = function () {
                        let iframe = $('#TB_iframeContent').contents();
                        let footer = iframe.find('#plugin-information-footer');
                        let slug = footer.find('#plugin_install_from_iframe').data('slug');
                        let btn = makeButton(slug);
                        footer.append(btn);
                      }
                    }
                  });
                });
              });

              observer.observe(document.querySelector('body'), { subtree: false, childList: true });
            });
          </script>
          <?php
        }

        public function informativeAdminNoticeHandler() {

          ?>

          <div class="notice notice-success is-dismissible" id="tifm_new_feature_notice">
            <p id="tifm_paragraph_notice">
              <span class="tifm-grow-1">
                <b>New: </b> Click on the&nbsp;
                <a class="button" style="color:#2d9418;border-color:#2d9418;text-align:center;width:88px" href="#!">Try it first</a>
                &nbsp;button to first test a plugin on a new WP instance.
              </span>

              <span class="tifm-grow-5">
                <a class="tifm_darker_a" href="#">Got it, close this notice</a>
              </span>

              <span class="tifm-grow-1">
                <a class="tifm_darker_a_muted" href="#">Disable this feature</a>
              </span>

              <span>
                Brought to you by <a class="tifm_brought_url" href="<?php echo esc_html($this->pluginPageURL); ?>"><?php echo esc_html($this->pluginName); ?></a>
              </span>
            </p>
          </div>

          <?php

        }

        public function insertActionButton() {

          add_filter('plugin_install_action_links', [&$this, 'actionButtonHandler'], 10, 2);

        }

        public static function noticeAjax() {

          // Nonce verification
          if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'tifm_notice_nonce')) {
            wp_send_json_error();
            return;
          }

          $method = '';
          if (isset($_POST['method'])) {

            $method = sanitize_text_field($_POST['method']);

          }

          if ($method == 'dismiss_notice') {

            update_option('_tifm_hide_notice_forever', true);
            wp_send_json_success();
            exit;

          } else if ($method == 'dismiss_notice_and_disable') {

            update_option('_tifm_hide_notice_forever', true);
            update_option('_tifm_disable_feature_forever', true);
            update_option('_tifm_feature_enabled', 'disabled');
            wp_send_json_success();
            exit;

          } else {

            wp_send_json_error();
            exit;

          }

        }

        public function actionButtonHandler($links, $plugin) {

          $url = 'https://tastewp.com/plugins/' . $plugin['slug'] . '/?anchor=wpsite';
          $button = ['<a class="button" style="color:#2d9418;border-color:#2d9418;text-align:center;width:88px" target="_blank" href="' . $url . '">Try it first</a>'];
          array_splice($links, 1, 0, $button);

          return $links;

        }

      }
    }

  }
