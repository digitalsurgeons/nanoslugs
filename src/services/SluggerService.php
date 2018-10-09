<?php
/**
 * Nanoslugs plugin for Craft CMS 3.x
 *
 * Hashes the Id of an entry when it is saved and replaces the slug.
 *
 * @link      coryzibell.com
 * @copyright Copyright (c) 2018 Cory Zibell
 */

namespace coryzibell\nanoslugs\services;

use coryzibell\nanoslugs\Nanoslugs;

use Craft;
use craft\base\Component;

/**
 * @author    Cory Zibell
 * @package   Nanoslugs
 * @since     1.0.0
 */
class NanoslugsService extends Component
{

	protected $length;
	protected $alphabet;
	protected $salt;
	protected $encoder;

	public function __construct()
	{
		$settings = Craft::$app->plugins->getPlugin('nanoslugs')->getSettings();

		$this->length = $settings['length'];
		$this->salt = $settings['salt'];
		$this->alphabet = $settings['alphabet'];

		$this->encoder = new \Hashids\Hashids($this->salt, $this->length, $this->alphabet);
	}


    /**
	 * Encode the id and return it
	 *
	 * This method will take EntryModel that's passed and encode it's ID, the entries slug attribute will then be replaced
	 * with the encoded ID and saved.
	 *
	 * @param $id  A number to hash.
	 *
	 *
	 * @return string|$encodedId the encoded ID
	 */
	public function encodeById($id, $settings)
	{

		if ( $settings['length'] )
		{
			$length = $settings['length'];
			$this->encoder = new \Hashids\Hashids($this->salt, $length, $this->alphabet);
		}
		$encodedId = $this->encoder->encode($id);
		return $encodedId;
	}

	public function decode($hash)
	{
		$length = strlen($hash);
		$this->encoder = new \Hashids\Hashids($this->salt, $length, $this->alphabet);
		$id = $this->encoder->decode($hash);
		return reset($id);
	}
}
