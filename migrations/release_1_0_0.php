<?php
/**
 * @package phpBB Extension - Board Tools
 * @copyright (c) Geert Eltink
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace xtreamwayz\tools\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['xtreamwayz_tools_version']) && version_compare($this->config['xtreamwayz_tools_version'], '1.0.0', '==');
    }

    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v310\gold');
    }

    public function update_data()
    {
        return array(
            array('config.add', array('xtreamwayz_tools_version', '1.0.0')),

            array('module.add', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'ACP_BOARDTOOLS_TITLE'
            )),
            array('module.add', array(
                'acp',
                'ACP_BOARDTOOLS_TITLE',
                array(
                    'module_basename'   => '\xtreamwayz\tools\acp\tools_module',
                    'modes'             => array('tools'),
                ),
            )),
        );
    }
}
