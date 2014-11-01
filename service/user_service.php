<?php
/**
 * @package phpBB Extension - Tools
 * @copyright (c) Geert Eltink
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace xtreamwayz\tools\service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \phpbb\config\config;
use \phpbb\db\driver\driver_interface;
use \phpbb\request\request;
use \phpbb\template\template;

/**
 * User Service Class
 */
class user_service
{
    /** @var \phpbb\config\config */
    protected $config;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\request\request */
    protected $request;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var ContainerInterface */
    protected $container;

    /**
     * @param driver_interface $db
     */
    public function __construct(config $config, driver_interface $db, request $request, template $template, ContainerInterface $container)
    {
        $this->config = $config;
        $this->db = $db;
        $this->request = $request;
        $this->template = $template;
        $this->container = $container;
    }

    public function reset_signatures()
    {
        // Construct the query
        $search_ary = array(
            'SELECT'    => 'u.user_id, u.user_type, u.user_sig, u.user_sig_bbcode_uid, u.user_sig_bbcode_bitfield',
            'FROM'      => array(
                USERS_TABLE => 'u',
            ),
            'WHERE'     => 'u.user_type <> 2'
        );

        // Build query
        $query = $this->db->sql_build_query('SELECT', $search_ary);
        // Execute query
        $result = $this->db->sql_query($query);

        $i = 0;
        while($user = $this->db->sql_fetchrow($result)) {
            $data = array(
                'user_sig' => '',
                'user_sig_bbcode_uid' => '',
                'user_sig_bbcode_bitfield' => ''
            );

            // Reset signature
            $sql = 'UPDATE ' . USERS_TABLE . '
                    SET ' . $this->db->sql_build_array('UPDATE', $data) . '
                    WHERE user_id = ' . $user['user_id'];
            $this->db->sql_query($sql);

            $i++;
        }

        return $i;
    }
}
