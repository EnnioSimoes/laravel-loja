<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Email\Delivery;


/**
 * Factory for delivery email HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Factory
	extends \Aimeos\Client\Html\Common\Factory\Base
	implements \Aimeos\Client\Html\Common\Factory\Iface
{
	/**
	 * Creates a delivery email client object.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Shop context instance with necessary objects
	 * @param array $templatePaths List of file system paths where the templates are stored
	 * @param string|null $name Client name (default: "Standard")
	 * @return \Aimeos\Client\Html\Iface Filter part implementing \Aimeos\Client\Html\Iface
	 * @throws \Aimeos\Client\Html\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function createClient( \Aimeos\MShop\Context\Item\Iface $context, array $templatePaths, $name = null )
	{
		/** client/html/email/delivery/name
		 * Class name of the used email delivery client implementation
		 *
		 * Each default HTML client can be replace by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the client factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  \Aimeos\Client\Html\Email\Delivery\Standard
		 *
		 * and you want to replace it with your own version named
		 *
		 *  \Aimeos\Client\Html\Email\Delivery\Mydelivery
		 *
		 * then you have to set the this configuration option:
		 *
		 *  client/html/email/delivery/name = Mydelivery
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyDelivery"!
		 *
		 * @param string Last part of the class name
		 * @since 2014.03
		 * @category Developer
		 */
		if( $name === null ) {
			$name = $context->getConfig()->get( 'client/html/email/delivery/name', 'Standard' );
		}

		if( ctype_alnum( $name ) === false )
		{
			$classname = is_string( $name ) ? '\\Aimeos\\Client\\Html\\Email\\Delivery\\' . $name : '<not a string>';
			throw new \Aimeos\Client\Html\Exception( sprintf( 'Invalid characters in class name "%1$s"', $classname ) );
		}

		$iface = '\\Aimeos\\Client\\Html\\Iface';
		$classname = '\\Aimeos\\Client\\Html\\Email\\Delivery\\' . $name;

		$client = self::createClientBase( $context, $classname, $iface, $templatePaths );

		return self::addClientDecorators( $context, $client, $templatePaths, 'email/delivery' );
	}
}
