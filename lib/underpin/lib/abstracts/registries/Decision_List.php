<?php
/**
 * Provides an extend-able way to make a hierarchial decision
 *
 * @since   1.0.0
 * @package Underpin\Abstracts\Registries
 */


namespace Underpin\Abstracts\Registries;


use Underpin\Abstracts\Decision;
use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Decision_List
 * Class Decision_List
 *
 * @since   1.0.0
 * @package Underpin\Abstracts\Registries
 */
abstract class Decision_List extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Underpin\Abstracts\Decision';

	protected $params = [];

	/**
	 * Make a decision based on the provided params.
	 *
	 * @since 1.0.0
	 *
	 * @param array $params
	 * @return array|\WP_Error
	 */
	public function decide( $params = [] ) {
		if ( empty( $this ) ) {
			return underpin()->logger()->log_as_error(
				'error',
				'decision_list_has_no_decisions',
				'A decision list ran, but there were no decisions to make.',
				['ref' => $this->get_registry_id()]
			);
		}

		// Sort decisions before looping through them.
		$this->sort_decisions();
		$invalid_decisions = [];

		foreach ( $this as $decision ) {
			$valid = $decision->is_valid( $params );
			if ( is_wp_error( $valid ) ) {
				underpin()->logger()->log_wp_error( 'notice', $valid );
				$invalid_decisions[ $decision->id ] = $valid;
			} else {
				break;
			}
		}

		// If the decision did not get set, return an error.
		if ( ! isset( $decision ) ) {
			return underpin()->logger()->log_as_error(
				'error',
				'decision_list_could_not_decide',
				'A decision list ran, but all decisions returned false.',
				['ref' => $this->get_registry_id()]
			);
		}

		return [ 'decision' => $decision, 'invalid_decisions' => $invalid_decisions ];
	}

	/**
	 * Sorts decisions by their priority.
	 * Items with lower priority numbers get a chance to be chosen first.
	 *
	 * @since 1.0.0
	 */
	public function sort_decisions() {
		$this->uasort( function( Decision $a, Decision $b ) {
			return $a->priority < $b->priority ? -1 : 1;
		} );
	}

}