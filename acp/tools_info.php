<?php
/**
 * @package phpBB Extension - Tools
 * @copyright (c) Geert Eltink
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace xtreamwayz\tools\acp;

class tools_info
{
    public function module()
    {
        return array(
            'filename'  => '\xtreamwayz\tools\acp\tools_module',
            'title'     => 'ACP_BOARDTOOLS_TITLE',
            'version'   => '1.0.0',
            'modes'     => array(
                'tools'  => array(
                    'title' => 'ACP_BOARDTOOLS',
                    'auth' => 'ext_xtreamwayz/tools && acl_a_board',
                    'cat' => array('ACP_BOARDTOOLS_TITLE')
                ),
            ),
        );
    }
}
