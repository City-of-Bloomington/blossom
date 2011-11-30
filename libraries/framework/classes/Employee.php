<?php
/**
 * A class for working with entries in LDAP.
 *
 * This class is written specifically for the City of Bloomington's
 * LDAP layout.  If you are going to be doing LDAP authentication
 * with your own LDAP server, you will probably need to customize
 * the fields used in this class.
 *
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Employee implements ExternalIdentity
{
	private static $connection;
	private $entry;

	/**
	 * @param string $username
	 * @param string $password
	 * @throws Exception
	 */
	public static function authenticate($username,$password)
	{
		$connection = ldap_connect(LDAP_SERVER) or die("Couldn't connect to LDAP");
		ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		if (ldap_bind($connection,LDAP_USERNAME_ATTRIBUTE."=$username,".LDAP_DN,"$password")) {
			return true;
		}
	}


	/**
	 * Loads an entry from the LDAP server for the given user
	 *
	 * @param string $username
	 */
	public function __construct($username)
	{
		$this->openConnection();

		$result = ldap_search(self::$connection,LDAP_DN,
								LDAP_USERNAME_ATTRIBUTE."=$username");
		if (ldap_count_entries(self::$connection,$result)) {
			$entries = ldap_get_entries(self::$connection, $result);
			$this->entry = $entries[0];
		}
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->entry['uid'];
	}

	/**
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->entry['givenname'][0];
	}

	/**
	 * @return string
	 */
	public function getLastname()
	{
		return $this->entry['sn'][0];
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->entry['mail'][0];
	}

	/**
	 * Creates the connection to the LDAP server
	 */
	private function openConnection()
	{
		if (!self::$connection) {
			if (self::$connection = ldap_connect(LDAP_SERVER)) {
				ldap_set_option(self::$connection,LDAP_OPT_PROTOCOL_VERSION,3);
				if (LDAP_ADMIN_USER) {
					if (!ldap_bind(self::$connection,
								   LDAP_USERNAME_ATTRIBUTE."=".LDAP_ADMIN_USER.",o=".LDAP_DOMAIN,
								   LDAP_ADMIN_PASS)) {
						throw new Exception(ldap_error(self::$connection));
					}
				}
				else {
					if (!ldap_bind(self::$connection)) {
						throw new Exception(ldap_error(self::$connection));
					}
				}
			}
			else {
				throw new Exception(ldap_error(self::$connection));
			}
		}
	}
}