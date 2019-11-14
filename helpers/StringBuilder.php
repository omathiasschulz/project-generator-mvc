<?php

namespace helpers;

/**
 * StringBuilder for PHP provide string manipulation logic in an object.
 */
class StringBuilder
{
    protected $string;

    /**
     * Create a new string builder object.
     *
     * @param string|null $string   The initial sequence
     */
    public function __construct($string = '')
    {
        $this->string = $string;
    }

    /**
     * Append a string to the sequence.
     *
     * @param string $string The sequence to append.
     *
     * @return StringBuilder
     */
    public function append($string)
    {
        // $string = static::convertString($string, $this->encoding);

        $this->string .= $string;

        return $this;
    }

    /**
	 * Returns the whole resulting string
	 *
	 * @return string
	 */
	public function build()
	{
		return $this->string;
	}
	/**
	 * Returns the whole resulting string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->build();
	}
}