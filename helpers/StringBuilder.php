<?php

namespace helpers;

class StringBuilder
{
    protected $string;

    public function __construct($string = "")
    {
        $this->string = $string;
    }

    /**
     * Método responsável por receber uma string e concatenar com a string atual
     */
    public function append($string)
    {
        $this->string .= $string;

        return $this;
    }

    /**
     * Método responsável por receber uma string e concatenar com a string atual
     * e no final realizar uma quebra de linha
     */
    public function appendNL($string)
    {
        $this->string .= $string . "\n";

        return $this;
    }

    /**
     * Método responsável por pegar a substring da string atual
     */
    public function subString($initialPosition, $finalPosition)
    {
        $this->string = substr($this->string, $initialPosition, $finalPosition);

        return $this;
    }

    /**
     * Método responsável por realizar a identação de uma string de acordo com os
     * colchetes da string
     */
    public function generateIdentation($initialSpacing = 0)
    {
        $aString = explode("\n", $this->string);

        foreach ($aString as &$string) {
            if (strpos($string, '}') !== false)
                $initialSpacing--;
            
            $tabs = "";
            for ($i = 0; $i < $initialSpacing; $i++)
                $tabs .= "\t";
            
            $string = $tabs . $string;
            if (strpos($string, '{') !== false)
                $initialSpacing++;
        }
        $this->string = implode("\n", $aString);
        
        return $this;
    }

    /**
	 * Método responsável por retornar a string
	 */
	public function build()
	{
		return $this->string;
    }
    
	/**
	 * Método mágico que retorna a string
	 */
	public function __toString()
	{
		return $this->build();
	}
}