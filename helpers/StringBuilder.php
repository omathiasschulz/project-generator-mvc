<?php

namespace helpers;

/**
 * StringBuilder for PHP provide string manipulation logic in an object.
 */
class StringBuilder
{
    protected $string;

    /**
     * Create a new string builder object
     */
    public function __construct($string = '')
    {
        $this->string = $string;
    }

    /**
     * Append a string to the sequence
     */
    public function append($string)
    {
        $this->string .= $string;

        return $this;
    }

    /**
     * Append a string and a new line to the sequence
     */
    public function appendNL($string)
    {
        $this->string .= $string . "\n";

        return $this;
    }

    /**
     * Get a sub string
     */
    public function subString($initialPosition, $finalPosition)
    {
        $this->string = substr($this->string, $initialPosition, $finalPosition);

        return $this;
    }

    /**
	 * Returns the whole resulting string
	 */
	public function build()
	{
		return $this->string;
    }
    
	/**
	 * Returns the whole resulting string
	 */
	public function __toString()
	{
		return $this->build();
	}
}