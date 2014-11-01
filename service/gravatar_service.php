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
 * Gravatar Service Class
 */
class gravatar_service
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

    public function check_gravatars()
    {
        // Construct the query
        $search_ary = array(
            'SELECT'    => 'u.user_id, u.user_type, u.user_email, u.user_avatar, u.user_avatar_type',
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
            $gravatar = false;

            // Check current gravatar
            if ($user['user_avatar_type'] === 'avatar.driver.gravatar') {
                // Check gravatar
                if ($this->is_valid_gravatar($user['user_avatar'])) {
                    $gravatar = $user['user_avatar'];
                }
            }

            // Check known email address
            if ($gravatar === false && $this->is_valid_gravatar($user['user_email'])) {
                $gravatar = $user['user_email'];
            }

            if ($gravatar !== false) {
                $data = array(
                    'user_avatar' => $gravatar,
                    'user_avatar_type' => 'avatar.driver.gravatar',
                    'user_avatar_width' => 90,
                    'user_avatar_height' => 90
                );

                // Update gravatar
                $sql = 'UPDATE ' . USERS_TABLE . '
                        SET ' . $this->db->sql_build_array('UPDATE', $data) . '
                        WHERE user_id = ' . $user['user_id'];
                $this->db->sql_query($sql);

                $i++;
            }
        }

        return $i;
    }

    public function is_valid_gravatar($email)
    {
        // Not a valid email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Generate hash
        $gravatar_hash = md5(strtolower(trim($email)));

        // Check gravatar
        $response = get_headers('http://www.gravatar.com/avatar/' . $gravatar_hash . '?d=404');
        if (strpos($response[0], "404 Not Found") === false) {
            return true;
        }

        return false;
    }
}
