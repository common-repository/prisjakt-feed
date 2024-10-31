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

declare( strict_types=1 );

namespace PrisjaktFeed\Common\Utils\Table;

use PrisjaktFeed\Common\Utils\Form\Elements\Input\Checkbox;
use PrisjaktFeed\Common\Utils\Form\Elements\Input\Hidden;
use PrisjaktFeed\Common\Utils\Form\Elements\Input\Radio;
use PrisjaktFeed\Common\Utils\Form\Elements\Input\Text;
use PrisjaktFeed\Common\Utils\Form\Elements\Label;
use PrisjaktFeed\Common\Utils\Form\Elements\Select\Select;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @since 1.0.0
 */
class Cell {

	/**
	 * @var int
	 */
	protected $index = 0;

	/**
	 * @var string
	 */
	protected $type = 'label';

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $content = '';


	public function get_content() {
		return $this->content->render();
	}

	/**
	 * @param string $content
	 */
	public function set_content( string $content ): void {
		$this->content = $content;
	}

	/**
	 * @return array
	 */
	public function get_data(): array {
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function set_data( array $data ): void {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function set_type( string $type ): void {
		$this->type = $type;
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
	 * @param $cell
	 */
	public function __construct( $cell ) {

		$args = wp_parse_args(
			$cell,
			[
				'index' => $this->index,
				'type'  => $this->type,
				'data'  => $this->data,
			]
		);

		$this->set_index( $args['index'] );
		$this->set_type( $args['type'] );
		$this->set_data( $args['data'] );

		$this->prepare_element( $cell );
	}

	/**
	 *
	 */
	public function prepare_element( $cell ): void {

		switch ( $this->type ) {
			case 'input_checkbox':
				$this->content = new Checkbox( $cell );
				break;

			case 'input_text':
				$this->content = new Text( $cell );
				break;

			case 'input_radio':
				$this->content = new Radio( $cell );
				break;

			case 'input_hidden':
				$this->content = new Hidden( $cell );
				break;

			case 'select':
			case 'select_optgroup':
				$this->content = new Select( $cell );
				break;

			case 'label':
			default:
				$this->content = new Label( $cell );
				break;
		}
	}
}
