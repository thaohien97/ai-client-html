<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Filter;


/**
 * Default implementation of catalog filter section HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	private static $headerSingleton;

	/** client/html/catalog/filter/standard/subparts
	 * List of HTML sub-clients rendered within the catalog filter section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/catalog/filter/standard/subparts';

	/** client/html/catalog/filter/search/name
	 * Name of the search part used by the catalog filter client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Catalog\Filter\Search\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/catalog/filter/tree/name
	 * Name of the tree part used by the catalog filter client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Catalog\Filter\Tree\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/catalog/filter/attribute/name
	 * Name of the attribute part used by the catalog filter client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Catalog\Filter\Attribute\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/catalog/filter/supplier/name
	 * Name of the supplier part used by the catalog filter client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Catalog\Filter\Supplier\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2018.07
	 * @category Developer
	 */
	private $subPartNames = array( 'search', 'tree', 'supplier', 'attribute' );
	private $tags = [];
	private $expire;
	private $view;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function getBody( string $uid = '' ) : string
	{
		$prefixes = array( 'f' );
		$context = $this->getContext();

		/** client/html/catalog/filter/cache
		 * Enables or disables caching only for the catalog filter component
		 *
		 * Disable caching for components can be useful if you would have too much
		 * entries to cache or if the component contains non-cacheable parts that
		 * can't be replaced using the modifyBody() and modifyHeader() methods.
		 *
		 * @param boolean True to enable caching, false to disable
		 * @category Developer
		 * @category User
		 * @see client/html/catalog/detail/cache
		 * @see client/html/catalog/lists/cache
		 * @see client/html/catalog/stage/cache
		 */

		/** client/html/catalog/filter
		 * All parameters defined for the catalog filter component and its subparts
		 *
		 * This returns all settings related to the filter component.
		 * Please refer to the single settings for details.
		 *
		 * @param array Associative list of name/value settings
		 * @category Developer
		 * @see client/html/catalog#filter
		 */
		$confkey = 'client/html/catalog/filter';

		if( ( $html = $this->getCached( 'body', $uid, $prefixes, $confkey ) ) === null )
		{
			$view = $this->getView();

			/** client/html/catalog/filter/standard/template-body
			 * Relative path to the HTML body template of the catalog filter client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the result shown in the body of the frontend. The
			 * configuration string is the path to the template file relative
			 * to the templates directory (usually in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page body
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/filter/standard/template-header
			 */
			$tplconf = 'client/html/catalog/filter/standard/template-body';
			$default = 'catalog/filter/body-standard';

			try
			{
				if( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->addData( $view, $this->tags, $this->expire );
				}

				$html = '';
				foreach( $this->getSubClients() as $subclient ) {
					$html .= $subclient->setView( $view )->getBody( $uid );
				}
				$view->filterBody = $html;

				$html = $view->render( $view->config( $tplconf, $default ) );
				$this->setCached( 'body', $uid, $prefixes, $confkey, $html, $this->tags, $this->expire );

				return $html;
			}
			catch( \Aimeos\Client\Html\Exception $e )
			{
				$error = array( $context->getI18n()->dt( 'client', $e->getMessage() ) );
				$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
			}
			catch( \Aimeos\Controller\Frontend\Exception $e )
			{
				$error = array( $context->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
				$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
			}
			catch( \Aimeos\MShop\Exception $e )
			{
				$error = array( $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
				$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
			}
			catch( \Exception $e )
			{
				$error = array( $context->getI18n()->dt( 'client', 'A non-recoverable error occured' ) );
				$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
				$this->logException( $e );
			}

			$html = $view->render( $view->config( $tplconf, $default ) );
		}
		else
		{
			$html = $this->modifyBody( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( string $uid = '' ) : ?string
	{
		if( self::$headerSingleton !== null ) {
			return '';
		}

		$prefixes = array( 'f' );
		$confkey = 'client/html/catalog/filter';

		if( ( $html = $this->getCached( 'header', $uid, $prefixes, $confkey ) ) === null )
		{
			$view = $this->getView();

			/** client/html/catalog/filter/standard/template-header
			 * Relative path to the HTML header template of the catalog filter client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the templates directory (usually
			 * in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/filter/standard/template-body
			 */
			$tplconf = 'client/html/catalog/filter/standard/template-header';
			$default = 'catalog/filter/header-standard';

			try
			{
				if( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->addData( $view, $this->tags, $this->expire );
				}

				$html = '';
				foreach( $this->getSubClients() as $subclient ) {
					$html .= $subclient->setView( $view )->getHeader( $uid );
				}
				$view->filterHeader = $html;

				$html = $view->render( $view->config( $tplconf, $default ) );
				$this->setCached( 'header', $uid, $prefixes, $confkey, $html, $this->tags, $this->expire );

				return $html;
			}
			catch( \Exception $e )
			{
				$this->logException( $e );
			}
		}
		else
		{
			$html = $this->modifyHeader( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Client\Html\Iface
	{
		/** client/html/catalog/filter/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog filter html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/catalog/filter/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/decorators/global
		 * @see client/html/catalog/filter/decorators/local
		 */

		/** client/html/catalog/filter/decorators/global
		 * Adds a list of globally available decorators only to the catalog filter html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/decorators/excludes
		 * @see client/html/catalog/filter/decorators/local
		 */

		/** client/html/catalog/filter/decorators/local
		 * Adds a list of local decorators only to the catalog filter html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Catalog\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Catalog\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/decorators/excludes
		 * @see client/html/catalog/filter/decorators/global
		 */

		return $this->createSubClient( 'catalog/filter/' . $type, $name );
	}


	/**
	 * Modifies the cached body content to replace content based on sessions or cookies.
	 *
	 * @param string $content Cached content
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string Modified body content
	 */
	public function modifyBody( string $content, string $uid ) : string
	{
		$content = parent::modifyBody( $content, $uid );

		return $this->replaceSection( $content, $this->getView()->csrf()->formfield(), 'catalog.filter.csrf' );
	}


	/**
	 * Processes the input, e.g. store given values.
	 *
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables if necessary.
	 */
	public function process()
	{
		$context = $this->getContext();
		$view = $this->getView();

		try
		{
			parent::process();
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
		}
		catch( \Aimeos\Controller\Frontend\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
		}
		catch( \Aimeos\Client\Html\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'client', $e->getMessage() ) );
			$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
		}
		catch( \Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'client', 'A non-recoverable error occured' ) );
			$view->filterErrorList = array_merge( $view->get( 'filterErrorList', [] ), $error );
			$this->logException( $e );
		}
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames() : array
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	public function addData( \Aimeos\MW\View\Iface $view, array &$tags = [], string &$expire = null ) : \Aimeos\MW\View\Iface
	{
		$config = $this->getContext()->getConfig();

		/** client/html/catalog/count/enable
		 * Enables or disables displaying product counts in the catalog filter
		 *
		 * This configuration option allows shop owners to display product
		 * counts in the catalog filter or to disable fetching product count
		 * information.
		 *
		 * The product count information is fetched via AJAX and inserted via
		 * Javascript. This allows to cache parts of the catalog filter by
		 * leaving out such highly dynamic content like product count which
		 * changes with used filter parameter.
		 *
		 * @param boolean Value of "1" to display product counts, "0" to disable them
		 * @since 2014.03
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/count/url/target
		 * @see client/html/catalog/count/url/controller
		 * @see client/html/catalog/count/url/action
		 * @see client/html/catalog/count/url/config
		 */
		if( $config->get( 'client/html/catalog/count/enable', true ) == true
			&& array_intersect( $this->getSubClientNames(), ['tree', 'supplier', 'attribute'] ) !== []
		) {
			/** client/html/catalog/count/url/target
			 * Destination of the URL where the controller specified in the URL is known
			 *
			 * The destination can be a page ID like in a content management system or the
			 * module of a software development framework. This "target" must contain or know
			 * the controller that should be called by the generated URL.
			 *
			 * @param string Destination of the URL
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/count/url/controller
			 * @see client/html/catalog/count/url/action
			 * @see client/html/catalog/count/url/config
			 */
			$target = $config->get( 'client/html/catalog/count/url/target' );

			/** client/html/catalog/count/url/controller
			 * Name of the controller whose action should be called
			 *
			 * In Model-View-Controller (MVC) applications, the controller contains the methods
			 * that create parts of the output displayed in the generated HTML page. Controller
			 * names are usually alpha-numeric.
			 *
			 * @param string Name of the controller
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/count/url/target
			 * @see client/html/catalog/count/url/action
			 * @see client/html/catalog/count/url/config
			 */
			$controller = $config->get( 'client/html/catalog/count/url/controller', 'catalog' );

			/** client/html/catalog/count/url/action
			 * Name of the action that should create the output
			 *
			 * In Model-View-Controller (MVC) applications, actions are the methods of a
			 * controller that create parts of the output displayed in the generated HTML page.
			 * Action names are usually alpha-numeric.
			 *
			 * @param string Name of the action
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/count/url/target
			 * @see client/html/catalog/count/url/controller
			 * @see client/html/catalog/count/url/config
			 */
			$action = $config->get( 'client/html/catalog/count/url/action', 'count' );

			/** client/html/catalog/count/url/config
			 * Associative list of configuration options used for generating the URL
			 *
			 * You can specify additional options as key/value pairs used when generating
			 * the URLs, like
			 *
			 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
			 *
			 * The available key/value pairs depend on the application that embeds the e-commerce
			 * framework. This is because the infrastructure of the application is used for
			 * generating the URLs. The full list of available config options is referenced
			 * in the "see also" section of this page.
			 *
			 * @param string Associative list of configuration options
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/count/url/target
			 * @see client/html/catalog/count/url/controller
			 * @see client/html/catalog/count/url/action
			 * @see client/html/url/config
			 */
			$config = $config->get( 'client/html/catalog/count/url/config', [] );

			$params = $this->getClientParams( $view->param(), array( 'f' ) );

			if( ( $startid = $view->config( 'client/html/catalog/filter/tree/startid' ) ) ) {
				$params['f_catid'] = $startid;
			}

			$view->filterCountUrl = $view->url( $target, $controller, $action, $params, [], $config );
			self::$headerSingleton = true;
		}

		return parent::addData( $view, $tags, $expire );
	}
}
