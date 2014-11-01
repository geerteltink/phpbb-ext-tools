<?php
/**
 * @package phpBB Extension - Tools
 * @copyright (c) Geert Eltink
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace xtreamwayz\tools\acp;

class tools_module
{
    public $u_action;

    private $controller;

    public function main($id, $mode)
    {
        global $config, $phpbb_container, $request, $template, $user;

        $user->add_lang_ext('xtreamwayz/tools', 'tools_acp');
        $this->tpl_name = 'acp_tools_main';
        $this->page_title = $user->lang('ACP_BOARDTOOLS_TITLE');
        add_form_key('xtreamwayz/tools');

        // Request
        $action = $request->variable('action', '');

        if ($request->is_set_post('submit') && $request->is_set_post('action')) {
            if (!check_form_key('xtreamwayz/tools')) {
                trigger_error('FORM_INVALID', E_USER_WARNING);
            }

            switch($action) {
                case 'reset_signatures':
                    $service = $phpbb_container->get('xtreamwayz.tools.user.service');
                    $result = $service->reset_signatures();
                    if ($result === false) {
                        trigger_error('Failed to reset signatures.' . adm_back_link($this->u_action), E_USER_WARNING);
                    }

                    trigger_error('Reset ' . $result . ' signatures.' . adm_back_link($this->u_action));

                    break;

                case 'sync_gravatars':
                    $service = $phpbb_container->get('xtreamwayz.tools.gravatar.service');
                    $result = $service->check_gravatars();
                    if ($result === false) {
                        trigger_error('Failed to sync gravatars.' . adm_back_link($this->u_action), E_USER_WARNING);
                    }

                    trigger_error('Synced ' . $result . ' gravatars.' . adm_back_link($this->u_action));

                    break;

                default:
                    trigger_error('Invalid form action.');
                    break;
            }
        }

        $template->assign_vars(array(
            'u_action'          => $this->u_action,
            'extension_version' => $config['xtreamwayz_tools_version'],
        ));
    }
}
