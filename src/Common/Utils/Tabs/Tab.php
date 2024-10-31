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

namespace PrisjaktFeed\Common\Utils\Tabs;

/**
 *
 * @package PrisjaktFeed\Common\Utils\Tabs
 * @since 1.0.0
 */
class Tab {



	/**
	 * @var string[]
	 */
	protected $tab_default = [
		'id'    => '',
		'label' => '',
	];

	/**
	 * @var int
	 */
	private $index = 0;

	/**
	 * @var string
	 */
	private $id = '';

	/**
	 * @var string
	 */
	private $label = '';

	/**
	 * @var string
	 */
	private $url = '';

	/**
	 * @var array|object
	 */
	private $args = [];

	/**
	 * @var string[]
	 */
	private $class = [ 'nav-tab' ];


	/**
	 * @return array|object
	 */
	public function get_args(): array {
		return $this->args;
	}

	/**
	 * @param array|object $args
	 */
	public function set_args( array $args ): void {
		$this->args = $args;
	}

	/**
	 * @return string[]
	 */
	public function get_tab_default(): array {
		return $this->tab_default;
	}

	/**
	 * @return string
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function set_id( string $id ): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function set_label( string $label ): void {
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function get_url(): string {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function set_url( string $url ): void {
		$this->url = $url;
	}


	/**
	 * @return string
	 */
	public function get_class(): string {
		return implode( ' ', $this->class );
	}

	/**
	 * @param string[] $class
	 */
	public function set_class( array $class ): void {
		$this->class = $class;
	}


	/**
	 * @return int
	 */
	public function get_index(): int {
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function set_index( int $index ): void {
		$this->index = $index;
	}


	/**
	 * Base constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $tab ) {

		if ( ! empty( $tab ) ) {
			$this->set_args( wp_parse_args( $tab, $this->get_tab_default() ) );
			$this->init();
		}
	}


	/**
	 *
	 */
	public function init(): void {

		$args = $this->get_args();

		$this->set_index( $args['index'] );
		$this->set_id( $args['id'] );
		$this->set_label( $args['label'] );

		$this->set_url( $this->get_tab_url() );
		$this->set_class( $this->get_tab_class() );
	}

	/**
	 * @return bool
	 */
	private function is_active_tab(): bool {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_REQUEST['tab'] ) ) {
			return $this->get_index() === 0;
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return ( sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) === $this->get_id() );
	}


	/**
	 * @return string|void
	 */
	private function get_tab_url(): string {
		return admin_url(
			sprintf(
				'edit.php?post_type=%s&page=%s&tab=%s',
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset( $_REQUEST['post_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_type'] ) ) : null,
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : null,
				$this->get_id()
			)
		);
	}

	/**
	 * @return array
	 */
	private function get_tab_class(): array {
		$extra_class = [];

		if ( $this->is_active_tab() ) {
			$extra_class[] = 'nav-tab-active';
		}

		return array_unique( array_merge( $this->class, $extra_class ) );
	}
}
