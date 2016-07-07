<?php namespace App;

use File;
use Storage;

class DraftConverter
{
	protected $styles = [
		'BOLD'      => 'strong',
		'ITALIC'    => 'em',
		'UNDERLINE' => 'u'
	];

	protected $types = [
		'unstyled'   => 'p',
		'header-one' => 'h1',
		'header-two' => 'h2',
	];

	/**
	 * The draft.js raw object.
	 *
	 * @var Object
	 */
	protected $draftObject;

	/**
	 * The files passed in from the editor.
	 *
	 * @var Object
	 */
	protected $files;

	/**
	 * This class converts a draft.js raw object to HTML.
	 *
	 * @param Object $draftObject
	 * @param Object $files
	 */
	function __construct($draftObject, $files) {
		$draftObject = json_decode($draftObject);
		$this->draftObject = $draftObject;
		$this->files = $files;
	}

	/**
	 * Apply inline styles to the text.
	 * This returns the text formatted with the inline styles.
	 *
	 * @param  String $text
	 * @param  Object $styles
	 *
	 * @return String
	 */
	protected function applyInlineStyles($text, $styles) {
		$text = str_split($text);
		$keys = array_keys($text);

		return array_reduce(
			$keys,
			function ($carry, $key) use ($keys, $text, $styles) {
				$item = $text[$key];
				$prefix = $this->getStylesForPosition($styles, $key);
				$suffix = $key === end($keys)
					? $this->applyClosingStyles($styles, $key)
					: '';

				return $carry . $prefix . $item . $suffix;
			}
		);
	}

	/**
	 * Apply entities to the text.
	 * This returns the text with the entities applied to it.
	 *
	 * @param  String $text
	 * @param  Object $entities
	 *
	 * @return  String
	 */
	protected function applyEntities($text, $entities) {
		$text = str_split($text);
		$keys = array_keys($text);

		return array_reduce(
			$keys,
			function ($carry, $key) use ($keys, $text, $entities) {
				$item = $text[$key];
				$entity = $this->getEntitiesForPosition($entities, $key);
				return $carry . $entity . $item;
			}
		);
	}

	/**
	 * Get the styles for the position of a text.
	 * This checks the offset & length of an inline style and apply it if
	 * needed.
	 *
	 * @param  Array $styles
	 * @param  Integer $position
	 *
	 * @return String
	 */
	protected function getStylesForPosition($styles, $position) {
		$result = array_map(function ($style) use ($position) {
			if ($style->offset === $position) {
				return $this->convertStyleToHTML($style->style, 'prefix');
			}

			if ($style->offset + $style->length === $position) {
				return $this->convertStyleToHTML($style->style, 'prefix');
			}
		}, $styles);

		return implode('', $result);
	}

	/**
	 * Get the entities for the position of a text.
	 * This checks if the position of the character matches an entity, if so
	 * it is added.
	 *
	 * @param  Array   $entityRanges
	 * @param  Integer $position
	 *
	 * @return String
	 */
	protected function getEntitiesForPosition($entityRanges, $position) {
		$result = array_map(function ($entityRange) use ($position) {
			if ($entityRange->offset === $position) {
				return $this->getEntity($entityRange->key);
			}
		}, $entityRanges);

		return implode('', $result);
	}

	/**
	 * Get the entity. This will call a dynamic function in the format of
	 * 'create{Type}Entity' to allow dynamic entity builders set up their own
	 * HTML & whatever it needs.
	 *
	 * @param  Integer $entityRangeKey
	 * @return String
	 */
	protected function getEntity($entityRangeKey) {
		$entity = $this->draftObject->entityMap->$entityRangeKey;
		$function = 'create' . ucfirst($entity->type) . 'Entity';
		return call_user_func([$this, $function], $entity);
	}

	/**
	 * Apply closing styles.
	 * This is a bit of a edge case where there is no inline style available
	 * anymore for the characters because it ends at the end. So here we check
	 * if there is any inline style offset + length combined 1 after the
	 * last character position so we can close the HTML.
	 *
	 * @param  Array   $styles
	 * @param  Integer $position
	 * @return String
	 */
	protected function applyClosingStyles($styles, $position) {
		$position = $position + 1;

		$result = array_map(function ($style) use ($position) {
			if ($style->offset === $position) {
				return $this->convertStyleToHTML($style->style, 'suffix');
			}

			if ($style->offset + $style->length === $position) {
				return $this->convertStyleToHTML($style->style, 'suffix');
			}
		}, $styles);

		return implode('', $result);
	}

	/**
	 * Convert a style to HTML by replacing the style key with actual HTML.
	 *
	 * @param  String  $style
	 * @param  Integer $position
	 * @return String
	 */
	protected function convertStyleToHTML($style, $position) {
		if (!in_array($style, $this->styles)) '';

		$element = $this->styles[$style];
		$slash = $position === 'suffix' ? '/' : '';
		return '<' . $slash . $element . '>';
	}

	/**
	 * Convert type (block element) to HTML.
	 *
	 * @param  String  $type
	 * @param  Integer $position
	 * @return String
	 */
	protected function convertTypeToHTML($type, $position) {
		if (!in_array($type, $this->types)) return '';

		$element = $this->types[$type];
		$slash = $position === 'suffix' ? '/' : '';
		return '<' . $slash . $element . '>';
	}

	/**
	 * Convert a draft.js block to HTML.
	 * We do this by applying inline styles & entities.
	 *
	 * @param  Object $block
	 * @return String
	 */
	protected function convertBlockToHtml($block) {
		$prefix = $this->convertTypeToHTML($block->type, 'prefix');
		$text = $this->applyInlineStyles($block->text, $block->inlineStyleRanges);
		$text = $this->applyEntities($block->text, $block->entityRanges);
		$suffix = $this->convertTypeToHTML($block->type, 'suffix');
		return $prefix . $text . $suffix;
	}

	/**
	 * Create an entity of type atomic.
	 *
	 * @param  Object $entity
	 * @return String
	 */
	protected function createAtomicEntity($entity) {
		$file = $this->files[$entity->data->id];
		$location = $this->moveFile($file);
		return '<img src="' . $location . '">';
	}

	/**
	 * Move file from temporary folder to storage.
	 * After that return it's location.
	 *
	 * @param  Object $file
	 * @return String
	 */
	protected function moveFile($file) {
		$source = File::get($file);
		$extension = $file->getClientOriginalExtension();
		$filename = $file->getFilename() . '.' . $extension;
		Storage::disk('public')->put($filename, File::get($file));
		return asset('storage/' . $filename);
	}

	/**
	 * Convert a draft object to HTML.
	 *
	 * @param Object $draftObject
	 * @return String
	 */
	protected function convert($draftObject) {
		$convertedBlocks = array_map(function ($block) {
			return $this->convertBlockToHtml($block);
		}, $draftObject->blocks);

		return implode("", $convertedBlocks);
	}

	/**
	 * Export the draft object by converting it to HTML.
	 *
	 * @return String
	 */
	public function export() {
		return $this->convert($this->draftObject);
	}
}
