<?php
/**
 * Prisjakt Feed
 *
 * @package   prisjakt-feed
 * @author    Prisjakt <support@prisjakt.nu>
 * @copyright 2022 Prisjakt Feed
 * @license   MIT
 * @link      https://prisjakt.nu
 */

declare(strict_types=1);

namespace PrisjaktFeed;

use PrisjaktFeed\Common\Abstracts\Base;
use PrisjaktFeed\Common\Traits\Requester;
use PrisjaktFeed\Common\Utils\Errors;
use PrisjaktFeed\Config\Classes;
use PrisjaktFeed\Config\I18n;
use PrisjaktFeed\Config\Requirements;

/**
 * Bootstrap the plugin
 *
 * @since 1.0.0
 */
final class Bootstrap extends Base {



	/**
	 * Determine what we're requesting
	 *
	 * @see Requester
	 */
	use Requester;

	/**
	 * Used to debug the Bootstrap class; this will print a visualised array
	 * of the classes that are loaded with the total execution time if set true
	 *
	 * @var array
	 */
	public $bootstrap = [ 'debug' => false ];

	/**
	 * List of class to init
	 *
	 * @var array : classes
	 */
	public $class_list = [];

	/**
	 * Composer autoload file list
	 *
	 * @var Composer\Autoload\ClassLoader
	 */
	public $composer;

	/**
	 * Requirements class object
	 *
	 * @var Requirements
	 */
	protected $requirements;

	/**
	 * I18n class object
	 *
	 * @var I18n
	 */
	protected $i18n;

	/**
	 * Bootstrap constructor that
	 * - Checks compatibility/plugin requirements
	 * - Defines the locale for this plugin for internationalization
	 * - Load the classes via Composer's class loader and initialize them on type of request
	 *
	 * @param \Composer\Autoload\ClassLoader $composer Composer autoload output.
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function __construct( $composer ) {
		parent::__construct();
		$this->start_execution_timer();
		$this->check_requirements();
		$this->set_locale();
		$this->get_class_loader( $composer );
		$this->load_classes( Classes::get() );
		$this->debugger();
	}

	/**
	 * Check plugin requirements
	 *
	 * @since 1.0.0
	 */
	public function check_requirements() {
		$set_timer          = microtime( true );
		$this->requirements = new Requirements();
		$this->requirements->check();
		$this->bootstrap['check_requirements'] = $this->stop_execution_timer( $set_timer );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since 1.0.0
	 */
	public function set_locale() {
		$set_timer  = microtime( true );
		$this->i18n = new I18n();
		$this->i18n->load();
		$this->bootstrap['set_locale'] = $this->stop_execution_timer( $set_timer );
	}

	/**
	 * Get the class loader from Composer
	 *
	 * @param $composer
	 *
	 * @since 1.0.0
	 */
	public function get_class_loader( $composer ) {
		$this->composer = $composer;
	}

	/**
	 * Initialize the requested classes
	 *
	 * @param $classes
	 *
	 * @since 1.0.0
	 */
	public function load_classes( $classes ) {
		$set_timer = microtime( true );
		foreach ( $classes as $class ) {
			if ( isset( $class['on_request'] ) && is_array( $class['on_request'] )
			) {
				foreach ( $class['on_request'] as $on_request ) {
					if ( ! $this->request( $on_request ) ) {
						continue;
					}
				}
			} elseif ( isset( $class['on_request'] ) && ! $this->request( $class['on_request'] )
			) {
				continue;
			}
			$this->get_classes( $class['init'] );
		}
		$this->initClasses();
		$this->bootstrap['initialized_classes']['timer'] = $this->stop_execution_timer( $set_timer, 'Total execution time of initialized classes' );
	}

	/**
	 * Init the classes
	 *
	 * @since 1.0.0
	 */
	public function initClasses() {
		$this->class_list = \apply_filters( 'prisjakt_feed_initialized_classes', $this->class_list );
		foreach ( $this->class_list as $class ) {
			try {
				$set_timer = microtime( true );
                // phpcs:ignore NeutronStandard.Functions.VariableFunctions.VariableFunction
				$this->bootstrap['initialized_classes'][ $class ] = new $class();
				$this->bootstrap['initialized_classes'][ $class ]->init();
				$this->bootstrap['initialized_classes'][ $class ] = $this->stop_execution_timer( $set_timer );
			} catch ( \Throwable $err ) {
				\do_action( 'prisjakt_feed_class_initialize_failed', $err, $class );
                // phpcs:disable
                Errors::wp_die(
                    sprintf(
                    /** translators: %s: php class namespace */
                        __(
                            'Could not load class "%s". The "init" method is probably missing or try a `composer dumpautoload -o` to refresh the autoloader.',
                            'prisjakt-feed'
                        ),
                        $class
                    ),
                    __('Plugin initialize failed', 'prisjakt-feed'),
                    __FILE__,
                    $err
                );
                // phpcs:enable
			}
		}
	}

	/**
	 * Get classes based on the directory automatically using the Composer autoload
	 *
	 * @param string $namespace Class name to find.
	 *
	 * @return array Return the classes.
	 * @since 1.0.0
	 */
	public function get_classes( string $namespace ): array {
		$namespace = $this->plugin->namespace() . '\\' . $namespace;
		if ( is_object( $this->composer ) !== false ) {
			$classmap = $this->composer->getClassMap();

			// First we're going to try to load the classes via Composer's Autoload
			// which will improve the performance. This is only possible if the Autoloader
			// has been optimized.
			if ( isset( $classmap[ $this->plugin->namespace() . '\\Bootstrap' ] ) ) {
				if ( ! isset( $this->bootstrap['initialized_classes']['load_by'] ) ) {
					$this->bootstrap['initialized_classes']['load_by'] = 'Autoloader';
				}
				$classes = array_keys( $classmap );
				foreach ( $classes as $class ) {
					if ( 0 !== strncmp( (string) $class, $namespace, strlen( $namespace ) ) ) {
						continue;
					}
					$this->class_list[] = $class;
				}

				return $this->class_list;
			}
		}

		return $this->get_by_extraction( $namespace );
	}

	/**
	 * Get classes by file extraction, will only run if autoload fails
	 *
	 * @param $namespace
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_by_extraction( $namespace ): array {
		if ( ! isset( $this->bootstrap['initialized_classes']['load_by'] ) ) {
			$this->bootstrap['initialized_classes']['load_by'] = 'Extraction; Try a `composer dumpautoload -o` to optimize the autoloader.';
		}
		$find_all_classes = [];
		foreach ( $this->files_from_this_dir() as $file ) {
			$file_data        = [
                // phpcs:disable
                // file_get_contents() is only discouraged by PHPCS for remote files
                'tokens' => token_get_all(file_get_contents($file->getRealPath())),
                // phpcs:enable
				'namespace' => '',
			];
			$find_all_classes = array_merge( $find_all_classes, $this->extract_classes( $file_data ) );
		}
		$this->class_belongs_to( $find_all_classes, $namespace . '\\' );

		return $this->class_list;
	}

	/**
	 * Extract class from file, will only run if autoload fails
	 *
	 * @param $file_data
	 * @param array     $classes
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function extract_classes( $file_data, $classes = [] ): array {
		for ( $index = 0; isset( $file_data['tokens'][ $index ] ); $index++ ) {
			if ( ! isset( $file_data['tokens'][ $index ][0] ) ) {
				continue;
			}
			if ( T_NAMESPACE === $file_data['tokens'][ $index ][0] ) {
				$index += 2;
				while ( isset( $file_data['tokens'][ $index ] ) && is_array( $file_data['tokens'][ $index ] ) ) {
					$file_data['namespace'] .= $file_data['tokens'][ $index++ ][1];
				}
			}
			if ( T_CLASS === $file_data['tokens'][ $index ][0] && T_WHITESPACE === $file_data['tokens'][ $index + 1 ][0] && T_STRING === $file_data['tokens'][ $index + 2 ][0] ) {
				$index += 2;
				/**
				 * So it only works with 1 class per file (which should be psr-4 compliant)
				 */
				$classes[] = $file_data['namespace'] . '\\' . $file_data['tokens'][ $index ][1];
				break;
			}
		}

		return $classes;
	}

	/**
	 * Get all files from current dir, will only run if autoload fails
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function files_from_this_dir(): \RegexIterator {
		$files = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( __DIR__ ) );
		$files = new \RegexIterator( $files, '/\.php$/' );

		return $files;
	}

	/**
	 * Checks if class belongs to namespace, will only run if autoload fails
	 *
	 * @param $classes
	 * @param $namespace
	 *
	 * @since 1.0.0
	 */
	public function class_belongs_to( $classes, $namespace ) {
		foreach ( $classes as $class ) {
			if ( strpos( $class, $namespace ) === 0 ) {
				$this->class_list[] = $class;
			}
		}
	}

	/**
	 * Start the execution timer of the plugin
	 *
	 * @since 1.0.0
	 */
	public function start_execution_timer(): void {
		if ( true === $this->bootstrap['debug'] ) {
			$this->bootstrap['execution_time']['start'] = microtime( true );
		}
	}

	/**
	 * @param $timer
	 * @param string $tag
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function stop_execution_timer( $timer, $tag = 'Execution time' ): string {
		if ( true === $this->bootstrap['debug'] ) {
			return 'Elapsed: ' . ( microtime( true ) - $this->bootstrap['execution_time']['start'] ) . ' | ' . $tag . ': ' . ( microtime( true ) - $timer );
		}

		return '';
	}

	/**
	 * Visual presentation of the classes that are loaded
	 */
	public function debugger() {
		if ( true === $this->bootstrap['debug'] ) {
			$this->bootstrap['execution_time'] =
				'Total execution time in seconds: ' . ( microtime( true ) - $this->bootstrap['execution_time']['start'] );
			add_action(
				'shutdown',
				function () {
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
					$output = highlight_string( "<?php\n\n" . var_export( $this->bootstrap, true ), true );
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo "<div style=\"background-color: #1C1E21; padding:5px; position: fixed; z-index:9999; bottom:0;\">{$output}</div>";
				}
			);
		}
	}
}
